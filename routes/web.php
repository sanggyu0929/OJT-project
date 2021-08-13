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
Route::get('contact-us', [ContactUsController::class, 'index'])->name('contact-us');
Route::get('about-us', [AboutUsController::class, 'index'])->name('about-us');
Route::get('category', [HomeController::class, 'goCategory'])->name('category');
Route::get('category/Register', [HomeController::class, 'goCaRegister'])->name('category.register');
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::get('sign-up', [SignUpController::class, 'index'])->name('sign-up');
Route::get('logout', [HomeController::class, 'logout'])->name('logout');

Route::post('login/post', [LoginController::class, 'loginChk'])->name('login-chk');
Route::post('sign-up/post', [SignUpController::class, 'signUp'])->name('sign-up-chk');
Route::post('sign-up/emailChk', [SignUpController::class, 'emailChk'])->name('email-chk');
Route::post('category/Register', [HomeController::class, 'caRegister'])->name('category.post');



