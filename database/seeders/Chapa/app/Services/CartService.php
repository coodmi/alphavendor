<?php

namespace App\Services;

use App\Exceptions\CartException;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/**
 * Cart Service.
 *
 * Handles all cart operations including adding, removing, and updating items.
 * Supports both session-based carts for guests and database-backed carts
 * for authenticated users.
 *
 * Cart Item Structure:
 * - product_id: int|null (null for service products)
 * - product_title: string
 * - product_image: string|null
 * - category: string
 * - format: string|null
 * - price: float
 * - quantity: int
 * - configurations: array (for service products)
 * - is_service_product: bool
 *
 * @see CartException
 */
class CartService
{
    /**
     * Session key for cart storage.
     */
    private const SESSION_KEY = 'cart';

    /**
     * Cache key prefix for user carts.
     */
    private const CACHE_PREFIX = 'user_cart:';

    /**
     * Cache TTL for user carts (24 hours).
     */
    private const CACHE_TTL = 86400;

    /**
     * Get the current cart.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getCart(): array
    {
        if (Auth::check()) {
            return $this->getUserCart(Auth::id());
        }

        return $this->getSessionCart();
    }

    /**
     * Get cart from session (for guests).
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getSessionCart(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * Get cart for authenticated user.
     *
     * @param  int  $userId  User ID
     * @return array<string, array<string, mixed>>
     */
    protected function getUserCart(int $userId): array
    {
        $cacheKey = self::CACHE_PREFIX.$userId;

        // Try to get from cache first
        $cart = Cache::get($cacheKey);

        if ($cart !== null) {
            return $cart;
        }

        // Fall back to session if no cached cart
        $sessionCart = $this->getSessionCart();

        if (! empty($sessionCart)) {
            // Migrate session cart to user cart
            $this->saveUserCart($userId, $sessionCart);
            Session::forget(self::SESSION_KEY);

            return $sessionCart;
        }

        return [];
    }

    /**
     * Save cart for authenticated user.
     *
     * @param  int  $userId  User ID
     * @param  array<string, array<string, mixed>>  $cart  Cart data
     */
    protected function saveUserCart(int $userId, array $cart): void
    {
        $cacheKey = self::CACHE_PREFIX.$userId;
        Cache::put($cacheKey, $cart, self::CACHE_TTL);
    }

    /**
     * Save the current cart.
     *
     * @param  array<string, array<string, mixed>>  $cart  Cart data
     */
    protected function saveCart(array $cart): void
    {
        if (Auth::check()) {
            $this->saveUserCart(Auth::id(), $cart);
        } else {
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    /**
     * Add a product to the cart.
     *
     * @param  array<string, mixed>  $productData  Product data
     * @param  int  $quantity  Quantity to add
     * @return array<string, mixed> The added/updated cart item
     *
     * @throws CartException When product cannot be added
     */
    public function addProduct(array $productData, int $quantity = 1): array
    {
        $this->validateProductData($productData);

        if ($quantity < 1) {
            throw CartException::invalidQuantity($quantity);
        }

        $cart = $this->getCart();

        // Generate unique key for the product
        $productKey = $this->generateProductKey($productData);

        if (isset($cart[$productKey])) {
            // Product already in cart, increase quantity
            $cart[$productKey]['quantity'] += $quantity;
        } else {
            // Add new product to cart
            $cart[$productKey] = $this->formatCartItem($productData, $quantity);
        }

        $this->saveCart($cart);

        Log::info('Product added to cart', [
            'product_key' => $productKey,
            'quantity' => $quantity,
            'user_id' => Auth::id(),
        ]);

        return $cart[$productKey];
    }

    /**
     * Add a service product with configurations to the cart.
     *
     * @param  array<string, mixed>  $serviceData  Service product data
     * @param  int  $quantity  Quantity to add
     * @return array<string, mixed> The added/updated cart item
     *
     * @throws CartException When service product cannot be added
     */
    public function addServiceProduct(array $serviceData, int $quantity = 1): array
    {
        if ($quantity < 1) {
            throw CartException::invalidQuantity($quantity);
        }

        $cart = $this->getCart();

        // Generate unique key based on configurations
        $configHash = md5(json_encode($serviceData['configurations'] ?? []));
        $productKey = 'service_'.($serviceData['category'] ?? 'unknown').'_'.
                      ($serviceData['product_type'] ?? 'unknown').'_'.$configHash;

        if (isset($cart[$productKey])) {
            // Same configuration exists, increase quantity
            $cart[$productKey]['quantity'] += $quantity;
        } else {
            // Add new configured product to cart
            $cart[$productKey] = [
                'product_title' => $serviceData['title'],
                'product_image' => $serviceData['image'] ?? null,
                'category' => $serviceData['category'] ?? 'Service',
                'product_type' => $serviceData['product_type'] ?? 'Custom',
                'format' => 'Custom Configuration',
                'price' => (float) $serviceData['price'],
                'quantity' => $quantity,
                'configurations' => $serviceData['configurations'] ?? [],
                'is_service_product' => true,
                'stock' => true,
            ];
        }

        $this->saveCart($cart);

        Log::info('Service product added to cart', [
            'product_key' => $productKey,
            'quantity' => $quantity,
            'user_id' => Auth::id(),
        ]);

        return $cart[$productKey];
    }

    /**
     * Update item quantity in cart.
     *
     * @param  string  $productKey  Product key in cart
     * @param  int  $quantity  New quantity
     * @return array<string, mixed>|null Updated cart item or null if removed
     *
     * @throws CartException When item not found or quantity invalid
     */
    public function updateQuantity(string $productKey, int $quantity): ?array
    {
        $cart = $this->getCart();

        if (! isset($cart[$productKey])) {
            throw CartException::itemNotFound($productKey);
        }

        if ($quantity < 0) {
            throw CartException::invalidQuantity($quantity);
        }

        if ($quantity === 0) {
            return $this->removeItem($productKey);
        }

        $cart[$productKey]['quantity'] = $quantity;
        $this->saveCart($cart);

        Log::info('Cart item quantity updated', [
            'product_key' => $productKey,
            'quantity' => $quantity,
            'user_id' => Auth::id(),
        ]);

        return $cart[$productKey];
    }

    /**
     * Remove an item from the cart.
     *
     * @param  string  $productKey  Product key in cart
     * @return null Always returns null after removal
     *
     * @throws CartException When item not found
     */
    public function removeItem(string $productKey): null
    {
        $cart = $this->getCart();

        if (! isset($cart[$productKey])) {
            throw CartException::itemNotFound($productKey);
        }

        unset($cart[$productKey]);
        $this->saveCart($cart);

        Log::info('Item removed from cart', [
            'product_key' => $productKey,
            'user_id' => Auth::id(),
        ]);

        return null;
    }

    /**
     * Clear the entire cart.
     */
    public function clear(): void
    {
        if (Auth::check()) {
            $cacheKey = self::CACHE_PREFIX.Auth::id();
            Cache::forget($cacheKey);
        }

        Session::forget(self::SESSION_KEY);

        Log::info('Cart cleared', [
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Get cart item count.
     */
    public function getItemCount(): int
    {
        return count($this->getCart());
    }

    /**
     * Get total quantity of all items.
     */
    public function getTotalQuantity(): int
    {
        $cart = $this->getCart();

        return array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['quantity'] ?? 0);
        }, 0);
    }

    /**
     * Calculate cart subtotal.
     */
    public function getSubtotal(): float
    {
        $cart = $this->getCart();

        return array_reduce($cart, function ($carry, $item) {
            return $carry + (($item['price'] ?? 0) * ($item['quantity'] ?? 0));
        }, 0.0);
    }

    /**
     * Calculate cart tax.
     */
    public function getTax(): float
    {
        return 0; // No tax
    }

    /**
     * Calculate cart total.
     */
    public function getTotal(): float
    {
        return $this->getSubtotal() + $this->getTax();
    }

    /**
     * Get cart summary.
     *
     * @return array<string, mixed>
     */
    public function getSummary(): array
    {
        $cart = $this->getCart();
        $subtotal = $this->getSubtotal();

        return [
            'items' => $cart,
            'item_count' => count($cart),
            'total_quantity' => $this->getTotalQuantity(),
            'subtotal' => $subtotal,
            'tax' => 0, // No tax
            'total' => $subtotal, // Total equals subtotal (no tax)
        ];
    }

    /**
     * Check if cart is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->getCart());
    }

    /**
     * Check if a product is in the cart.
     *
     * @param  string  $productKey  Product key
     */
    public function hasItem(string $productKey): bool
    {
        $cart = $this->getCart();

        return isset($cart[$productKey]);
    }

    /**
     * Get a specific item from the cart.
     *
     * @param  string  $productKey  Product key
     * @return array<string, mixed>|null
     */
    public function getItem(string $productKey): ?array
    {
        $cart = $this->getCart();

        return $cart[$productKey] ?? null;
    }

    /**
     * Merge guest cart with user cart on login.
     *
     * @param  int  $userId  User ID
     */
    public function mergeOnLogin(int $userId): void
    {
        $sessionCart = $this->getSessionCart();

        if (empty($sessionCart)) {
            return;
        }

        $userCart = $this->getUserCart($userId);

        // Merge carts, session cart items take precedence for quantities
        foreach ($sessionCart as $key => $item) {
            if (isset($userCart[$key])) {
                $userCart[$key]['quantity'] += $item['quantity'];
            } else {
                $userCart[$key] = $item;
            }
        }

        $this->saveUserCart($userId, $userCart);
        Session::forget(self::SESSION_KEY);

        Log::info('Cart merged on login', [
            'user_id' => $userId,
            'merged_items' => count($sessionCart),
        ]);
    }

    /**
     * Get cart items formatted for order creation.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getItemsForOrder(): array
    {
        $cart = $this->getCart();

        return array_values(array_map(function ($item) {
            return [
                'product_id' => $item['product_id'] ?? null,
                'product_title' => $item['product_title'] ?? $item['title'] ?? 'Unknown',
                'product_image' => $item['product_image'] ?? $item['image'] ?? null,
                'format' => $item['format'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'configurations' => $item['configurations'] ?? null,
            ];
        }, $cart));
    }

    /**
     * Validate cart items against current product data.
     *
     * @return array<string, array<string, mixed>> Validation results
     */
    public function validateCart(): array
    {
        $cart = $this->getCart();
        $issues = [];
        $validItems = [];

        foreach ($cart as $key => $item) {
            // Skip service products (they don't have database records)
            if (! empty($item['is_service_product'])) {
                $validItems[$key] = $item;
                continue;
            }

            $productId = $item['product_id'] ?? null;

            if (! $productId) {
                $issues[$key] = ['error' => 'Missing product ID'];
                continue;
            }

            $product = Product::find($productId);

            if (! $product) {
                $issues[$key] = ['error' => 'Product no longer exists'];
                continue;
            }

            if (! $product->is_active) {
                $issues[$key] = ['error' => 'Product is no longer available'];
                continue;
            }

            if (! $product->stock) {
                $issues[$key] = ['error' => 'Product is out of stock'];
                continue;
            }

            // Update price if changed
            if ((float) $item['price'] !== (float) $product->price) {
                $item['price'] = $product->price;
                $issues[$key] = ['warning' => 'Price has been updated'];
            }

            $validItems[$key] = $item;
        }

        // Update cart with valid items
        if (! empty($issues)) {
            $this->saveCart($validItems);
        }

        return [
            'valid' => empty(array_filter($issues, fn ($i) => isset($i['error']))),
            'issues' => $issues,
            'items' => $validItems,
        ];
    }

    /**
     * Track cart abandonment.
     */
    public function trackAbandonment(): void
    {
        if ($this->isEmpty()) {
            return;
        }

        $cart = $this->getCart();

        Log::info('Cart abandonment tracked', [
            'user_id' => Auth::id(),
            'item_count' => count($cart),
            'total' => $this->getTotal(),
            'items' => array_keys($cart),
        ]);

        // Could dispatch event for email reminders, analytics, etc.
        event('cart.abandoned', [
            'user_id' => Auth::id(),
            'cart' => $cart,
            'total' => $this->getTotal(),
        ]);
    }

    /**
     * Generate a unique product key.
     *
     * @param  array<string, mixed>  $productData  Product data
     */
    protected function generateProductKey(array $productData): string
    {
        if (! empty($productData['product_id'])) {
            return 'product_'.$productData['product_id'];
        }

        // For products without ID, use a hash of the data
        return 'item_'.md5(json_encode($productData));
    }

    /**
     * Format product data as cart item.
     *
     * @param  array<string, mixed>  $productData  Product data
     * @param  int  $quantity  Quantity
     * @return array<string, mixed>
     */
    protected function formatCartItem(array $productData, int $quantity): array
    {
        return [
            'product_id' => $productData['product_id'] ?? null,
            'product_title' => $productData['title'] ?? $productData['product_title'] ?? 'Unknown',
            'product_image' => $productData['image'] ?? $productData['product_image'] ?? null,
            'category' => $productData['category'] ?? 'Uncategorized',
            'format' => $productData['format'] ?? null,
            'price' => (float) ($productData['price'] ?? 0),
            'quantity' => $quantity,
            'stock' => $productData['stock'] ?? true,
            'is_service_product' => false,
        ];
    }

    /**
     * Validate product data for adding to cart.
     *
     * @param  array<string, mixed>  $productData  Product data
     *
     * @throws CartException When validation fails
     */
    protected function validateProductData(array $productData): void
    {
        if (empty($productData['price']) || $productData['price'] <= 0) {
            throw CartException::invalidPrice($productData['price'] ?? 0);
        }

        if (empty($productData['title']) && empty($productData['product_title'])) {
            throw CartException::missingProductTitle();
        }
    }
}
