<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $existing = NewsletterSubscriber::where('email', $request->email)->first();

        if ($existing) {
            if ($existing->status === 'unsubscribed') {
                $existing->update(['status' => 'active']);
                return response()->json(['success' => true, 'message' => 'You have been re-subscribed!']);
            }
            return response()->json(['success' => false, 'message' => 'This email is already subscribed.'], 409);
        }

        NewsletterSubscriber::create([
            'email'      => $request->email,
            'status'     => 'active',
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Thank you for subscribing!']);
    }
}
