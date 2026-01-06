const fs = require("fs");

// Read the file
let content = fs.readFileSync("routes/web.php", "utf8");

// Add the import statements
const importSearch = `use App\Http\Controllers\RetailerProductController;
use App\Http\Controllers\Admin\RetailPageController as AdminRetailPageController;`;

const importReplace = `use App\Http\Controllers\RetailerProductController;
use App\Http\Controllers\Wholesaler\WholesalerBrandController;
use App\Http\Controllers\Wholesaler\WholesalerCategoryController;
use App\Http\Controllers\Wholesaler\WholesalerProductController;
use App\Http\Controllers\Admin\RetailPageController as AdminRetailPageController;`;

content = content.replace(importSearch, importReplace);

// Replace the wholesaler routes section
const routesSearch = `    // Wholesaler routes
    Route::middleware('role:wholesaler')->prefix('wholesaler')->name('wholesaler.')->group(function () {
        Route::get('/dashboard', [WholesalerDashboardController::class, 'index'])->name('dashboard');
    });`;

const routesReplace = `    // Wholesaler routes
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
    });`;

content = content.replace(routesSearch, routesReplace);

// Write back
fs.writeFileSync("routes/web.php", content, "utf8");

console.log("Successfully updated routes");
