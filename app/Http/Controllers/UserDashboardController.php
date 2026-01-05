<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    /**
     * Show user dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $pendingApplication = $user->pendingRoleApplication;

        return view('dashboards.user', compact('pendingApplication'));
    }
}
