<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignUpController;

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

// Route::get('/', function () {
//     return view('index');
// });

// Route::get('/', [HomeController::class, 'index'])->name('home');


// Route::get('product', [HomeController::class, 'goProduct'])->name('product');
// Route::get('product/Register', [HomeController::class, 'goProductRegister'])->name('product.register');
// Route::get('product/Edit/{Pidx}', [HomeController::class, 'goProductEdit'])->name('product.edit');


// Route::get('brand', [HomeController::class, 'goBrand'])->name('brand');
// Route::get('brand/Register', [HomeController::class, 'goBrandRegister'])->name('brand.register');
// Route::get('brand/Edit/{Bidx}', [HomeController::class, 'goBrandEdit'])->name('brand.edit');


// Route::get('category', [HomeController::class, 'goCategory'])->name('category');
// Route::get('category/Register', [HomeController::class, 'goCaRegister'])->name('category.register');
// Route::get('category/Edit/{Cidx}', [HomeController::class, 'goCaEdit'])->name('category.Edit');


// Route::get('login', [LoginController::class, 'index'])->name('login');
// Route::get('sign-up', [SignUpController::class, 'index'])->name('sign-up');
// Route::get('logout', [HomeController::class, 'logout'])->name('logout');

// Route::post('login/post', [LoginController::class, 'loginChk'])->name('login-chk');

// Route::post('sign-up/post', [SignUpController::class, 'signUp'])->name('sign-up-chk');
// Route::post('sign-up/emailChk', [SignUpController::class, 'emailChk'])->name('email-chk');

// Route::post('category/Register', [HomeController::class, 'caRegister'])->name('category.post');
// Route::post('category/Edit', [HomeController::class, 'caEdit']);

// Route::post('brand/Register', [HomeController::class, 'brandRegister']);
// Route::post('brand/Edit', [HomeController::class, 'brandEdit']);
// Route::post('brand/Delete', [HomeController::class, 'brandDelete']);

// Route::post('product/Register', [HomeController::class, 'productRegister'])->name('product.register');
// Route::post('product/Edit', [HomeController::class, 'productEdit'])->name('product.edit');
// Route::post('product/Delete', [HomeController::class, 'productDelete'])->name('product.delete');
// Route::post('product/search', [HomeController::class, 'productSearch'])->name('product.search');

Route::middleware(['guest'])->group(function() {
    Route::view('/', 'index')->name('home');
    Route::view('login', 'login')->name('login');
    Route::view('signup', 'sign-up')->name('sign-up');
    Route::post('login', [LoginController::class, 'loginPost']);
    Route::post('signup', [SignupController::class, 'signupPost']);
    Route::post('emailChk', [SignupController::class, 'emailPost']);
    // Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    // Route::view('/category', 'category')->name('category');
});

Route::prefix('category')->name('category.')->group(function() {
    
    Route::middleware(['auth'])->group(function() {
        Route::get('/', [HomeController::class, 'goCategory'])->name('index');
        Route::get('Register', [HomeController::class, 'goCaRegister'])->name('register');
        Route::get('Edit/{Cidx}', [HomeController::class, 'goCaEdit'])->name('Edit');
    });
});

Route::prefix('brand')->name('brand.')->group(function() {
    
    Route::middleware(['auth'])->group(function() {
        Route::get('/', [HomeController::class, 'goBrand'])->name('index');
        Route::get('Register', [HomeController::class, 'goBrandRegister'])->name('register');
        Route::get('Edit/{Bidx}', [HomeController::class, 'goBrandEdit'])->name('edit');
    });
});

Route::prefix('product')->name('product.')->group(function() {
    
    Route::middleware(['auth'])->group(function() {
        Route::get('/', [HomeController::class, 'goProduct'])->name('index');
        Route::get('Register', [HomeController::class, 'goProductRegister'])->name('register');
        Route::get('Edit/{Pidx}', [HomeController::class, 'goProductEdit'])->name('edit');
    });
});

Route::prefix('user')->name('user.')->group(function() {
    
    Route::middleware(['auth'])->group(function() {
        // Route::view('/', 'user.index')->name('home');
        Route::view('category', 'category')->name('category');
        Route::view('brand', 'brand')->name('brand');
        Route::view('product', 'product')->name('product');
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    });
});



