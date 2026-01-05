<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExporterDashboardController extends Controller
{
    /**
     * Show exporter dashboard
     */
    public function index()
    {
        return view('dashboards.exporter');
    }
}
