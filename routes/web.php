<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Upload
    Route::post('/upload', [UploadController::class, 'store'])->name('upload');

    // Resources
    // We will handle role-based authorization via Policies or Controller checks (or Middleware here if needed)
    Route::resource('sales', SaleController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('passports', PassportController::class);
    Route::patch('/passports/{passport}/status', [PassportController::class, 'updateStatus'])->name('passports.updateStatus');

});
