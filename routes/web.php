<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierAccountController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Login Route
Route::get('/login', function () {
    return view('layouts.login');
})->name('login');

// Register Route (for registering users)
Route::post('register', [UserController::class, 'login'])->name('register');

// Logout Route
Route::post('logout', [UserController::class, 'logout'])->name('logout');

// Dashboard Route - Protected by the auth middleware
Route::get('dashboard', [UserController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('table', [UserController::class, 'table'])->name('table')->middleware('auth');
Route::resource('/products', ProductController::class)->middleware('auth');
Route::post('/updateProduct/{id}', [ProductController::class, 'updateProduct'])->name('updateProduct')->middleware('auth');
Route::get('/productData', [ProductController::class, 'productData'])->name('productData')->middleware('auth');
Route::get('/getSpecific/{id}', [ProductController::class, 'getSpecificProduct'])->name('product.getSpecific');

Route::resource('/suppliers', SupplierController::class)->middleware('auth');
Route::post('/updateSupplier/{id}', [SupplierController::class, 'updateSupplier'])->name('updateSupplier')->middleware('auth');
Route::get('/supplierData', [SupplierController::class, 'supplierData'])->name('supplierData')->middleware('auth');
Route::resource('/inventories', InventoryController::class)->middleware('auth');
Route::post('/updateinventory/{id}', [InventoryController::class, 'updateinventory'])->name('updateinventory')->middleware('auth');

Route::resource('/accounts', SupplierAccountController::class)->middleware('auth');
Route::post('/update-account/{id}', [SupplierAccountController::class,'update'])->name('update-account')->middleware('auth');
// routes/web.php
Route::get('/account/create/{supplierId}', [SupplierAccountController::class, 'create'])->name('account.create');

Route::resource('/categories', CategoryController::class)->middleware('auth');
Route::post('/update-product/{id}', [CategoryController::class, 'update'])->name('update-product')->middleware('auth');



Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');
