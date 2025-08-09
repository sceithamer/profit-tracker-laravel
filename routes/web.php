<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StorageUnitController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportsController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('storage-units', StorageUnitController::class);
Route::resource('sales', SaleController::class);
Route::resource('platforms', PlatformController::class);
Route::resource('categories', CategoryController::class);

Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
