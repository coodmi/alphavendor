<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RetailController;
use App\Http\Controllers\WholesaleController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RetailerDashboardController;
use App\Http\Controllers\WholesalerDashboardController;
use App\Http\Controllers\ExporterDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\RoleApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\RetailerBrandController;
use App\Http\Controllers\RetailerCategoryController;
use App\Http\Controllers\RetailerProductController;
use App\Http\Controllers\Wholesaler\WholesalerBrandController;
use App\Http\Controllers\Wholesaler\WholesalerCategoryController;
use App\Http\Controllers\Wholesaler\WholesalerProductController;
use App\Http\Controllers\SupplierLocationController;
use App\Http\Controllers\ExporterBrandController;
use App\Http\Controllers\ExporterCategoryController;
use App\Http\Controllers\ExporterProductController;
use App\Http\Controllers\ExporterCertificationController;
use App\Http\Controllers\Admin\RetailPageController as AdminRetailPageController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\CommissionController as AdminCommissionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WithdrawalController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/retail', [RetailController::class, 'index'])->name('retail');
Route::get('/wholesale', [WholesaleController::class, 'index'])->name('wholesale');
Route::get('/export', [ExportController::class, 'index'])->name('export');

// Cart routes (accessible to all users)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

        // Role applications management
        Route::get('/applications', [RoleApplicationController::class, 'index'])->name('applications');
        Route::get('/applications/{application}', [RoleApplicationController::class, 'show'])->name('applications.show');
        Route::post('/applications/{application}/approve', [RoleApplicationController::class, 'approve'])->name('applications.approve');
        Route::post('/applications/{application}/reject', [RoleApplicationController::class, 'reject'])->name('applications.reject');

        // Category management
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Product management
        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Brand management
        Route::get('/brands', [BrandController::class, 'index'])->name('brands');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

        // Order management
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');

        // Retail Page CMS
        Route::get('/retail-page', [AdminRetailPageController::class, 'index'])->name('retail-page');
        Route::post('/retail-page', [AdminRetailPageController::class, 'update'])->name('retail-page.update');

        // Banner management
        Route::get('/banners', [AdminBannerController::class, 'index'])->name('banners');
        Route::post('/banners', [AdminBannerController::class, 'store'])->name('banners.store');
        Route::put('/banners/{banner}', [AdminBannerController::class, 'update'])->name('banners.update');
        Route::delete('/banners/{banner}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');

        // Commission management
        Route::get('/commissions', [AdminCommissionController::class, 'index'])->name('commissions');
        Route::post('/commissions', [AdminCommissionController::class, 'store'])->name('commissions.store');
        Route::put('/commissions/{commission}', [AdminCommissionController::class, 'update'])->name('commissions.update');
        Route::delete('/commissions/{commission}', [AdminCommissionController::class, 'destroy'])->name('commissions.destroy');
    });

    // Retailer routes
    Route::middleware('role:retailer')->prefix('retailer')->name('retailer.')->group(function () {
        Route::get('/dashboard', [RetailerDashboardController::class, 'index'])->name('dashboard');

        // Brand management
        Route::get('/brands', [RetailerBrandController::class, 'index'])->name('brands');
        Route::post('/brands', [RetailerBrandController::class, 'store'])->name('brands.store');
        Route::put('/brands/{brand}', [RetailerBrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [RetailerBrandController::class, 'destroy'])->name('brands.destroy');

        // Category management
        Route::get('/categories', [RetailerCategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [RetailerCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [RetailerCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [RetailerCategoryController::class, 'destroy'])->name('categories.destroy');

        // Product management
        Route::get('/products', [RetailerProductController::class, 'index'])->name('products');
        Route::post('/products', [RetailerProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [RetailerProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [RetailerProductController::class, 'destroy'])->name('products.destroy');

        // Order management
        Route::get('/orders', [RetailerDashboardController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [RetailerDashboardController::class, 'showOrder'])->name('orders.show');
        Route::patch('/orders/{order}/status', [RetailerDashboardController::class, 'updateOrderStatus'])->name('orders.update-status');
    });

    // Wholesaler routes
    Route::middleware('role:wholesaler')->prefix('wholesaler')->name('wholesaler.')->group(function () {
        Route::get('/dashboard', [WholesalerDashboardController::class, 'index'])->name('dashboard');

        // Brand management
        Route::get('/brands', [WholesalerBrandController::class, 'index'])->name('brands');
        Route::post('/brands', [WholesalerBrandController::class, 'store'])->name('brands.store');
        Route::put('/brands/{brand}', [WholesalerBrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [WholesalerBrandController::class, 'destroy'])->name('brands.destroy');

        // Category management
        Route::get('/categories', [WholesalerCategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [WholesalerCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [WholesalerCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [WholesalerCategoryController::class, 'destroy'])->name('categories.destroy');

        // Product management
        Route::get('/products', [WholesalerProductController::class, 'index'])->name('products');
        Route::post('/products', [WholesalerProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [WholesalerProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [WholesalerProductController::class, 'destroy'])->name('products.destroy');

        // Supplier Location management
        Route::get('/supplier-locations', [SupplierLocationController::class, 'index'])->name('supplier-locations.index');
        Route::post('/supplier-locations', [SupplierLocationController::class, 'store'])->name('supplier-locations.store');
        Route::put('/supplier-locations/{supplierLocation}', [SupplierLocationController::class, 'update'])->name('supplier-locations.update');
        Route::delete('/supplier-locations/{supplierLocation}', [SupplierLocationController::class, 'destroy'])->name('supplier-locations.destroy');
    });

    // Exporter routes
    Route::middleware('role:exporter')->prefix('exporter')->name('exporter.')->group(function () {
        Route::get('/dashboard', [ExporterDashboardController::class, 'index'])->name('dashboard');

        // Orders
        Route::get('/orders', [ExporterDashboardController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [ExporterDashboardController::class, 'showOrder'])->name('orders.show');

        // Certification management
        Route::get('/certifications', [ExporterCertificationController::class, 'index'])->name('certifications');
        Route::post('/certifications', [ExporterCertificationController::class, 'store'])->name('certifications.store');
        Route::post('/certifications/bulk-update', [ExporterCertificationController::class, 'bulkUpdate'])->name('certifications.bulk-update');
        Route::put('/certifications/{certification}', [ExporterCertificationController::class, 'update'])->name('certifications.update');
        Route::delete('/certifications/{certification}', [ExporterCertificationController::class, 'destroy'])->name('certifications.destroy');

        // Brand management
        Route::get('/brands', [ExporterBrandController::class, 'index'])->name('brands');
        Route::post('/brands', [ExporterBrandController::class, 'store'])->name('brands.store');
        Route::put('/brands/{brand}', [ExporterBrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [ExporterBrandController::class, 'destroy'])->name('brands.destroy');

        // Category management
        Route::get('/categories', [ExporterCategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [ExporterCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [ExporterCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [ExporterCategoryController::class, 'destroy'])->name('categories.destroy');

        // Product management
        Route::get('/products', [ExporterProductController::class, 'index'])->name('products');
        Route::post('/products', [ExporterProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ExporterProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ExporterProductController::class, 'destroy'])->name('products.destroy');
    });

    // User routes
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    });

    // Role application routes (for regular users)
    Route::middleware('role:user')->group(function () {
        Route::get('/apply-role', [RoleApplicationController::class, 'create'])->name('role.apply');
        Route::post('/apply-role', [RoleApplicationController::class, 'store'])->name('role.apply.store');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-image', [ProfileController::class, 'uploadImage'])->name('profile.upload-image');
    Route::delete('/profile/delete-image', [ProfileController::class, 'deleteImage'])->name('profile.delete-image');

    // Order routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // Vendor order routes
    Route::middleware('role:retailer,wholesaler,exporter')->group(function () {
        Route::get('/vendor/orders', [OrderController::class, 'vendorOrders'])->name('vendor.orders');
        Route::patch('/vendor/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('vendor.orders.update-status');

        // Wallet routes
        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');

        // Withdrawal routes
        Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/create', [WithdrawalController::class, 'create'])->name('withdrawals.create');
        Route::post('/withdrawals', [WithdrawalController::class, 'store'])->name('withdrawals.store');

        // Payment methods
        Route::get('/withdrawals/payment-methods', [WithdrawalController::class, 'paymentMethods'])->name('withdrawals.payment-methods');
        Route::post('/withdrawals/payment-methods', [WithdrawalController::class, 'storePaymentMethod'])->name('withdrawals.payment-methods.store');
        Route::delete('/withdrawals/payment-methods/{id}', [WithdrawalController::class, 'deletePaymentMethod'])->name('withdrawals.payment-methods.delete');
    });
});
