<?php

namespace App\Http\Controllers;
use App\Models\Grade;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;



class AdminDashboardController extends Controller
{
    public function index()
    {

        // Ensure the admin is logged in
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'You must log in first.');
        }

        return view('admin.dashboard'); // Ensure this file exists in views/admin/
    }

    public function settings()
    {
        // Get the current school year and semester from settings
        $currentSchoolYear = Setting::getSchoolYear();
        $currentSemester = Setting::getSemester();

        // Get the latest semester setting (you can also filter by current school year and semester if needed)
        $latestSemester = Setting::latest()->first();

        // Pass start and end dates to the view
        return view('admin.settings', compact(
            'currentSemester',
            'currentSchoolYear',
            'latestSemester'
        ));
    }

    public function updateSemester(Request $request)
    {
        $request->validate([
            'semester' => 'required|in:1,2,3'
        ]);

        // Update semester setting globally
        Setting::query()->update(['current_semester' => $request->semester]);
           // Save the start and end dates to the Setting model
    $startDate = $request->start_date;
    $endDate = $request->end_date; // Capture the end_date from the form


        // Update all students' semester
        Student::query()->update(['semester' => $request->semester]);

        // Fetch updated school year from settings
        $currentSchoolYear = Setting::getSchoolYear();

    // Reset the is_enrolled flag for all students when the semester is updated
    Student::query()->update(['is_enrolled' => 0]);
        // Get all students
        $students = Student::all();

        foreach ($students as $student) {
            Log::info("Processing student: {$student->student_id}");

            // Re-Add Failed or Null-Graded Subjects for the Same Semester
            $failedSubjects = Grade::where('student_id', $student->student_id)
                ->where(function ($query) {
                    $query->where('grade', '<=', 74.49)
                        ->orWhereNull('grade'); // Also check ungraded subjects
                })
                ->where('semester', $request->semester) // Only re-enroll for the current semester
                ->pluck('subject_id')
                ->toArray();

            $subjectsToReAdd = Subject::whereIn('id', $failedSubjects)->get();

            foreach ($subjectsToReAdd as $subject) {
                $alreadyReAdded = Grade::where('student_id', $student->student_id)
                    ->where('subject_id', $subject->id)
                    ->where('semester', $subject->semester)
                    ->where('school_year', $currentSchoolYear) //
                    ->exists();

                if (!$alreadyReAdded) {
                    Log::info("Re-adding failed/null subject: {$subject->id} for student: {$student->student_id}");

                    Grade::create([
                        'student_id' => $student->student_id,
                        'department_id' => $student->department_id,
                        'subject_id' => $subject->id,
                        'semester' => $subject->semester, // Same semester
                        'year_level' => $student->year_level, // Stay in current year
                        'school_year' => $currentSchoolYear, //
                        'grade' => null, // Reset grade
                    ]);
                }
            }

            // Add Next Eligible Subjects (After Passing Prerequisites)
            $passedSubjects = Grade::where('student_id', $student->student_id)
                ->where('grade', '>=', 74.50)
                ->pluck('subject_id')
                ->toArray();

            // Find subjects that require these passed subjects as prerequisites
            $newSubjects = Subject::whereIn('prerequisite_id', $passedSubjects)
                ->where('semester', $request->semester) // Use dynamic semester
                ->where('year', '<=', $student->year_level) //
                ->whereNotIn('id', Grade::where('student_id', $student->student_id)->pluck('subject_id')) // ✅ Exclude subjects already taken
                ->get();



            foreach ($newSubjects as $subject) {
                $alreadyTaken = Grade::where('student_id', $student->student_id)
                    ->where('subject_id', $subject->id)
                    ->where('school_year', $currentSchoolYear) //
                    ->exists();

                if (!$alreadyTaken) {
                    Log::info("Adding new subject: {$subject->id} (Prerequisite Passed) for student: {$student->student_id}");

                    Grade::create([
                        'student_id' => $student->student_id,
                        'department_id' => $student->department_id,
                        'subject_id' => $subject->id,
                        'semester' => $subject->semester,
                        'year_level' => $student->year_level, // Keep current year
                        'school_year' => $currentSchoolYear, //
                        'grade' => null, // Not yet graded
                    ]);
                }
            }

           // Special Case: Summer Subjects (Semester 3)
if ($request->semester == 3) {
    $summerSubjects = Subject::where('semester', 3)
        ->where('department_id', $student->department_id)
        ->where('year', $student->year_level) // ✅ Only subjects for current year level
        ->get();

    foreach ($summerSubjects as $subject) {
        $alreadyEnrolled = Grade::where('student_id', $student->student_id)
            ->where('subject_id', $subject->id)
            ->where('semester', 3)
            ->where('school_year', $currentSchoolYear)
            ->exists();

        if (!$alreadyEnrolled) {
            Log::info("Enrolling Student: {$student->student_id} in Summer Subject: {$subject->id}");

            Grade::create([
                'student_id' => $student->student_id,
                'department_id' => $student->department_id,
                'subject_id' => $subject->id,
                'semester' => 3,
                'year_level' => $subject->year, // Optional: can use subject year
                'school_year' => $currentSchoolYear,
                'grade' => null,
            ]);
        }
    }
}

        } // End of foreach ($students as $student)

        return redirect()->back()->with('success', 'Semester updated successfully. Failed/null subjects re-added, prerequisite subjects assigned, and summer subjects enrolled.');
    }



    public function incrementYearLevel()
    {
        // Get the current school year from settings
        $currentSchoolYear = Setting::getSchoolYear();
        $nextSchoolYear = $currentSchoolYear + 1;

        Log::info("Starting Year Level Increment | Current School Year: $currentSchoolYear | Next School Year: $nextSchoolYear");

        $students = Student::where('year_level', '<', 5)
                   ->where('is_graduated', false)
                   ->get();


        foreach ($students as $student) {
            Log::info("Processing Student: {$student->student_id} | Current Year Level: {$student->year_level}");
            // Graduation logic
if ($student->year_level == 4) {
    $totalSubjects = Subject::where('department_id', $student->department_id)->count();

    $passedSubjectsCount = Grade::where('student_id', $student->student_id)
        ->where('grade', '>=', 74.50)
        ->distinct('subject_id')
        ->count('subject_id');

    if ($passedSubjectsCount >= $totalSubjects) {
        $student->is_graduated = true;
        $student->save();

        Log::info("🎓 Student Graduated: {$student->student_id}");
        continue; // Skip further processing for this student
    }
}

            // 🔹 Get all passed subjects
            $passedSubjects = Grade::where('student_id', $student->student_id)
                ->where('grade', '>=', 74.50)
                ->pluck('subject_id')
                ->toArray();

            //Get only the subjects that are still failed and were never passed
            $failedSubjects = Grade::where('student_id', $student->student_id)
                ->where(function ($query) {
                    $query->where('grade', '<=', 74.49)
                        ->orWhereNull('grade');
                })
                ->whereNotIn('subject_id', $passedSubjects) //
                ->get();

           // Promote student only if they passed at least 5 subjects
            if (count($passedSubjects) >= 5) {
                $student->year_level += 1;
                $student->semester = 1;
                $student->school_year = $nextSchoolYear;
                $student->save();
                Log::info("Student Promoted | Student ID: {$student->student_id} | New Year Level: {$student->year_level}");
            } else {
                Log::info("Student: {$student->student_id} not promoted due to not meeting subject requirements (Passed: " . count($passedSubjects) . ").");
                continue; // Skip re-enrollment if the student is not promoted
            }


            //Re-enroll failed subjects in the same semester but next year level
            foreach ($failedSubjects as $failedSubject) {
                $newYearLevel = $student->year_level; // Moved to next year level
                $newSemester = $failedSubject->semester; // Keep the same semester

                $existingGrade = Grade::where('student_id', $student->student_id)
                    ->where('subject_id', $failedSubject->subject_id)
                    ->where('year_level', $newYearLevel)
                    ->where('school_year', $nextSchoolYear)
                    ->exists();

                if (!$existingGrade) {
                    Grade::create([
                        'student_id' => $student->student_id,
                        'department_id' => $student->department_id,
                        'subject_id' => $failedSubject->subject_id,
                        'semester' => $newSemester,
                        'year_level' => $newYearLevel,
                        'school_year' => $nextSchoolYear,
                        'grade' => null,
                    ]);
                    Log::info("Re-enrolled Failed Subject | Student ID: {$student->student_id} | Subject ID: {$failedSubject->subject_id} | Year Level: $newYearLevel | Semester: $newSemester");
                }
            }

            //Assign new subjects for the next year level
            $semesters = [1, 2];

            foreach ($semesters as $semester) {
                $newSubjects = Subject::where('year', $student->year_level)
                    ->where('semester', $semester)
                    ->where('department_id', $student->department_id)
                    ->get();

                foreach ($newSubjects as $subject) {
                    // Check if prerequisite is met
                    if ($subject->prerequisite_id) {
                        $prerequisiteGrade = Grade::where('student_id', $student->student_id)
                            ->where('subject_id', $subject->prerequisite_id)
                            ->where('grade', '>=', 74.50)
                            ->exists();

                        if (!$prerequisiteGrade) {
                            Log::info("Skipping Subject: {$subject->id} for Student: {$student->student_id} due to failed prerequisite.");
                            continue;
                        }
                    }

                    // Avoid duplicate subject enrollment
                    $existingGrade = Grade::where('student_id', $student->student_id)
                        ->where('subject_id', $subject->id)
                        ->where('school_year', $nextSchoolYear)
                        ->exists();

                    if (!$existingGrade) {
                        Grade::create([
                            'student_id' => $student->student_id,
                            'department_id' => $student->department_id,
                            'subject_id' => $subject->id,
                            'semester' => $semester,
                            'year_level' => $student->year_level,
                            'school_year' => $nextSchoolYear,
                            'grade' => null,
                        ]);
                        Log::info("Assigned New Subject | Student ID: {$student->student_id} | Subject ID: {$subject->id}");
                    }
                }
            }

            //Add subjects that became available after passing prerequisites
            $nextSubjects = Subject::whereIn('prerequisite_id', $passedSubjects)
                ->where('year', $student->year_level)
                ->get();

            foreach ($nextSubjects as $subject) {
                $alreadyEnrolled = Grade::where('student_id', $student->student_id)
                    ->where('subject_id', $subject->id)
                    ->where('school_year', $nextSchoolYear)
                    ->exists();

                if (!$alreadyEnrolled) {
                    Grade::create([
                        'student_id' => $student->student_id,
                        'department_id' => $student->department_id,
                        'subject_id' => $subject->id,
                        'semester' => $subject->semester,
                        'year_level' => $student->year_level,
                        'school_year' => $nextSchoolYear,
                        'grade' => null,
                    ]);
                    Log::info("Enrolled Subject After Prerequisite Passed | Student ID: {$student->student_id} | Subject ID: {$subject->id}");
                }
            }
        }

        //Update school year and reset semester in settings
        Setting::incrementSchoolYear();
        Setting::query()->update(['current_semester' => 1]);

        // 👇 This marks students as graduated
Student::where('year_level', '>', 4)
->whereNotExists(function ($query) {
    $query->select(DB::raw(1))
        ->from('grades')
        ->whereColumn('grades.student_id', 'students.student_id')
        ->where(function ($subquery) {
            $subquery->where('grade', '<', 74.5)
                     ->orWhereNull('grade');
        });
})
->update(['is_graduated' => true]);
Log::info("Graduation processing completed.");
        // Fetch the updated school year AFTER incrementing it
        $nextSchoolYear = Setting::getSchoolYear();

        Log::info("Year Level Increment Completed.");

        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return redirect()->back()->with('success', 'Students promoted, semester reset, failed subjects re-enrolled, and prerequisite subjects assigned.');
    }



     // Show the Admin Dashboard
     public function dashboard()
     {

        $totalStudents = Student::count(); // Get the total number of students
        $totalTeacher = Teacher::count(); // Get the total number of instructor
        $enrolledStudents = Student::where('is_enrolled', 1)->count();
        $notEnrolledStudents = Student::where('is_enrolled', 0)->count();
          // Get the current school year from settings
      $currentSchoolYear = Setting::getSchoolYear();
      $currentSemester = Setting::query()->value('current_semester');
        $departments = Department::withCount([
            'students as male_count' => function ($query) {
                $query->where('sex', 'Male');
            },
            'students as female_count' => function ($query) {
                $query->where('sex', 'Female');
            }
        ])->get();
        $graduates = Student::where('is_graduated', true)->get();
         return view('admin.dashboard', compact('departments', 'totalStudents', 'totalTeacher', 'graduates', 'currentSchoolYear', 'currentSemester', 'enrolledStudents', 'notEnrolledStudents'));
     }

     // ===========================
    // Change Password
    // ===========================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Get the currently authenticated admin
        $admin = Auth::guard('admin')->user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    // ===========================
    // Change Email
    // ===========================
    public function updateEmail(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_email' => 'required|email|confirmed|unique:admins,email',
        ]);

        // Get the currently authenticated admin
        $admin = Auth::guard('admin')->user();

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the email
        $admin->update([
            'email' => $request->new_email,
        ]);

        return back()->with('success', 'Email updated successfully!');
    }


    public function showGraduates()
    {
        $graduates = Student::where('is_graduated', true)->get();
        return view('admin.graduates', compact('graduates'));
    }

    // public function showSemesterSettings()
    // {
    //     // // Get current semester from settings
    //     // $currentSemester = Setting::query()->value('current_semester');

    //     // // Get current school year from settings (you can adjust this if you have a specific method like `getSchoolYear()`)
    //     // $currentSchoolYear = Setting::query()->value('school_year'); // Assuming 'school_year' is a field in your 'settings' table

    //     return view('admin.semester-settings', compact('currentSemester', 'currentSchoolYear'));
    // }

    public function assignTutorial(Request $request, $studentId)
{
    $student = Student::findOrFail($studentId);

    // Only loop through explicitly selected subjects
    foreach ($request->input('subjects', []) as $subjectId) {
        // Get re-enrollment year and semester from the request data
        $year = $request->input("re_enroll_data.$subjectId.year_level");
        $semester = $request->input("re_enroll_data.$subjectId.semester");

        // Check if the student is already enrolled in the subject for the given semester and year
        $alreadyEnrolled = $student->subjects()
            ->wherePivot('subject_id', $subjectId)
            ->wherePivot('year_level', $year)
            ->wherePivot('semester', $semester)
            ->exists();

        if (!$alreadyEnrolled) {
            // Attach the subject for tutorial with the provided year and semester
            $student->subjects()->attach($subjectId, [
                'year_level' => $year,
                'semester' => $semester,
                'grade' => null, // Initially, the grade will be null
                'department_id' => $student->department_id, // Optional, for reference
                'status' => 'tutorial' // Mark as 'tutorial'
            ]);
        } else {
            // Optional: You can add logic here if you want to handle the case where the subject is already enrolled
        }
    }

    return redirect()->back()->with('success', 'Tutorial subjects assigned successfully!');
}


    public function updateSemesterDates(Request $request)
    {
        // Validate the incoming request to ensure proper date format
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date', // end_date must be after start_date
        ]);

        // Get the first Setting record
        $setting = Setting::first();

        if ($setting) {
            // Update the start_date and end_date
            $setting->start_date = $request->start_date;
            $setting->end_date = $request->end_date;
            $setting->save();
        } else {
            // Optionally, create a new record if none exists
            Setting::create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'current_semester' => 1,  // Default to first semester
                'current_school_year' => date('Y'),
            ]);
        }

        // Return back with success message
        return back()->with('success', 'Semester dates updated successfully!');
    }

}
