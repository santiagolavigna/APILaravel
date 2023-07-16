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
    Route::get('products/list', 'index');
    Route::get('productsBO/list', 'indexBO');
    Route::get('products/category/{id}', 'indexCategory');
    Route::get('products/quantity/all', 'quantity');
    Route::get('products/find', 'find');
    Route::get('product/{id}', 'show');
    Route::post('product/update/{id}', 'update');

}); 

Route::controller(CategoryController::class)->group(function () {
     Route::get('categories/list', 'index');
     Route::get('categories/{id}', 'show');
     Route::put('category/edit/{id}', 'update');
     Route::get('categories/quantity/all', 'quantity');
     Route::delete('categories/{id}', 'destroy');
}); 