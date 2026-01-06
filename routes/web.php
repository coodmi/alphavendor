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
use App\Http\Controllers\Admin\RetailPageController as AdminRetailPageController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/retail', [RetailController::class, 'index'])->name('retail');
Route::get('/wholesale', [WholesaleController::class, 'index'])->name('wholesale');
Route::get('/export', [ExportController::class, 'index'])->name('export');

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

        // Retail Page CMS
        Route::get('/retail-page', [AdminRetailPageController::class, 'index'])->name('retail-page');
        Route::post('/retail-page', [AdminRetailPageController::class, 'update'])->name('retail-page.update');
    });

    // Retailer routes
    Route::middleware('role:retailer')->prefix('retailer')->name('retailer.')->group(function () {
        Route::get('/dashboard', [RetailerDashboardController::class, 'index'])->name('dashboard');
    });

    // Wholesaler routes
    Route::middleware('role:wholesaler')->prefix('wholesaler')->name('wholesaler.')->group(function () {
        Route::get('/dashboard', [WholesalerDashboardController::class, 'index'])->name('dashboard');
    });

    // Exporter routes
    Route::middleware('role:exporter')->prefix('exporter')->name('exporter.')->group(function () {
        Route::get('/dashboard', [ExporterDashboardController::class, 'index'])->name('dashboard');
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
});
