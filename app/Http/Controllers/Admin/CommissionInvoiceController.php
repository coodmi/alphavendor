<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionInvoice;
use App\Models\Order;
use App\Services\CommissionInvoiceService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CommissionInvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(CommissionInvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * List all invoices
     */
    public function index(Request $request)
    {
        $query = CommissionInvoice::with(['seller', 'order']);

        // Filter by seller
        if ($request->has('seller_id') && $request->seller_id != '') {
            $query->where('seller_id', $request->seller_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(20);

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show invoice details
     */
    public function show(CommissionInvoice $invoice)
    {
        $invoice->load(['seller', 'order', 'items']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Generate invoice for an order
     */
    public function generate(Order $order)
    {
        // Check if invoice already exists
        $existingInvoice = $this->invoiceService->getInvoiceForOrder($order->id);
        
        if ($existingInvoice) {
            return redirect()->route('admin.invoices.show', $existingInvoice)
                ->with('info', 'Invoice already exists for this order');
        }

        $invoice = $this->invoiceService->generateInvoice($order);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice generated successfully');
    }

    /**
     * Download invoice as PDF
     */
    public function downloadPdf(CommissionInvoice $invoice)
    {
        $invoice->load(['seller', 'order', 'items']);

        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        
        $filename = $invoice->invoice_number . '.pdf';
        
        // Save PDF to storage
        $pdfPath = 'invoices/' . $filename;
        Storage::put($pdfPath, $pdf->output());
        
        // Update invoice with PDF path
        $invoice->update(['pdf_path' => $pdfPath]);
        
        return $pdf->download($filename);
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(CommissionInvoice $invoice)
    {
        $invoice->markAsPaid();
        
        return redirect()->back()->with('success', 'Invoice marked as paid');
    }
}
