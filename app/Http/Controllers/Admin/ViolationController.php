<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationRule;
use App\Models\SellerViolation;
use App\Models\User;
use App\Models\Order;
use App\Services\ViolationService;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    protected $violationService;

    public function __construct(ViolationService $violationService)
    {
        $this->violationService = $violationService;
    }

    /**
     * Show violation rules management
     */
    public function rules()
    {
        $rules = ViolationRule::orderBy('rule_name_en')->get();
        return view('admin.violations.rules', compact('rules'));
    }

    /**
     * Store new violation rule
     */
    public function storeRule(Request $request)
    {
        $validated = $request->validate([
            'rule_code' => 'required|unique:violation_rules,rule_code',
            'rule_name_en' => 'required|string|max:255',
            'rule_name_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'penalty_type' => 'required|in:fixed,percentage',
            'penalty_amount' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        ViolationRule::create($validated);

        return redirect()->back()->with('success', 'Violation rule created successfully');
    }

    /**
     * Update violation rule
     */
    public function updateRule(Request $request, ViolationRule $rule)
    {
        $validated = $request->validate([
            'rule_name_en' => 'required|string|max:255',
            'rule_name_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'penalty_type' => 'required|in:fixed,percentage',
            'penalty_amount' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $rule->update($validated);

        return redirect()->back()->with('success', 'Violation rule updated successfully');
    }

    /**
     * Delete violation rule
     */
    public function destroyRule(ViolationRule $rule)
    {
        $rule->delete();
        return redirect()->back()->with('success', 'Violation rule deleted successfully');
    }

    /**
     * Show violations list
     */
    public function index(Request $request)
    {
        $query = SellerViolation::with(['seller', 'order', 'rule']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by seller
        if ($request->has('seller_id') && $request->seller_id != '') {
            $query->where('seller_id', $request->seller_id);
        }

        $violations = $query->latest()->paginate(20);
        $sellers = User::whereIn('role', ['retailer', 'wholesaler', 'importer', 'exporter'])
            ->orderBy('name')
            ->get();

        return view('admin.violations.index', compact('violations', 'sellers'));
    }

    /**
     * Create manual violation
     */
    public function create()
    {
        $sellers = User::whereIn('role', ['retailer', 'wholesaler', 'importer', 'exporter'])
            ->orderBy('name')
            ->get();
        $rules = ViolationRule::where('is_active', true)->get();
        
        return view('admin.violations.create', compact('sellers', 'rules'));
    }

    /**
     * Store manual violation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'seller_id' => 'required|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'rule_code' => 'required|exists:violation_rules,rule_code',
            'custom_penalty' => 'nullable|numeric|min:0'
        ]);

        $this->violationService->createViolation(
            $validated['seller_id'],
            $validated['rule_code'],
            $validated['order_id'] ?? null,
            $validated['custom_penalty'] ?? null
        );

        return redirect()->route('admin.violations.index')
            ->with('success', 'Violation created and admins notified');
    }

    /**
     * Approve violation
     */
    public function approve(Request $request, SellerViolation $violation)
    {
        $this->violationService->approveViolation(
            $violation->id,
            $request->admin_notes
        );

        return redirect()->back()->with('success', 'Violation approved');
    }

    /**
     * Waive violation
     */
    public function waive(Request $request, SellerViolation $violation)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string'
        ]);

        $this->violationService->waiveViolation(
            $violation->id,
            $validated['admin_notes']
        );

        return redirect()->back()->with('success', 'Violation waived');
    }
}
