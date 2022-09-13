<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', 'loginPage')->name('login');
        Route::post('/login', 'login');
    });

    Route::get('/logout', 'logout')->middleware('auth');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('index', [
            'name' => "dashboard",
            "menu" => "",
        ]);
    });
    Route::controller(ProductCategoryController::class)->group(function () {
        Route::prefix('/product-categories')->group(function () {
            Route::get('/', 'data');
            Route::get('/create', 'create');
            Route::post('/store', 'store');
            Route::get('/{product_category:id}/edit', 'edit');
            Route::put('/{product_category:id}/update', 'update');
            Route::delete('/{product_category:id}/delete', 'delete');
        });
    });

    Route::controller(ProductController::class)->group(function () {
        Route::prefix('/products')->group(function () {
            Route::get('/', 'data');
            Route::get('/create', 'create');
            Route::post('/store', 'store');
            Route::get('/{product:id}/edit', 'edit');
            Route::put('/{product:id}/update', 'update');
            Route::delete('/{product:id}/delete', 'delete');
        });
    });

    Route::controller(VoucherController::class)->group(function () {
        Route::prefix('/vouchers')->group(function () {
            Route::get('/', 'data');
            Route::get('/create', 'create');
            Route::post('/store', 'store');
            Route::get('/{voucher:id}/edit', 'edit');
            Route::put('/{voucher:id}/update', 'update');
            Route::delete('/{voucher:id}/delete', 'delete');
        });
    });

    Route::controller(TransactionController::class)->group(function () {
        Route::prefix('/transactions')->group(function () {
            Route::get('/', 'data');
            Route::get('/create', 'create');
            Route::post('/store', 'store');
            Route::get('/{transaction:id}/edit', 'edit');
            Route::put('/{transaction:id}/update', 'update');
            Route::delete('/{transaction:id}/delete', 'delete');
        });
    });
});
