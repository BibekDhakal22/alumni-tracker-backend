<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('createdBy:id,name,email')
            ->orderBy('event_date', 'asc')
            ->get();
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'location'      => 'required|string',
            'event_date'    => 'required|date',
            'type'          => 'required|in:reunion,seminar,workshop,networking,other',
            'max_attendees' => 'nullable|integer',
            'contact_email' => 'nullable|email',
        ]);

        $event = Event::create([
            'created_by'    => $request->user()->id,
            'title'         => $request->title,
            'description'   => $request->description,
            'location'      => $request->location,
            'event_date'    => $request->event_date,
            'type'          => $request->type,
            'max_attendees' => $request->max_attendees,
            'contact_email' => $request->contact_email,
        ]);

        return response()->json($event->load('createdBy:id,name,email'), 201);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }
}