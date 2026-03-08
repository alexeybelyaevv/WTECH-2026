<?php

use App\Http\Controllers\Api\CatalogProductController;
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
