<?php

namespace App\Http\Controllers;

use App\Models\MentorshipRequest;
use App\Models\User;
use Illuminate\Http\Request;

class MentorshipController extends Controller
{
    // Get all mentors (employed alumni)
    public function mentors()
    {
        $mentors = User::where('role', 'alumni')
            ->whereHas('alumniProfile', function($q) {
                $q->where('status', 'employed');
            })
            ->with('alumniProfile')
            ->get();
        return response()->json($mentors);
    }

    // Send a mentorship request
    public function store(Request $request)
    {
        $request->validate([
            'mentor_id' => 'required|exists:users,id',
            'topic'     => 'required|string|max:255',
            'message'   => 'required|string',
        ]);

        // Check if request already exists
        $existing = MentorshipRequest::where('student_id', $request->user()->id)
            ->where('mentor_id', $request->mentor_id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You already have a pending request with this mentor'
            ], 422);
        }

        $mentorship = MentorshipRequest::create([
            'student_id' => $request->user()->id,
            'mentor_id'  => $request->mentor_id,
            'topic'      => $request->topic,
            'message'    => $request->message,
        ]);

        return response()->json(
            $mentorship->load('student:id,name,email', 'mentor:id,name,email'),
            201
        );
    }

    // Get my requests (as student)
    public function myRequests(Request $request)
    {
        $requests = MentorshipRequest::where('student_id', $request->user()->id)
            ->with('mentor:id,name,email', 'mentor.alumniProfile')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($requests);
    }

    // Get requests received (as mentor)
    public function receivedRequests(Request $request)
    {
        $requests = MentorshipRequest::where('mentor_id', $request->user()->id)
            ->with('student:id,name,email', 'student.alumniProfile')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($requests);
    }

    // Accept or reject a request
    public function respond(Request $request, $id)
    {
        $request->validate([
            'status'   => 'required|in:accepted,rejected',
            'response' => 'nullable|string',
        ]);

        $mentorship = MentorshipRequest::findOrFail($id);

        if ($mentorship->mentor_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mentorship->update([
            'status'   => $request->status,
            'response' => $request->response,
        ]);

        return response()->json($mentorship->load('student:id,name,email'));
    }
}