<?php

use App\Http\Controllers\UcpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UcpController::class, 'login_index'])->name('login');

// REGITSTER UCP
Route::get('/register',[UcpController::class, 'register_index']);
Route::post('/register', [UcpController::class, 'register']);

Route::post('/login', [UcpController::class, 'login']);

Route::middleware('auth')->group(function () {
  Route::prefix('/user')->group(function () {
    Route::prefix('/dashboard')->group(function () {
      Route::get('/', [UcpController::class, 'dashboard_index'])->name('dashboard.member');
      Route::get('/ucp', [UcpController::class, 'ucp_index'])->name('dashboard.ucp.member');
      Route::post('/create-ucp', [UcpController::class, 'createUcp'])->name('ucp.create');
    });

    Route::get('/logout', [UcpController::class, 'logout']);
  });
  
});
