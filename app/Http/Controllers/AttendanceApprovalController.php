<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceApprovalController extends Controller
{
    /**
     * Approve attendance (OPD Operator only).
     */
    public function store(Request $request, Attendance $attendance)
    {
        $user = $request->user();
        
        if (!$user->isOperatorOpd()) {
            return redirect()->route('attendances.index')->with('error', 'Only OPD operators can approve attendance.');
        }
        
        if ($attendance->user->opd_id !== $user->opd_id) {
            return redirect()->route('attendances.index')->with('error', 'You can only approve attendance from your OPD.');
        }
        
        $validated = $request->validate([
            'approval_notes' => 'nullable|string|max:500',
            'action' => 'required|in:approve,reject',
        ]);
        
        $approvalStatus = $validated['action'] === 'approve' ? 'approved' : 'rejected';
        
        $attendance->update([
            'approval_status' => $approvalStatus,
            'approved_by' => $user->id,
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'],
        ]);
        
        $message = $approvalStatus === 'approved' ? 'Attendance approved successfully.' : 'Attendance rejected successfully.';
        
        return redirect()->route('attendances.show', $attendance)->with('success', $message);
    }
}