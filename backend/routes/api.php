<?php

use App\Http\Controllers\Api\CatalogProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::get('/', [CatalogProductController::class, 'index']);
    Route::get('/{slug}', [CatalogProductController::class, 'show']);
});

