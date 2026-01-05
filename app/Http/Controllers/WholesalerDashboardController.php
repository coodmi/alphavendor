<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WholesalerDashboardController extends Controller
{
    /**
     * Show wholesaler dashboard
     */
    public function index()
    {
        return view('dashboards.wholesaler');
    }
}
