<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AttendanceService $attendanceService)
    {
        $user = $request->user();
        
        if ($user->isAsn()) {
            return $attendanceService->getAsnAttendances($user, $request);
        } elseif ($user->isOperatorOpd()) {
            return $attendanceService->getOperatorAttendances($user, $request);
        } elseif ($user->isAdmin()) {
            return $attendanceService->getAdminAttendances($request);
        }
        
        return redirect()->route('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('attendances/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        if (!$user->isAsn()) {
            return redirect()->route('dashboard')->with('error', 'Only ASN can record attendance.');
        }
        
        $validated = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:present,absent,late,sick,leave,business_trip',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Check if attendance already exists for this date
        $existingAttendance = $user->attendances()
            ->where('date', $validated['date'])
            ->first();
        
        if ($existingAttendance) {
            return redirect()->back()->withErrors(['date' => 'Attendance for this date already exists.']);
        }
        
        $attendance = $user->attendances()->create($validated);
        
        return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        $user = request()->user();
        
        // Check permissions
        if ($user->isAsn() && $attendance->user_id !== $user->id) {
            return redirect()->route('attendances.index')->with('error', 'You can only view your own attendance.');
        }
        
        if ($user->isOperatorOpd() && $attendance->user->opd_id !== $user->opd_id) {
            return redirect()->route('attendances.index')->with('error', 'You can only view attendance from your OPD.');
        }
        
        $attendance->load(['user', 'user.profile', 'user.opd', 'approver']);
        
        return Inertia::render('attendances/show', [
            'attendance' => $attendance,
            'userRole' => $user->role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $user = request()->user();
        
        if (!$user->isAsn() || $attendance->user_id !== $user->id) {
            return redirect()->route('attendances.index')->with('error', 'You can only edit your own attendance.');
        }
        
        if ($attendance->approval_status !== 'pending') {
            return redirect()->route('attendances.show', $attendance)->with('error', 'Cannot edit approved/rejected attendance.');
        }
        
        return Inertia::render('attendances/edit', [
            'attendance' => $attendance,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $user = $request->user();
        
        if (!$user->isAsn() || $attendance->user_id !== $user->id) {
            return redirect()->route('attendances.index')->with('error', 'You can only update your own attendance.');
        }
        
        if ($attendance->approval_status !== 'pending') {
            return redirect()->route('attendances.show', $attendance)->with('error', 'Cannot update approved/rejected attendance.');
        }
        
        $validated = $request->validate([
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:present,absent,late,sick,leave,business_trip',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $attendance->update($validated);
        
        return redirect()->route('attendances.show', $attendance)->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $user = request()->user();
        
        if (!$user->isAsn() || $attendance->user_id !== $user->id) {
            return redirect()->route('attendances.index')->with('error', 'You can only delete your own attendance.');
        }
        
        if ($attendance->approval_status !== 'pending') {
            return redirect()->route('attendances.show', $attendance)->with('error', 'Cannot delete approved/rejected attendance.');
        }
        
        $attendance->delete();
        
        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully.');
    }


}