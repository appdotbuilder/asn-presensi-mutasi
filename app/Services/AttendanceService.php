<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Show ASN attendance records.
     */
    public function getAsnAttendances(User $user, Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        
        $attendances = $user->attendances()
            ->with('approver')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->paginate(20);
        
        return Inertia::render('attendances/index', [
            'attendances' => $attendances,
            'currentMonth' => $month,
            'userRole' => 'asn',
        ]);
    }

    /**
     * Show OPD operator attendance management.
     */
    public function getOperatorAttendances(User $user, Request $request)
    {
        if (!$user->opd_id) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned to any OPD.');
        }
        
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $status = $request->get('status', 'all');
        $approval = $request->get('approval', 'all');
        
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        
        $query = Attendance::whereHas('user', function ($query) use ($user) {
                $query->where('opd_id', $user->opd_id);
            })
            ->with(['user', 'user.profile', 'approver'])
            ->whereBetween('date', [$startDate, $endDate]);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($approval !== 'all') {
            $query->where('approval_status', $approval);
        }
        
        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return Inertia::render('attendances/index', [
            'attendances' => $attendances,
            'currentMonth' => $month,
            'filters' => [
                'status' => $status,
                'approval' => $approval,
            ],
            'userRole' => 'operator_opd',
            'opd' => $user->opd,
        ]);
    }

    /**
     * Show admin attendance overview.
     */
    public function getAdminAttendances(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $opd = $request->get('opd', 'all');
        $status = $request->get('status', 'all');
        
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        
        $query = Attendance::with(['user', 'user.profile', 'user.opd', 'approver'])
            ->whereBetween('date', [$startDate, $endDate]);
        
        if ($opd !== 'all') {
            $query->whereHas('user', function ($query) use ($opd) {
                $query->where('opd_id', $opd);
            });
        }
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $opds = \App\Models\Opd::active()->get();
        
        return Inertia::render('attendances/index', [
            'attendances' => $attendances,
            'currentMonth' => $month,
            'opds' => $opds,
            'filters' => [
                'opd' => $opd,
                'status' => $status,
            ],
            'userRole' => 'admin',
        ]);
    }
}