<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Total counts
        $totalAlumni = User::where('role', 'alumni')->count();
        $totalJobs   = JobPost::count();

        // Employment status breakdown
        $employmentStats = DB::table('alumni_profiles')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Industry breakdown
        $industryStats = DB::table('alumni_profiles')
            ->select('industry', DB::raw('count(*) as count'))
            ->whereNotNull('industry')
            ->where('industry', '!=', '')
            ->groupBy('industry')
            ->orderByDesc('count')
            ->get();

        // Batch year breakdown
        $batchStats = DB::table('alumni_profiles')
            ->select('batch_year', DB::raw('count(*) as count'))
            ->whereNotNull('batch_year')
            ->groupBy('batch_year')
            ->orderBy('batch_year')
            ->get();

        // Alumni registered per month (last 12 months)
        $growthStats = DB::table('users')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as count')
            )
            ->where('role', 'alumni')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return response()->json([
            'total_alumni'     => $totalAlumni,
            'total_jobs'       => $totalJobs,
            'employment_stats' => $employmentStats,
            'industry_stats'   => $industryStats,
            'batch_stats'      => $batchStats,
            'growth_stats'     => $growthStats,
        ]);
    }
}