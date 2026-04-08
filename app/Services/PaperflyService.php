<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaperflyService
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $apiKey;
    protected $merchantCode;

    public function __construct()
    {
        $this->baseUrl = config('services.paperfly.base_url', 'https://api.paperfly.com.bd');
        $this->username = config('services.paperfly.username', 'dummy_user');
        $this->password = config('services.paperfly.password', 'dummy_pass');
        $this->apiKey = config('services.paperfly.key', 'dummy_key');
        $this->merchantCode = config('services.paperfly.merchant_code', 'dummy_merchant');
    }

    /**
     * Create a new delivery order
     */
    public function createOrder($orderData)
    {
        // Check if using dummy credentials
        if ($this->username === 'dummy_user' || $this->apiKey === 'dummy_key') {
            Log::info('Paperfly: Using dummy credentials - order not actually created', [
                'order_number' => $orderData['order_number']
            ]);
            
            return [
                'success' => true,
                'tracking_number' => 'MOCK_' . strtoupper(uniqid()),
                'tracking_barcode' => 'MOCK_BARCODE_' . time(),
                'message' => 'Mock order created (Paperfly not configured)',
                'response_code' => 200,
                'is_mock' => true
            ];
        }

        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'paperflykey' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/merchant/api/service/new_order_v2.php', [
                    'merchantOrderReference' => $orderData['order_number'],
                    'storeName' => $orderData['store_name'] ?? 'Ovi',
                    'productBrief' => $orderData['product_brief'],
                    'packagePrice' => (string) $orderData['package_price'],
                    'max_weight' => (string) ($orderData['max_weight'] ?? '0.3'),
                    'customerName' => $orderData['customer_name'],
                    'customerAddress' => $orderData['customer_address'],
                    'customerPhone' => $orderData['customer_phone'],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Paperfly order created', [
                    'order_reference' => $orderData['order_number'],
                    'response' => $data
                ]);

                return [
                    'success' => true,
                    'tracking_number' => $data['success']['tracking_number'] ?? null,
                    'tracking_barcode' => $data['success']['tracking_barcode'] ?? null,
                    'message' => $data['success']['message'] ?? 'Order created successfully',
                    'response_code' => $data['response_code'] ?? 200,
                ];
            }

            Log::error('Paperfly order creation failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create delivery order',
                'error' => $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('Paperfly order creation exception', [
                'error' => $e->getMessage(),
                'order_data' => $orderData
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Track order status
     */
    public function trackOrder($referenceNumber)
    {
        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'paperflykey' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/API-Order-Tracking', [
                    'ReferenceNumber' => $referenceNumber,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['success']['trackingStatus'][0])) {
                    $status = $data['success']['trackingStatus'][0];
                    
                    return [
                        'success' => true,
                        'status' => $this->parseTrackingStatus($status),
                        'raw_data' => $status
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Unable to track order'
            ];

        } catch (\Exception $e) {
            Log::error('Paperfly tracking failed', [
                'error' => $e->getMessage(),
                'reference' => $referenceNumber
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Exchange/Return order
     */
    public function exchangeOrder($orderData)
    {
        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'paperflykey' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/merchant/api/service/new_order_v2.php', [
                    'merchantOrderReference' => $orderData['order_number'],
                    'storeName' => $orderData['store_name'] ?? 'Ovi',
                    'productBrief' => $orderData['product_brief'],
                    'packagePrice' => $orderData['package_price'],
                    'max_weight' => $orderData['max_weight'] ?? '0.3',
                    'customerName' => $orderData['customer_name'],
                    'customerAddress' => $orderData['customer_address'],
                    'customerPhone' => $orderData['customer_phone'],
                    'orderType' => 'Exchange',
                    'exchangeDescription' => $orderData['exchange_description'] ?? 'exchange product',
                    'exchangePrice' => $orderData['exchange_price'] ?? '100',
                    'exchangeWeight' => $orderData['exchange_weight'] ?? '1.5',
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to create exchange order'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cancel order
     */
    public function cancelOrder($orderId)
    {
        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'paperflykey' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/api/v1/cancel-order', [
                    'order_id' => $orderId,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Order cancelled successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to cancel order'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Parse tracking status into readable format
     */
    private function parseTrackingStatus($status)
    {
        $currentStatus = 'pending';
        $statusTime = null;

        if (!empty($status['Delivered'])) {
            $currentStatus = 'delivered';
            $statusTime = $status['DeliveredTime'];
        } elseif (!empty($status['PickedForDelivery'])) {
            $currentStatus = 'out_for_delivery';
            $statusTime = $status['PickedForDeliveryTime'];
        } elseif (!empty($status['inTransit'])) {
            $currentStatus = 'in_transit';
            $statusTime = $status['inTransitTime'];
        } elseif (!empty($status['Pick'])) {
            $currentStatus = 'picked';
            $statusTime = $status['PickTime'];
        } elseif (!empty($status['Returned'])) {
            $currentStatus = 'returned';
            $statusTime = $status['ReturnedTime'];
        } elseif (!empty($status['Partial'])) {
            $currentStatus = 'partial';
            $statusTime = $status['PartialTime'];
        }

        return [
            'status' => $currentStatus,
            'status_time' => $statusTime,
            'invoice_number' => $status['invNum'] ?? null,
            'received_amount' => $status['receivedAmount'] ?? null,
        ];
    }
}
