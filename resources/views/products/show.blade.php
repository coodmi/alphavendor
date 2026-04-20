@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product-detail-mobile.css') }}">
@endpush

@section('content')
<section class="py-10 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="bg-gradient-to-r from-white to-gray-50 py-3.5 px-5 mb-6 rounded-xl shadow-sm border border-gray-100">
            <ul class="flex items-center gap-1.5 text-sm flex-wrap">
                <li class="flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-100 hover:text-teal-600 transition-colors duration-200 flex items-center gap-1.5 font-medium px-2.5 py-1.5 rounded-lg hover:bg-teal-50">
                        <i class="fas fa-home text-teal-600 text-base"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-300 text-xs mx-1"></i>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('shop') }}" class="text-gray-100 hover:text-teal-600 transition-colors duration-200 font-medium px-2.5 py-1.5 rounded-lg hover:bg-teal-50">Shop</a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-300 text-xs mx-1"></i>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('shop', ['categories' => [$product->category_id]]) }}" class="text-gray-100 hover:text-teal-600 transition-colors duration-200 font-medium px-2.5 py-1.5 rounded-lg hover:bg-teal-50">{{ $product->category->name ?? 'Products' }}</a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-300 text-xs mx-1"></i>
                </li>
                <li class="flex items-center">
                    <span class="text-white font-semibold px-2.5 py-1.5 bg-teal-50 rounded-lg">{{ Str::limit($product->name, 40) }}</span>
                </li>
            </ul>
        </nav>

        <!-- Product Detail -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 bg-white p-10 rounded-2xl shadow-md mb-10">
            <!-- Product Gallery -->
            <div class="flex flex-col gap-5">
                <div class="w-full aspect-square rounded-2xl overflow-hidden border-2 border-gray-200 group">
                    @if($product->image)
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" id="mainImage" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=800&fit=crop" alt="{{ $product->name }}" id="mainImage" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="flex flex-col gap-5">
                @if($product->badge)
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-semibold uppercase w-fit
                        {{ strtolower($product->badge) === 'new' ? 'bg-green-500' : '' }}
                        {{ strtolower($product->badge) === 'sale' ? 'bg-red-500' : '' }}
                        {{ strtolower($product->badge) === 'hot' ? 'bg-teal-600' : '' }}
                        {{ !in_array(strtolower($product->badge), ['new', 'sale', 'hot']) ? 'bg-teal-600' : '' }}
                        text-white">
                        {{ $product->badge }}
                    </span>
                @endif

                <h1 class="text-3xl lg:text-4xl font-bold text-white leading-tight">{{ $product->name }}</h1>

                <div class="flex items-center gap-5 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <div class="flex text-teal-500 text-base">
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
                        <span class="text-gray-200 text-sm">{{ number_format($product->rating, 1) }} ({{ $product->reviews_count }} reviews)</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-200 text-sm">
                        <i class="fas fa-store text-teal-600"></i>
                        <span>Sold by: <strong class="text-white">{{ $product->vendor->name ?? 'AlphaVendor' }}</strong></span>
                    </div>
                </div>

                <div class="py-5 border-b border-gray-200">
                    <div class="flex items-center gap-4 mb-3">
                        <div>
                            <div class="text-sm text-gray-200 mb-1">Unit Price</div>
                            <span class="text-2xl font-bold text-white" id="unitPrice">{{ currency($product->price) }}</span>
                        </div>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-xl text-gray-400 line-through">{{ currency($product->old_price) }}</span>
                            <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm font-semibold">-{{ $product->discount_percentage }}% OFF</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <div class="text-sm text-gray-200 mb-1">Total Price</div>
                        <span class="text-4xl font-bold text-teal-600" id="totalPrice">{{ currency($product->price) }}</span>
                    </div>
                    <div class="mt-2.5 text-sm">
                        @if($product->stock > 0)
                            <span class="text-green-600 font-semibold"><i class="fas fa-check-circle"></i> In Stock ({{ $product->stock }} available)</span>
                        @else
                            <span class="text-red-600 font-semibold"><i class="fas fa-times-circle"></i> Out of Stock</span>
                        @endif
                    </div>
                </div>

                @if($product->description)
                <div class="py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-white mb-4">Product Description</h3>
                    <p class="text-gray-100 leading-relaxed product-description">{{ $product->description }}</p>
                </div>
                @endif

                @php $productAttributes = $product->attributes()->withPivot('value')->get(); @endphp
                @if($productAttributes->count() > 0)
                <div class="py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-white mb-4">Specifications</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($productAttributes as $attr)
                        <div class="flex items-start gap-3 bg-gray-50 rounded-lg px-4 py-3">
                            <span class="text-sm font-semibold text-gray-200 min-w-[100px]">{{ $attr->name }}</span>
                            <span class="text-sm text-white font-medium">
                                @if($attr->type === 'color')
                                    <span class="inline-flex items-center gap-2">
                                        <span style="width:16px;height:16px;border-radius:50%;background:{{ $attr->pivot->value }};display:inline-block;border:1px solid #e5e7eb;"></span>
                                        {{ $attr->pivot->value }}
                                    </span>
                                @else
                                    {{ $attr->pivot->value ?? '—' }}
                                @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($product->stock > 0)
                <div class="flex flex-col gap-4 py-5">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        <div class="flex items-center gap-2.5 mb-4">
                            <label class="font-semibold text-white">Quantity:</label>
                            <div class="flex items-center border-2 border-gray-200 rounded-lg overflow-hidden">
                                <button type="button" onclick="decreaseQuantity()" class="w-10 h-10 bg-gray-50 hover:bg-teal-600 hover:text-white transition-all duration-300 text-lg">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="{{ request('quantity', $product->minimum_order ?? 1) }}" min="{{ $product->minimum_order ?? 1 }}" max="{{ $product->stock }}" readonly class="w-16 h-10 text-center border-none font-semibold text-base">
                                <button type="button" onclick="increaseQuantity({{ $product->stock }})" class="w-10 h-10 bg-gray-50 hover:bg-teal-600 hover:text-white transition-all duration-300 text-lg">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Coupon Code Section -->
                        <div class="mb-4 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg border border-purple-200">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-ticket-alt text-purple-600 text-lg"></i>
                                <label class="font-semibold text-white">Have a Coupon Code?</label>
                            </div>
                            <div class="flex gap-2">
                                <input type="text" id="couponCode" name="coupon_code" placeholder="Enter coupon code" class="flex-1 px-4 py-2.5 border-2 border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent uppercase" style="text-transform: uppercase;">
                                <button type="button" onclick="applyCoupon()" class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-purple-600 hover:to-indigo-700 transition-all">
                                    Apply
                                </button>
                            </div>
                            <div id="couponMessage" class="mt-2 text-sm hidden"></div>
                            <div id="couponDiscount" class="mt-3 hidden">
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-green-200">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                        <span class="text-sm font-medium text-white">Coupon Applied: <span id="appliedCouponCode" class="font-bold text-purple-600"></span></span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-bold text-green-600">-<span id="discountAmount">$0.00</span></span>
                                        <button type="button" onclick="removeCoupon()" class="text-red-500 hover:text-red-700 text-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="applied_coupon" id="appliedCoupon" value="">
                        <input type="hidden" name="discount_amount" id="hiddenDiscountAmount" value="0">

                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 px-8 py-4 bg-teal-600 text-white rounded-lg text-base font-semibold hover:bg-teal-700 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2.5">
                                <i class="fas fa-shopping-cart"></i>
                                Add to Cart
                            </button>
                            <button type="button" onclick="buyNow()" class="flex-1 px-8 py-4 bg-gradient-to-r from-teal-700 to-teal-800 text-white rounded-lg text-base font-semibold hover:from-teal-800 hover:to-teal-900 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2.5">
                                <i class="fas fa-bolt"></i>
                                Buy Now
                            </button>
                            <button type="button" class="w-12 h-12 bg-white border-2 border-teal-600 text-teal-600 rounded-lg text-xl hover:bg-teal-600 hover:text-white transition-all duration-300" title="Add to Wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </form>

                    @if(in_array($product->vendor->role ?? '', ['wholesaler', 'exporter']))
                    <!-- Pay Advance Button for Wholesale and Import Products -->
                    <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fas fa-hand-holding-usd text-blue-600 text-2xl"></i>
                            <div>
                                <h4 class="font-bold text-white">Pay Advance Option Available</h4>
                                <p class="text-sm text-gray-100">Secure your order with an advance payment</p>
                            </div>
                        </div>
                        <button type="button" onclick="openAdvancePaymentModal()" class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-money-check-alt"></i>
                            Pay Advance
                        </button>
                    </div>
                    @endif
                </div>
                @else
                <div class="py-5">
                    <button class="w-full px-8 py-4 bg-gray-400 text-white rounded-lg text-base font-semibold cursor-not-allowed flex items-center justify-center gap-2.5" disabled>
                        <i class="fas fa-ban"></i>
                        Out of Stock
                    </button>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4 py-5">
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-tag text-teal-600 text-xl"></i>
                        <div class="flex-1">
                            <div class="text-xs text-gray-200">Category</div>
                            <div class="font-semibold text-white">{{ $product->category->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    @if($product->brand)
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-copyright text-teal-600 text-xl"></i>
                        <div class="flex-1">
                            <div class="text-xs text-gray-200">Brand</div>
                            <div class="font-semibold text-white">{{ $product->brand->name }}</div>
                        </div>
                    </div>
                    @endif
                    @if($product->sku)
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-barcode text-teal-600 text-xl"></i>
                        <div class="flex-1">
                            <div class="text-xs text-gray-200">SKU</div>
                            <div class="font-semibold text-white">{{ $product->sku }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-truck text-teal-600 text-xl"></i>
                        <div class="flex-1">
                            <div class="text-xs text-gray-200">Shipping</div>
                            <div class="font-semibold text-white">Free Delivery</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="py-10">
            <h2 class="text-3xl font-bold text-white mb-8 text-center">You May Also Like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <a href="{{ route('product.show', $relatedProduct->id) }}" class="block">
                        <div class="relative aspect-square overflow-hidden">
                            @if($relatedProduct->image)
                                <img src="{{ str_starts_with($relatedProduct->image, 'http') ? $relatedProduct->image : asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                            @endif
                            @if($relatedProduct->badge)
                            <span class="absolute top-2.5 left-2.5 px-3 py-1 rounded-full text-white text-xs font-semibold
                                {{ strtolower($relatedProduct->badge) === 'new' ? 'bg-green-500' : '' }}
                                {{ strtolower($relatedProduct->badge) === 'sale' ? 'bg-red-500' : '' }}
                                {{ strtolower($relatedProduct->badge) === 'hot' ? 'bg-teal-600' : '' }}
                                {{ !in_array(strtolower($relatedProduct->badge), ['new', 'sale', 'hot']) ? 'bg-teal-600' : '' }}">
                                {{ $relatedProduct->badge }}
                            </span>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="text-gray-200 text-xs mb-1">{{ $relatedProduct->category->name ?? 'Uncategorized' }}</div>
                            <h4 class="text-base font-semibold text-white mb-2.5 line-clamp-2">{{ $relatedProduct->name }}</h4>
                            <div class="flex items-center gap-2.5">
                                <span class="text-xl font-bold text-teal-600">${{ number_format($relatedProduct->price, 2) }}</span>
                                @if($relatedProduct->old_price)
                                    <span class="text-sm text-gray-400 line-through">${{ number_format($relatedProduct->old_price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Reviews Section -->
        <div class="py-10">
            <div class="bg-white rounded-2xl shadow-md p-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-white">Customer Reviews</h2>
                    @auth
                        <button onclick="toggleReviewForm()" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-star"></i>
                            Write a Review
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-sign-in-alt"></i>
                            Login to Review
                        </a>
                    @endauth
                </div>

                <!-- Review Form (Hidden by default) -->
                <div id="reviewForm" class="hidden mb-8 p-6 bg-gray-50 rounded-xl">
                    <h3 class="text-xl font-semibold text-white mb-4">Write Your Review</h3>
                    <form id="submitReviewForm" action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Rating *</label>
                                <div class="flex gap-1" id="ratingStars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" class="text-3xl text-gray-300 hover:text-teal-500 transition-colors star-rating-btn" data-rating="{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                                            <i class="far fa-star"></i>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" required>
                                <div id="ratingError" class="text-red-500 text-sm mt-1 hidden">Please select a rating</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Review Title</label>
                                <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent" placeholder="Optional title for your review">
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-white mb-2">Your Review *</label>
                            <textarea name="comment" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent" placeholder="Share your experience with this product..." required></textarea>
                        </div>
                        
                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors duration-300 flex items-center gap-2">
                                <i class="fas fa-paper-plane"></i>
                                Submit Review
                            </button>
                            <button type="button" onclick="toggleReviewForm()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-300">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Reviews List -->
                <div id="reviewsContainer">
                    <!-- Reviews will be loaded here via AJAX -->
                    @php
                        $reviews = $product->approvedReviews()->with('user')->latest()->take(10)->get();
                    @endphp
                    @if($reviews->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 24px;">
                            @foreach($reviews as $review)
                                <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 24px;">
                                    <div style="display: flex; gap: 16px;">
                                        <div style="width: 48px; height: 48px; background: #fed7aa; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fas fa-user" style="color: #ea580c;"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                                <h4 style="font-weight: 600; color: #1f2937;">{{ $review->user->name }}</h4>
                                                <div style="display: flex; gap: 2px;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star" style="color: #1a6b73;"></i>
                                                    @endfor
                                                </div>
                                                <span style="font-size: 14px; color: #6b7280;">{{ $review->created_at->format('M d, Y') }}</span>
                                            </div>
                                            @if($review->title)
                                                <h5 style="font-weight: 500; color: #374151; margin-bottom: 8px;">{{ $review->title }}</h5>
                                            @endif
                                            <p style="color: #4b5563; line-height: 1.6;">{{ $review->comment }}</p>

                                            {{-- Vendor Reply --}}
                                            @if($review->vendor_reply)
                                            <div style="margin-top: 12px; padding: 12px 16px; background: #f0f9ff; border-left: 3px solid #3b82f6; border-radius: 6px;">
                                                <p style="font-size: 13px; font-weight: 600; color: #1d4ed8; margin-bottom: 4px;"><i class="fas fa-store" style="margin-right: 6px;"></i>Seller Reply</p>
                                                <p style="font-size: 14px; color: #374151;">{{ $review->vendor_reply }}</p>
                                            </div>
                                            @endif

                                            {{-- Admin Response --}}
                                            @if($review->admin_response)
                                            <div style="margin-top: 10px; padding: 12px 16px; background: #f0fdf4; border-left: 3px solid #16a34a; border-radius: 6px;">
                                                <p style="font-size: 13px; font-weight: 600; color: #15803d; margin-bottom: 4px;"><i class="fas fa-shield-alt" style="margin-right: 6px;"></i>AlphaVendor Support</p>
                                                <p style="font-size: 14px; color: #374151;" id="admin-reply-text-{{ $review->id }}">{{ $review->admin_response }}</p>
                                            </div>
                                            @endif

                                            {{-- Admin inline reply form --}}
                                            @php $isAdmin = auth()->check() && auth()->user()->role === 'admin'; @endphp
                                            @if($isAdmin)
                                            <div style="margin-top: 12px;">
                                                <button onclick="toggleAdminReply({{ $review->id }})"
                                                    style="font-size: 13px; color: #16a34a; background: none; border: 1px solid #16a34a; border-radius: 6px; cursor: pointer; padding: 5px 12px; display: inline-flex; align-items: center; gap: 6px;">
                                                    <i class="fas fa-reply"></i>
                                                    {{ $review->admin_response ? 'Edit Reply' : 'Reply as Admin' }}
                                                </button>
                                                <div id="admin-reply-form-{{ $review->id }}" style="display:none; margin-top: 10px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px;">
                                                    <div style="display: flex; gap: 10px; align-items: flex-start;">
                                                        <div style="width: 36px; height: 36px; background: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                            <i class="fas fa-shield-alt" style="color: #fff; font-size: 13px;"></i>
                                                        </div>
                                                        <div style="flex: 1;">
                                                            <p style="font-size: 12px; font-weight: 600; color: #15803d; margin-bottom: 6px;">AlphaVendor Support Reply</p>
                                                            <textarea id="admin-reply-input-{{ $review->id }}" rows="3"
                                                                style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: none; outline: none; box-sizing: border-box;"
                                                                placeholder="Write your official reply...">{{ $review->admin_response }}</textarea>
                                                            <div style="display: flex; gap: 8px; margin-top: 8px;">
                                                                <button onclick="submitAdminReply({{ $review->id }})"
                                                                    style="padding: 8px 18px; background: #16a34a; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                                                    <i class="fas fa-paper-plane"></i> Post Reply
                                                                </button>
                                                                <button type="button" onclick="document.getElementById('admin-reply-form-{{ $review->id }}').style.display='none'"
                                                                    style="padding: 8px 14px; background: #f3f4f6; color: #374151; border: none; border-radius: 6px; font-size: 13px; cursor: pointer;">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 48px 0;">
                            <i class="fas fa-comments" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;"></i>
                            <h3 style="font-size: 20px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">No reviews yet</h3>
                            <p style="color: #9ca3af;">Be the first to review this product!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    console.log('Product show page JavaScript loaded');
    
    const unitPrice = {{ $product->price }};

    function updateTotalPrice() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const total = unitPrice * quantity;
        document.getElementById('totalPrice').textContent = '৳' + total.toFixed(2);
    }

    function increaseQuantity(max) {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue < max) {
            input.value = currentValue + 1;
            updateTotalPrice();
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        const minValue = parseInt(input.min) || 1;
        if (currentValue > minValue) {
            input.value = currentValue - 1;
            updateTotalPrice();
        }
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-5 right-5 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white font-semibold flex items-center gap-3`;
        toast.style.animation = 'slideInRight 0.3s ease-out';

        toast.innerHTML = `
            <i class="fas fa-check-circle text-xl"></i>
            <span>${message}</span>
        `;

        document.body.appendChild(toast);

        // Fade out after 2.7 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }, 2700);
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Update cart badge
    function updateCartBadge(count) {
        const badge = document.querySelector('.action-link .fas.fa-shopping-bag').parentElement.querySelector('span');
        if (count > 0) {
            if (badge) {
                badge.textContent = count;
            } else {
                const cartLink = document.querySelector('.action-link .fas.fa-shopping-bag').parentElement;
                const newBadge = document.createElement('span');
                newBadge.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #0d5c63; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;';
                newBadge.textContent = count;
                cartLink.appendChild(newBadge);
            }
        }
    }

    // Reviews Functions
    let currentRating = 0;

    function toggleAdminReply(reviewId) {
        const form = document.getElementById('admin-reply-form-' + reviewId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    function submitAdminReply(reviewId) {
        const text = document.getElementById('admin-reply-input-' + reviewId).value.trim();
        if (!text) return;

        fetch(`/admin/reviews/${reviewId}/respond`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ admin_response: text })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                // Update or insert the reply display
                let replyBox = document.getElementById('admin-reply-text-' + reviewId);
                if (replyBox) {
                    replyBox.textContent = text;
                } else {
                    const form = document.getElementById('admin-reply-form-' + reviewId);
                    const box = document.createElement('div');
                    box.style.cssText = 'margin-top:10px;padding:12px 16px;background:#f0fdf4;border-left:3px solid #16a34a;border-radius:6px;';
                    box.innerHTML = `<p style="font-size:13px;font-weight:600;color:#15803d;margin-bottom:4px;"><i class="fas fa-shield-alt" style="margin-right:6px;"></i>AlphaVendor Support</p><p style="font-size:14px;color:#374151;" id="admin-reply-text-${reviewId}">${text}</p>`;
                    form.parentElement.insertBefore(box, form);
                }
                document.getElementById('admin-reply-form-' + reviewId).style.display = 'none';
            }
        });
    }

    function toggleReviewForm() {
        const form = document.getElementById('reviewForm');
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function setRating(rating) {
        currentRating = rating;
        document.getElementById('ratingInput').value = rating;
        
        // Hide error message
        document.getElementById('ratingError').classList.add('hidden');
        
        const stars = document.querySelectorAll('.star-rating-btn');
        stars.forEach((star, index) => {
            const icon = star.querySelector('i');
            if (index < rating) {
                icon.className = 'fas fa-star text-teal-500';
                star.classList.add('selected');
            } else {
                icon.className = 'far fa-star text-gray-300';
                star.classList.remove('selected');
            }
        });
    }

    function loadReviews() {
        fetch(`/products/{{ $product->id }}/reviews`)
            .then(response => response.json())
            .then(data => {
                const reviews = data.reviews.data || data.reviews || [];
                displayReviews(reviews);
            })
            .catch(() => {});
    }

    const IS_ADMIN = {{ auth()->check() && auth()->user()->role === 'admin' ? 'true' : 'false' }};

    function displayReviews(reviews) {
        const container = document.getElementById('reviewsContainer');
        if (!container) return;

        if (reviews.length === 0) {
            container.innerHTML = `
                <div style="text-align:center;padding:48px 0;">
                    <i class="fas fa-comments" style="font-size:48px;color:#d1d5db;margin-bottom:16px;"></i>
                    <h3 style="font-size:20px;font-weight:600;color:#6b7280;margin-bottom:8px;">No reviews yet</h3>
                    <p style="color:#9ca3af;">Be the first to review this product!</p>
                </div>`;
            return;
        }

        let html = `<div style="display:flex;flex-direction:column;gap:24px;">`;

        reviews.forEach(review => {
            const stars = generateStars(review.rating);
            const date = new Date(review.created_at).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'});

            const vendorReply = review.vendor_reply ? `
                <div style="margin-top:12px;padding:12px 16px;background:#f0f9ff;border-left:3px solid #3b82f6;border-radius:6px;">
                    <p style="font-size:13px;font-weight:600;color:#1d4ed8;margin-bottom:4px;"><i class="fas fa-store" style="margin-right:6px;"></i>Seller Reply</p>
                    <p style="font-size:14px;color:#374151;">${review.vendor_reply}</p>
                </div>` : '';

            const adminReply = review.admin_response ? `
                <div style="margin-top:10px;padding:12px 16px;background:#f0fdf4;border-left:3px solid #16a34a;border-radius:6px;" id="admin-reply-box-${review.id}">
                    <p style="font-size:13px;font-weight:600;color:#15803d;margin-bottom:4px;"><i class="fas fa-shield-alt" style="margin-right:6px;"></i>AlphaVendor Support</p>
                    <p style="font-size:14px;color:#374151;" id="admin-reply-text-${review.id}">${review.admin_response}</p>
                </div>` : `<div id="admin-reply-box-${review.id}"></div>`;

            const adminBtn = IS_ADMIN ? `
                <div style="margin-top:10px;">
                    <button onclick="toggleAdminReply(${review.id})"
                        style="font-size:13px;color:#16a34a;background:none;border:1px solid #16a34a;border-radius:6px;cursor:pointer;padding:5px 12px;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas fa-reply"></i> ${review.admin_response ? 'Edit Reply' : 'Reply as Admin'}
                    </button>
                    <div id="admin-reply-form-${review.id}" style="display:none;margin-top:10px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px;">
                        <div style="display:flex;gap:10px;align-items:flex-start;">
                            <div style="width:36px;height:36px;background:#16a34a;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-shield-alt" style="color:#fff;font-size:13px;"></i>
                            </div>
                            <div style="flex:1;">
                                <p style="font-size:12px;font-weight:600;color:#15803d;margin-bottom:6px;">AlphaVendor Support Reply</p>
                                <textarea id="admin-reply-input-${review.id}" rows="3"
                                    style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;resize:none;outline:none;box-sizing:border-box;"
                                    placeholder="Write your official reply...">${review.admin_response || ''}</textarea>
                                <div style="display:flex;gap:8px;margin-top:8px;">
                                    <button onclick="submitAdminReply(${review.id})"
                                        style="padding:8px 18px;background:#16a34a;color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
                                        <i class="fas fa-paper-plane"></i> Post Reply
                                    </button>
                                    <button type="button" onclick="document.getElementById('admin-reply-form-${review.id}').style.display='none'"
                                        style="padding:8px 14px;background:#f3f4f6;color:#374151;border:none;border-radius:6px;font-size:13px;cursor:pointer;">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>` : '';

            html += `
                <div style="border-bottom:1px solid #e5e7eb;padding-bottom:24px;">
                    <div style="display:flex;gap:16px;">
                        <div style="width:48px;height:48px;background:#fed7aa;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-user" style="color:#ea580c;"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                                <h4 style="font-weight:600;color:#1f2937;">${review.user.name}</h4>
                                <div style="display:flex;gap:2px;">${stars}</div>
                                <span style="font-size:14px;color:#6b7280;">${date}</span>
                            </div>
                            ${review.title ? `<h5 style="font-weight:500;color:#374151;margin-bottom:8px;">${review.title}</h5>` : ''}
                            <p style="color:#4b5563;line-height:1.6;">${review.comment}</p>
                            ${vendorReply}
                            ${adminReply}
                            ${adminBtn}
                        </div>
                    </div>
                </div>`;
        });

        html += `</div>`;
        container.innerHTML = html;
    }

    function generateStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="fa${i <= rating ? 's' : 'r'} fa-star text-teal-500"></i>`;
        }
        return stars;
    }

    // Load reviews on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOMContentLoaded fired, loading reviews...');
        loadReviews();
        
        // Add event listeners for star rating
        const starButtons = document.querySelectorAll('.star-rating-btn');
        starButtons.forEach((button) => {
            button.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                setRating(rating);
            });
        });
    });

    // Handle review form submission
    document.getElementById('submitReviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const ratingInput = document.getElementById('ratingInput');
        const commentInput = document.getElementById('submitReviewForm').querySelector('[name="comment"]');
        
        // Validate rating
        if (!ratingInput.value || ratingInput.value < 1 || ratingInput.value > 5) {
            document.getElementById('ratingError').classList.remove('hidden');
            showToast('Please select a rating between 1 and 5 stars.', 'error');
            return;
        }
        
        // Validate comment
        if (!commentInput.value || commentInput.value.trim().length < 10) {
            showToast('Please write a review with at least 10 characters.', 'error');
            return;
        }
        
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
        // Disable button and show loading
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            // Log the form data being sent
            console.log('Form data being sent:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            return response.json().catch(() => {
                throw new Error('Invalid JSON response from server');
            });
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                showToast('Review submitted successfully!', 'success');
                form.reset();
                setRating(0); // Reset stars
                toggleReviewForm(); // Hide form
                loadReviews(); // Reload reviews
            } else {
                // Handle validation errors
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join('\n');
                    showToast(errorMessages, 'error');
                } else {
                    showToast(data.message || 'Failed to submit review', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'Failed to submit review. Please try again.', 'error');
        })
        .finally(() => {
            // Re-enable button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    });

    // Add to cart with quantity
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;

        // Disable button and show loading
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);

            if (data.success) {
                // Update cart badge
                updateCartBadge(data.cartCount);

                // Show success toast
                showToast('Product added to cart successfully!');

                // Redirect to cart page after 3 seconds
                setTimeout(() => {
                    window.location.href = '{{ route("cart.index") }}';
                }, 3000);
            } else {
                throw new Error(data.message || 'Failed to add product');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Re-enable button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            showToast('Failed to add product to cart. Please try again.', 'error');
        });
    });

    // Buy Now function - Direct checkout
    function buyNow() {
        const form = document.getElementById('addToCartForm');
        const formData = new FormData(form);
        
        // Show loading state
        const buyNowBtn = event.target.closest('button');
        const originalText = buyNowBtn.innerHTML;
        buyNowBtn.disabled = true;
        buyNowBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        fetch('{{ route("cart.buy-now") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect directly to checkout
                window.location.href = '{{ route("orders.checkout") }}';
            } else {
                throw new Error(data.message || 'Failed to process');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            buyNowBtn.disabled = false;
            buyNowBtn.innerHTML = originalText;
            showToast('Failed to process. Please try again.', 'error');
        });
    }

    // Coupon Functions
    let appliedCouponData = null;

    async function applyCoupon() {
        const couponCode = document.getElementById('couponCode').value.trim().toUpperCase();
        const quantity = parseInt(document.getElementById('quantity').value);
        const unitPrice = {{ $product->price }};
        const subtotal = unitPrice * quantity;

        if (!couponCode) {
            showCouponMessage('Please enter a coupon code', 'error');
            return;
        }

        try {
            const response = await fetch('/api/validate-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    code: couponCode,
                    subtotal: subtotal
                })
            });

            const data = await response.json();

            if (data.success) {
                appliedCouponData = data.coupon;
                const discount = data.discount;

                // Update UI
                document.getElementById('appliedCouponCode').textContent = couponCode;
                document.getElementById('discountAmount').textContent = '$' + discount.toFixed(2);
                document.getElementById('appliedCoupon').value = couponCode;
                document.getElementById('hiddenDiscountAmount').value = discount;
                document.getElementById('couponDiscount').classList.remove('hidden');
                document.getElementById('couponCode').disabled = true;

                // Update total price
                const newTotal = subtotal - discount;
                document.getElementById('totalPrice').textContent = '$' + newTotal.toFixed(2);

                showCouponMessage('Coupon applied successfully! You saved $' + discount.toFixed(2), 'success');
            } else {
                showCouponMessage(data.message || 'Invalid coupon code', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showCouponMessage('Failed to apply coupon. Please try again.', 'error');
        }
    }

    function removeCoupon() {
        appliedCouponData = null;
        document.getElementById('couponCode').value = '';
        document.getElementById('couponCode').disabled = false;
        document.getElementById('appliedCoupon').value = '';
        document.getElementById('hiddenDiscountAmount').value = '0';
        document.getElementById('couponDiscount').classList.add('hidden');
        document.getElementById('couponMessage').classList.add('hidden');

        // Recalculate total
        updateTotalPrice();
    }

    function showCouponMessage(message, type) {
        const messageEl = document.getElementById('couponMessage');
        messageEl.textContent = message;
        messageEl.className = 'mt-2 text-sm ' + (type === 'success' ? 'text-green-600' : 'text-red-600');
        messageEl.classList.remove('hidden');

        if (type === 'error') {
            setTimeout(() => {
                messageEl.classList.add('hidden');
            }, 5000);
        }
    }

    // Update total price when quantity changes
    const originalUpdateTotalPrice = updateTotalPrice;
    updateTotalPrice = function() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const unitPrice = {{ $product->price }};
        let total = unitPrice * quantity;

        // Apply coupon discount if exists
        const appliedCoupon = document.getElementById('appliedCoupon').value;
        if (appliedCoupon && appliedCouponData) {
            const discount = calculateDiscount(total, appliedCouponData);
            document.getElementById('discountAmount').textContent = '$' + discount.toFixed(2);
            document.getElementById('hiddenDiscountAmount').value = discount;
            total -= discount;
        }

        document.getElementById('totalPrice').textContent = '$' + total.toFixed(2);
    };

    function calculateDiscount(subtotal, coupon) {
        if (coupon.min_purchase && subtotal < coupon.min_purchase) {
            return 0;
        }

        let discount = 0;
        if (coupon.type === 'percentage') {
            discount = (subtotal * coupon.value) / 100;
            if (coupon.max_discount && discount > coupon.max_discount) {
                discount = coupon.max_discount;
            }
        } else {
            discount = Math.min(coupon.value, subtotal);
        }

        return discount;
    }
</script>

<script>
    // Read More/Less for Description on Mobile
    if (window.innerWidth <= 768) {
        const description = document.querySelector('.product-description');
        if (description && description.scrollHeight > 150) {
            const readMoreBtn = document.createElement('button');
            readMoreBtn.className = 'text-teal-600 font-semibold text-sm mt-2 hover:text-teal-700';
            readMoreBtn.textContent = 'Read More';
            readMoreBtn.onclick = function() {
                description.classList.toggle('expanded');
                this.textContent = description.classList.contains('expanded') ? 'Read Less' : 'Read More';
            };
            description.parentElement.appendChild(readMoreBtn);
        }
    }
</script>

<!-- Advance Payment Modal -->
<div id="advancePaymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-money-check-alt text-blue-600"></i>
                    Pay Advance
                </h3>
                <button onclick="closeAdvancePaymentModal()" class="text-gray-400 hover:text-gray-100 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <form id="advancePaymentForm" class="p-6">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <!-- Product Summary -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-semibold text-white mb-2">{{ $product->name }}</h4>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-100">Unit Price:</span>
                    <span class="font-semibold">{{ currency($product->price) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-1">
                    <span class="text-gray-100">Quantity:</span>
                    <span class="font-semibold" id="modalQuantity">1</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-1 pt-2 border-t border-gray-200">
                    <span class="text-gray-100">Total Amount:</span>
                    <span class="font-bold text-lg text-teal-600" id="modalTotalPrice">{{ currency($product->price) }}</span>
                </div>
            </div>

            <!-- Advance Payment Options -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-white mb-3">Select Advance Payment Amount</label>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-all">
                        <input type="radio" name="advance_percentage" value="25" class="w-5 h-5 text-blue-600" checked onchange="updateAdvanceAmount()">
                        <span class="ml-3 flex-1">
                            <span class="font-semibold text-white">25% Advance</span>
                            <span class="block text-sm text-gray-200" id="advance25">$0.00</span>
                        </span>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-all">
                        <input type="radio" name="advance_percentage" value="50" class="w-5 h-5 text-blue-600" onchange="updateAdvanceAmount()">
                        <span class="ml-3 flex-1">
                            <span class="font-semibold text-white">50% Advance</span>
                            <span class="block text-sm text-gray-200" id="advance50">$0.00</span>
                        </span>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-all">
                        <input type="radio" name="advance_percentage" value="75" class="w-5 h-5 text-blue-600" onchange="updateAdvanceAmount()">
                        <span class="ml-3 flex-1">
                            <span class="font-semibold text-white">75% Advance</span>
                            <span class="block text-sm text-gray-200" id="advance75">$0.00</span>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-white mb-3">Payment Method</label>
                <select name="payment_method" id="paymentMethodSelect" onchange="toggleTransactionField()" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="bkash">bKash</option>
                    <option value="nagad">Nagad</option>
                    <option value="rocket">Rocket</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>

            <!-- Transaction ID (for bKash/Nagad/Rocket) -->
            <div class="mb-6" id="transactionIdField">
                <label class="block text-sm font-semibold text-white mb-2">Transaction ID</label>
                <input type="text" name="transaction_id" id="transactionIdInput" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your transaction ID">
                <p class="text-xs text-gray-200 mt-1">Send payment to the number provided and enter the transaction ID here.</p>
            </div>

            <!-- Bank Transfer Info -->
            <!-- Bank Transfer - User Input Fields -->
            <div class="mb-6" id="bankInfoField" style="display:none;">
                <div class="rounded-xl border border-blue-200 overflow-hidden mb-3">
                    <div class="bg-blue-600 px-4 py-3 flex items-center gap-2">
                        <i class="fas fa-university text-white"></i>
                        <span class="text-white font-semibold text-sm">Your Bank Transfer Info</span>
                    </div>
                    <div class="bg-blue-50 px-4 py-3 text-xs text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Please transfer <strong id="bankAmountDisplay">৳0.00</strong> and fill in your sender details below for verification.
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Your Bank Name</label>
                        <input type="text" name="bank_name" id="bankNameInput"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g. Dutch-Bangla Bank">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Account Holder Name</label>
                        <input type="text" name="bank_account_holder"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Name on your bank account">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Your Account Number</label>
                        <input type="text" name="bank_account_number"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Your bank account number">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Transaction / Reference ID</label>
                        <input type="text" name="transaction_id" id="bankTxInput"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Transaction or reference number">
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-white mb-2">Contact Number</label>
                <input type="tel" name="contact_number" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your phone number">
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-white mb-2">Additional Notes (Optional)</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Any special requirements or notes..."></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-bold text-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i>
                Confirm Advance Payment
            </button>
        </form>
    </div>
</div>

<script>
    function toggleTransactionField() {
        const method = document.getElementById('paymentMethodSelect').value;
        const txField = document.getElementById('transactionIdField');
        const txInput = document.getElementById('transactionIdInput');
        const bankField = document.getElementById('bankInfoField');
        const bankInputs = bankField.querySelectorAll('input');

        if (method === 'bank_transfer') {
            txField.style.display = 'none';
            txInput.removeAttribute('required');
            bankField.style.display = 'block';
            bankInputs.forEach(i => i.setAttribute('required', 'required'));
            updateBankAmount();
        } else {
            txField.style.display = 'block';
            txInput.setAttribute('required', 'required');
            bankField.style.display = 'none';
            bankInputs.forEach(i => i.removeAttribute('required'));
        }
    }

    function updateBankAmount() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const totalPrice = unitPrice * quantity;
        const pct = document.querySelector('input[name="advance_percentage"]:checked');
        const amount = pct ? totalPrice * (parseInt(pct.value) / 100) : 0;
        const el = document.getElementById('bankAmountDisplay');
        if (el) el.textContent = '৳' + amount.toFixed(2);
    }

    function openAdvancePaymentModal() {
        const modal = document.getElementById('advancePaymentModal');
        const quantity = parseInt(document.getElementById('quantity').value);
        const totalPrice = unitPrice * quantity;
        
        // Update modal with current quantity and price
        document.getElementById('modalQuantity').textContent = quantity;
        document.getElementById('modalTotalPrice').textContent = '৳' + totalPrice.toFixed(2);
        
        // Calculate advance amounts
        updateAdvanceAmount();
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAdvancePaymentModal() {
        const modal = document.getElementById('advancePaymentModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function updateAdvanceAmount() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const totalPrice = unitPrice * quantity;
        
        document.getElementById('advance25').textContent = '৳' + (totalPrice * 0.25).toFixed(2);
        document.getElementById('advance50').textContent = '৳' + (totalPrice * 0.50).toFixed(2);
        document.getElementById('advance75').textContent = '৳' + (totalPrice * 0.75).toFixed(2);
        updateBankAmount();
    }

    // Handle advance payment form submission
    document.getElementById('advancePaymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const quantity = parseInt(document.getElementById('quantity').value);
        formData.append('quantity', quantity);
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalContent = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Submit to API
        fetch('{{ route("advance-payments.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeAdvancePaymentModal();
                showToast(data.message, 'success');
                this.reset();
            } else {
                throw new Error(data.message || 'Failed to process');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'Failed to submit advance payment request', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
        });
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAdvancePaymentModal();
        }
    });
</script>

@endsection
