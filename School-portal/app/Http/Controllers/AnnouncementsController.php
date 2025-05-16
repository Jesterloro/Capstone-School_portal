<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $announcements = Announcement::all();
        return view('admin/announcements', compact('announcements'));
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
    // Validate the incoming data
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'file' => 'nullable|mimes:pdf,doc,docx|max:5120', // Max size 5MB for PDF/Word files
    ]);

    // Create a new announcement
    $announcement = new Announcement();
    $announcement->title = $request->title;
    $announcement->description = $request->description;

    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('announcements/images', 'public');
        $announcement->image = $imagePath;
    }

    // Handle file upload (PDF/Word)
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('announcements/files', 'public');
        $announcement->file = $filePath;
    }

    $announcement->save();

    // Redirect with success message
    return redirect()->route('announcements.store')->with('success', 'Announcement added successfully.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();
        return redirect()->route('admin.announcements')->with('deleted', 'Announcement deleted successfully.');

    }
}
