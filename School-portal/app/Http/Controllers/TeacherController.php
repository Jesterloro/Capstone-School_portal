<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teachers = Teacher::all();
        $departments = Department::all();
         // Get active teachers
    $activeTeachers = Teacher::where('status', 'active')->get();

    // Get inactive teachers
    $inactiveTeachers = Teacher::where('status', 'inactive')->get();
        $search = $request->input('search');

        $teachers = Teacher::when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        })
        ->orderBy('department_id')// sorting of department
        ->orderBy('name')// sorting by name
        ->paginate(10);


        $allTeachers = Teacher::with('department')
    ->orderBy('department_id')
    ->orderBy('name')
    ->get();

        return view('admin.teachers', compact('teachers','departments','search', 'activeTeachers', 'inactiveTeachers', 'allTeachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:teachers,email',
        'password' => 'required|string|min:6',
        'department_id' => 'required|exists:departments,id',
        'phoneNumber' => 'nullable|numeric',
    ]);

    Teacher::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'department_id' => $request->department_id,
        'phoneNumber' => $request->phoneNumber,
    ]);

    return redirect()->back()->with('success', 'Teacher added successfully!');
}

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {

    // }

    //for viewing the Subjects and Students in Admin/Teachers
    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);

        $currentSemester = Setting::first()->current_semester;

        // Corrected: use `where` instead of `wherePivot` inside `whereHas`
        $subjects = Subject::where('teacher_id', $teacher->id)
            ->whereHas('students', function ($query) use ($currentSemester) {
                $query->where('grades.semester', $currentSemester); // reference the actual pivot table column
            })
            ->with([
                'students' => function ($query) use ($currentSemester) {
                    $query->wherePivot('semester', $currentSemester)
                          ->withPivot('grade', 'year_level', 'semester');
                },
                'department'
            ])
            ->get();

        $subjectsByDepartment = $subjects->groupBy('department_id');

        return view('admin.show', compact('teacher', 'subjects', 'subjectsByDepartment'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'department_id' => 'required|string|max:255',
            'phoneNumber' => 'nullable|numeric',
            'password' => 'nullable|string|min:6', // Add this line for optional password
        ]);

        // Prepare the data to update
        $data = $request->only('name', 'email', 'department_id', 'phoneNumber');

        // If password is provided, hash and include it
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Update the teacher record
        $teacher->update($data);

        return redirect()->back()->with('success', 'Teacher updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->back()->with('deleted', 'Teacher deleted successfully!');
    }



    public function updateStatus($id)
    {
        $teacher = Teacher::findOrFail($id);

        // Toggle the teacher's status
        $teacher->status = $teacher->status == 'active' ? 'inactive' : 'active';
        $teacher->save();

        // Redirect back to the teachers list
        return redirect()->route('teachers.index')->with('status', 'Teacher status updated!');
    }

    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $teacher = Auth::guard('teacher')->user();

    if (!Hash::check($request->current_password, $teacher->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect']);
    }

    $teacher->password = Hash::make($request->new_password);
    $teacher->save();

    return back()->with('success', 'Password changed successfully!');
}


}
