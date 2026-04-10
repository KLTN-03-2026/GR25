<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchBatDongSanAdminRequest;
use App\Models\BatDongSan;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BatDongSanController extends Controller
{
     // Tìm kiếm BDS cho admin (có thể tìm theo tiêu đề, mô tả, giá, loại, địa chỉ)
    public function searchAdmin(SearchBatDongSanAdminRequest $request) // Chính xác
    {
        $id_chuc_nang = 60; // ID tìm kiếm BDS cho admin
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
        // Lấy đúng keyword từ request
        $keyword = $request->keyword;

        if (!$keyword || trim($keyword) === '') {
            return response()->json([
                'status' => false,
                'message' => 'Vui lòng nhập từ khóa tìm kiếm'
            ], 400);
        }

        $noi_dung = '%' . $keyword . '%';

        // Tìm kiếm
        $data = BatDongSan::where('tieu_de', 'like', $noi_dung)
            ->orWhere('mo_ta', 'like', $noi_dung)
            ->orWhere('gia', 'like', $noi_dung)
            ->orWhere('loai_id', 'like', $noi_dung)
            ->orWhere('dia_chi_id', 'like', $noi_dung)
            ->get();

        // Kiểm tra nếu không có kết quả
        if ($data->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'Không tìm thấy bất động sản nào phù hợp',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
