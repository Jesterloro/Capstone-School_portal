<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // Display all subjects
    public function index(Request $request)
    {
        $subjects = Subject::with('department', 'teacher')->get();  // Retrieve all subjects and teachers
        $departments = Department::all(); // Fetch all departments

        // Fetch active teachers and group by department_id
        $teachers = Teacher::where('status', 'active')  // Ensure only active teachers
                    ->get()  // Get the active teachers
                    ->groupBy('department_id'); // Then group them by department_id

        $search = $request->input('search');

        $subjects = Subject::when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('department', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('teacher', function ($q) use ($search) { // Search by teacher name
                    $q->where('name', 'like', '%' . $search . '%');
                  });
        })->get();

        return view('admin.subject', compact('subjects', 'departments', 'teachers', 'search')); // Return the view with subjects data
    }


    // Store a newly created subject
    public function store(Request $request)
{
    // Validate the incoming data
    $request->validate([
        'code' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'units' => 'nullable|integer',
        'day' => 'nullable|string',
        'time' => 'nullable|string',
        'room' => 'nullable|string',
        'teacher_id' => 'nullable|exists:teachers,id', // Correct validation for teacher
        'department_id' => 'required|exists:departments,id', // Validate department_id
        'semester' => 'required|in:1,2',
        'year' => 'required|in:1,2,3,4',
    ]);

    // Create a new subject with the validated data
    Subject::create([
        'code' => $request->code,
        'name' => $request->name,
        'description' => $request->description,
        'units' => $request->units,
        'day' => $request->day,
        'time' => $request->time,
        'room' => $request->room,
        'teacher_id' => $request->teacher_id, // Saving selected teacher
        'department_id' => $request->department_id, // Saving department
        'semester' => $request->semester,
        'year' => $request->year,
    ]);

    return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
}


    // Show the form to edit an existing subject
    public function edit(Subject $subject)
    {
        $departments = Department::all();
        $teachers = Teacher::all()->groupBy('department_id'); // Get teachers grouped by department
        return view('admin.subject', compact('subject', 'departments', 'teachers'));
    }

    // Update the subject in the database
    public function update(Request $request, Subject $subject)
{
    // Validate the incoming data
    $request->validate([
        'code' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'units' => 'nullable|integer',
        'day' => 'nullable|string',
        'time' => 'nullable|string',
        'room' => 'nullable|string',
        'teacher_id' => 'nullable|exists:teachers,id', // Add this line to validate teacher_id
        'department_id' => 'required|exists:departments,id',
        'semester' => 'required|in:1,2,3',
        'year' => 'required|in:1,2,3,4',
    ]);

    // Update the subject with the validated data
    $subject->update([
        'code' => $request->code,
        'name' => $request->name,
        'description' => $request->description,
        'units' => $request->units,
        'day' => $request->day,
        'time' => $request->time,
        'room' => $request->room,
        'teacher_id' => $request->teacher_id, // Save the updated teacher_id
        'department_id' => $request->department_id,
        'semester' => $request->semester,
        'year' => $request->year,
    ]);

    return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
}

    // Delete the subject from the database
    public function destroy(Subject $subject)
    {
        // Delete the subject
        $subject->delete();

        // Redirect back with a success message
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
