<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RetailerDashboardController extends Controller
{
    /**
     * Show retailer dashboard
     */
    public function index()
    {
        return view('dashboards.retailer');
    }
}
