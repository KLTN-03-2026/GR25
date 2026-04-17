<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteKhachHangRequest;
use App\Models\KhachHang;
use App\Http\Requests\KhachHangLoginRequest;
use App\Http\Requests\KhachHangRegisterRequest;
use App\Http\Requests\UpdateKhachHangRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\DestroyRequest;
use App\Http\Requests\KhachHangUpdatePasswordRequest;
use App\Http\Requests\KhachHangUpdateProfileRequest;
use App\Http\Requests\SearchKhachHangRequest;
use App\Http\Requests\updatePasswordKhachHangRequest;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class KhachHangController extends Controller
{
    //Khách hàng đăng nhập
    public function login(KhachHangLoginRequest $request)
    {
        $khachhang = KhachHang::where('email', $request->email)->first();

        if (!$khachhang || !Hash::check($request->password, $khachhang->password)) {
            return response()->json([
                'status' => 0,
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }

        $token = $khachhang->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 1,
            'message' => 'Đăng nhập thành công',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => $khachhang
        ], 200);
    }

    // Kiểm tra token còn hiệu lực không
    public function checkToken()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            return response()->json([
                'status' => "true",
                'data' => ['ten' => $user->ten],
            ]);
        }
        return response()->json([
            'status' => "false",
            'message' => 'Chưa đăng nhập',
        ]);
    }

    // Khách hàng đăng ký
    public function register(KhachHangRegisterRequest $request)
    {
        $khachHang = KhachHang::create([
            'ten'                 => $request->ten,
            'email'               => $request->email,
            'so_dien_thoai'       => $request->so_dien_thoai,
            'password'            => Hash::make($request->password),
            'zalo_link'           => $request->zalo_link,
            'mo_ta'               => $request->mo_ta,
        ]);

        $token = $khachHang->createToken('khach-hang-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Đăng ký thành công',
            'data'    => [
                'token'        => $token,
                'token_type'   => 'Bearer',
                'khach_hang'   => [
                    'id'            => $khachHang->id,
                    'ten'           => $khachHang->ten,
                    'email'         => $khachHang->email,
                    'so_dien_thoai' => $khachHang->so_dien_thoai,
                    'zalo_link'     => $khachHang->zalo_link,
                ]
            ]
        ], 201);
    }
    // Khách hàng đăng xuất
    public function logout(Request $request)
    {
        /** @var MoiGioi|null $user */
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json([
                'status' => 'true',
                'message' => 'Đăng xuất thành công'
            ], 200);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Không tìm thấy người dùng hoặc token không hợp lệ'
            ], 401);
        }
    }

    //Khách hàng xem thông tin cá nhân
    public function profile()
    {
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            return response()->json([
                'status' => true,
                'data' => $user,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Chưa đăng nhập',
        ]);
    }

    //Khách hàng cập nhật thông tin cá nhân
    public function updateProfile(KhachHangUpdateProfileRequest $request)
    {
        $user = Auth::guard('sanctum')->user();
        $data = KhachHang::find($user->id);

        if ($data) {
            $data->update([
                'ten'           => $request->ten,
                'email'         => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Cập nhật thông tin thành công!',
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Thông tin khách hàng không tồn tại!',
            ]);
        }
    }

    //Khách hàng cập nhật mật khẩu
    public function updatePassword(KhachHangUpdatePasswordRequest $request)
    {
        $user = Auth::guard('sanctum')->user();
        $data = KhachHang::find($user->id);

        if ($data) {
            if (!Hash::check($request->old_password, $data->password)) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Mật khẩu cũ không đúng!',
                ], 400);
            }

            $data->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status'  => 1,
                'message' => 'Cập nhật mật khẩu thành công!',
            ]);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => 'Thông tin khách hàng không tồn tại!',
            ]);
        }
    }

    //Gửi OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = KhachHang::where('email', $request->email)->first();

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

        $user = KhachHang::where('email', $request->email)->first();

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

    // Admin lấy danh sách khách hàng
    public function getData(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            $data = KhachHang::query();

            return response()->json([
                'status' => true,
                'data' => $data->get()
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }

    // Admin tìm kiếm khách hàng
    public function search(SearchKhachHangRequest $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ], 401);
        }

        $keyword = $request->keyword;
        $data = KhachHang::where('ten', 'like', '%' . $keyword . '%')
            ->orWhere('email', 'like', '%' . $keyword . '%')
            ->orWhere('so_dien_thoai', 'like', '%' . $keyword . '%')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => true,  // Vẫn là true vì request hợp lệ, chỉ là không có kết quả
                'message' => 'Không tìm thấy khách hàng nào phù hợp với từ khóa "' . $keyword . '"',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // Admin cập nhật thông tin khách hàng
    public function update(UpdateKhachHangRequest $request)
    {
        $id_chuc_nang = 13;
        $user = Auth::guard('sanctum')->user();
        $check = PhanQuyen::where('id_chuc_vu', $user->id_chuc_vu)
            ->where('id_chuc_nang', $id_chuc_nang)
            ->first();
        if (!$user->is_super &&  !$check) {
            return response()->json([
                'status' => false,
                'message' => "Bạn không có quyền thực hiện chức năng này"
            ]);
        }
        $data = KhachHang::find($request->id);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy khách hàng'
            ], 404);
        }
        $data->update([
            'ten' => $request->ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'email' => $request->email,
            'is_active' => $request->is_active,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thành công',
            'data' => $data
        ]);
    }

    // Admin xóa khách hàng
    public function destroy(DeleteKhachHangRequest $request)
    {
        $id_chuc_nang = 14;
        $user = Auth::guard('sanctum')->user();
        $check = PhanQuyen::where('id_chuc_vu', $user->id_chuc_vu)
            ->where('id_chuc_nang', $id_chuc_nang)
            ->first();
        if (!$user->is_super && !$check) {
            return response()->json([
                'status' => false,
                'message' => "Bạn không có quyền thực hiện chức năng này"
            ], 403);
        }

        $data = KhachHang::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy khách hàng để xóa'
        ], 404);
    }

    // Admin thay đổi trạng thái khách hàng
    public function changeStatus(Request $request)
    {
        $id_chuc_nang = 66;
        $user = Auth::guard('sanctum')->user();
        $check = PhanQuyen::where('id_chuc_vu', $user->id_chuc_vu)
            ->where('id_chuc_nang', $id_chuc_nang)
            ->first();
        if (!$user->is_super && !$check) {
            return response()->json([
                'status' => false,
                'message' => "Bạn không có quyền thực hiện chức năng này"
            ], 403);
        }
        $data = KhachHang::find($request->id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ], 401);
        }
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy khách hàng'
            ], 404);
        }
        $data->update([
            'is_active' => $request->is_active
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'data' => $data
        ]);
    }

    public function exportKhachHang(Request $request)
    {
        $format = $request->input('format', 'csv');
        $filename = "khach_hang_" . now()->format('Ymd_His');

        // Chỉ select đúng cột cần thiết -> Query nhanh & nhẹ RAM
        $query = KhachHang::query()
            ->select('id', 'ten', 'so_dien_thoai', 'email', 'created_at')
            ->orderBy('created_at', 'desc');

        // Filter cơ bản
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ten', 'like', "%{$request->search}%")
                    ->orWhere('so_dien_thoai', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->date_to);

        return match ($format) {
            'excel' => $this->exportExcel($query, $filename),
            'pdf'   => $this->exportPdf($query, $filename),
            default => $this->exportCsv($query, $filename)
        };
    }

    private function exportCsv($query, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        return response()->stream(function () use ($query) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($file, ['ID', 'Họ tên', 'Số điện thoại', 'Email', 'Ngày tạo'], ';');

            $query->chunk(500, function ($items) use ($file) {
                foreach ($items as $item) {
                    fputcsv($file, [
                        $item->id,
                        $item->ten,
                        $item->so_dien_thoai,
                        $item->email,
                        $item->created_at->format('d/m/Y H:i')
                    ], ';');
                }
            });
            fclose($file);
        }, 200, $headers);
    }

    private function exportExcel($query, $filename)
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            return response()->json(['message' => 'Thiếu package'], 500);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = ['ID', 'Họ tên', 'Email', 'SĐT', 'Trạng thái', 'Ngày tạo'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        // ✅ SỬA: Lấy data với select rõ ràng
        $customers = $query->select('id', 'ten', 'email', 'so_dien_thoai', 'created_at', 'is_active', 'trang_thai')->get();

        $row = 2;
        foreach ($customers as $item) {
            // ✅ SỬA: Kiểm tra cả 2 trường is_active và trang_thai
            $trangThai = 'Bị khóa'; // Mặc định

            // Kiểm tra nếu có cột is_active (1/0 hoặc true/false)
            if (isset($item->is_active)) {
                $trangThai = ($item->is_active == 1 || $item->is_active === true || $item->is_active === '1')
                    ? 'Đang hoạt động'
                    : 'Bị khóa';
            }
            // Hoặc kiểm tra cột trang_thai (active/inactive)
            elseif (isset($item->trang_thai)) {
                $trangThai = ($item->trang_thai === 'active')
                    ? 'Đang hoạt động'
                    : 'Bị khóa';
            }

            $sheet->fromArray([
                $item->id,
                $item->ten,
                $item->email,
                $item->so_dien_thoai,
                $trangThai, // ✅ Đã fix
                $item->created_at->format('d/m/Y H:i')
            ], null, 'A' . $row);
            $row++;
        }

        // Auto size
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xlsx\"",
        ]);
    }

    private function exportPdf($query, $filename)
    {
        $data = $query->limit(1000)->get()->map(fn($item) => [
            'id' => $item->id,
            'ten' => $item->ten,
            'email' => $item->email,
            'sdt' => $item->so_dien_thoai,
            'trang_thai' => $item->is_active ? 'Đang hoạt động' : 'Bị khóa',
            'ngay_tao' => $item->created_at->format('d/m/Y H:i')
        ]);

        try {
            $html = view('khach_hang_pdf', compact('data'))->render();

            $pdf = \PDF::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            return $pdf->download("{$filename}.pdf");
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
