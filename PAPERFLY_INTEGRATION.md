# Paperfly Delivery API Integration

This document outlines the Paperfly delivery API integration completed for the Alpha Vendor platform.

## Overview
Paperfly is a Bangladesh-based delivery service that has been integrated to automate order fulfillment and tracking. The integration replaces manual shipping management with automated API-based delivery handling.

## Files Created

### 1. `/app/Services/PaperflyService.php`
Complete service class for Paperfly API integration with the following methods:

- **createOrder($orderData)** - Submit new orders to Paperfly
- **trackOrder($referenceNumber)** - Track order status  
- **cancelOrder($orderId)** - Cancel orders
- **parseTrackingStatus($trackingStatus)** - Parse API responses
- **getDeliveryStatus($trackingStatus)** - Get readable delivery status

## Files Modified

### 1. `/config/services.php`
Added Paperfly configuration:
```php
'paperfly' => [
    'base_url' => env('PAPERFLY_BASE_URL', 'https://api.paperfly.com.bd'),
    'username' => env('PAPERFLY_USERNAME'),
    'password' => env('PAPERFLY_PASSWORD'),
    'key' => env('PAPERFLY_KEY'),
    'merchant_code' => env('PAPERFLY_MERCHANT_CODE'),
],
```

### 2. Database Migration: `2026_01_11_125818_add_paperfly_fields_to_orders_table.php`
Added tracking fields to orders table:
- `paperfly_tracking_number` - Paperfly's tracking number
- `paperfly_merchant_order_ref` - Merchant order reference
- `delivery_status` - Current delivery status (pending/picked/in_transit/out_for_delivery/delivered)
- `tracking_history` - JSON field for full tracking history
- `picked_at` - Timestamp when order was picked up
- `in_transit_at` - Timestamp when order entered transit
- `delivered_at` - Timestamp when order was delivered

**Status:** ✅ Migration completed successfully

### 3. `/app/Models/Order.php`
Updated model with new fillable fields and datetime casts for tracking timestamps.

### 4. `/resources/views/dashboards/admin.blade.php`
Major UI updates:

#### Shipping Methods Section (Lines ~2382-2497)
- **Replaced:** Complex shipping carrier management (FedEx, UPS, DHL, USPS)
- **New:** Paperfly API Configuration with:
  - API credentials form (username, password, key, merchant code)
  - Default pickup location settings (merchant name, phone, address, thana, district)
  - Simplified delivery options (Regular 3-5 days, Express 1-2 days)

#### Shipping Zones Section
- **Status:** Removed completely
- **Reason:** Paperfly automatically handles geographic zones and routing
- **Sidebar Menu:** Commented out "Shipping Zones" link

#### Delivery Personnel Section (Lines ~2856+)
- **Replaced:** Manual delivery staff management  
- **New:** Paperfly Order Tracking interface with:
  - Real-time delivery statistics dashboard
  - Order search by tracking number or order ID
  - Visual tracking timeline (Pending → Picked → In Transit → Out for Delivery → Delivered)
  - Customer and delivery information display
  - Recent orders table with status filtering
  - Sync all orders button for batch updates

#### JavaScript Functions Added
Comprehensive JavaScript functions for Paperfly integration:
- `savePaperflyConfig()` - Save API credentials
- `testPaperflyConnection()` - Test API connectivity
- `savePickupSettings()` - Save default pickup location
- `trackPaperflyOrder()` - Track specific order
- `refreshTracking()` - Refresh tracking data
- `displayTrackingResult()` - Display tracking timeline
- `filterPaperflyOrders()` - Filter orders by status
- `syncAllTracking()` - Sync all active orders
- `loadPaperflyOrders()` - Load orders table
- `viewPaperflyOrder()` - View specific order details
- `refreshSingleTracking()` - Refresh single order
- `updateDeliveryStats()` - Update dashboard statistics

### 5. `.env.example`
Added Paperfly environment variables:
```env
# Paperfly Delivery API Configuration
PAPERFLY_BASE_URL=https://api.paperfly.com.bd
PAPERFLY_USERNAME=your_paperfly_username
PAPERFLY_PASSWORD=your_paperfly_password
PAPERFLY_KEY=Paperfly_~YourAPIKey
PAPERFLY_MERCHANT_CODE=your_merchant_code
```

## Paperfly API Details

### Authentication
- **Method:** Basic Auth (username/password) + Custom header
- **Header Required:** `Paperflykey: Paperfly_~La?Rj73FcLm` (format example)

### Endpoints Used
1. **Order Submission:** `POST /NewOrderUpload`
2. **Order Tracking:** `POST /API-Order-Tracking`
3. **Order Cancellation:** `POST /api/v1/cancel-order`
4. **Merchant Registration:** `POST /MerchantRegistration` (for initial setup)

### Required Order Fields
- Merchant Code
- Merchant Order Reference
- Product Size/Weight
- Package Price (COD amount or 0 for prepaid)
- Delivery Option (regular/express)
- Customer: Name, Phone, Address, Thana, District
- Payment Mode: beftn/cash/bkash/rocket/nagad
- Pickup Location (optional - leave blank to use registered merchant location)

### Delivery Statuses
- **pending** - Order placed, awaiting pickup
- **Pick** - Order picked up from merchant
- **inTransit** - Order in transit to customer
- **Delivered** - Successfully delivered
- **Returned** - Delivery failed, returned to merchant
- **Partial** - Partial delivery (for multiple items)

## Setup Instructions

### 1. Environment Configuration
Copy the Paperfly variables from `.env.example` to your `.env` file and fill in your credentials:

```bash
PAPERFLY_BASE_URL=https://api.paperfly.com.bd
PAPERFLY_USERNAME=your_actual_username
PAPERFLY_PASSWORD=your_actual_password
PAPERFLY_KEY=Paperfly_~YourActualAPIKey
PAPERFLY_MERCHANT_CODE=your_merchant_code
```

**Note:** Contact Paperfly support to obtain your API credentials after merchant registration.

### 2. Database Migration
The migration has already been run. To verify:
```bash
php artisan migrate:status
```

### 3. Configure Admin Dashboard
1. Login to admin dashboard
2. Navigate to Shipping & Logistics → Shipping Methods
3. Fill in Paperfly API Configuration:
   - Username (from .env)
   - Password (from .env)
   - API Key (from .env)
   - Merchant Code (from .env)
4. Click "Test Connection" to verify
5. Click "Save Configuration"

### 4. Configure Pickup Location
In the same Shipping Methods section:
1. Fill in Default Pickup Location:
   - Merchant Name
   - Phone Number
   - Complete Address
   - Thana (sub-district)
   - District
2. Click "Save Pickup Settings"

## Next Steps (Implementation Required)

### 1. Order Controller Integration
Update your order processing to automatically submit to Paperfly:

```php
use App\Services\PaperflyService;

public function createOrder(Request $request)
{
    // Create order in your database
    $order = Order::create($orderData);
    
    // Submit to Paperfly
    $paperflyService = new PaperflyService();
    $paperflyData = [
        'merchantCode' => config('services.paperfly.merchant_code'),
        'merOrderRef' => $order->order_number,
        'productSizeWeight' => 'standard', // or calculate based on products
        'packagePrice' => $order->total_amount,
        'deliveryOption' => $order->delivery_type, // regular or express
        'recipientName' => $order->customer_name,
        'recipientPhone' => $order->customer_phone,
        'recipientAddress' => $order->shipping_address,
        'thana' => $order->thana,
        'area' => $order->district,
        'paymentInvoiceNo' => $order->invoice_number,
        'codAmount' => $order->payment_method === 'cod' ? $order->total_amount : 0,
        'paymentMethod' => $this->mapPaymentMethod($order->payment_method),
    ];
    
    $result = $paperflyService->createOrder($paperflyData);
    
    if (isset($result['tracking_number'])) {
        $order->update([
            'paperfly_tracking_number' => $result['tracking_number'],
            'paperfly_merchant_order_ref' => $order->order_number,
            'delivery_status' => 'pending',
        ]);
    }
    
    return $order;
}

private function mapPaymentMethod($method)
{
    $map = [
        'cod' => 'cash',
        'bank_transfer' => 'beftn',
        'bkash' => 'bkash',
        'rocket' => 'rocket',
        'nagad' => 'nagad',
    ];
    return $map[$method] ?? 'cash';
}
```

### 2. Create Tracking Sync Command
Create a scheduled command to sync tracking status:

```bash
php artisan make:command SyncPaperflyTracking
```

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\PaperflyService;

class SyncPaperflyTracking extends Command
{
    protected $signature = 'paperfly:sync-tracking';
    protected $description = 'Sync order tracking status from Paperfly';

    public function handle()
    {
        $paperflyService = new PaperflyService();
        
        // Get all orders that are not delivered/cancelled
        $orders = Order::whereIn('delivery_status', ['pending', 'picked', 'in_transit', 'out_for_delivery'])
                      ->whereNotNull('paperfly_tracking_number')
                      ->get();
        
        $this->info("Syncing {$orders->count()} orders...");
        
        foreach ($orders as $order) {
            try {
                $tracking = $paperflyService->trackOrder($order->paperfly_tracking_number);
                $status = $paperflyService->getDeliveryStatus($tracking);
                
                $updateData = [
                    'delivery_status' => $status,
                    'tracking_history' => json_encode($tracking),
                ];
                
                // Update timestamps based on status
                if ($status === 'picked' && !$order->picked_at) {
                    $updateData['picked_at'] = now();
                } elseif ($status === 'in_transit' && !$order->in_transit_at) {
                    $updateData['in_transit_at'] = now();
                } elseif ($status === 'delivered' && !$order->delivered_at) {
                    $updateData['delivered_at'] = now();
                }
                
                $order->update($updateData);
                $this->info("✓ Updated {$order->order_number} - Status: {$status}");
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to update {$order->order_number}: {$e->getMessage()}");
            }
        }
        
        $this->info('Sync completed!');
    }
}
```

Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Sync Paperfly tracking every hour
    $schedule->command('paperfly:sync-tracking')->hourly();
}
```

### 3. Create Customer Tracking Page
Create a route and controller for customer order tracking:

**routes/web.php:**
```php
Route::get('/track-order/{tracking}', [TrackingController::class, 'show'])->name('track.order');
```

**Controller:**
```php
public function show($tracking)
{
    $order = Order::where('paperfly_tracking_number', $tracking)
                  ->orWhere('order_number', $tracking)
                  ->firstOrFail();
    
    $paperflyService = new PaperflyService();
    $trackingData = $paperflyService->trackOrder($order->paperfly_tracking_number);
    
    return view('tracking.show', [
        'order' => $order,
        'tracking' => $trackingData,
    ]);
}
```

### 4. Add API Routes (Optional)
For AJAX calls from admin dashboard:

**routes/api.php:**
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/paperfly/track', [PaperflyController::class, 'track']);
    Route::post('/paperfly/sync-all', [PaperflyController::class, 'syncAll']);
    Route::post('/paperfly/test-connection', [PaperflyController::class, 'testConnection']);
    Route::get('/paperfly/orders', [PaperflyController::class, 'getOrders']);
});
```

## Testing

### Test Paperfly Connection
```bash
php artisan tinker
```

```php
$service = new App\Services\PaperflyService();

// Test credentials (should not throw exception if valid)
$testOrder = [
    'merchantCode' => config('services.paperfly.merchant_code'),
    'merOrderRef' => 'TEST-' . time(),
    'productSizeWeight' => 'standard',
    'packagePrice' => 100,
    'deliveryOption' => 'regular',
    'recipientName' => 'Test Customer',
    'recipientPhone' => '01712345678',
    'recipientAddress' => 'Test Address',
    'thana' => 'Gulshan',
    'area' => 'Dhaka',
    'paymentInvoiceNo' => 'TEST-INV-001',
    'codAmount' => 100,
    'paymentMethod' => 'cash',
];

$result = $service->createOrder($testOrder);
dd($result);
```

## Important Notes

1. **Bangladesh-Specific:** Paperfly operates only in Bangladesh. Ensure customer addresses include proper Thana and District information.

2. **Payment Methods:** Only these payment methods are supported:
   - `beftn` - Bank transfer
   - `cash` - Cash on delivery
   - `bkash` - bKash mobile payment
   - `rocket` - Rocket mobile payment
   - `nagad` - Nagad mobile payment

3. **Pickup Location:** If you have multiple pickup locations, you can specify different merchant details per order. Otherwise, leave pickup fields blank to use the registered merchant location.

4. **Package Size:** Use descriptive values like 'small', 'medium', 'large', 'standard', or specific measurements.

5. **API Rate Limits:** Check with Paperfly support for any API rate limits.

6. **Error Handling:** The PaperflyService logs all errors. Check `storage/logs/laravel.log` for debugging.

7. **Order Cancellation:** Use the `cancelOrder()` method before the order is picked up. Once picked up, contact Paperfly support for cancellations.

## Support & Documentation

- **Paperfly Website:** https://paperfly.com.bd
- **Merchant Registration:** Contact Paperfly sales team
- **API Support:** Contact Paperfly technical support for API credentials and documentation
- **API Base URL:** https://api.paperfly.com.bd

## Security Considerations

1. **Never commit** `.env` file with real credentials to version control
2. Store API credentials securely in `.env` file
3. Use HTTPS for all API communication (already enforced by Paperfly)
4. Validate and sanitize customer input before sending to API
5. Implement rate limiting on tracking endpoints to prevent abuse
6. Log all API interactions for auditing purposes

## Troubleshooting

### Common Issues:

**1. "Authentication failed"**
- Verify credentials in `.env` file
- Ensure Paperflykey header format is correct
- Check that merchant account is active

**2. "Order creation failed"**
- Verify all required fields are present
- Check phone number format (Bangladesh format)
- Ensure thana and district are valid
- Verify payment method is one of the supported values

**3. "Tracking not found"**
- Order may not be in Paperfly system yet (wait a few minutes)
- Verify tracking number is correct
- Check if order was successfully submitted

**4. Migration errors**
- Ensure database connection is configured
- Check if `orders` table exists
- Verify no duplicate column names

## Rollback Instructions

If you need to rollback the changes:

1. **Rollback migration:**
```bash
php artisan migrate:rollback --step=1
```

2. **Remove service file:**
```bash
rm app/Services/PaperflyService.php
```

3. **Revert config changes in `config/services.php`** (remove paperfly array)

4. **Revert .env changes** (remove PAPERFLY_* variables)

5. The admin dashboard changes are backward compatible and can be left in place or reverted to previous version using git.

---

**Integration Date:** January 11, 2026  
**Status:** ✅ Integration Complete - Ready for Testing  
**Next Phase:** Controller integration and production testing
