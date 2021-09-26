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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('product', [HomeController::class, 'goProduct'])->name('product');
Route::get('product/Register', [HomeController::class, 'goProductRegister'])->name('product.register');
Route::get('product/Edit/{Pidx}', [HomeController::class, 'goProductEdit'])->name('product.edit');
Route::get('brand', [HomeController::class, 'goBrand'])->name('brand');
Route::get('brand/Register', [HomeController::class, 'goBrandRegister'])->name('brand.register');
Route::get('brand/Edit/{Bidx}', [HomeController::class, 'goBrandEdit'])->name('brand.edit');
Route::get('category', [HomeController::class, 'goCategory'])->name('category');
Route::get('category/Register', [HomeController::class, 'goCaRegister'])->name('category.register');
Route::get('category/Edit/{Cidx}', [HomeController::class, 'goCaEdit'])->name('category.Edit');
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::get('sign-up', [SignUpController::class, 'index'])->name('sign-up');
Route::get('logout', [HomeController::class, 'logout'])->name('logout');

Route::post('login/post', [LoginController::class, 'loginChk'])->name('login-chk');
Route::post('sign-up/post', [SignUpController::class, 'signUp'])->name('sign-up-chk');
Route::post('sign-up/emailChk', [SignUpController::class, 'emailChk'])->name('email-chk');
Route::post('category/Register', [HomeController::class, 'caRegister'])->name('category.post');
Route::post('category/Edit', [HomeController::class, 'caEdit']);
Route::post('brand/Register', [HomeController::class, 'brandRegister']);
Route::post('brand/Edit', [HomeController::class, 'brandEdit']);
Route::post('brand/Delete', [HomeController::class, 'brandDelete']);
Route::post('product/Register', [HomeController::class, 'productRegister'])->name('product.register');
Route::post('product/Edit', [HomeController::class, 'productEdit'])->name('product.edit');
Route::post('product/Delete', [HomeController::class, 'productDelete'])->name('product.delete');
Route::post('product/search', [HomeController::class, 'productSearch'])->name('product.search');



