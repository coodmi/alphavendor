<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class PageContentController extends Controller
{
    public function terms()
    {
        $page = PageContent::where('page_type', 'terms')->first();
        return view('admin.page-contents.terms', compact('page'));
    }

    public function updateTerms(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        PageContent::updateOrCreate(
            ['page_type' => 'terms'],
            $validated
        );

        return redirect()->route('admin.page-contents.terms')
            ->with('success', 'Terms & Conditions updated successfully!');
    }

    public function privacy()
    {
        $page = PageContent::where('page_type', 'privacy')->first();
        return view('admin.page-contents.privacy', compact('page'));
    }

    public function updatePrivacy(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        PageContent::updateOrCreate(
            ['page_type' => 'privacy'],
            $validated
        );

        return redirect()->route('admin.page-contents.privacy')
            ->with('success', 'Privacy Policy updated successfully!');
    }

    public function shipping()
    {
        $page = PageContent::where('page_type', 'shipping')->first();
        return view('admin.page-contents.shipping', compact('page'));
    }

    public function updateShipping(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        PageContent::updateOrCreate(
            ['page_type' => 'shipping'],
            $validated
        );

        return redirect()->route('admin.page-contents.shipping')
            ->with('success', 'Shipping Info updated successfully!');
    }
}
