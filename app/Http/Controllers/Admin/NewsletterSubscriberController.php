<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::latest();

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscribers = $query->paginate(20);

        $stats = [
            'total'         => NewsletterSubscriber::count(),
            'active'        => NewsletterSubscriber::where('status', 'active')->count(),
            'unsubscribed'  => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
            'today'         => NewsletterSubscriber::whereDate('created_at', today())->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return redirect()->back()->with('success', 'Subscriber deleted.');
    }

    public function toggleStatus(NewsletterSubscriber $subscriber)
    {
        $subscriber->update([
            'status' => $subscriber->status === 'active' ? 'unsubscribed' : 'active',
        ]);
        return response()->json(['success' => true, 'status' => $subscriber->status]);
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::where('status', 'active')->get();

        $csv = "Email,Status,Subscribed At\n";
        foreach ($subscribers as $s) {
            $csv .= "\"{$s->email}\",\"{$s->status}\",\"{$s->created_at->format('Y-m-d H:i')}\"\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
