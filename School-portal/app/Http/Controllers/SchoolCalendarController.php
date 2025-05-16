<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolCalendar;
use Illuminate\Support\Facades\Storage;

class SchoolCalendarController extends Controller
{
    public function index()
    {
        $schoolCalendars = SchoolCalendar::all();
        return view('admin/schoolCalendar', compact('schoolCalendars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'semester' => 'required|string|max:255', // Removed as requested
            'sy' => 'required|max:255',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,pdf|max:20480' // Accept image & PDF
        ]);

        // Delete the most recent record if it exists
        $latestRecord = SchoolCalendar::latest()->first();
        if ($latestRecord) {
            $latestRecord->delete();
        }

        $data = $request->all();

        // Remove semester from data if it exists
        // unset($data['semester']); // Optional: ensure 'semester' isn't saved accidentally

        // Handle file upload (image or PDF)
        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('school_calendars', 'public');
            $data['image'] = $filePath;
        }

        // Store the new record
        SchoolCalendar::create($data);

        return redirect()->route('schoolCalendar.store')->with('success', 'School Calendar updated successfully.');
    }



    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'semester' => 'required|string|max:255',
    //         'sy' => 'required|max:255',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480'
    //     ]);

    //     $data = $request->all();

    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('school_calendars', 'public');
    //         $data['image'] = $imagePath;
    //     }

    //     SchoolCalendar::create($data);
    //     return redirect()->route('schoolCalendar.store')->with('success', 'School Calendar   added successfully.');
    // }

    public function update(Request $request, SchoolCalendar $schoolCalendar)
{
    $request->validate([
        'semester' => 'required|string|max:255',
        'sy' => 'required|max:255',
        'image' => 'nullable|mimes:jpg,jpeg,png,gif,pdf|max:20480' // Accept image & PDF
    ]);

    $data = $request->only(['semester', 'sy']);

    // Handle file upload (image or PDF)
    if ($request->hasFile('image')) {
        // Delete old file if it exists
        if ($schoolCalendar->image) {
            Storage::disk('public')->delete($schoolCalendar->image);
        }

        // Store new file
        $filePath = $request->file('image')->store('school_calendars', 'public');
        $data['image'] = $filePath;
    }

    $schoolCalendar->update($data);

    return redirect()->route('admin.schoolCalendar')->with('success', 'School Calendar updated successfully.');
}


    public function destroy(SchoolCalendar $schoolCalendar)
    {
        if ($schoolCalendar->image) {
            Storage::disk('public')->delete($schoolCalendar->image);
        }

        $schoolCalendar->delete();
        return redirect()->route('admin.announcements')->with('success', 'Announcement deleted successfully.');
    }
}
