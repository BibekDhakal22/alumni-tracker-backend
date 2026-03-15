<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AlumniProfile;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index()
    {
        $alumni = User::where('role', 'alumni')
            ->with('alumniProfile')
            ->get();
        return response()->json($alumni);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->alumniProfile;

        $request->validate([
            'batch_year'  => 'nullable|string',
            'phone'       => 'nullable|string',
            'address'     => 'nullable|string',
            'current_job' => 'nullable|string',
            'company'     => 'nullable|string',
            'industry'    => 'nullable|string',
            'linkedin'    => 'nullable|string',
            'status'      => 'nullable|in:employed,unemployed,studying',
        ]);

        $profile->update($request->all());

        return response()->json($user->load('alumniProfile'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Alumni deleted successfully']);
    }
}