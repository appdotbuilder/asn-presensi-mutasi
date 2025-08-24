<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the role-based dashboard.
     */
    public function index(Request $request, DashboardService $dashboardService)
    {
        $user = $request->user();
        
        if ($user->isAsn()) {
            return $dashboardService->getAsnDashboard($user);
        } elseif ($user->isOperatorOpd()) {
            return $dashboardService->getOperatorOpdDashboard($user);
        } elseif ($user->isAdmin()) {
            return $dashboardService->getAdminDashboard($user);
        }
        
        return Inertia::render('dashboard', [
            'stats' => []
        ]);
    }
}