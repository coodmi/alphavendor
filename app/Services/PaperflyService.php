<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaperflyService
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $paperflyKey;
    protected $merchantCode;

    public function __construct()
    {
        $this->baseUrl = config('services.paperfly.base_url', 'https://api.paperfly.com.bd');
        $this->username = config('services.paperfly.username');
        $this->password = config('services.paperfly.password');
        $this->paperflyKey = config('services.paperfly.key');
        $this->merchantCode = config('services.paperfly.merchant_code');
    }

    /**
     * Create order in Paperfly system
     */
    public function createOrder($orderData)
    {
        try {
            $payload = [
                'merchantCode' => $this->merchantCode,
                'merOrderRef' => $orderData['order_number'],
                'pickMerchantName' => $orderData['pick_merchant_name'] ?? '',
                'pickMerchantAddress' => $orderData['pick_merchant_address'] ?? '',
                'pickMerchantThana' => $orderData['pick_merchant_thana'] ?? '',
                'pickMerchantDistrict' => $orderData['pick_merchant_district'] ?? '',
                'pickupMerchantPhone' => $orderData['pickup_merchant_phone'] ?? '',
                'productSizeWeight' => $orderData['product_size_weight'] ?? 'standard',
                'productBrief' => $orderData['product_brief'] ?? 'Order Items',
                'packagePrice' => $orderData['package_price'],
                'deliveryOption' => $orderData['delivery_option'] ?? 'regular',
                'custname' => $orderData['customer_name'],
                'custaddress' => $orderData['customer_address'],
                'customerThana' => $orderData['customer_thana'] ?? '',
                'customerDistrict' => $orderData['customer_district'],
                'custPhone' => $orderData['customer_phone'],
            ];

            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'Paperflykey' => $this->paperflyKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/NewOrderUpload', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'tracking_number' => $data['success']['tracking_number'] ?? null,
                    'message' => $data['success']['message'] ?? 'Order created successfully',
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Failed to create order',
                'error' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error('Paperfly Order Creation Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Track order status
     */
    public function trackOrder($referenceNumber)
    {
        try {
            $payload = [
                'ReferenceNumber' => $referenceNumber,
                'merchantCode' => $this->merchantCode,
            ];

            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'Paperflykey' => $this->paperflyKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/API-Order-Tracking', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'tracking_status' => $data['success']['trackingStatus'] ?? [],
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to track order',
                'error' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error('Paperfly Order Tracking Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cancel order
     */
    public function cancelOrder($orderId)
    {
        try {
            $payload = [
                'order_id' => $orderId,
                'merchantCode' => $this->merchantCode,
            ];

            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'Paperflykey' => $this->paperflyKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/api/v1/cancel-order', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => $data['success']['message'] ?? 'Order cancelled successfully',
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to cancel order',
                'error' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error('Paperfly Order Cancellation Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Parse tracking status to readable format
     */
    public function parseTrackingStatus($trackingStatus)
    {
        $statuses = [];
        
        foreach ($trackingStatus as $status) {
            $statuses[] = [
                'status' => $status['Pick'] ?? $status['inTransit'] ?? $status['Delivered'] ?? 'Unknown',
                'time' => $status['PickTime'] ?? $status['inTransitTime'] ?? $status['DeliveredTime'] ?? '',
                'location' => $status['ReceivedAtPoint'] ?? '',
            ];
        }

        return $statuses;
    }

    /**
     * Get delivery status
     */
    public function getDeliveryStatus($trackingStatus)
    {
        if (isset($trackingStatus['Delivered']) && !empty($trackingStatus['Delivered'])) {
            return 'delivered';
        }
        
        if (isset($trackingStatus['PickedForDelivery']) && !empty($trackingStatus['PickedForDelivery'])) {
            return 'out_for_delivery';
        }
        
        if (isset($trackingStatus['inTransit']) && !empty($trackingStatus['inTransit'])) {
            return 'in_transit';
        }
        
        if (isset($trackingStatus['Pick']) && !empty($trackingStatus['Pick'])) {
            return 'picked';
        }

        return 'pending';
    }
}
