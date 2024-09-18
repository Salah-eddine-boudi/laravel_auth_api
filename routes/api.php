<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
    
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');  // Utiliser GET pour 'profile'
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
});