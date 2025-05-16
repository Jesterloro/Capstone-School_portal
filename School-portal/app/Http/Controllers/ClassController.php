<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with('department')->get();
        $departments = Department::all(); // Fetch all departments
        return view('admin.classes', compact('classes', 'departments'));
    }


    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'department_id' => 'required|exists:departments,id',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
    ]);

    $data = $request->all();

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('classes', 'public');
        $data['image'] = $imagePath;
    }

    ClassModel::create($data);
    return redirect()->route('classes.index')->with('success', 'Class added successfully.');
}


    public function destroy(ClassModel $class)
    {
        if ($class->image) {
            Storage::disk('public')->delete($class->image);
        }

        $class->delete();
        return redirect()->route('classes.index')->with('deleted', 'Class deleted successfully.');
    }
}
