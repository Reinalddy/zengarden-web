<?php

use App\Http\Controllers\UcpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UcpController::class, 'login_index']);

// REGITSTER UCP
Route::get('/register',[UcpController::class, 'register_index']);
Route::post('/register', [UcpController::class, 'register']);
