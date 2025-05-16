<?php

namespace App\Http\Controllers;
use App\Models\Grade;
use App\Models\Setting;
use App\Models\Student;

use App\Models\Subject;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\SchoolCalendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentDashboardController extends Controller
{
    public function dashboard()
    {
              // Get the current school year from settings
         $currentSchoolYear = Setting::getSchoolYear();
         $currentSemester = Setting::query()->value('current_semester');
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()->route('student_login.homepage')->with('error', 'Please log in first.');
        }
             // Check if the success message has already been displayed
    if (!session()->has('has_logged_in')) {
        // Flash a success message to the session
        session()->flash('success', 'Welcome ' . $student->first_name . '!');
        // Mark that the user has logged in and the message has been displayed
        session(['has_logged_in' => true]);
    }
    // Get all grades for current student and current semester
    $filteredSubjects = $student->grades->filter(function ($grade) use ($currentSemester) {
        return $grade->subject->semester == $currentSemester;
    });

     // Fetch the latest semester from the database (if needed)
     $latestSemester = Setting::latest()->first();
    // Group subjects by year level
    $groupedSubjects = $filteredSubjects->groupBy(function ($grade) {
        return $grade->subject->year ?? 'Unknown';
    });
        return view('student.studentDashboard', compact('student', 'currentSchoolYear', 'currentSemester', 'groupedSubjects','latestSemester'));
    }

    ///////FUNCTIONS FOR STUDENT PROFILE///////////////////////
    public function profile()
    {
        $student = Auth::guard('student')->user();

        if (!$student){
            return redirect()->route('student_login.homepage')->with('error', 'Please log in first');
        }
        return view('student/profile', compact('student')); // Ensure this matches your Blade file
    }

    //Function for uploading profile picture
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        // Ensure we're getting an instance of the Student model
        $student = Student::where('student_id', Auth::guard('student')->id())->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        if (!$request->hasFile('profile_picture')) {
            return redirect()->back()->with('error', 'No file was uploaded.');
        }

        // Store the uploaded file in 'storage/app/public/profile_pictures'
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        if (!$path) {
            return redirect()->back()->with('error', 'File upload failed.');
        }

        // Update the student's profile_picture field
        $student->profile_picture = $path;
        $student->save(); // This should now work

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
    ///////END OF FUNCTIONS FOR STUDENT PROFILE///////////////////////
   public function grades()
    {
        // Get the current school year from settings
        $currentSchoolYear = Setting::getSchoolYear();
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()->route('student_login.homepage')->with('error', 'You must log in first.');
        }

        $currentSemester = Setting::getSemester(); // Get the current semester

        // Fetch grades with subject details for the student's current semester
        $grades = Grade::where('student_id', $student->student_id)
            ->where('semester', $currentSemester) // Only current semester
            ->with(['subject:id,code,name,units,day,time,room,teacher_id']) // Load relevant subject fields
            ->get();

        return view('student.grades', compact('student', 'grades', 'currentSemester', 'currentSchoolYear'));
    }



    public function schedule()
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()->route('student_login.homepage')->with('error', 'You must log in first.');
        }

        // Fetch subjects where the subject's `year` matches the student's `year_level`
        $subjects = $student->subjects()
            ->where('year', $student->year_level) // Ensure subject's `year` matches student's `year_level`
            ->get()
            ->groupBy('semester'); // Group after fetching data

        return view('student.schedule', compact('student', 'subjects'));
    }


    public function schoolCalendar()
    {
        $calendar = SchoolCalendar::latest()->first(); // Fetch the latest calendar entry
        return view('student/schoolCalendar', compact('calendar')); // Ensure this matches your Blade file
    }
    public function announcements()
    {
        $announcements = Announcement::latest()->take(9)->get(); // Fetch the latest 9 announcements
        return view('student/announcements', compact('announcements')); // Ensure this matches your Blade file
    }

    public function showSemesterSettings()
{
    $currentSemester = Setting::query()->value('current_semester');
    $currentSchoolYear = Setting::getSchoolYear(); // ← get the current school year

    return view('admin.semester-settings', compact('currentSemester', 'currentSchoolYear'));
}

}
