<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RetailController;
use App\Http\Controllers\WholesaleController;
use App\Http\Controllers\ExportController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/retail', [RetailController::class, 'index'])->name('retail');
Route::get('/wholesale', [WholesaleController::class, 'index'])->name('wholesale');
Route::get('/export', [ExportController::class, 'index'])->name('export');
