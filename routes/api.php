<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\HouseOwnerController;
use App\Http\Controllers\RenterController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\FlatController;
use App\Http\Controllers\BillCategoryController;
use App\Http\Controllers\BillController;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::group(['middleware' => ['auth:api', 'idempotency']], function () {
    // Admin Routes
    Route::apiResource('tenants', TenantController::class);
    Route::post('house-owners', [HouseOwnerController::class, 'store']);
    Route::apiResource('renters', RenterController::class)->except(['update', 'destroy']); // Add other methods if needed
    Route::post('assign-renter', [RenterController::class, 'assign']);

    // House Owner Routes
    Route::apiResource('buildings', BuildingController::class);
    Route::apiResource('flats', FlatController::class);
    Route::apiResource('bill-categories', BillCategoryController::class);
    Route::apiResource('bills', BillController::class);
});

