<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = JobPost::with('postedBy:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($jobs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'company'       => 'required|string',
            'location'      => 'required|string',
            'type'          => 'required|in:full-time,part-time,internship,freelance',
            'industry'      => 'nullable|string',
            'deadline'      => 'nullable|date',
            'contact_email' => 'nullable|email',
        ]);

        $job = JobPost::create([
            'posted_by'     => $request->user()->id,
            'title'         => $request->title,
            'description'   => $request->description,
            'company'       => $request->company,
            'location'      => $request->location,
            'type'          => $request->type,
            'industry'      => $request->industry,
            'deadline'      => $request->deadline,
            'contact_email' => $request->contact_email,
        ]);

        return response()->json($job->load('postedBy:id,name,email'), 201);
    }

    public function destroy($id)
    {
        $job = JobPost::findOrFail($id);
        $job->delete();
        return response()->json(['message' => 'Job deleted successfully']);
    }
}