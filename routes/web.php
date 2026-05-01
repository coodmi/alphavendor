<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RetailController;
use App\Http\Controllers\WholesaleController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RetailerDashboardController;
use App\Http\Controllers\WholesalerDashboardController;
use App\Http\Controllers\ExporterDashboardController;
use App\Http\Controllers\EmployeeDashboardController;
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
use App\Http\Controllers\Admin\AboutPageController as AdminAboutPageController;
use App\Http\Controllers\Admin\ContactPageController as AdminContactPageController;
use App\Http\Controllers\Admin\HomePageController as AdminHomePageController;
use App\Http\Controllers\Admin\WholesalePageController as AdminWholesalePageController;
use App\Http\Controllers\Admin\ImportPageController as AdminImportPageController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\PromoBannerController as AdminPromoBannerController;
use App\Http\Controllers\Admin\CommissionController as AdminCommissionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ManualPaymentController;
use App\Http\Controllers\Admin\PaymentSettingsController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\Auth\OtpRegisterController;
use App\Http\Controllers\Admin\OtpController as AdminOtpController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AdvancePaymentController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/shipping-info', [HomeController::class, 'shippingInfo'])->name('shipping-info');
Route::get('/terms-and-conditions', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/special-offers', [HomeController::class, 'allOffers'])->name('special-offers.index');
Route::get('/special-offers/{slug}', [\App\Http\Controllers\Admin\SpecialOfferController::class, 'show'])->name('special-offers.show');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/chat/faqs', function() {
    return response()->json(\App\Models\ChatFaq::active()->get(['id','question','answer']));
})->name('chat.faqs.public');
Route::post('/chat/widget/send', [\App\Http\Controllers\ChatController::class, 'widgetSend'])->name('chat.widget.send');
Route::get('/chat/widget/messages', [\App\Http\Controllers\ChatController::class, 'widgetMessages'])->name('chat.widget.messages');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/retail', [RetailController::class, 'index'])->name('retail');
Route::get('/wholesale', [WholesaleController::class, 'index'])->name('wholesale');
Route::get('/import', [ImportController::class, 'index'])->name('import');
// Redirect /export to /import
Route::redirect('/export', '/import', 301);

// OTP Test Route (remove in production)
Route::get('/test-otp', function () {
    return view('test-otp');
})->name('test-otp');

// Sellers routes
Route::get('/sellers', [SellerController::class, 'index'])->name('sellers.index');
Route::get('/sellers/{seller}/products', [SellerController::class, 'products'])->name('sellers.products');

// Cart routes (accessible to all users)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buy-now');
Route::patch('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');

// Coupon validation API
Route::post('/api/validate-coupon', [CartController::class, 'validateCoupon'])->name('api.validate-coupon');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [OtpRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [OtpRegisterController::class, 'register']);
    Route::get('/register/otp', [OtpRegisterController::class, 'showOtpForm'])->name('register.otp.form');
    Route::post('/register/otp', [OtpRegisterController::class, 'verifyOtp'])->name('register.otp.verify');
    Route::post('/register/otp/resend', [OtpRegisterController::class, 'resendOtp'])->name('register.otp.resend');
    
    // Password Reset Routes (Mobile OTP)
    Route::get('/password/reset', [App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/send-otp', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetOtp'])->name('password.send.otp');
    Route::get('/password/verify-otp', [App\Http\Controllers\Auth\PasswordResetController::class, 'showOtpForm'])->name('password.otp.form');
    Route::post('/password/verify-otp', [App\Http\Controllers\Auth\PasswordResetController::class, 'verifyOtp'])->name('password.verify.otp');
    Route::post('/password/resend-otp', [App\Http\Controllers\Auth\PasswordResetController::class, 'resendOtp'])->name('password.resend.otp');
    Route::get('/password/reset-form', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/password/reset', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Public review routes (anyone can view reviews)
Route::get('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'productReviews'])->name('product.reviews');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Wishlist routes
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [App\Http\Controllers\WishlistController::class, 'index'])->name('index');
        Route::post('/add/{product}', [App\Http\Controllers\WishlistController::class, 'add'])->name('add');
        Route::delete('/remove/{product}', [App\Http\Controllers\WishlistController::class, 'remove'])->name('remove');
        Route::post('/toggle/{product}', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('toggle');
        Route::get('/count', [App\Http\Controllers\WishlistController::class, 'count'])->name('count');
        Route::get('/check/{product}', [App\Http\Controllers\WishlistController::class, 'check'])->name('check');
    });

    // Verification routes (for vendors)
    Route::get('/verification', [\App\Http\Controllers\VerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification/upload', [\App\Http\Controllers\VerificationController::class, 'upload'])->name('verification.upload');
    Route::delete('/verification/documents/{document}', [\App\Http\Controllers\VerificationController::class, 'delete'])->name('verification.delete');
    Route::post('/verification/submit', [\App\Http\Controllers\VerificationController::class, 'submit'])->name('verification.submit');

    // Review routes (only authenticated users can submit/manage reviews)
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/my-reviews', [App\Http\Controllers\ReviewController::class, 'userReviews'])->name('reviews.user');
    Route::post('/reviews/{review}/helpful', [App\Http\Controllers\ReviewController::class, 'markHelpful'])->name('reviews.helpful');
    Route::get('/products/{product}/can-review', [App\Http\Controllers\ReviewController::class, 'canReview'])->name('product.can-review');

    // Advance Payment routes
    Route::post('/advance-payments', [App\Http\Controllers\AdvancePaymentController::class, 'store'])->name('advance-payments.store');
    Route::get('/my-advance-payments', [App\Http\Controllers\AdvancePaymentController::class, 'userPayments'])->name('advance-payments.user');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Analytics & Reports
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetUserPassword'])->name('users.reset-password');

        // Role applications management
        Route::get('/applications', [RoleApplicationController::class, 'index'])->name('applications');
        Route::get('/applications/{application}', [RoleApplicationController::class, 'show'])->name('applications.show');
        Route::post('/applications/{application}/approve', [RoleApplicationController::class, 'approve'])->name('applications.approve');
        Route::post('/applications/{application}/reject', [RoleApplicationController::class, 'reject'])->name('applications.reject');

        // User Permissions Management
        Route::get('/user-permissions', [AdminController::class, 'userPermissions'])->name('user-permissions');
        Route::post('/user-permissions/{user}', [AdminController::class, 'updateUserPermissions'])->name('user-permissions.update');
        Route::get('/user-permissions/{user}/edit', [AdminController::class, 'editUserPermissions'])->name('user-permissions.edit');

        // Role Settings Management
        Route::get('/role-settings', [AdminController::class, 'roleSettings'])->name('role-settings');
        Route::post('/role-settings', [AdminController::class, 'updateRoleSettings'])->name('role-settings.update');
        Route::post('/role-settings/create', [AdminController::class, 'createRole'])->name('role-settings.create');
        Route::delete('/role-settings/{role}', [AdminController::class, 'deleteRole'])->name('role-settings.delete');

        // Employee Role Management
        Route::post('/employee-roles', [AdminController::class, 'storeEmployeeRole'])->name('employee-roles.store');
        Route::put('/employee-roles/{employeeRole}', [AdminController::class, 'updateEmployeeRole'])->name('employee-roles.update');
        Route::delete('/employee-roles/{employeeRole}', [AdminController::class, 'deleteEmployeeRole'])->name('employee-roles.delete');
        Route::post('/employee-roles/{employeeRole}/toggle', [AdminController::class, 'toggleEmployeeRole'])->name('employee-roles.toggle');

        // User Activity Logs
        Route::get('/user-activity', [AdminController::class, 'userActivity'])->name('user-activity');
        Route::get('/user-activity/{user}', [AdminController::class, 'userActivityDetails'])->name('user-activity.details');
        Route::delete('/user-activity/clear', [AdminController::class, 'clearActivityLogs'])->name('user-activity.clear');

        // Employee Management
        Route::get('/employees', [AdminController::class, 'employees'])->name('employees');
        Route::get('/employees/create', [AdminController::class, 'createEmployee'])->name('employees.create');
        Route::post('/employees', [AdminController::class, 'storeEmployee'])->name('employees.store');
        Route::get('/employees/{user}/edit', [AdminController::class, 'editEmployee'])->name('employees.edit');
        Route::put('/employees/{user}', [AdminController::class, 'updateEmployee'])->name('employees.update');
        Route::delete('/employees/{user}', [AdminController::class, 'deleteEmployee'])->name('employees.delete');
        Route::get('/employee-permissions', [AdminController::class, 'employeePermissions'])->name('employee-permissions');
        Route::post('/employee-permissions/{user}', [AdminController::class, 'updateEmployeePermissions'])->name('employee-permissions.update');

        // Vendor Management
        Route::get('/vendors', [AdminController::class, 'vendors'])->name('vendors');
        Route::get('/vendors/{user}', [AdminController::class, 'showVendor'])->name('vendors.show');
        Route::patch('/vendors/{user}/status', [AdminController::class, 'updateVendorStatus'])->name('vendors.update-status');
        Route::patch('/vendors/{user}/commission', [AdminController::class, 'updateVendorCommission'])->name('vendors.update-commission');
        Route::patch('/vendors/{user}/badge', [AdminController::class, 'updateVendorBadge'])->name('vendors.update-badge');
        
        // Bulk vendor actions
        Route::post('/vendors/bulk-update-status', [AdminController::class, 'bulkUpdateVendorStatus'])->name('vendors.bulk-update-status');
        Route::post('/vendors/bulk-delete', [AdminController::class, 'bulkDeleteVendors'])->name('vendors.bulk-delete');

        // Vendor Applications (combines role applications and verification documents)
        Route::get('/vendor-applications', [AdminController::class, 'vendorApplications'])->name('vendor-applications');
        Route::get('/vendor-applications/{user}', [AdminController::class, 'showVendorApplication'])->name('vendor-applications.show');
        Route::post('/vendor-applications/{user}/approve', [AdminController::class, 'approveVendorApplication'])->name('vendor-applications.approve');
        Route::post('/vendor-applications/{user}/reject', [AdminController::class, 'rejectVendorApplication'])->name('vendor-applications.reject');

        // Coupon Management
        Route::get('/coupons', [AdminController::class, 'coupons'])->name('coupons');
        Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('coupons.store');
        Route::put('/coupons/{coupon}', [AdminController::class, 'updateCoupon'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [AdminController::class, 'deleteCoupon'])->name('coupons.delete');
        Route::patch('/coupons/{coupon}/toggle', [AdminController::class, 'toggleCoupon'])->name('coupons.toggle');

        // Category management
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Product management
        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::post('/products/draft', [ProductController::class, 'saveDraft'])->name('products.draft');
        Route::get('/products/draft/{id}', [ProductController::class, 'getDraft'])->name('products.draft.get');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Brand management
        Route::get('/brands', [BrandController::class, 'index'])->name('brands');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

        // Special Offers management
        Route::get('/special-offers', [\App\Http\Controllers\Admin\SpecialOfferController::class, 'index'])->name('special-offers.index');
        Route::post('/special-offers', [\App\Http\Controllers\Admin\SpecialOfferController::class, 'store'])->name('special-offers.store');
        Route::put('/special-offers/{specialOffer}', [\App\Http\Controllers\Admin\SpecialOfferController::class, 'update'])->name('special-offers.update');
        Route::delete('/special-offers/{specialOffer}', [\App\Http\Controllers\Admin\SpecialOfferController::class, 'destroy'])->name('special-offers.destroy');

        // Vendor Badges management
        Route::get('/vendor-badges', [\App\Http\Controllers\Admin\VendorBadgeController::class, 'index'])->name('vendor-badges.index');
        Route::post('/vendor-badges', [\App\Http\Controllers\Admin\VendorBadgeController::class, 'store'])->name('vendor-badges.store');
        Route::put('/vendor-badges/{vendorBadge}', [\App\Http\Controllers\Admin\VendorBadgeController::class, 'update'])->name('vendor-badges.update');
        Route::delete('/vendor-badges/{vendorBadge}', [\App\Http\Controllers\Admin\VendorBadgeController::class, 'destroy'])->name('vendor-badges.destroy');

        // Site Settings (Header & Footer)
        Route::get('/site-settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'index'])->name('site-settings.index');
        Route::post('/site-settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('site-settings.update');

        // Page Content Management
        Route::get('/page-contents/terms', [\App\Http\Controllers\Admin\PageContentController::class, 'terms'])->name('page-contents.terms');
        Route::post('/page-contents/terms', [\App\Http\Controllers\Admin\PageContentController::class, 'updateTerms'])->name('page-contents.terms.update');
        Route::get('/page-contents/privacy', [\App\Http\Controllers\Admin\PageContentController::class, 'privacy'])->name('page-contents.privacy');
        Route::post('/page-contents/privacy', [\App\Http\Controllers\Admin\PageContentController::class, 'updatePrivacy'])->name('page-contents.privacy.update');
        Route::get('/page-contents/shipping', [\App\Http\Controllers\Admin\PageContentController::class, 'shipping'])->name('page-contents.shipping');
        Route::post('/page-contents/shipping', [\App\Http\Controllers\Admin\PageContentController::class, 'updateShipping'])->name('page-contents.shipping.update');

        // Contact Page Management
        Route::get('/contact-page', [\App\Http\Controllers\Admin\ContactPageContentController::class, 'index'])->name('contact-page.index');
        Route::post('/contact-page', [\App\Http\Controllers\Admin\ContactPageContentController::class, 'update'])->name('contact-page.update');

        // Shipping Methods Management
        Route::get('/shipping-methods', [\App\Http\Controllers\Admin\ShippingMethodController::class, 'index'])->name('shipping-methods.index');
        Route::post('/shipping-methods', [\App\Http\Controllers\Admin\ShippingMethodController::class, 'store'])->name('shipping-methods.store');
        Route::put('/shipping-methods/{shippingMethod}', [\App\Http\Controllers\Admin\ShippingMethodController::class, 'update'])->name('shipping-methods.update');
        Route::delete('/shipping-methods/{shippingMethod}', [\App\Http\Controllers\Admin\ShippingMethodController::class, 'destroy'])->name('shipping-methods.destroy');

        // Order management
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::get('/orders/{order}/invoice', [App\Http\Controllers\OrderController::class, 'invoice'])->name('orders.invoice');
        Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');

        // Return & Refund Management
        Route::prefix('returns')->name('returns.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ReturnManagementController::class, 'index'])->name('index');
            Route::get('/{return}', [App\Http\Controllers\Admin\ReturnManagementController::class, 'show'])->name('show');
            Route::post('/{return}/approve', [App\Http\Controllers\Admin\ReturnManagementController::class, 'approve'])->name('approve');
            Route::post('/{return}/reject', [App\Http\Controllers\Admin\ReturnManagementController::class, 'reject'])->name('reject');
            Route::patch('/{return}/status', [App\Http\Controllers\Admin\ReturnManagementController::class, 'updateStatus'])->name('update-status');
            Route::post('/{return}/refund', [App\Http\Controllers\Admin\ReturnManagementController::class, 'processRefund'])->name('process-refund');
        });

        // Retail Page CMS
        Route::get('/retail-page', [AdminRetailPageController::class, 'index'])->name('retail-page');
        Route::post('/retail-page', [AdminRetailPageController::class, 'update'])->name('retail-page.update');

        // Home Page CMS
        Route::get('/home-page', [AdminHomePageController::class, 'index'])->name('home-page');
        Route::post('/home-page/slides', [AdminHomePageController::class, 'store'])->name('home-page.slides.store');
        Route::put('/home-page/slides/{slide}', [AdminHomePageController::class, 'update'])->name('home-page.slides.update');
        Route::delete('/home-page/slides/{slide}', [AdminHomePageController::class, 'destroy'])->name('home-page.slides.destroy');
        Route::post('/home-page/slides/{slide}/toggle', [AdminHomePageController::class, 'toggleStatus'])->name('home-page.slides.toggle');

        // Wholesale Page CMS
        Route::get('/wholesale-page', [AdminWholesalePageController::class, 'index'])->name('wholesale-page');
        Route::post('/wholesale-page/slides', [AdminWholesalePageController::class, 'store'])->name('wholesale-page.slides.store');
        Route::put('/wholesale-page/slides/{slide}', [AdminWholesalePageController::class, 'update'])->name('wholesale-page.slides.update');
        Route::delete('/wholesale-page/slides/{slide}', [AdminWholesalePageController::class, 'destroy'])->name('wholesale-page.slides.destroy');
        Route::post('/wholesale-page/slides/{slide}/toggle', [AdminWholesalePageController::class, 'toggleStatus'])->name('wholesale-page.slides.toggle');

        // Import Page CMS
        Route::get('/import-page', [AdminImportPageController::class, 'index'])->name('import-page');
        Route::post('/import-page/slides', [AdminImportPageController::class, 'store'])->name('import-page.slides.store');
        Route::put('/import-page/slides/{slide}', [AdminImportPageController::class, 'update'])->name('import-page.slides.update');
        Route::delete('/import-page/slides/{slide}', [AdminImportPageController::class, 'destroy'])->name('import-page.slides.destroy');
        Route::post('/import-page/slides/{slide}/toggle', [AdminImportPageController::class, 'toggleStatus'])->name('import-page.slides.toggle');

        // About Page CMS
        Route::get('/about-page', [\App\Http\Controllers\Admin\AboutPageContentController::class, 'index'])->name('about-page.index');
        Route::post('/about-page', [\App\Http\Controllers\Admin\AboutPageContentController::class, 'update'])->name('about-page.update');

        // Chatbot Management
        Route::get('/chatbot', [\App\Http\Controllers\Admin\ChatbotController::class, 'index'])->name('chatbot.index');
        Route::post('/chatbot', [\App\Http\Controllers\Admin\ChatbotController::class, 'update'])->name('chatbot.update');
        Route::post('/chatbot/faqs', [\App\Http\Controllers\Admin\ChatbotController::class, 'storeFaq'])->name('chatbot.faqs.store');
        Route::put('/chatbot/faqs/{faq}', [\App\Http\Controllers\Admin\ChatbotController::class, 'updateFaq'])->name('chatbot.faqs.update');
        Route::delete('/chatbot/faqs/{faq}', [\App\Http\Controllers\Admin\ChatbotController::class, 'destroyFaq'])->name('chatbot.faqs.destroy');
        Route::get('/chatbot/conversations/{conversation}', [\App\Http\Controllers\Admin\ChatbotController::class, 'conversation'])->name('chatbot.conversation');
        Route::post('/chatbot/conversations/{conversation}/reply', [\App\Http\Controllers\Admin\ChatbotController::class, 'reply'])->name('chatbot.reply');

        // Offers Management
        Route::get('/offers', [\App\Http\Controllers\Admin\OfferController::class, 'index'])->name('offers.index');
        Route::post('/offers', [\App\Http\Controllers\Admin\OfferController::class, 'store'])->name('offers.store');
        Route::put('/offers/{offer}', [\App\Http\Controllers\Admin\OfferController::class, 'update'])->name('offers.update');
        Route::delete('/offers/{offer}', [\App\Http\Controllers\Admin\OfferController::class, 'destroy'])->name('offers.destroy');

        // Banner management
        Route::get('/banners', [AdminBannerController::class, 'index'])->name('banners');
        Route::post('/banners', [AdminBannerController::class, 'store'])->name('banners.store');
        Route::put('/banners/{banner}', [AdminBannerController::class, 'update'])->name('banners.update');
        Route::delete('/banners/{banner}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');

        // Promo Banner management
        Route::get('/promo-banners', [AdminPromoBannerController::class, 'index'])->name('promo-banners');
        Route::post('/promo-banners', [AdminPromoBannerController::class, 'store'])->name('promo-banners.store');
        Route::put('/promo-banners/{promoBanner}', [AdminPromoBannerController::class, 'update'])->name('promo-banners.update');
        Route::delete('/promo-banners/{promoBanner}', [AdminPromoBannerController::class, 'destroy'])->name('promo-banners.destroy');

        // Commission management
        Route::get('/commissions', [AdminCommissionController::class, 'index'])->name('commissions');
        Route::post('/commissions', [AdminCommissionController::class, 'store'])->name('commissions.store');
        Route::put('/commissions/{commission}', [AdminCommissionController::class, 'update'])->name('commissions.update');
        Route::delete('/commissions/{commission}', [AdminCommissionController::class, 'destroy'])->name('commissions.destroy');
        Route::put('/commissions/cod/update', [AdminCommissionController::class, 'updateCodCommission'])->name('commissions.cod.update');

        // Violation management
        Route::get('/violations', [\App\Http\Controllers\Admin\ViolationController::class, 'index'])->name('violations.index');
        Route::get('/violations/create', [\App\Http\Controllers\Admin\ViolationController::class, 'create'])->name('violations.create');
        Route::post('/violations', [\App\Http\Controllers\Admin\ViolationController::class, 'store'])->name('violations.store');
        Route::post('/violations/{violation}/approve', [\App\Http\Controllers\Admin\ViolationController::class, 'approve'])->name('violations.approve');
        Route::post('/violations/{violation}/waive', [\App\Http\Controllers\Admin\ViolationController::class, 'waive'])->name('violations.waive');
        
        // Violation rules
        Route::get('/violations/rules', [\App\Http\Controllers\Admin\ViolationController::class, 'rules'])->name('violations.rules');
        Route::post('/violations/rules', [\App\Http\Controllers\Admin\ViolationController::class, 'storeRule'])->name('violations.rules.store');
        Route::put('/violations/rules/{rule}', [\App\Http\Controllers\Admin\ViolationController::class, 'updateRule'])->name('violations.rules.update');
        Route::delete('/violations/rules/{rule}', [\App\Http\Controllers\Admin\ViolationController::class, 'destroyRule'])->name('violations.rules.destroy');

        // Commission invoices
        Route::get('/invoices', [\App\Http\Controllers\Admin\CommissionInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [\App\Http\Controllers\Admin\CommissionInvoiceController::class, 'show'])->name('invoices.show');
        Route::post('/invoices/generate/{order}', [\App\Http\Controllers\Admin\CommissionInvoiceController::class, 'generate'])->name('invoices.generate');
        Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\Admin\CommissionInvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
        Route::post('/invoices/{invoice}/mark-paid', [\App\Http\Controllers\Admin\CommissionInvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');

        // Delivery Management (Paperfly)
        Route::get('/delivery', [\App\Http\Controllers\Admin\DeliveryController::class, 'index'])->name('delivery.index');
        Route::post('/delivery/{order}/create', [\App\Http\Controllers\Admin\DeliveryController::class, 'createDelivery'])->name('delivery.create');
        Route::post('/delivery/{order}/track', [\App\Http\Controllers\Admin\DeliveryController::class, 'trackDelivery'])->name('delivery.track');
        Route::post('/delivery/bulk-track', [\App\Http\Controllers\Admin\DeliveryController::class, 'bulkTrack'])->name('delivery.bulk-track');
        Route::delete('/delivery/{order}/cancel', [\App\Http\Controllers\Admin\DeliveryController::class, 'cancelDelivery'])->name('delivery.cancel');

        // Manual Payment Verification
        Route::get('/manual-payments', [ManualPaymentController::class, 'index'])->name('manual-payments.index');
        Route::get('/manual-payments/{manualPayment}', [ManualPaymentController::class, 'show'])->name('manual-payments.show');
        Route::post('/manual-payments/{manualPayment}/verify', [ManualPaymentController::class, 'verify'])->name('manual-payments.verify');
        Route::post('/manual-payments/{manualPayment}/reject', [ManualPaymentController::class, 'reject'])->name('manual-payments.reject');

        // Cash on Delivery Orders
        Route::get('/cod-orders', [\App\Http\Controllers\Admin\CodOrderController::class, 'index'])->name('cod-orders.index');
        Route::post('/cod-orders/{order}/mark-paid', [\App\Http\Controllers\Admin\CodOrderController::class, 'markPaid'])->name('cod-orders.mark-paid');
        Route::post('/cod-orders/{order}/update-status', [\App\Http\Controllers\Admin\CodOrderController::class, 'updateStatus'])->name('cod-orders.update-status');

        // Verification Management
        Route::get('/verification', [\App\Http\Controllers\Admin\VerificationManagementController::class, 'index'])->name('verification.index');
        Route::get('/verification/{user}', [\App\Http\Controllers\Admin\VerificationManagementController::class, 'show'])->name('verification.show');
        Route::post('/verification/{user}/approve', [\App\Http\Controllers\Admin\VerificationManagementController::class, 'approve'])->name('verification.approve');
        Route::post('/verification/{user}/reject', [\App\Http\Controllers\Admin\VerificationManagementController::class, 'reject'])->name('verification.reject');
        Route::post('/verification/{user}/reset', [\App\Http\Controllers\Admin\VerificationManagementController::class, 'reset'])->name('verification.reset');

        // Payment Settings
        Route::get('/payment-settings', [PaymentSettingsController::class, 'index'])->name('payment-settings.index');
        Route::post('/payment-settings', [PaymentSettingsController::class, 'update'])->name('payment-settings.update');

        // OTP & API Settings
        Route::get('/otp-settings', [App\Http\Controllers\Admin\OtpSettingsController::class, 'index'])->name('otp-settings.index');
        Route::post('/otp-settings', [App\Http\Controllers\Admin\OtpSettingsController::class, 'update'])->name('otp-settings.update');
        Route::post('/otp-settings/test', [App\Http\Controllers\Admin\OtpSettingsController::class, 'testApi'])->name('otp-settings.test');

        // Attribute management
        Route::resource('attributes', AttributeController::class);

        // Review management
        Route::get('/reviews/stats', [App\Http\Controllers\Admin\ReviewController::class, 'stats'])->name('reviews.stats');
        Route::post('/reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{review}/reject', [App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
        Route::post('/reviews/{review}/report', [App\Http\Controllers\Admin\ReviewController::class, 'report'])->name('reviews.report');
        Route::post('/reviews/{review}/respond', [App\Http\Controllers\Admin\ReviewController::class, 'respond'])->name('reviews.respond');
        Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class);

        // Direct order creation
        Route::post('/direct-order/{product}', [App\Http\Controllers\Admin\ReviewController::class, 'createDirectOrder'])->name('direct-order');

        // OTP management
        Route::get('/otp', [AdminOtpController::class, 'index'])->name('otp.index');
        Route::post('/otp/{id}/resend', [AdminOtpController::class, 'resend'])->name('otp.resend');

        // OTP/API settings
        Route::get('/settings/otp', [SettingController::class, 'edit'])->name('settings.otp.edit');
        Route::post('/settings/otp', [SettingController::class, 'update'])->name('settings.otp.update');

        // Support Tickets Management
        Route::get('/tickets', [App\Http\Controllers\Admin\TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [App\Http\Controllers\Admin\TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\Admin\TicketController::class, 'reply'])->name('tickets.reply');
        Route::patch('/tickets/{ticket}/status', [App\Http\Controllers\Admin\TicketController::class, 'updateStatus'])->name('tickets.update-status');
        Route::patch('/tickets/{ticket}/priority', [App\Http\Controllers\Admin\TicketController::class, 'updatePriority'])->name('tickets.update-priority');
        Route::patch('/tickets/{ticket}/assign', [App\Http\Controllers\Admin\TicketController::class, 'assign'])->name('tickets.assign');
        Route::delete('/tickets/{ticket}', [App\Http\Controllers\Admin\TicketController::class, 'destroy'])->name('tickets.destroy');

        // Advance Payments Management
        Route::get('/advance-payments', [App\Http\Controllers\Admin\AdvancePaymentController::class, 'index'])->name('advance-payments.index');
        Route::get('/advance-payments/{advancePayment}', [App\Http\Controllers\Admin\AdvancePaymentController::class, 'show'])->name('advance-payments.show');
        Route::patch('/advance-payments/{advancePayment}/status', [App\Http\Controllers\Admin\AdvancePaymentController::class, 'updateStatus'])->name('advance-payments.update-status');
        Route::delete('/advance-payments/{advancePayment}', [App\Http\Controllers\Admin\AdvancePaymentController::class, 'destroy'])->name('advance-payments.destroy');

        // Withdrawal Management
        Route::get('/withdrawals', [App\Http\Controllers\Admin\WithdrawalManagementController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/{withdrawal}', [App\Http\Controllers\Admin\WithdrawalManagementController::class, 'show'])->name('withdrawals.show');
        Route::post('/withdrawals/{withdrawal}/approve', [App\Http\Controllers\Admin\WithdrawalManagementController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/complete', [App\Http\Controllers\Admin\WithdrawalManagementController::class, 'complete'])->name('withdrawals.complete');
        Route::post('/withdrawals/{withdrawal}/reject', [App\Http\Controllers\Admin\WithdrawalManagementController::class, 'reject'])->name('withdrawals.reject');
        Route::post('/withdrawals/bulk-action', [App\Http\Controllers\Admin\WithdrawalManagementController::class, 'bulkAction'])->name('withdrawals.bulk-action');
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
        Route::get('/orders/{order}/invoice', [App\Http\Controllers\OrderController::class, 'invoice'])->name('orders.invoice');
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

        // Attribute management
        Route::get('/attributes', [App\Http\Controllers\Wholesaler\AttributeController::class, 'index'])->name('attributes.index');
        Route::post('/attributes', [App\Http\Controllers\Wholesaler\AttributeController::class, 'store'])->name('attributes.store');
        Route::put('/attributes/{id}', [App\Http\Controllers\Wholesaler\AttributeController::class, 'update'])->name('attributes.update');
        Route::delete('/attributes/{id}', [App\Http\Controllers\Wholesaler\AttributeController::class, 'destroy'])->name('attributes.destroy');
    });

    // Exporter routes
    Route::middleware('role:exporter')->prefix('exporter')->name('exporter.')->group(function () {
        Route::get('/dashboard', [ExporterDashboardController::class, 'index'])->name('dashboard');

        // Orders
        Route::get('/orders', [ExporterDashboardController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [ExporterDashboardController::class, 'showOrder'])->name('orders.show');
        Route::get('/orders/{order}/invoice', [App\Http\Controllers\OrderController::class, 'invoice'])->name('orders.invoice');

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

        // Attribute management
        Route::get('/attributes', [App\Http\Controllers\Exporter\AttributeController::class, 'index'])->name('attributes.index');
        Route::post('/attributes', [App\Http\Controllers\Exporter\AttributeController::class, 'store'])->name('attributes.store');
        Route::put('/attributes/{id}', [App\Http\Controllers\Exporter\AttributeController::class, 'update'])->name('attributes.update');
        Route::delete('/attributes/{id}', [App\Http\Controllers\Exporter\AttributeController::class, 'destroy'])->name('attributes.destroy');
    });

    // Importer routes - mirror exporter functionality where applicable
    Route::middleware('role:importer')->prefix('importer')->name('importer.')->group(function () {
        // Dashboard (reuses exporter controller logic for shared metrics)
        Route::get('/dashboard', [ExporterDashboardController::class, 'index'])->name('dashboard');

        // Orders (importer-facing)
        Route::get('/orders', [ExporterDashboardController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [ExporterDashboardController::class, 'showOrder'])->name('orders.show');
        Route::get('/orders/{order}/invoice', [App\Http\Controllers\OrderController::class, 'invoice'])->name('orders.invoice');

        // Certifications (importer can view/manage where applicable)
        Route::get('/certifications', [ExporterCertificationController::class, 'index'])->name('certifications');
        Route::post('/certifications', [ExporterCertificationController::class, 'store'])->name('certifications.store');
        Route::post('/certifications/bulk-update', [ExporterCertificationController::class, 'bulkUpdate'])->name('certifications.bulk-update');
        Route::put('/certifications/{certification}', [ExporterCertificationController::class, 'update'])->name('certifications.update');
        Route::delete('/certifications/{certification}', [ExporterCertificationController::class, 'destroy'])->name('certifications.destroy');

        // Products / Brands / Categories (reuse exporter controllers for now)
        Route::get('/brands', [ExporterBrandController::class, 'index'])->name('brands');
        Route::post('/brands', [ExporterBrandController::class, 'store'])->name('brands.store');
        Route::put('/brands/{brand}', [ExporterBrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [ExporterBrandController::class, 'destroy'])->name('brands.destroy');

        Route::get('/categories', [ExporterCategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [ExporterCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [ExporterCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [ExporterCategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/products', [ExporterProductController::class, 'index'])->name('products');
        Route::post('/products', [ExporterProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ExporterProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ExporterProductController::class, 'destroy'])->name('products.destroy');
    });

    // Employee routes (moderator role)
    Route::middleware('role:employee')->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
        
        // Product management
        Route::get('/products', [EmployeeDashboardController::class, 'products'])->name('products');
        
        // Order management
        Route::get('/orders', [EmployeeDashboardController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [EmployeeDashboardController::class, 'showOrder'])->name('orders.show');
        Route::patch('/orders/{order}/status', [EmployeeDashboardController::class, 'updateOrderStatus'])->name('orders.update-status');
        
        // Application management
        Route::get('/applications', [EmployeeDashboardController::class, 'applications'])->name('applications');
        Route::get('/applications/{application}', [EmployeeDashboardController::class, 'showApplication'])->name('applications.show');
        Route::post('/applications/{application}/approve', [EmployeeDashboardController::class, 'approveApplication'])->name('applications.approve');
        Route::post('/applications/{application}/reject', [EmployeeDashboardController::class, 'rejectApplication'])->name('applications.reject');
        
        // User management
        Route::get('/users', [EmployeeDashboardController::class, 'users'])->name('users');
        Route::patch('/users/{user}/status', [EmployeeDashboardController::class, 'updateUserStatus'])->name('users.update-status');
    });

    // User routes
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/track-order', [UserDashboardController::class, 'index'])->name('track.order');
    });

    // User Address Management (available for all authenticated users)
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::post('/', [App\Http\Controllers\UserAddressController::class, 'store'])->name('store');
        Route::put('/{address}', [App\Http\Controllers\UserAddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [App\Http\Controllers\UserAddressController::class, 'destroy'])->name('destroy');
        Route::post('/{address}/set-default', [App\Http\Controllers\UserAddressController::class, 'setDefault'])->name('set-default');
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
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    // Notification routes
    Route::get('/notifications/page', function() {
        return view('notifications.index');
    })->name('notifications.page');

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::post('/', [NotificationController::class, 'store'])->name('store');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/read/all', [NotificationController::class, 'deleteAllRead'])->name('delete-all-read');
    });

    // Chat routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/conversations', [ChatController::class, 'index'])->name('conversations');
        Route::post('/conversations', [ChatController::class, 'storeConversation'])->name('conversations.store');
        Route::get('/conversations/{conversationId}/messages', [ChatController::class, 'getMessages'])->name('messages');
        Route::post('/conversations/{conversationId}/messages', [ChatController::class, 'sendMessage'])->name('send-message');
        Route::patch('/conversations/{conversationId}/status', [ChatController::class, 'updateStatus'])->name('update-status');
        Route::get('/unread-count', [ChatController::class, 'unreadCount'])->name('unread-count');
    });

    // SMS & OTP routes
    Route::prefix('sms')->name('sms.')->group(function () {
        Route::get('/logs', [SmsController::class, 'logs'])->name('logs');
        Route::post('/send', [SmsController::class, 'send'])->name('send');
        Route::get('/otp/logs', [SmsController::class, 'otpLogs'])->name('otp.logs');
        Route::post('/otp/generate', [SmsController::class, 'generateOtp'])->name('otp.generate');
        Route::post('/otp/verify', [SmsController::class, 'verifyOtp'])->name('otp.verify');
        Route::get('/templates', [SmsController::class, 'templates'])->name('templates');
        Route::post('/templates', [SmsController::class, 'storeTemplate'])->name('templates.store');
        Route::put('/templates/{id}', [SmsController::class, 'updateTemplate'])->name('templates.update');
        Route::delete('/templates/{id}', [SmsController::class, 'deleteTemplate'])->name('templates.delete');
        Route::get('/stats', [SmsController::class, 'stats'])->name('stats');
    });

    // Support Tickets routes
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{id}', [TicketController::class, 'show'])->name('show');
        Route::post('/{id}/messages', [TicketController::class, 'addMessage'])->name('add-message');
        Route::patch('/{id}/status', [TicketController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{id}/assign', [TicketController::class, 'assign'])->name('assign');
        Route::post('/{id}/rate', [TicketController::class, 'rate'])->name('rate');
        Route::get('/categories/list', [TicketController::class, 'categories'])->name('categories');
        Route::get('/stats/overview', [TicketController::class, 'stats'])->name('stats');
    });

    // Order routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my-orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // Customer Return & Refund routes
    Route::prefix('returns')->name('customer.returns.')->group(function () {
        Route::get('/', [App\Http\Controllers\Customer\ReturnController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Customer\ReturnController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Customer\ReturnController::class, 'store'])->name('store');
        Route::get('/{return}', [App\Http\Controllers\Customer\ReturnController::class, 'show'])->name('show');
        Route::patch('/{return}/cancel', [App\Http\Controllers\Customer\ReturnController::class, 'cancel'])->name('cancel');
    });

    // Vendor order routes
    Route::middleware('role:retailer,wholesaler,exporter')->group(function () {
        Route::get('/vendor/orders', [OrderController::class, 'vendorOrders'])->name('vendor.orders');
        Route::patch('/vendor/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('vendor.orders.update-status');
        
        // Vendor Return Management (Also accessible by employees and admin)
        Route::prefix('vendor/returns')->name('vendor.returns.')->group(function () {
            Route::get('/', [App\Http\Controllers\Vendor\VendorReturnController::class, 'index'])->name('index');
            Route::get('/{return}', [App\Http\Controllers\Vendor\VendorReturnController::class, 'show'])->name('show');
            Route::post('/{return}/status', [App\Http\Controllers\Vendor\VendorReturnController::class, 'updateStatus'])->name('update-status');
            Route::post('/{return}/note', [App\Http\Controllers\Vendor\VendorReturnController::class, 'addNote'])->name('add-note');
            Route::post('/bulk-action', [App\Http\Controllers\Vendor\VendorReturnController::class, 'bulkAction'])->name('bulk-action');
        });

        // Vendor Review Management
        Route::prefix('vendor/reviews')->name('vendor.reviews.')->group(function () {
            Route::get('/', [App\Http\Controllers\Vendor\ReviewController::class, 'index'])->name('index');
            Route::get('/{review}', [App\Http\Controllers\Vendor\ReviewController::class, 'show'])->name('show');
            Route::post('/{review}/reply', [App\Http\Controllers\Vendor\ReviewController::class, 'reply'])->name('reply');
        });

        // Vendor Reports
        Route::prefix('vendor/reports')->name('vendor.reports.')->group(function () {
            Route::get('/', [App\Http\Controllers\Vendor\ReportController::class, 'index'])->name('index');
            Route::get('/product-sales', [App\Http\Controllers\Vendor\ReportController::class, 'productSales'])->name('product-sales');
            Route::get('/product-wishlist', [App\Http\Controllers\Vendor\ReportController::class, 'productWishlist'])->name('product-wishlist');
            Route::get('/product-stock', [App\Http\Controllers\Vendor\ReportController::class, 'productStock'])->name('product-stock');
            Route::get('/commission-history', [App\Http\Controllers\Vendor\ReportController::class, 'commissionHistory'])->name('commission-history');
            Route::get('/export/{type}', [App\Http\Controllers\Vendor\ReportController::class, 'export'])->name('export');
        });

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

    // Support Tickets (Available for all authenticated users)
    Route::prefix('vendor/tickets')->name('vendor.tickets.')->group(function () {
        Route::get('/', [App\Http\Controllers\Vendor\TicketController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Vendor\TicketController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Vendor\TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [App\Http\Controllers\Vendor\TicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [App\Http\Controllers\Vendor\TicketController::class, 'reply'])->name('reply');
        Route::patch('/{ticket}/close', [App\Http\Controllers\Vendor\TicketController::class, 'close'])->name('close');
    });

    Route::post('/otp/send', [OtpController::class, 'send']);
    Route::post('/otp/verify', [OtpController::class, 'verify']);
    Route::post('/otp/resend', [OtpController::class, 'resend']);
    Route::get('/otp/status', [OtpController::class, 'status']);
});
