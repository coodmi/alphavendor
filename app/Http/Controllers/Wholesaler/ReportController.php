<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Services\VendorReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private VendorReportService $reports
    ) {}

    public function index(Request $request)
    {
        return view('wholesaler.reports.index', $this->reports->build($request));
    }
}
