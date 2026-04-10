<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteMoiGioiRequest;
use App\Http\Requests\DestroyRequest;
use App\Http\Requests\MoiGioiLoginRequest;
use App\Http\Requests\MoiGioiRegisterRequest;
use App\Http\Requests\SearchMoiGioiRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateMoiGioiRequest;
use App\Http\Requests\updatePasswordMoiGioiRequest;
use App\Models\MoiGioi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\KhachHang;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;

class MoiGioiController extends Controller
{
    public function login(MoiGioiLoginRequest $request): JsonResponse
    {
        $user = Auth::guard('moi_gioi')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($user) {
            $user = Auth::guard('moi_gioi')->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'Dang nhap thanh cong',
                'token' => $token,
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }
    }

    public function updatePassword(UpdatePasswordMoiGioiRequest $request)
    {
        $user = Auth::guard('sanctum')->user();
        $data = KhachHang::where('id', $user->id)
            ->where('password', $request->old_password)
            ->first();
        if ($data) {
            $data->update([
                'password' => $request->password,
            ]);
            return response()->json([
                'status'    => 1,
                'message'   => 'Cập nhật mật khẩu thành công!',
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Mật khẩu cũ không đúng!',
            ]);
        }
    }

    public function updateProfile(UpdateMoiGioiRequest $request)
    {
        $user = Auth::guard('sanctum')->user();
        $data = MoiGioi::find($user->id);

        if ($data) {
            $data->update([
                'ten'           => $request->ten,
                'email'         => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
                'mo_ta'         => $request->mo_ta,
                'zalo_link'     => $request->zalo_link,
            ]);

            return response()->json([
                'status'  => 1,
                'message' => 'Cập nhật thông tin thành công!',
            ]);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => 'Thông tin môi giới không tồn tại!',
            ]);
        }
    }

    public function register(MoiGioiRegisterRequest $request)
    {
        $moiGioi = MoiGioi::create([
            'ten' => $request->ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'password' => Hash::make($request->input('password')),
            'zalo_link' => $request->zalo_link ?? '',
            'mo_ta' => $request->mo_ta ?? '',
            'is_active' => true,
        ]);

        $token = $moiGioi->createToken('moi-gioi-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Đăng ký thành công',
            'data' => [
                'token' => $token,
                'moi_gioi' => $moiGioi,
            ],
        ]);
    }

    public function profile()
    {
        /** @var MoiGioi|null $user */
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            return response()->json([
                'status' => true,
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Có lỗi xảy ra',
        ]);
    }

    public function checkToken(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            return response()->json([
                'status' => 'success',
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Token không hợp lệ'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        /** @var MoiGioi|null $user */
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng xuất thành công'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy người dùng hoặc token không hợp lệ'
            ], 401);
        }
    }

    //Gửi OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = MoiGioi::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email không tồn tại'
            ]);
        }

        $otp = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp,
                'created_at' => now()
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'OTP đã gửi',
            'otp' => $otp // dev thôi
        ]);
    }

    //Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:6'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'OTP không đúng'
            ]);
        }

        $user = MoiGioi::where('email', $request->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Đổi mật khẩu thành công'
        ]);
    }
}