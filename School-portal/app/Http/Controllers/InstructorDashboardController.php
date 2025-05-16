<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\SchoolCalendar;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InstructorDashboardController extends Controller
{


//     public function changePassword(Request $request)
// {
//     $request->validate([
//         'current_password' => 'required',
//         'new_password' => 'required|min:6|confirmed',
//     ]);

//     $teacher = Auth::guard('teacher')->user();

//     if (!Hash::check($request->current_password, $teacher->password)) {
//         return back()->withErrors(['current_password' => 'Current password is incorrect']);
//     }

//     $teacher->password = Hash::make($request->new_password);
//     $teacher->save();

//     return back()->with('success', 'Password changed successfully!');
// }

    public function dashboard()
{
    // Get the logged-in teacher
    $teacher = Auth::guard('teacher')->user();

    // Fetch the latest school calendar uploaded
    $calendar = SchoolCalendar::latest()->first();
      // Get the current school year from settings
      $currentSchoolYear = Setting::getSchoolYear();
      $currentSemester = Setting::query()->value('current_semester');

// Fetch subjects for the current semester assigned to the teacher
$subjects = $teacher->subjects()
    ->where('semester', $currentSemester)
    ->with('students')
    ->get();

$reTakingStudents = collect();

$editableGrades = Grade::whereIn('subject_id', $subjects->pluck('id'))
    ->whereNotNull('grade') // ✅ Exclude NULL grades
    ->get();

foreach ($editableGrades as $grade) {
    // Only consider grades that are 0 (INC) or <= 74.49 (Failed)
    if ($grade->grade == 0 || $grade->grade <= 74.49) {
        // Attach a custom reason for frontend display
        $status = $grade->grade == 0 ? 'INC' : 'Failed - Retaking next school year';

        $student = $grade->student;
        $student->retake_status = $status;
        $student->subject = $grade->subject; // Optional: if you want to show which subject

        $reTakingStudents->push($student);
    }
}
    // 🔹 Get subjects the instructor is handling with student count
    $subjects = $teacher->subjects()
        ->withCount(['students' => function ($query) {
            $query->distinct('student_id');
        }])
        ->orderBy('year', 'asc')
        ->orderBy('semester', 'asc')
        ->get();

        // Fetch the latest semester from the database (if needed)
    $latestSemester = Setting::latest()->first();

    // Pass data to the view
    return view('instructor.instructorDashboard', compact('teacher', 'calendar', 'subjects', 'reTakingStudents', 'editableGrades', 'currentSchoolYear', 'currentSemester', 'latestSemester'));
}

    public function sched()
    {
        $teacher = Auth::guard('teacher')->user();

        // Fetch schedules (images) for the instructor's department
        $scheduleImages = ClassModel::where('department_id', $teacher->department_id)->get();

        return view('instructor.instructorSched', compact('scheduleImages'));
    }

    public function studGrade()
    {
        $teacher = Auth::guard('teacher')->user();
        $currentYear = Setting::orderBy('id', 'desc')->value('current_school_year') ?? date('Y');

        // Fetch all subjects assigned to the teacher (no semester filter)
        $subjects = $teacher->subjects()
            ->with([
                'students' => function ($query) use ($currentYear) {
                    $query->whereHas('grades', function ($gradeQuery) use ($currentYear) {
                        $gradeQuery->where('school_year', $currentYear);
                    })->orWhereDoesntHave('grades');
                },
                'department'
            ])
            ->get();

        // Fetch tutorial subjects (no semester filter here either)
        $tutorialSubjects = $teacher->subjects()
            ->whereHas('grades', function ($query) use ($currentYear) {
                $query->where('status', 'tutorial')
                      ->where('school_year', $currentYear);
            })
            ->with([
                'students' => function ($query) use ($currentYear) {
                    $query->whereHas('grades', function ($gradeQuery) use ($currentYear) {
                        $gradeQuery->where('school_year', $currentYear)
                                   ->where('status', 'tutorial');
                    });
                }
            ])
            ->get();

        // Combine both sets
        $allSubjects = $subjects->merge($tutorialSubjects);

        // Editable grades across all semesters
        $editableGrades = Grade::whereIn('id', function ($query) use ($teacher, $currentYear) {
            $query->selectRaw('MAX(id)')
                ->from('grades')
                ->whereIn('subject_id', $teacher->subjects()->pluck('id'))
                ->where('school_year', $currentYear)
                ->groupBy('student_id', 'subject_id');
        })
        ->orderByDesc('school_year')
        ->orderByDesc('id')
        ->get();

        return view('instructor.studGrade', [
            'subjects' => $allSubjects,
            'editableGrades' => $editableGrades,
            // currentSemester is no longer needed if you're showing all
        ]);
    }






    public function updateGrade(Request $request)
{
    $request->validate([
        'student_id' => 'required',
        'subject_id' => 'required',
        'grade' => 'nullable|numeric|min:0|max:100',
    ]);

    $currentYear = Setting::orderBy('id', 'desc')->value('current_school_year') ?? date('Y');

    // Retrieve student and subject details
    $student = Student::where('student_id', $request->student_id)->first();
    $subject = Subject::find($request->subject_id);

    if (!$student || !$subject) {
        return redirect()->back()->with('error', 'Student or Subject not found.');
    }

    $departmentId = $subject->department_id;

    // Find the most recent grade record (latest retake) **only for the current school year**
    $grade = Grade::where([
        'student_id' => $request->student_id,
        'subject_id' => $request->subject_id,
        'semester' => $subject->semester,
        'department_id' => $departmentId,
        'school_year' => $currentYear, // 🔹 Restrict to current school year
    ])
    ->latest()
    ->first();

    if (!$grade) {
        Log::info('No existing grade found, creating new entry...', [
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'year_level' => $student->year_level,
            'school_year' => $currentYear,
            'semester' => $subject->semester,
            'department_id' => $departmentId,
            'new_grade' => $request->grade
        ]);

        // Insert a new grade if no record exists
        Grade::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'year_level' => $student->year_level,
            'semester' => $subject->semester,
            'school_year' => $currentYear,
            'department_id' => $departmentId,
            'grade' => $request->grade
        ]);
    } else {
        Log::info('Updating grade...', [
            'id' => $grade->id,
            'student_id' => $grade->student_id,
            'subject_id' => $grade->subject_id,
            'year_level' => $grade->year_level,
            'old_grade' => $grade->grade,
            'new_grade' => $request->grade
        ]);

        // Update only the most recent record
        $grade->update(['grade' => $request->grade]);
    }

    Log::info('Latest grade updated successfully.');

    return redirect()->back()->with('success', 'Grade updated successfully.');
}

    public function schoolCalendar()
    {
        $calendar = SchoolCalendar::latest()->first();
        return view('instructor/schoolCalendar', compact('calendar'));
    }
    public function announcements()
    {
        $announcements = Announcement::latest()->take(9)->get(); // Fetch the latest 9 announcements
        return view('instructor/announcements', compact('announcements'));
    }

    public function setIncomplete(Request $request)
{
    $grade = Grade::updateOrCreate(
        [
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
        ],
        [
            'grade' => 0, // Set grade to 0 to indicate INC
            'updated_at' => now(),
        ]
    );

    return back()->with('success', 'Grade marked as INC successfully!');
}

// In your controller function
public function showTutorialSubjects()
{
    $editableGrades = Grade::with('subject', 'student') // Load subjects and students with the grades
                            ->where('status', 'tutorial')
                            ->get();

    $subjects = Subject::all(); // Load all subjects, or filter based on your criteria

    return view('your_view_name', compact('editableGrades', 'subjects'));
}
public function updateTutorialGrades(Request $request)
{
    foreach ($request->grades as $gradeId => $newGrade) {
        $grade = Grade::find($gradeId);
        if ($grade && $grade->status === 'tutorial') {
            $grade->grade = $newGrade;
            $grade->save();
        }
    }

    return redirect()->back()->with('success', 'Tutorial grades updated successfully.');
}



}
