<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Users\LoginController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\MenuController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Services\UploadService;


Route::get('admin/users/login', [LoginController::class,'index'])->name('login');
Route::post('admin/users/login/store', [LoginController::class,'store']);

Route::middleware(['auth'])->group(function () {
    
    Route::prefix('admin')->group(function () {
        Route::get('/', [MainController::class, 'index'])->name('admin');
        Route::get('main', [MainController::class, 'index'])->name('admin.main');

        Route::prefix('menus')->group(function () {
            Route::get('add', [MenuController::class, 'create'])->name('admin.menus.create');
            Route::post('add', [MenuController::class, 'store'])->name('admin.menus.store');
            Route::get('list', [MenuController::class, 'index'])->name('admin.menus.index');
            Route::delete('destroy/{id}', [MenuController::class, 'destroy'])->name('admin.menus.destroy');
            Route::put('update/{id}', [MenuController::class, 'update'])->name('admin.menus.update');

        });
        Route::prefix('products')->group(function(){
            Route::get('add', [ProductController::class, 'create'])->name('admin.products.create');
            Route::post('add', [ProductController::class, 'store'])->name('admin.products.store');
            Route::get('list', [ProductController::class, 'index'])->name('admin.products.index');
            Route::get('edit/{product}', [ProductController::class, 'show'])->name('admin.products.edit');
            Route::post('edit/{product}', [ProductController::class, 'update'])->name('admin.products.update');
            Route::delete('destroy', [ProductController::class, 'destroy'])->name('admin.products.destroy');
        });

        Route::post('upload/services', [UploadController::class, 'store'])->name('admin.upload');
    });
});