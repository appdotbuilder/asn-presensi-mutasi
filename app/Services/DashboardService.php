<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Mutation;
use App\Models\User;
use App\Models\Opd;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardService
{
    /**
     * Display ASN dashboard.
     */
    public function getAsnDashboard(User $user)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Get today's attendance
        $todayAttendance = $user->attendances()
            ->where('date', $today)
            ->first();
        
        // Get monthly attendance stats
        $monthlyAttendanceCount = $user->attendances()
            ->where('date', '>=', $thisMonth)
            ->count();
            
        $monthlyPresentCount = $user->attendances()
            ->where('date', '>=', $thisMonth)
            ->where('status', 'present')
            ->count();
        
        // Get pending mutations
        $pendingMutations = $user->mutations()
            ->whereIn('status', ['draft', 'submitted', 'opd_review', 'bkpsdm_review'])
            ->count();
        
        // Get recent attendances
        $recentAttendances = $user->attendances()
            ->with('approver')
            ->latest('date')
            ->take(5)
            ->get();
        
        // Get recent mutations
        $recentMutations = $user->mutations()
            ->with(['fromOpd', 'toOpd'])
            ->latest()
            ->take(3)
            ->get();
        
        return Inertia::render('dashboard', [
            'userRole' => 'asn',
            'stats' => [
                'todayAttendance' => $todayAttendance,
                'monthlyAttendanceCount' => $monthlyAttendanceCount,
                'monthlyPresentCount' => $monthlyPresentCount,
                'pendingMutations' => $pendingMutations,
            ],
            'recentAttendances' => $recentAttendances,
            'recentMutations' => $recentMutations,
        ]);
    }

    /**
     * Display OPD Operator dashboard.
     */
    public function getOperatorOpdDashboard(User $user)
    {
        if (!$user->opd_id) {
            return Inertia::render('dashboard', [
                'userRole' => 'operator_opd',
                'error' => 'You are not assigned to any OPD. Please contact your administrator.',
            ]);
        }
        
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Get ASN users in this OPD
        $opdAsnUsers = User::where('opd_id', $user->opd_id)
            ->where('role', 'asn')
            ->count();
        
        // Get pending attendance approvals
        $pendingAttendances = Attendance::whereHas('user', function ($query) use ($user) {
                $query->where('opd_id', $user->opd_id);
            })
            ->where('approval_status', 'pending')
            ->count();
        
        // Get pending mutations for OPD review
        $pendingMutations = Mutation::whereHas('user', function ($query) use ($user) {
                $query->where('opd_id', $user->opd_id);
            })
            ->where('status', 'opd_review')
            ->count();
        
        // Get today's attendance summary
        $todayAttendanceStats = Attendance::whereHas('user', function ($query) use ($user) {
                $query->where('opd_id', $user->opd_id);
            })
            ->where('date', $today)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        // Get recent attendances for review
        $recentAttendances = Attendance::whereHas('user', function ($query) use ($user) {
                $query->where('opd_id', $user->opd_id);
            })
            ->with(['user', 'user.profile'])
            ->where('approval_status', 'pending')
            ->latest('date')
            ->take(10)
            ->get();
        
        // Get recent mutations for review
        $recentMutations = Mutation::whereHas('user', function ($query) use ($user) {
                $query->where('opd_id', $user->opd_id);
            })
            ->with(['user', 'user.profile', 'fromOpd', 'toOpd'])
            ->where('status', 'opd_review')
            ->latest()
            ->take(5)
            ->get();
        
        return Inertia::render('dashboard', [
            'userRole' => 'operator_opd',
            'opd' => $user->opd,
            'stats' => [
                'opdAsnUsers' => $opdAsnUsers,
                'pendingAttendances' => $pendingAttendances,
                'pendingMutations' => $pendingMutations,
                'todayAttendanceStats' => $todayAttendanceStats,
            ],
            'recentAttendances' => $recentAttendances,
            'recentMutations' => $recentMutations,
        ]);
    }

    /**
     * Display Admin dashboard.
     */
    public function getAdminDashboard(User $user)
    {
        $today = Carbon::today();
        
        // Get total counts
        $totalAsn = User::where('role', 'asn')->count();
        $totalOpds = Opd::active()->count();
        $totalOperators = User::where('role', 'operator_opd')->count();
        
        // Get pending items
        $pendingAttendances = Attendance::where('approval_status', 'pending')->count();
        $pendingMutations = Mutation::where('status', 'bkpsdm_review')->count();
        
        // Get today's attendance summary across all OPDs
        $todayAttendanceStats = Attendance::where('date', $today)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        // Get recent system activities
        $recentMutations = Mutation::with(['user', 'user.profile', 'fromOpd', 'toOpd'])
            ->where('status', 'bkpsdm_review')
            ->latest()
            ->take(10)
            ->get();
        
        // Get OPD statistics
        $opdStats = Opd::withCount(['users as asn_count' => function ($query) {
                $query->where('role', 'asn');
            }])
            ->active()
            ->get();
        
        return Inertia::render('dashboard', [
            'userRole' => 'admin',
            'stats' => [
                'totalAsn' => $totalAsn,
                'totalOpds' => $totalOpds,
                'totalOperators' => $totalOperators,
                'pendingAttendances' => $pendingAttendances,
                'pendingMutations' => $pendingMutations,
                'todayAttendanceStats' => $todayAttendanceStats,
            ],
            'recentMutations' => $recentMutations,
            'opdStats' => $opdStats,
        ]);
    }
}