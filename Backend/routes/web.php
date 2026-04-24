<?php

use App\Http\Controllers\GiaoDichController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Only return URL in web.php if needed for browser redirects
Route::get('/payment/sepay-return', [GiaoDichController::class, 'handleSePayReturn']);