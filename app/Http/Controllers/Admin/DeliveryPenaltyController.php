<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPenaltyRule;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DeliveryPenaltyController extends Controller
{
    /**
     * Show penalty rules management page.
     */
    public function index()
    {
        $rules = DeliveryPenaltyRule::orderBy('days_threshold')->get();

        // Recent penalty transactions
        $recentPenalties = Transaction::where('type', 'penalty')
            ->with(['vendor', 'order'])
            ->latest()
            ->take(20)
            ->get();

        // Stats
        $stats = [
            'total_penalties'  => Transaction::where('type', 'penalty')->count(),
            'total_amount'     => Transaction::where('type', 'penalty')->sum('amount'),
            'pending_orders'   => Order::whereNotNull('confirmed_at')
                                       ->whereNotIn('status', ['delivered', 'cancelled'])
                                       ->where('penalty_applied', false)
                                       ->count(),
        ];

        return view('admin.delivery-penalty.index', compact('rules', 'recentPenalties', 'stats'));
    }

    /**
     * Store a new penalty rule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'days_threshold' => 'required|integer|min:1|max:365',
            'penalty_amount' => 'required|numeric|min:1|max:99999',
            'description'    => 'nullable|string|max:255',
            'is_active'      => 'boolean',
        ]);

        // Check for duplicate threshold
        if (DeliveryPenaltyRule::where('days_threshold', $validated['days_threshold'])->exists()) {
            return back()->with('error', $validated['days_threshold'] . ' দিনের জন্য একটি rule ইতিমধ্যে আছে।');
        }

        DeliveryPenaltyRule::create([
            'days_threshold' => $validated['days_threshold'],
            'penalty_amount' => $validated['penalty_amount'],
            'description'    => $validated['description'] ?? $validated['days_threshold'] . ' দিনের বেশি দেরি',
            'is_active'      => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Penalty rule সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Update an existing rule.
     */
    public function update(Request $request, DeliveryPenaltyRule $rule)
    {
        $validated = $request->validate([
            'days_threshold' => 'required|integer|min:1|max:365',
            'penalty_amount' => 'required|numeric|min:1|max:99999',
            'description'    => 'nullable|string|max:255',
            'is_active'      => 'boolean',
        ]);

        // Check duplicate (excluding self)
        if (DeliveryPenaltyRule::where('days_threshold', $validated['days_threshold'])
                ->where('id', '!=', $rule->id)->exists()) {
            return back()->with('error', $validated['days_threshold'] . ' দিনের জন্য একটি rule ইতিমধ্যে আছে।');
        }

        $rule->update([
            'days_threshold' => $validated['days_threshold'],
            'penalty_amount' => $validated['penalty_amount'],
            'description'    => $validated['description'] ?? $rule->description,
            'is_active'      => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Penalty rule আপডেট হয়েছে।');
    }

    /**
     * Toggle active status.
     */
    public function toggle(DeliveryPenaltyRule $rule)
    {
        $rule->update(['is_active' => !$rule->is_active]);
        return back()->with('success', 'Rule ' . ($rule->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়') . ' করা হয়েছে।');
    }

    /**
     * Delete a rule.
     */
    public function destroy(DeliveryPenaltyRule $rule)
    {
        $rule->delete();
        return back()->with('success', 'Penalty rule মুছে ফেলা হয়েছে।');
    }

    /**
     * Manually run the penalty check (for testing / immediate apply).
     */
    public function runNow()
    {
        \Artisan::call('violations:check-orders');
        $output = \Artisan::output();
        return back()->with('success', 'Penalty check চালানো হয়েছে। ' . trim($output));
    }
}
