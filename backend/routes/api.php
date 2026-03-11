<?php

use App\Http\Controllers\Api\CatalogProductController;
use App\Http\Controllers\Api\AdminProductController;
use App\Http\Controllers\Api\AdminPromoCodeController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::get('/', [CatalogProductController::class, 'index']);
    Route::get('/{slug}', [CatalogProductController::class, 'show']);
});

Route::get('/checkout/options', [OrderController::class, 'options']);

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{orderNumber}', [OrderController::class, 'show']);
});

Route::prefix('admin')->middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/references', [AdminProductController::class, 'references']);

    Route::prefix('products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index']);
        Route::post('/', [AdminProductController::class, 'store']);
        Route::put('/{product}', [AdminProductController::class, 'update']);
        Route::delete('/{product}', [AdminProductController::class, 'destroy']);
        Route::delete('/{product}/images/{image}', [AdminProductController::class, 'destroyImage']);
    });

    Route::prefix('promo-codes')->group(function () {
        Route::get('/', [AdminPromoCodeController::class, 'index']);
        Route::post('/', [AdminPromoCodeController::class, 'store']);
        Route::put('/{promoCode}', [AdminPromoCodeController::class, 'update']);
        Route::delete('/{promoCode}', [AdminPromoCodeController::class, 'destroy']);
    });
});
