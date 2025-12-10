<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::group(['middleware' => ['auth:api', 'idempotency']], function () {
    // Admin Routes
    Route::apiResource('tenants', \App\Http\Controllers\TenantController::class);
    Route::post('house-owners', [\App\Http\Controllers\HouseOwnerController::class, 'store']);
    Route::apiResource('renters', \App\Http\Controllers\RenterController::class)->except(['update', 'destroy']); // Add other methods if needed
    Route::post('assign-renter', [\App\Http\Controllers\RenterController::class, 'assign']);

    // House Owner Routes
    Route::apiResource('buildings', \App\Http\Controllers\BuildingController::class);
    Route::apiResource('flats', \App\Http\Controllers\FlatController::class);
    Route::apiResource('bill-categories', \App\Http\Controllers\BillCategoryController::class);
    Route::apiResource('bills', \App\Http\Controllers\BillController::class);
});

