<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSettings();
        return view('admin.site-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'site_name' => 'required|string|max:255',
                'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'site_description' => 'nullable|string',
                'footer_text' => 'nullable|string',
                'footer_copyright' => 'nullable|string',
                'developer_name' => 'nullable|string|max:255',
                'developer_url' => 'nullable|url',
                'facebook_url' => 'nullable|url',
                'twitter_url' => 'nullable|url',
                'instagram_url' => 'nullable|url',
                'linkedin_url' => 'nullable|url',
                'payment_logo_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'payment_logo_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'payment_logo_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'contact_email' => 'nullable|email',
                'contact_phone' => 'nullable|string|max:20',
                'contact_address' => 'nullable|string',
                'bank_name' => 'nullable|string|max:255',
                'bank_account_name' => 'nullable|string|max:255',
                'bank_account_number' => 'nullable|string|max:255',
                'bank_branch' => 'nullable|string|max:255',
                'bank_routing_number' => 'nullable|string|max:255',
            ]);

            $settings = SiteSetting::getSettings();

            // Prepare data for update
            $updateData = $request->only([
                'site_name',
                'site_description',
                'footer_text',
                'footer_copyright',
                'developer_name',
                'developer_url',
                'facebook_url',
                'twitter_url',
                'instagram_url',
                'linkedin_url',
                'contact_email',
                'contact_phone',
                'contact_address',
                'bank_name',
                'bank_account_name',
                'bank_account_number',
                'bank_branch',
                'bank_routing_number',
            ]);

            // Handle site logo upload
            if ($request->hasFile('site_logo')) {
                // Delete old logo if exists
                if ($settings->site_logo && Storage::disk('public')->exists($settings->site_logo)) {
                    Storage::disk('public')->delete($settings->site_logo);
                }
                
                $updateData['site_logo'] = $request->file('site_logo')->store('site-settings', 'public');
            }

            // Handle payment logo 1 upload
            if ($request->hasFile('payment_logo_1')) {
                if ($settings->payment_logo_1 && Storage::disk('public')->exists($settings->payment_logo_1)) {
                    Storage::disk('public')->delete($settings->payment_logo_1);
                }
                $updateData['payment_logo_1'] = $request->file('payment_logo_1')->store('site-settings/payment-logos', 'public');
            }

            // Handle payment logo 2 upload
            if ($request->hasFile('payment_logo_2')) {
                if ($settings->payment_logo_2 && Storage::disk('public')->exists($settings->payment_logo_2)) {
                    Storage::disk('public')->delete($settings->payment_logo_2);
                }
                $updateData['payment_logo_2'] = $request->file('payment_logo_2')->store('site-settings/payment-logos', 'public');
            }

            // Handle payment logo 3 upload
            if ($request->hasFile('payment_logo_3')) {
                if ($settings->payment_logo_3 && Storage::disk('public')->exists($settings->payment_logo_3)) {
                    Storage::disk('public')->delete($settings->payment_logo_3);
                }
                $updateData['payment_logo_3'] = $request->file('payment_logo_3')->store('site-settings/payment-logos', 'public');
            }

            $settings->update($updateData);

            return redirect()->route('admin.site-settings.index')
                ->with('success', 'Site settings updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.site-settings.index')
                ->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }
}
