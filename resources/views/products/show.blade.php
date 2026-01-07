@extends('layouts.app')

@section('title', $product->name)

@section('content')
<style>
    .product-detail-section {
        padding: 40px 0;
        background: #f8f9fa;
    }

    .breadcrumb-nav {
        padding: 15px 0;
        margin-bottom: 30px;
        background: white;
    }

    .breadcrumb-nav ul {
        display: flex;
        list-style: none;
        gap: 10px;
        align-items: center;
    }

    .breadcrumb-nav li {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #7f8c8d;
    }

    .breadcrumb-nav li:after {
        content: "/";
        margin-left: 10px;
    }

    .breadcrumb-nav li:last-child:after {
        display: none;
    }

    .breadcrumb-nav a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .breadcrumb-nav a:hover {
        text-decoration: underline;
    }

    .product-detail-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 40px;
    }

    .product-gallery {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .main-image {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 15px;
        overflow: hidden;
        border: 2px solid #e0e0e0;
    }

    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .main-image:hover img {
        transform: scale(1.05);
    }

    .product-details {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .product-badge {
        display: inline-block;
        padding: 6px 15px;
        background: var(--primary-color);
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        width: fit-content;
    }

    .product-badge.new {
        background: #27ae60;
    }

    .product-badge.sale {
        background: #e74c3c;
    }

    .product-badge.hot {
        background: #e67e22;
    }

    .product-title {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        line-height: 1.3;
    }

    .product-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 15px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .product-rating .stars {
        color: #f39c12;
        font-size: 16px;
    }

    .product-rating .rating-text {
        color: #7f8c8d;
        font-size: 14px;
    }

    .product-vendor {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #7f8c8d;
        font-size: 14px;
    }

    .product-vendor i {
        color: var(--primary-color);
    }

    .product-price-section {
        padding: 20px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .price-row {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .current-price {
        font-size: 36px;
        font-weight: 700;
        color: var(--primary-color);
    }

    .old-price {
        font-size: 24px;
        color: #95a5a6;
        text-decoration: line-through;
    }

    .discount-badge {
        padding: 6px 12px;
        background: #e74c3c;
        color: white;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .stock-status {
        margin-top: 10px;
        font-size: 14px;
    }

    .in-stock {
        color: #27ae60;
        font-weight: 600;
    }

    .out-of-stock {
        color: #e74c3c;
        font-weight: 600;
    }

    .product-description {
        padding: 20px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .product-description h3 {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .product-description p {
        color: #7f8c8d;
        line-height: 1.8;
    }

    .product-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 20px 0;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quantity-selector label {
        font-weight: 600;
        color: #2c3e50;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }

    .quantity-controls button {
        width: 40px;
        height: 40px;
        background: #f8f9fa;
        border: none;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.3s;
    }

    .quantity-controls button:hover {
        background: var(--primary-color);
        color: white;
    }

    .quantity-controls input {
        width: 60px;
        height: 40px;
        text-align: center;
        border: none;
        font-weight: 600;
        font-size: 16px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
    }

    .btn-add-to-cart {
        flex: 1;
        padding: 15px 30px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-add-to-cart:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 165, 0, 0.3);
    }

    .btn-wishlist {
        width: 50px;
        height: 50px;
        background: white;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: 8px;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-wishlist:hover {
        background: var(--primary-color);
        color: white;
    }

    .product-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        padding: 20px 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .info-item i {
        color: var(--primary-color);
        font-size: 20px;
    }

    .info-item .info-text {
        flex: 1;
    }

    .info-item .info-label {
        font-size: 12px;
        color: #7f8c8d;
    }

    .info-item .info-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .related-products-section {
        padding: 40px 0;
    }

    .section-title {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 30px;
        text-align: center;
    }

    .related-products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
    }

    @media (max-width: 1200px) {
        .related-products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .product-detail-container {
            grid-template-columns: 1fr;
        }

        .related-products-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .product-title {
            font-size: 24px;
        }

        .current-price {
            font-size: 28px;
        }
    }

    @media (max-width: 480px) {
        .related-products-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<section class="product-detail-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb-nav">
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('shop') }}">Shop</a></li>
                <li><a href="{{ route('shop', ['categories' => [$product->category_id]]) }}">{{ $product->category->name ?? 'Products' }}</a></li>
                <li>{{ $product->name }}</li>
            </ul>
        </nav>

        <!-- Product Detail -->
        <div class="product-detail-container">
            <!-- Product Gallery -->
            <div class="product-gallery">
                <div class="main-image">
                    @if($product->image)
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" id="mainImage">
                    @else
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=800&fit=crop" alt="{{ $product->name }}" id="mainImage">
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="product-details">
                @if($product->badge)
                    <span class="product-badge {{ strtolower($product->badge) }}">{{ $product->badge }}</span>
                @endif

                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="product-meta">
                    <div class="product-rating">
                        <div class="stars">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < floor($product->rating))
                                    <i class="fas fa-star"></i>
                                @elseif($i < $product->rating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-text">{{ number_format($product->rating, 1) }} ({{ $product->reviews_count }} reviews)</span>
                    </div>
                    <div class="product-vendor">
                        <i class="fas fa-store"></i>
                        <span>Sold by: <strong>{{ $product->vendor->name ?? 'AlphaVendor' }}</strong></span>
                    </div>
                </div>

                <div class="product-price-section">
                    <div class="price-row">
                        <span class="current-price">${{ number_format($product->price, 2) }}</span>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="old-price">${{ number_format($product->old_price, 2) }}</span>
                            <span class="discount-badge">-{{ $product->discount_percentage }}% OFF</span>
                        @endif
                    </div>
                    <div class="stock-status">
                        @if($product->stock > 0)
                            <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock ({{ $product->stock }} available)</span>
                        @else
                            <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                        @endif
                    </div>
                </div>

                @if($product->description)
                <div class="product-description">
                    <h3>Product Description</h3>
                    <p>{{ $product->description }}</p>
                </div>
                @endif

                @if($product->stock > 0)
                <div class="product-actions">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        <div class="quantity-selector">
                            <label>Quantity:</label>
                            <div class="quantity-controls">
                                <button type="button" onclick="decreaseQuantity()">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" readonly>
                                <button type="button" onclick="increaseQuantity({{ $product->stock }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn-add-to-cart">
                                <i class="fas fa-shopping-cart"></i>
                                Add to Cart
                            </button>
                            <button type="button" class="btn-wishlist" title="Add to Wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="product-actions">
                    <button class="btn-add-to-cart" style="background: #95a5a6; cursor: not-allowed;" disabled>
                        <i class="fas fa-ban"></i>
                        Out of Stock
                    </button>
                </div>
                @endif

                <div class="product-info-grid">
                    <div class="info-item">
                        <i class="fas fa-tag"></i>
                        <div class="info-text">
                            <div class="info-label">Category</div>
                            <div class="info-value">{{ $product->category->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    @if($product->brand)
                    <div class="info-item">
                        <i class="fas fa-copyright"></i>
                        <div class="info-text">
                            <div class="info-label">Brand</div>
                            <div class="info-value">{{ $product->brand->name }}</div>
                        </div>
                    </div>
                    @endif
                    @if($product->sku)
                    <div class="info-item">
                        <i class="fas fa-barcode"></i>
                        <div class="info-text">
                            <div class="info-label">SKU</div>
                            <div class="info-value">{{ $product->sku }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="info-item">
                        <i class="fas fa-truck"></i>
                        <div class="info-text">
                            <div class="info-label">Shipping</div>
                            <div class="info-value">Free Delivery</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="related-products-section">
            <h2 class="section-title">You May Also Like</h2>
            <div class="related-products-grid">
                @foreach($relatedProducts as $relatedProduct)
                <div class="product-card">
                    <a href="{{ route('product.show', $relatedProduct->id) }}" style="text-decoration: none; color: inherit;">
                        <div class="product-image" style="position: relative; overflow: hidden; border-radius: 12px; aspect-ratio: 1;">
                            @if($relatedProduct->image)
                                <img src="{{ str_starts_with($relatedProduct->image, 'http') ? $relatedProduct->image : asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop" alt="{{ $relatedProduct->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                            @if($relatedProduct->badge)
                            <span class="badge {{ strtolower($relatedProduct->badge) }}" style="position: absolute; top: 10px; left: 10px; padding: 5px 12px; background: var(--primary-color); color: white; border-radius: 15px; font-size: 11px; font-weight: 600;">{{ $relatedProduct->badge }}</span>
                            @endif
                        </div>
                        <div class="product-info" style="padding: 15px 0;">
                            <div class="product-category" style="color: #7f8c8d; font-size: 12px; margin-bottom: 5px;">{{ $relatedProduct->category->name ?? 'Uncategorized' }}</div>
                            <h4 style="margin: 0 0 10px 0; font-size: 16px; color: #2c3e50; font-weight: 600;">{{ $relatedProduct->name }}</h4>
                            <div class="price" style="display: flex; align-items: center; gap: 10px;">
                                <span class="current-price" style="font-size: 20px; font-weight: 700; color: var(--primary-color);">${{ number_format($relatedProduct->price, 2) }}</span>
                                @if($relatedProduct->old_price)
                                    <span class="old-price" style="font-size: 14px; color: #95a5a6; text-decoration: line-through;">${{ number_format($relatedProduct->old_price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<script>
    function increaseQuantity(max) {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue < max) {
            input.value = currentValue + 1;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }

    // Add to cart with quantity
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Product added to cart successfully!');
                // Update cart count if you have a cart counter in header
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Still submit normally if AJAX fails
            form.submit();
        });
    });
</script>
@endsection
