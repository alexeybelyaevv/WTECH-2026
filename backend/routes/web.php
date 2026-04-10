<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/index.html');

Route::prefix('api/auth')
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

Route::get('/store_data.js', [FrontendController::class, 'asset'])
    ->defaults('asset', 'store_data.js');

Route::get('/auth_ui.js', [FrontendController::class, 'asset'])
    ->defaults('asset', 'auth_ui.js');

Route::get('/styles.css', [FrontendController::class, 'asset'])
    ->defaults('asset', 'styles.css');

Route::get('/{page}.html', [FrontendController::class, 'page'])
    ->where('page', implode('|', FrontendController::PAGES));

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
