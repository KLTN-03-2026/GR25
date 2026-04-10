<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BatDongSanController;
use App\Http\Controllers\ClientHomeController;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MoiGioiController;
use App\Http\Controllers\YeuThichController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Sprint 1
// Bất Động Sản
Route::get('/bat-dong-san', [ClientHomeController::class, 'getAllPublic']);
// Xem chi tiết bất động sản
Route::get('/bat-dong-san/{id}', [ClientHomeController::class, 'xemChiTiet']);
// Tìm Kiếm 
Route::post('/tim-kiem', [ClientHomeController::class, 'search']);
//--------------------- AUTH + CHECK TOKEN-----------------------------

Route::prefix('admin')->group(function () { //xong
    Route::post('/dang-nhap', [AdminController::class, 'login']);
    Route::get('/dang-xuat', [AdminController::class, 'logout']);
    Route::get('/check-token', [AdminController::class, 'checkToken']);
    Route::post('/forgot-password/send-otp', [AdminController::class, 'sendOtp']);
    Route::post('/forgot-password/reset', [AdminController::class, 'resetPassword']);
});

Route::prefix('khach-hang')->group(function () { //xong
    Route::post('/dang-nhap', [KhachHangController::class, 'login']);
    Route::post('/register', [KhachHangController::class, 'register']);
    Route::get('/check-token', [KhachHangController::class, 'checkToken']);
    Route::post('/forgot-password/send-otp', [KhachHangController::class, 'sendOtp']);
    Route::post('/forgot-password/reset', [KhachHangController::class, 'resetPassword']);
});

Route::prefix('moi-gioi')->group(function () { //xong
    Route::post('/dang-nhap', [MoiGioiController::class, 'login']);
    Route::post('/dang-ky', [MoiGioiController::class, 'register']);
    Route::get('/check-token', [MoiGioiController::class, 'checkToken']);
    Route::post('/forgot-password/send-otp', [MoiGioiController::class, 'sendOtp']);
    Route::post('/forgot-password/reset', [MoiGioiController::class, 'resetPassword']);
});

Route::prefix('admin')->middleware('')->group(function () {
    // LOGOUT ALL //xong
     Route::get('/dang-xuat-tat-ca', [AdminController::class, 'logoutAll']);

    //PROFILE ADMIN //xong
    Route::get('/profile', [AdminController::class, 'profile']); // đã test postman
    Route::post('/update-profile', [AdminController::class, 'updateProfile']); // đã test postman

    //QUẢN LÝ BẤT ĐỘNG SẢN (xong)
    Route::prefix('bds')->group(function () {
        Route::post('/tim-kiem', [BatDongSanController::class, 'searchAdmin']); // đã test postman
    });
});
Route::prefix('moi-gioi')->middleware('')->group(function () {
    //PROFILE MÔI GIỚI //xong
    Route::get('/profile', [MoiGioiController::class, 'profile']);
    Route::post('/update-profile', [MoiGioiController::class, 'updateProfile']);
    Route::post('/update-password', [MoiGioiController::class, 'updatePassword']);
    Route::post('/logout', [MoiGioiController::class, 'logout']);
});
Route::prefix('khach-hang')->middleware('')->group(function () {
    //PROFILE KHÁCH HÀNG //xong
    Route::get('/profile', [KhachHangController::class, 'profile']);
    Route::post('/update-profile', [KhachHangController::class, 'updateProfile']);
    Route::post('/update-password', [KhachHangController::class, 'updatePassword']);
    Route::post('/logout', [KhachHangController::class, 'logout']);

    //MAP (HIỂN THỊ BĐS THEO KHU VỰC) //xong
    Route::get('/map/bat-dong-san', [MapController::class, 'getBatDongSanMap']); // đã test postman ?bounds={"north":10,"south":9,"east":106,"west":105}&min_price=1000000000&max_price=5000000000&loai_id=1
    Route::get('/map/nearby', [MapController::class, 'getNearbyProperties']); // đã test postman ?lat=10.762622&lng=106.660172&radius=5

    //YÊU THÍCH (THẢ TIM)
    Route::post('/bds/yeu-thich', [YeuThichController::class, 'like']); // đã test postman
    Route::get('/bds/yeu-thich/data', [YeuThichController::class, 'getData']); // đã test postman

});

// Sprint 1
// Đăng nhập (v)
// Đăng xuất (v)
// Đăng ký (v)
// Quên mật khẩu (v)
// Cập nhật tài khoản (v)
// Tìm kiếm bất động sản
// Xem chi tiết bất động sản
// Xem BĐS theo khu vực, bản đồ BĐS, tìm gần vị trí (v)
// Thả tim, bỏ tim (tương tác bài đăng BĐS) (v)
