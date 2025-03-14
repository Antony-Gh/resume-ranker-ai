<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResumeController extends Controller
{

    public function upload(Request $request)
    {
        $request->validate([
            'resume' => 'required|mimes:pdf,docx|max:2048',
        ]);

        $path = $request->file('resume')->store('resumes', 'public');

        Resume::create([
            'user_id' => auth()->id(),
            'file_path' => $path,
        ]);

        return back()->with('success', 'Resume uploaded successfully');
    }
}
