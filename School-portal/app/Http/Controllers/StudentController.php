<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
// Display a listing of the resource.
 // Display a listing of the students
 public function index(Request $request)
{
    $search = $request->input('search');
    $departments = Department::all();

   // Fetch active students (not graduated)
   $students = Student::when($search, function ($query, $search) {
    $query->where('last_name', 'like', '%' . $search . '%')
          ->orWhere('student_id', 'like', '%' . $search . '%')
          ->orWhere('first_name', 'like', '%' . $search . '%');
})
->where('is_graduated', false)  // Ensure only active students
->with(['department','grades.subject'])  // Eager load grades and subjects
->orderBy('department_id')  // Sort by department
->orderBy('year_level')
->orderBy('last_name')  // Then sort by last name
->paginate(40);
$adminStudent = Student::all();     // for full list (hidden input or elsewhere)
      // Fetch all students (without pagination) for the modal, sorted by department and last name
      $allStudents = Student::where('is_graduated', false)  // Only active students
      ->with(['department'])  // Eager load department
      ->orderBy('department_id')  // Sort by department
      ->orderBy('year_level')
      ->orderBy('last_name')  // Then sort by last name
      ->get();  // Fetch all students


  // Log student grades and subjects for debugging
  foreach ($students as $student) {
    foreach ($student->grades as $grade) {
        Log::info("Student: " . $student->first_name . " " . $student->last_name . " - Subject: " . $grade->subject->name . " - Grade: " . $grade->grade);
    }
}
    // Organize subjects by year & semester
    $groupedSubjects = [];
    foreach ($students as $student) {
        foreach ($student->grades as $grade) {
            $subject = $grade->subject;
            $yearLevel = $subject->year;
            $semester = $subject->semester;

            $groupedSubjects[$student->student_id][$yearLevel][$semester][] = $subject;
        }
    }
     // Fetch graduated students to be shown in the modal
    $graduates = Student::where('is_graduated', true)
    ->with('department')  // Eager load department
    ->get();
    $enrolledStudents = $students->where('is_enrolled', 1)->count();
    $notEnrolledStudents = Student::where('is_enrolled', 0)
    ->where('is_graduated', false)
    ->with('department')
    ->orderBy('department_id')
    ->orderBy('year_level')
    ->orderBy('last_name')
    ->get();


    return view('admin.students', compact('students', 'departments', 'groupedSubjects','graduates', 'allStudents', 'enrolledStudents', 'notEnrolledStudents'));
}


// Show the form for creating a new resource.
public function create()
{
    return view('admin.students.create');  // Show form to create new student
}

// Store a newly created resource in storage.
public function store(Request $request)
    {
        try {
            // Log incoming request
            Log::info('Received student registration data:', $request->all());

            // Validate request
            $validatedData = $request->validate([
                'student_id' => 'required|string|max:255|unique:students,student_id',
                'last_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'required|string|max:255',
                // 'suffix' => 'required|string|max:255',
                // 'section' => 'required|string|max:255',
                'age' => 'required|integer|between:1,150',
                'sex' => 'required|string|max:255',
                'bdate' => 'required|date',
                'bplace' => 'required|string|max:255',
                'civil_status' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'cell_no' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
                'email' => 'required|email|unique:students,email',
                'father_last_name' => 'required|string|max:255',
                'father_first_name' => 'required|string|max:255',
                'father_middle_name' => 'required|string|max:255',
                'mother_last_name' => 'required|string|max:255',
                'mother_first_name' => 'required|string|max:255',
                'mother_middle_name' => 'required|string|max:255',
                'elem_school_name' => 'required|string|max:255',
                'hs_school_name' => 'required|string|max:255',
                'tertiary_school_name' => 'required|string|max:255',
                'elem_grad_year' => 'required|integer|between:1900,' . date('Y'),
                'hs_grad_year' => 'required|integer|between:1900,' . date('Y'),
                'tertiary_grad_year' => 'required|integer|between:1900,' . date('Y'),
                'department_id' => 'required|exists:departments,id',
                'year_level' => 'required|integer|between:1,4',
                'semester' => 'required|integer|between:1,2',
                'password' => 'required|string|min:6',
            ]);

            // Create the student (default as regular)
            $validatedData['regular'] = 1; // Assume regular initially
            $student = Student::create($validatedData);

            // Determine if prerequisites should be ignored
            if ($student->year_level > 1) {
                // Ignore prerequisites for students in 2nd year and above
                $allSubjects = Subject::where('department_id', $student->department_id)
                    ->where('year', '<=', $student->year_level)
                    ->get();
            } else {
                // Enforce prerequisites for 1st-year students
                $allSubjects = Subject::where('department_id', $student->department_id)
                    ->where('year', '<=', $student->year_level)
                    ->where(function ($query) use ($student) {
                        $query->whereNull('prerequisite_id')
                            ->orWhereIn('prerequisite_id', function ($subQuery) use ($student) {
                                $subQuery->select('subject_id')
                                    ->from('grades')
                                    ->where('student_id', $student->student_id)
                                    ->where('grade', '>=', 75); // Passed prerequisite
                            });
                    })
                    ->get();
            }

            if ($allSubjects->isEmpty()) {
                Log::warning("No subjects found for department ID: " . $student->department_id);
                return back()->with('error', 'No subjects found for this department.');
            }

            // Insert all subjects with NULL grades
            foreach ($allSubjects as $subject) {
                Log::info('Assigning subject: ' . $subject->id . ' to student: ' . $student->student_id);

                Grade::create([
                    'student_id' => (string) $student->student_id,
                    'department_id' => $student->department_id,
                    'subject_id' => $subject->id,
                    'semester' => $subject->semester,
                    'year_level' => $subject->year,
                    'grade' => null, // Grade is initially null
                    'enrolled' => 1, // Automatically set to enrolled
                ]);
            }

            // The student is always considered regular initially
            Log::info("Student ID {$student->student_id} set as Regular");

            return redirect()->route('students.index')->with('success', 'Student added successfully with assigned subjects.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while storing student data: ' . $e->getMessage());
            return back()->withInput()->with('error', 'A database error occurred while adding the student.');
        } catch (Exception $e) {
            Log::error('Error storing student data: ' . $e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while adding the student. Please try again.');
        }
    }


// Display the specified resource.
public function show(Student $student)
    {
        return view('students.show', compact('students'));  // Show single student's details
    }

// Show the form for editing the specified resource.
public function edit(Student $student_id)
    {
        $student = Student::findOrFail($student_id);
        return view('students.edit', compact('students'));  // Show form to edit student
    }

    public function updateGrades(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);

        // Update grades for existing subjects
        foreach ($request->subjects as $subjectId => $data) {
            Grade::where('student_id', $student->student_id)
                ->where('subject_id', $subjectId)
                ->update(['grade' => $data['grade']]);
        }

        // Fetch all subjects the student has taken with grades
        $grades = Grade::where('student_id', $student->student_id)->get();

        // Check if student has any failing grades
        $hasFailingGrade = $grades->where('grade', '<=', 74.49)->isNotEmpty();

        // Update student's regular status
        $student->update(['regular' => $hasFailingGrade ? 0 : 1]);

        return redirect()->back()->with('success', 'Grades updated successfully.');
    }
    // public function updateGrades(Request $request, $studentId)
    // {
    //     $student = Student::findOrFail($studentId);

    //     // Update grades for existing subjects
    //     foreach ($request->subjects as $subjectId => $data) {
    //         Grade::where('student_id', $student->student_id)
    //             ->where('subject_id', $subjectId)
    //             ->update(['grade' => $data['grade']]);
    //     }

    //     // Fetch all subjects the student has taken with grades
    //     $grades = Grade::where('student_id', $student->student_id)->get();
    //     $passedSubjects = $grades->where('grade', '>=', 75)->pluck('subject_id')->toArray();

    //     // Find new subjects that have passed subjects as prerequisites
    //     $newSubjects = Subject::whereNotIn('id', $grades->pluck('subject_id')) // Avoid duplicates
    //         ->whereNotNull('prerequisite_id') // Ensure the subject has a prerequisite
    //         ->where('year', $student->year_level) // Only subjects for the student's current year level
    //         ->get();


    //     foreach ($newSubjects as $subject) {
    //         // Get the prerequisite subject's grade
    //         $prerequisiteGrade = $grades->where('subject_id', $subject->prerequisite_id)->first();

    //         // Only add the subject if the prerequisite exists **and** has a passing grade (>= 75)
    //         if ($prerequisiteGrade && $prerequisiteGrade->grade >= 75) {
    //             Log::info("Adding subject: {$subject->id} for student: {$student->student_id}");

    //             Grade::create([
    //                 'student_id' => $student->student_id,
    //                 'department_id' => $student->department_id,
    //                 'subject_id' => $subject->id,
    //                 'semester' => $subject->semester,
    //                 'year_level' => $student->year_level, // Keep current year
    //                 'grade' => null, // Not yet graded
    //             ]);
    //         }
    //     }

    //     // Check if student has any failing grades
    //     $hasFailingGrade = $grades->where('grade', '<', 75)->isNotEmpty();

    //     // Update student's regular status
    //     $student->update(['regular' => $hasFailingGrade ? 0 : 1]);

    //     return redirect()->back()->with('success', 'Grades updated successfully.');
    // }






// Update the specified resource in storage.
public function update(Request $request, Student $student)
{
    $validated = $request->validate([
        'student_id' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'age' => 'required|integer|between:1,150',
            'sex' => 'required|string|max:255',
            'bdate' => 'required|date',
            'bplace' => 'required|string|max:255',
            'civil_status' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'cell_no' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'email' => 'required|email',
            'father_last_name' => 'required|string|max:255',
            'father_first_name' => 'required|string|max:255',
            'father_middle_name' => 'required|string|max:255',
            'mother_last_name' => 'required|string|max:255',
            'mother_first_name' => 'required|string|max:255',
            'mother_middle_name' => 'required|string|max:255',
            'elem_school_name' => 'required|string|max:255',
            'hs_school_name' => 'required|string|max:255',
            'tertiary_school_name' => 'required|string|max:255',
            'elem_grad_year' => 'required|integer|between:1900,' . date('Y'),
            'hs_grad_year' => 'required|integer|between:1900,' . date('Y'),
            'tertiary_grad_year' => 'required|integer|between:1900,' . date('Y'),
            'password' => 'nullable|string|min:6',
            // 'department_id' => 'required|exists:departments,id',
    ]);
    if ($request->filled('password')) {
        $validated['password'] = bcrypt($request->password);
    } else {
        unset($validated['password']); // ✅ Prevents overwriting with NULL
    }
    $student->update($validated);

    return redirect()->route('students.index')->with('success', 'Student updated successfully.');
}


// Remove the specified resource from storage.
public function destroy(Student $student)
{
    $student->delete();  // Delete the student from database
    return redirect()->route('students.index')->with('deleted','Student deleted successfully');  // Redirect to the student index page
}

public function toggleEnrollment(Student $student)
{
    $student->is_enrolled = !$student->is_enrolled;
    $student->save();

    return back()->with('success', 'Student enrollment status updated.');
}
public function promoteSemester()
{
    DB::beginTransaction();
    try {
        // Promote year_level for all students below year 4
        Log::info('Promoting year level...');
        Student::where('year_level', '<', 4)->increment('year_level');

        // Reset all students' is_enrolled status to false
        Log::info('Resetting is_enrolled...');
        Student::query()->update(['is_enrolled' => false]);

        // Reset enrolled field in grades
        Log::info('Resetting enrolled field in grades...');
        Grade::query()->update(['enrolled' => 0]);

        DB::commit();
        Log::info('Promotion completed and enrolled status reset.');
        return back()->with('success', 'All students promoted and enrollment statuses reset.');
    } catch (Exception $e) {
        DB::rollBack();
        Log::error("Semester promotion failed: " . $e->getMessage());
        return back()->with('error', 'Something went wrong during promotion.');
    }
}

public function showGraduatedStudents()
{
    $graduates = Student::where('is_graduated', true)->get();


    return view('students.index', compact('graduates')); // assuming your modal is part of the 'students.index' view
}
public function showStudents()
{
    // Fetch active students (those who are not graduated)
    $students = Student::where('year_level', '<', 5)  // Only include students with year level less than 5
                      ->where('is_graduated', false)  // Exclude graduated students
                      ->get();

    // Fetch graduated students
    $graduates = Student::where('is_graduated', true)->get();

    // Graduation Logic: Automatically graduate students who meet the criteria
    Student::where('year_level', 4)  // Only those in year level 4 (final year)
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

    return view('admin.students', compact('students', 'graduates'));
}
public function graduateStudent($student_id)
{
    $student = Student::findOrFail($student_id);
    $student->is_graduated = true;
    $student->graduation_date = now(); // You can set the graduation date here
    $student->save();

    return redirect()->route('admin.students')->with('success', 'Student graduated successfully.');
}
public function getStudentGrades($studentId)
{
    // Fetch the student's grades and subjects
    $student = Student::with(['grades.subject'])->find($studentId);

    // Return the grades and subjects as a JSON response
    return response()->json($student->grades);
}


public function showStudentGrades($studentId)
{
    // Retrieve the student with their subjects and grades, grouped by year level and semester
    $student = Student::with(['subjects' => function($query) {
        $query->orderBy('pivot.year_level')->orderBy('pivot.semester');
    }])->findOrFail($studentId);

    // Group subjects by year level and semester
    $groupedSubjects = $student->subjects->groupBy(function($subject) {
        return $subject->pivot->year_level . '-' . $subject->pivot->semester;
    });

    return view('your-view', compact('student', 'groupedSubjects'));
}


// public function enroll($student_id)
// {
//     $student = Student::where('student_id', $student_id)->firstOrFail();
//     $student->is_enrolled = 1;
//     $student->save();

//     return redirect()->back()->with('success', $student->first_name . ' has been enrolled successfully.');
// }
// public function showNotEnrolledStudents()
// {
//     $notEnrolledStudents = Student::where('is_enrolled', false)->get();
//     return view('students.not-enrolled', compact('notEnrolledStudents'));
// }

// In StudentController.php
public function bulkEnroll(Request $request)
{
    if ($request->has('select_all')) {
        Student::query()->update(['is_enrolled' => 1]);
        return redirect()->back()->with('success', 'All students have been enrolled.');
    }

    $student_ids = $request->input('student_ids', []);

    if (count($student_ids) > 0) {
        Student::whereIn('student_id', $student_ids)
            ->update(['is_enrolled' => 1]);

        return redirect()->back()->with('success', 'Selected students have been enrolled successfully.');
    }

    return redirect()->back()->with('error', 'No students selected for enrollment.');
}





public function updateProfile(Request $request)
{
    try {
        $student = Auth::guard('student')->user();

        if (!$student) {
            Log::warning('No authenticated student found.');
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'cell_no' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'address' => 'required|string|max:255',
            'bplace' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'bdate' => 'nullable|date',
            'sex' => 'required|string',
            'civil_status' => 'required|string',

            'father_first_name' => 'required|string|max:255',
            'father_middle_name' => 'required|string|max:255',
            'father_last_name' => 'required|string|max:255',

            'mother_first_name' => 'required|string|max:255',
            'mother_middle_name' => 'required|string|max:255',
            'mother_last_name' => 'required|string|max:255',

            'elem_school_name' => 'required|string|max:255',
            'elem_grad_year' => 'required|integer|between:1900,' . date('Y'),

            'hs_school_name' => 'required|string|max:255',
            'hs_grad_year' => 'required|integer|between:1900,' . date('Y'),

            'tertiary_school_name' => 'required|string|max:255',
            'tertiary_grad_year' => 'required|integer|between:1900,' . date('Y'),
        ]);

        $student->update($validated);

        Log::info('Student updated successfully', ['student_id' => $student->id]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    } catch (Exception $e) {
        Log::error('Error updating student profile', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);

        return redirect()->back()->with('error', 'Something went wrong while updating the profile.');
    }
}




}
