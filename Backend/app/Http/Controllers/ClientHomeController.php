<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchBatDongSanRequest;
use App\Models\BatDongSan;
use App\Models\LoaiBatDongSan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClientHomeController extends Controller
{
    /**
     * Helper: Xử lý ẩn/hiện dữ liệu nhạy cảm
     * - Guest: Ẩn SĐT, địa chỉ chi tiết, cắt ngắn mô tả
     * - User: Hiển thị đầy đủ
     */
    private function prepareData($bds)
    {
        $isGuest = !Auth::guard('sanctum')->check();

        if ($isGuest) {
            // // 1. Ẩn SĐT môi giới
            // if ($bds->moiGioi) {
            //     $bds->moiGioi->so_dien_thoai = 'Đăng nhập để xem SĐT';
            //     $bds->moiGioi->zalo_link = null;
            // }

            // // 2. Ẩn địa chỉ chi tiết
            // if ($bds->diaChi) {
            //     $bds->diaChi->dia_chi_chi_tiet = Str::limit($bds->diaChi->dia_chi_chi_tiet, '20', '...');
            // }

            // // 3. Cắt ngắn mô tả
            // if (isset($bds->mo_ta)) {
            //     $bds->mo_ta = Str::limit($bds->mo_ta, 150);
            // }

            // // 4. ✅ MASK GIÁ - Chỉ hiện số đầu, còn lại là xxxx
            // if (isset($bds->gia) && $bds->gia > 0) {
            //     $giaStr = (string)$bds->gia;
            //     $firstDigit = $giaStr[0];
            //     $remainingDigits = strlen($giaStr) - 1;
            //     $bds->gia_display = $firstDigit . str_repeat('x', $remainingDigits);
            //     $bds->gia_original = $bds->gia; // Lưu giá gốc để xử lý sau
            //     $bds->gia = null; // Xóa giá gốc
            // }

            // 5. Flag gửi về để FE biết hiển thị nút Login
            // $bds->is_guest_view = true;
        } else {
            // $bds->is_guest_view = false;
            // $bds->gia_display = null;
        }

        return $bds;
    }

    /**
     * Public: Danh sách BĐS
     * GET /api/bat-dong-san
     */
    public function getAllPublic(Request $request)
    {
        $query = BatDongSan::with([
            'loai',
            'moiGioi',
            'hinhAnh'
        ])->where('is_duyet', true);

        $data = $query->paginate(6);

        // Áp dụng mask cho từng item trong danh sách nếu là guest
        if (!Auth::guard('sanctum')->check()) {
            $data->getCollection()->transform(fn($item) => $this->prepareData($item));
        }

        return response()->json(['status' => true, 'data' => $data]);
    }

    /**
     * Public: Chi tiết BĐS
     * GET /api/bat-dong-san/{id}
     */
    public function xemChiTiet($id)
    {
        $bds = BatDongSan::with([
            'loai',
            'trangThai',
            'moiGioi',
            'diaChi.tinh',
            'diaChi.quan',
            'diaChi',
            'hinhAnh'
        ])->find($id);

        if (!$bds) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy bất động sản'
            ], 404);
        }

        // Áp dụng mask cho chi tiết nếu là guest
        if (!Auth::guard('sanctum')->check()) {
            $bds = $this->prepareData($bds);
        }

        return response()->json(['status' => true, 'data' => $bds]);
    }

    /**
     * Public: Search BĐS
     * POST /api/tim-kiem
     */
    public function search(SearchBatDongSanRequest $request)
    {
        $query = BatDongSan::query();

        // Giữ nguyên logic filter của bạn
        $query->when($request->tinh_id, fn($q) => $q->where('tinh_id', $request->tinh_id));
        $query->when($request->loai_id, fn($q) => $q->where('loai_id', $request->loai_id));
        $query->when($request->gia_min, fn($q) => $q->where('gia', '>=', $request->gia_min));
        $query->when($request->gia_max, fn($q) => $q->where('gia', '<=', $request->gia_max));
        $query->when($request->tieu_de, fn($q) => $q->where('tieu_de', 'like', '%' . $request->tieu_de . '%'));
        $query->where('is_duyet', true);

        $data = $query->with(['loai', 'moiGioi'])->paginate(10);

        // Áp dụng mask cho kết quả tìm kiếm nếu là guest
        if (!Auth::guard('sanctum')->check()) {
            $data->getCollection()->transform(fn($item) => $this->prepareData($item));
        }

        return response()->json(['status' => true, 'data' => $data]);
    }

    // Lấy dữ liệu loại BĐS 
    public function getLoaiBDS(Request $request)
    {
        $data = LoaiBatDongSan::all();
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
