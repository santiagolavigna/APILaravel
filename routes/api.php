<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'index');
    // Route::post('todo', 'store');
    // Route::get('todo/{id}', 'show');
    // Route::put('todo/{id}', 'update');
    // Route::delete('todo/{id}', 'destroy');
}); 

Route::controller(CategoryController::class)->group(function () {
    Route::get('categories/list', 'index');
    // Route::post('todo', 'store');
     Route::get('categories/{id}', 'show');
     Route::put('category/edit/{id}', 'update');
     Route::get('categories/quantity/all', 'quantity');
     Route::delete('categories/{id}', 'destroy');
}); 