<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PageContentSeeder extends Seeder
{
    public function run()
    {
        // Terms & Conditions Content
        PageContent::updateOrCreate(
            ['page_type' => 'terms'],
            [
                'title' => 'Terms & Conditions',
                'content' => $this->getTermsContent(),
                'is_active' => true,
            ]
        );

        // Privacy Policy Content
        PageContent::updateOrCreate(
            ['page_type' => 'privacy'],
            [
                'title' => 'Privacy Policy',
                'content' => $this->getPrivacyContent(),
                'is_active' => true,
            ]
        );

        // Shipping Info Content
        PageContent::updateOrCreate(
            ['page_type' => 'shipping'],
            [
                'title' => 'Shipping Information',
                'content' => $this->getShippingContent(),
                'is_active' => true,
            ]
        );
    }

    private function getTermsContent()
    {
        return <<<'HTML'
<div class="space-y-8">
    <p class="text-lg">Welcome to <strong>ARMarketBD</strong>. These terms and conditions outline the rules and regulations for the use of our website and services.</p>
    
    <div>
        <h3 class="text-xl font-bold mb-3">1. Account Terms</h3>
        <ul class="list-disc pl-6 space-y-2">
            <li>You must be at least 18 years old to use our services</li>
            <li>You are responsible for maintaining the security of your account</li>
            <li>You must provide accurate and complete information</li>
        </ul>
    </div>
    
    <div>
        <h3 class="text-xl font-bold mb-3">2. Products & Services</h3>
        <ul class="list-disc pl-6 space-y-2">
            <li>All products are subject to availability</li>
            <li>We reserve the right to limit quantities</li>
            <li>Product descriptions may be updated without notice</li>
        </ul>
    </div>
    
    <div>
        <h3 class="text-xl font-bold mb-3">3. Pricing & Payment</h3>
        <ul class="list-disc pl-6 space-y-2">
            <li>All prices are in BDT unless stated otherwise</li>
            <li>We accept various payment methods</li>
            <li>Payment must be received before processing</li>
        </ul>
    </div>
</div>
HTML;
    }

    private function getPrivacyContent()
    {
        return <<<'HTML'
<div class="space-y-8">
    <p class="text-lg">At <strong>ARMarketBD</strong>, we are committed to protecting your privacy and ensuring the security of your personal information.</p>
    
    <div>
        <h3 class="text-xl font-bold mb-3">1. Information We Collect</h3>
        <h4 class="font-semibold mb-2">Personal Information</h4>
        <p class="mb-2">We may collect personal information that you provide to us, including:</p>
        <ul class="list-disc pl-6 space-y-2">
            <li>Name, email address, and phone number</li>
            <li>Shipping and billing addresses</li>
            <li>Payment information</li>
            <li>Account credentials</li>
        </ul>
    </div>
    
    <div>
        <h3 class="text-xl font-bold mb-3">2. How We Use Your Information</h3>
        <ul class="list-disc pl-6 space-y-2">
            <li>Process and fulfill your orders</li>
            <li>Communicate with you about orders</li>
            <li>Send promotional emails (with consent)</li>
            <li>Improve our services</li>
            <li>Prevent fraud and enhance security</li>
        </ul>
    </div>
    
    <div>
        <h3 class="text-xl font-bold mb-3">3. Data Security</h3>
        <p>We implement various security measures including SSL encryption, secure payment processing, and regular security audits.</p>
    </div>
</div>
HTML;
    }

    private function getShippingContent()
    {
        return <<<'HTML'
<div class="space-y-8">
    <p class="text-lg">We offer reliable shipping services across Bangladesh with multiple delivery options.</p>
    
    <div>
        <h3 class="text-xl font-bold mb-3">Shipping Methods</h3>
        <ul class="list-disc pl-6 space-y-2">
            <li><strong>Standard Delivery:</strong> 3-5 business days</li>
            <li><strong>Express Delivery:</strong> 1-2 business days</li>
            <li><strong>Same Day Delivery:</strong> Available in Dhaka city</li>
        </ul>
    </div>
    
    <div>
        <h3 class="text-xl font-bold mb-3">Shipping Charges</h3>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Zone</th>
                    <th class="border border-gray-300 px-4 py-2">Delivery Time</th>
                    <th class="border border-gray-300 px-4 py-2">Cost</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-gray-300 px-4 py-2">Dhaka City</td>
                    <td class="border border-gray-300 px-4 py-2">1-2 days</td>
                    <td class="border border-gray-300 px-4 py-2">৳60</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 px-4 py-2">Dhaka Suburbs</td>
                    <td class="border border-gray-300 px-4 py-2">2-3 days</td>
                    <td class="border border-gray-300 px-4 py-2">৳100</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 px-4 py-2">Other Areas</td>
                    <td class="border border-gray-300 px-4 py-2">3-5 days</td>
                    <td class="border border-gray-300 px-4 py-2">৳150</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div>
        <h3 class="text-xl font-bold mb-3">Free Shipping</h3>
        <p>Enjoy free shipping on orders over <strong>৳1000</strong> within Dhaka city!</p>
    </div>
    
    <div>
        <h3 class="text-xl font-bold mb-3">Contact Support</h3>
        <p>For shipping inquiries, contact us at:</p>
        <ul class="list-none space-y-2 mt-2">
            <li>📞 <a href="tel:+8801700000000">+880 1700-000000</a></li>
            <li>✉️ <a href="mailto:support@armarketbd.com">support@armarketbd.com</a></li>
        </ul>
    </div>
</div>
HTML;
    }
}
