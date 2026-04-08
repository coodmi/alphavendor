<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ViolationRule;

class ViolationRulesSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'rule_code' => 'LATE_DELIVERY',
                'rule_name_en' => 'Late Delivery (3+ Days)',
                'rule_name_bn' => 'দেরিতে ডেলিভারি (৩+ দিন)',
                'description' => 'Order not delivered within 3 days of confirmation',
                'penalty_type' => 'fixed',
                'penalty_amount' => 500.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'DELAYED_PROCESSING',
                'rule_name_en' => 'Delayed Order Processing',
                'rule_name_bn' => 'অর্ডার প্রসেসিং বিলম্ব',
                'description' => 'Order not confirmed within 24 hours',
                'penalty_type' => 'fixed',
                'penalty_amount' => 300.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'EXCESSIVE_CANCELLATIONS',
                'rule_name_en' => 'Excessive Order Cancellations',
                'rule_name_bn' => 'অতিরিক্ত অর্ডার বাতিল',
                'description' => 'More than 5 order cancellations in a month',
                'penalty_type' => 'fixed',
                'penalty_amount' => 1000.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'STOCK_UNAVAILABLE',
                'rule_name_en' => 'Stock Unavailable After Order',
                'rule_name_bn' => 'অর্ডারের পর স্টক অনুপলব্ধ',
                'description' => 'Product out of stock after customer placed order',
                'penalty_type' => 'fixed',
                'penalty_amount' => 400.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'LOW_PRODUCT_QUALITY',
                'rule_name_en' => 'Low Product Quality',
                'rule_name_bn' => 'নিম্নমানের পণ্য',
                'description' => 'Product rating below 2.5 stars with 5+ reviews',
                'penalty_type' => 'fixed',
                'penalty_amount' => 800.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'FAKE_PRODUCT',
                'rule_name_en' => 'Fake/Counterfeit Product',
                'rule_name_bn' => 'নকল পণ্য',
                'description' => 'Selling fake or counterfeit products',
                'penalty_type' => 'percentage',
                'penalty_amount' => 20.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'WRONG_PRODUCT',
                'rule_name_en' => 'Wrong Product Shipped',
                'rule_name_bn' => 'ভুল পণ্য পাঠানো',
                'description' => 'Shipped wrong product to customer',
                'penalty_type' => 'percentage',
                'penalty_amount' => 10.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'POOR_PACKAGING',
                'rule_name_en' => 'Poor Packaging',
                'rule_name_bn' => 'খারাপ প্যাকেজিং',
                'description' => 'Product damaged due to poor packaging',
                'penalty_type' => 'fixed',
                'penalty_amount' => 200.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'INCOMPLETE_ORDER',
                'rule_name_en' => 'Incomplete Order',
                'rule_name_bn' => 'অসম্পূর্ণ অর্ডার',
                'description' => 'Missing items in the order',
                'penalty_type' => 'percentage',
                'penalty_amount' => 15.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'PRICE_MANIPULATION',
                'rule_name_en' => 'Price Manipulation',
                'rule_name_bn' => 'মূল্য কারসাজি',
                'description' => 'Inflated or manipulated product prices',
                'penalty_type' => 'percentage',
                'penalty_amount' => 25.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'QUALITY_ISSUE',
                'rule_name_en' => 'Product Quality Issue',
                'rule_name_bn' => 'পণ্যের মান সমস্যা',
                'description' => 'Product quality below standards',
                'penalty_type' => 'percentage',
                'penalty_amount' => 12.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'FALSE_DESCRIPTION',
                'rule_name_en' => 'False Product Description',
                'rule_name_bn' => 'মিথ্যা পণ্য বর্ণনা',
                'description' => 'Product description does not match actual product',
                'penalty_type' => 'percentage',
                'penalty_amount' => 15.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'CUSTOMER_COMPLAINT',
                'rule_name_en' => 'Verified Customer Complaint',
                'rule_name_bn' => 'যাচাইকৃত গ্রাহক অভিযোগ',
                'description' => 'Verified complaint from customer',
                'penalty_type' => 'fixed',
                'penalty_amount' => 400.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'POOR_CUSTOMER_SERVICE',
                'rule_name_en' => 'Poor Customer Service',
                'rule_name_bn' => 'খারাপ গ্রাহক সেবা',
                'description' => 'Not responding to customer queries',
                'penalty_type' => 'fixed',
                'penalty_amount' => 350.00,
                'is_active' => true
            ],
            [
                'rule_code' => 'POLICY_VIOLATION',
                'rule_name_en' => 'Platform Policy Violation',
                'rule_name_bn' => 'প্ল্যাটফর্ম নীতি লঙ্ঘন',
                'description' => 'Violation of platform terms and policies',
                'penalty_type' => 'fixed',
                'penalty_amount' => 1500.00,
                'is_active' => true
            ]
        ];

        foreach ($rules as $rule) {
            ViolationRule::updateOrCreate(
                ['rule_code' => $rule['rule_code']],
                $rule
            );
        }
    }
}
