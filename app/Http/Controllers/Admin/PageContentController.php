<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class PageContentController extends Controller
{
    private function getPage(string $type)
    {
        return PageContent::where('page_type', $type)->first();
    }

    private function updatePage(Request $request, string $type, string $successRoute, string $previewRoute = null)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'content'          => 'required|string',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        PageContent::updateOrCreate(['page_type' => $type], $validated);

        return redirect()->route($successRoute)->with('success', 'Page updated successfully!');
    }

    // ── Terms & Conditions ──────────────────────────────────────────────────
    public function terms()
    {
        $page = $this->getPage('terms');
        return view('admin.page-contents.terms', compact('page'));
    }

    public function updateTerms(Request $request)
    {
        return $this->updatePage($request, 'terms', 'admin.page-contents.terms');
    }

    // ── Privacy Policy ──────────────────────────────────────────────────────
    public function privacy()
    {
        $page = $this->getPage('privacy');
        return view('admin.page-contents.privacy', compact('page'));
    }

    public function updatePrivacy(Request $request)
    {
        return $this->updatePage($request, 'privacy', 'admin.page-contents.privacy');
    }

    // ── Shipping Info ───────────────────────────────────────────────────────
    public function shipping()
    {
        $page = $this->getPage('shipping');
        return view('admin.page-contents.shipping', compact('page'));
    }

    public function updateShipping(Request $request)
    {
        return $this->updatePage($request, 'shipping', 'admin.page-contents.shipping');
    }

    // ── Exchange Policy ─────────────────────────────────────────────────────
    public function exchange()
    {
        $page = $this->getPage('exchange');
        return view('admin.page-contents.exchange', compact('page'));
    }

    public function updateExchange(Request $request)
    {
        return $this->updatePage($request, 'exchange', 'admin.page-contents.exchange');
    }

    // ── Return & Refund ─────────────────────────────────────────────────────
    public function returnRefund()
    {
        $page = $this->getPage('return-refund');
        return view('admin.page-contents.return-refund', compact('page'));
    }

    public function updateReturnRefund(Request $request)
    {
        return $this->updatePage($request, 'return-refund', 'admin.page-contents.return-refund');
    }
}
