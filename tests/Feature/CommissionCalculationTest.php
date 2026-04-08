<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\CommissionService;
use App\Models\CommissionSetting;
use App\Models\CodCommissionSetting;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommissionCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected $commissionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commissionService = new CommissionService();
    }

    /** @test */
    public function it_calculates_category_commission_for_retailer()
    {
        // Create test data
        $category = Category::factory()->create();
        $vendor = User::factory()->create(['role' => 'retailer']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'vendor_id' => $vendor->id,
            'price' => 1000
        ]);

        // Set commission rate
        CommissionSetting::create([
            'category_id' => $category->id,
            'seller_type' => 'retailer',
            'commission_rate' => 8.00,
            'is_active' => true
        ]);

        // Calculate commission
        $result = $this->commissionService->calculateItemCommission(
            $product,
            1,
            1000,
            $vendor->id,
            'online'
        );

        $this->assertEquals(1000, $result['item_total']);
        $this->assertEquals(8.00, $result['category_commission_rate']);
        $this->assertEquals(80, $result['category_commission_amount']);
        $this->assertEquals(0, $result['cod_commission_amount']);
        $this->assertEquals(920, $result['vendor_earning']);
    }

    /** @test */
    public function it_calculates_cod_commission_for_cod_orders()
    {
        // Create test data
        $category = Category::factory()->create();
        $vendor = User::factory()->create(['role' => 'retailer']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'vendor_id' => $vendor->id,
            'price' => 1000
        ]);

        // Set commission rates
        CommissionSetting::create([
            'category_id' => $category->id,
            'seller_type' => 'retailer',
            'commission_rate' => 8.00,
            'is_active' => true
        ]);

        CodCommissionSetting::create([
            'commission_rate' => 2.00,
            'is_active' => true
        ]);

        // Calculate commission for COD order
        $result = $this->commissionService->calculateItemCommission(
            $product,
            1,
            1000,
            $vendor->id,
            'cod',
            100
        );

        $this->assertEquals(1000, $result['item_total']);
        $this->assertEquals(80, $result['category_commission_amount']);
        $this->assertEquals(20, $result['cod_commission_amount']);
        $this->assertEquals(100, $result['total_commission']);
        $this->assertEquals(900, $result['vendor_earning']);
    }

    /** @test */
    public function it_applies_different_rates_for_different_seller_types()
    {
        $category = Category::factory()->create();

        // Create commission rates for different seller types
        CommissionSetting::create([
            'category_id' => $category->id,
            'seller_type' => 'retailer',
            'commission_rate' => 8.00,
            'is_active' => true
        ]);

        CommissionSetting::create([
            'category_id' => $category->id,
            'seller_type' => 'wholesaler',
            'commission_rate' => 5.00,
            'is_active' => true
        ]);

        // Test retailer
        $retailer = User::factory()->create(['role' => 'retailer']);
        $productRetailer = Product::factory()->create([
            'category_id' => $category->id,
            'vendor_id' => $retailer->id,
            'price' => 1000
        ]);

        $resultRetailer = $this->commissionService->calculateItemCommission(
            $productRetailer,
            1,
            1000,
            $retailer->id,
            'online'
        );

        $this->assertEquals(80, $resultRetailer['category_commission_amount']);

        // Test wholesaler
        $wholesaler = User::factory()->create(['role' => 'wholesaler']);
        $productWholesaler = Product::factory()->create([
            'category_id' => $category->id,
            'vendor_id' => $wholesaler->id,
            'price' => 1000
        ]);

        $resultWholesaler = $this->commissionService->calculateItemCommission(
            $productWholesaler,
            1,
            1000,
            $wholesaler->id,
            'online'
        );

        $this->assertEquals(50, $resultWholesaler['category_commission_amount']);
    }
}
