<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StorageUnitController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PlatformController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('storage-units', StorageUnitController::class);
Route::resource('sales', SaleController::class);
Route::resource('platforms', PlatformController::class);
