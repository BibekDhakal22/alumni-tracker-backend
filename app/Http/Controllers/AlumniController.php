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

    public function export()
{
    $alumni = User::where('role', 'alumni')
        ->with('alumniProfile')
        ->get();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="alumni_export.csv"',
    ];

    $callback = function() use ($alumni) {
        $file = fopen('php://output', 'w');

        // CSV Header row
        fputcsv($file, [
            'ID', 'Name', 'Email', 'Role',
            'Batch Year', 'Phone', 'Address',
            'Job Title', 'Company', 'Industry',
            'LinkedIn', 'Status', 'Registered On'
        ]);

        // Data rows
        foreach ($alumni as $a) {
            fputcsv($file, [
                $a->id,
                $a->name,
                $a->email,
                $a->role,
                $a->alumni_profile?->batch_year ?? '',
                $a->alumni_profile?->phone ?? '',
                $a->alumni_profile?->address ?? '',
                $a->alumni_profile?->current_job ?? '',
                $a->alumni_profile?->company ?? '',
                $a->alumni_profile?->industry ?? '',
                $a->alumni_profile?->linkedin ?? '',
                $a->alumni_profile?->status ?? '',
                $a->created_at->format('Y-m-d'),
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}