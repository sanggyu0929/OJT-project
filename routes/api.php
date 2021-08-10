<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('admin', [HomeController::class, 'postAPI']);
