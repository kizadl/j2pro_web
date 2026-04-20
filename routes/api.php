<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\V1\TvController;
use App\Http\Controllers\Api\V1\Auth\AdminAuthController;
use App\Http\Controllers\Api\V1\HomeController;


Route::prefix('v1')->group(function () {
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/home/menus', [HomeController::class, 'menus']);

    Route::prefix('tv')->group(function () {
        Route::get('/channels', [TvController::class, 'channels']);
        Route::get('/schedules', [TvController::class, 'schedules']);
        Route::get('/overview', [TvController::class, 'overview']);
        Route::get('/channels/{id}', [TvController::class, 'showChannel']);
        Route::get('/channels/{id}/today-schedules', [TvController::class, 'todaySchedules']);
    });

    Route::prefix('admin')->group(function () {
        Route::post('/login', [AdminAuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AdminAuthController::class, 'me']);
            Route::post('/logout', [AdminAuthController::class, 'logout']);
        });
    });
});