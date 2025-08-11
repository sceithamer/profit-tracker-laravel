<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StorageUnitController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard (authenticated users) or login (guests)
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Protected application routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Storage Units with permission protection
    Route::get('/storage-units', [StorageUnitController::class, 'index'])->name('storage-units.index');
    Route::get('/storage-units/create', [StorageUnitController::class, 'create'])->name('storage-units.create')->middleware('permission:create_storage_units');
    Route::post('/storage-units', [StorageUnitController::class, 'store'])->name('storage-units.store')->middleware('permission:create_storage_units');
    Route::get('/storage-units/{storage_unit}', [StorageUnitController::class, 'show'])->name('storage-units.show');
    Route::get('/storage-units/{storage_unit}/edit', [StorageUnitController::class, 'edit'])->name('storage-units.edit')->middleware('permission:edit_storage_units');
    Route::put('/storage-units/{storage_unit}', [StorageUnitController::class, 'update'])->name('storage-units.update')->middleware('permission:edit_storage_units');
    Route::patch('/storage-units/{storage_unit}', [StorageUnitController::class, 'update'])->name('storage-units.update')->middleware('permission:edit_storage_units');
    Route::delete('/storage-units/{storage_unit}', [StorageUnitController::class, 'destroy'])->name('storage-units.destroy')->middleware('permission:delete_storage_units');
    
    // Sales with permission protection
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create')->middleware('permission:create_sales');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store')->middleware('permission:create_sales');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit')->middleware('permission:edit_sales');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update')->middleware('permission:edit_sales');
    Route::patch('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update')->middleware('permission:edit_sales');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy')->middleware('permission:delete_sales');
    
    // Platforms with permission protection
    Route::get('/platforms', [PlatformController::class, 'index'])->name('platforms.index');
    Route::get('/platforms/create', [PlatformController::class, 'create'])->name('platforms.create')->middleware('permission:create_platforms');
    Route::post('/platforms', [PlatformController::class, 'store'])->name('platforms.store')->middleware('permission:create_platforms');
    Route::get('/platforms/{platform}', [PlatformController::class, 'show'])->name('platforms.show');
    Route::get('/platforms/{platform}/edit', [PlatformController::class, 'edit'])->name('platforms.edit')->middleware('permission:edit_platforms');
    Route::put('/platforms/{platform}', [PlatformController::class, 'update'])->name('platforms.update')->middleware('permission:edit_platforms');
    Route::patch('/platforms/{platform}', [PlatformController::class, 'update'])->name('platforms.update')->middleware('permission:edit_platforms');
    Route::delete('/platforms/{platform}', [PlatformController::class, 'destroy'])->name('platforms.destroy')->middleware('permission:delete_platforms');
    
    // Categories with permission protection
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create')->middleware('permission:create_categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:create_categories');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:edit_categories');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:edit_categories');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:edit_categories');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:delete_categories');
    
    // User Management with permission protection
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create_users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create_users');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit_users');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit_users');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit_users');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete_users');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
});

// Profile management (from Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
