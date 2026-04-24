<?php

namespace App\Http\Controllers;

use App\Models\GoiTin;
use App\Models\GiaoDich;
use App\Models\MoiGioi;
use App\Services\SePayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Events\ThanhToanThanhCong;

class GiaoDichController extends Controller
{
    protected $sePay;

    public function __construct(SePayService $sePay)
    {
        $this->sePay = $sePay;
    }

    private function resolveSePayReturnUrl(Request $request): string
    {
        $configuredReturnUrl = env('SEPAY_RETURN_URL');

        if ($configuredReturnUrl) {
            return $configuredReturnUrl;
        }

        return rtrim($request->getSchemeAndHttpHost(), '/') . '/payment/sepay-return';
    }

    private function generateOrderCode(): string
    {
        do {
            $code = 'SE' . now()->format('YmdHis') . random_int(1000, 9999);
        } while (GiaoDich::where('ma_giao_dich', $code)->exists());

        return $code;
    }

    //1. Tạo đơn hàng & Redirect sang SePay (POST /api/moi-gioi/payment/create)
    public function createPayment(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $goi = GoiTin::find($request->goi_tin_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if (!$goi) {
            return response()->json([
                'status' => false,
                'message' => 'Package not found'
            ], 404);
        }

        try {
            $paymentData = DB::transaction(function () use ($goi, $request, $user) {
                $orderCode = $this->generateOrderCode();

                $transaction = GiaoDich::create([
                    'moi_gioi_id' => $user->id,
                    'goi_tin_id' => $goi->id,
                    'so_tien' => $goi->gia,
                    'phuong_thuc' => 'sepay',
                    'trang_thai' => GiaoDich::STATUS_PENDING,
                    'ma_giao_dich' => $orderCode,
                ]);

                $paymentForm = $this->sePay->createPaymentUrl(
                    $orderCode,
                    $goi->gia,
                    "Thanh toán  {$goi->ten_goi}",
                    $this->resolveSePayReturnUrl($request)
                );

                return [
                    'transaction_id' => $transaction->id,
                    'order_code' => $orderCode,
                    'payment_form' => $paymentForm,
                ];
            });
        } catch (\Throwable $exception) {
            Log::error('SePay create payment failed', [
                'user_id' => $user->id,
                'goi_tin_id' => $request->goi_tin_id,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Khong the tao thanh toan SePay'
            ], 500);
        }

        return response()->json([
            'status' => true,
            'transaction_id' => $paymentData['transaction_id'],
            'order_code' => $paymentData['order_code'],
            'payment_form' => $paymentData['payment_form'],
            'data' => $paymentData,
        ]);
    }

    // 2. Xử lý webhook từ SePay (POST /api/payment/sepay-webhook)
    public function handleSePayWebhook(Request $request)
    {
        Log::info('========== WEBHOOK START ==========');

        // ===== AUTH CHECK (Có thể bỏ qua khi test) =====
        $authHeader = $request->header('Authorization') ?? $request->header('authorization');
        $expectedToken = config('services.sepay.secret_key');

        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $receivedToken = substr($authHeader, 7);
            if (!hash_equals($expectedToken, $receivedToken)) {
                Log::error('❌ Auth failed', ['received' => $receivedToken, 'expected' => $expectedToken]);
                return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
            }
            Log::info('✅ Auth passed');
        } else {
            Log::warning('⚠️ Skipping auth check (test mode)');
        }
        // ===== END AUTH =====

        $data = $request->json()->all();
        Log::info('Payload:', $data);

        if (!isset($data['notification_type'], $data['order']['order_invoice_number'], $data['order']['order_amount'], $data['transaction']['transaction_status'])) {
            Log::error('❌ Invalid payload');
            return response()->json(['success' => false, 'message' => 'Invalid payload'], 422);
        }

        if ($data['notification_type'] !== 'ORDER_PAID') {
            Log::info('⏭️ Skipping non-ORDER_PAID event');
            return response()->json(['success' => true], 200);
        }

        $orderCode = $data['order']['order_invoice_number'];
        $amount = (float) $data['order']['order_amount'];
        $status = $data['transaction']['transaction_status'];

        Log::info('🔍 Looking for order: ' . $orderCode);

        $transaction = GiaoDich::where('ma_giao_dich', $orderCode)->lockForUpdate()->first();

        if (!$transaction) {
            Log::error('❌ Order NOT found', ['code' => $orderCode]);
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        Log::info('✅ Order found', ['id' => $transaction->id, 'status' => $transaction->trang_thai]);

        if ($transaction->trang_thai === 'success') {
            Log::warning('⚠️ Already processed');
            return response()->json(['success' => true], 200);
        }

        if ($amount != $transaction->so_tien) {
            Log::error('❌ Amount mismatch', ['sepay' => $amount, 'db' => $transaction->so_tien]);
            $transaction->update(['trang_thai' => 'failed']);
            return response()->json(['success' => false, 'message' => 'Amount mismatch'], 400);
        }

        $validStatuses = ['APPROVED', 'SUCCESS', 'CAPTURED', 'COMPLETED'];
        if (!in_array(strtoupper($status), $validStatuses)) {
            Log::error('❌ Payment not approved', ['status' => $status]);
            $transaction->update(['trang_thai' => 'failed']);
            return response()->json(['success' => false, 'message' => 'Payment not approved'], 400);
        }

        // Update transaction
        $transaction->update([
            'trang_thai' => 'success',
            'paid_at' => now(),
            'ma_sepay_txn_ref' => $data['transaction']['transaction_id'] ?? null,
        ]);
        Log::info('✅ Transaction updated');

        // 🔥 QUAN TRỌNG: Update user credits
        Log::info('👤 Updating user credits...');

        $goiTin = GoiTin::find($transaction->goi_tin_id);
        $user = MoiGioi::find($transaction->moi_gioi_id);

        if (!$goiTin || !$user) {
            Log::error('❌ Related model not found');
            return response()->json(['success' => false, 'message' => 'Model not found'], 404);
        }

        $baseDate = $user->ngay_het_han_goi && $user->ngay_het_han_goi->isFuture()
            ? $user->ngay_het_han_goi->copy()
            : now();

        $user->goi_tin_id = $goiTin->id;
        $user->so_tin_con_lai += $goiTin->so_luong_tin;
        $user->ngay_het_han_goi = $baseDate->addDays($goiTin->so_ngay);
        $user->save();

        Log::info('✅ User credits updated', [
            'new_credits' => $user->so_tin_con_lai,
            'new_expiry' => $user->ngay_het_han_goi,
        ]);

        event(new ThanhToanThanhCong($user->id));

        Log::info('========== WEBHOOK END ==========');
        return response()->json(['success' => true], 200);
    }

    // 2️⃣ Xử lý return URL từ SePay (khi user được redirect về)
    public function handleSePayReturn(Request $request)
    {
        $status = $request->query('status', 'cancel');
        $orderCode = $request->query('order_code');

        Log::info('SePay Return Hit', [
            'status' => $status,
            'order_code' => $orderCode,
            'all_query' => $request->query(),
        ]);

        // ✅ QUAN TRỌNG: Redirect về frontend URL
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173'); // Hoặc port FE của bạn
        $redirectUrl = "{$frontendUrl}/moi-gioi/goi-tin";

        // Thêm query params
        $params = [];
        if ($status) $params['status'] = $status;
        if ($orderCode) $params['order_code'] = $orderCode;

        if (!empty($params)) {
            $redirectUrl .= '?' . http_build_query($params);
        }

        Log::info('Redirecting to: ' . $redirectUrl);

        return redirect()->away($redirectUrl);
    }

    // 3️⃣ Xử lý redirect khi thanh toán THÀNH CÔNG
    public function handlePaymentSuccess(Request $request)
    {
        $orderCode = $request->query('order_code');
        $txnRef = $request->query('transaction_id');

        Log::info('Payment Success Redirect', [
            'order_code' => $orderCode,
            'txn_ref' => $txnRef,
            'query' => $request->query(),
        ]);

        // 🔥 QUAN TRỌNG: KHÔNG update DB ở đây! 
        // Chỉ redirect về frontend, frontend sẽ polling check status

        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
        $redirectUrl = "{$frontendUrl}/payment/success?order_code={$orderCode}";

        return redirect()->away($redirectUrl);
    }

    // 4️⃣ Xử lý redirect khi thanh toán THẤT BẠI
    public function handlePaymentError(Request $request)
    {
        $orderCode = $request->query('order_code');
        $errorMessage = $request->query('message', 'Thanh toán thất bại');

        Log::warning('Payment Error Redirect', [
            'order_code' => $orderCode,
            'message' => $errorMessage,
        ]);

        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
        $redirectUrl = "{$frontendUrl}/payment/error?order_code={$orderCode}&message=" . urlencode($errorMessage);

        return redirect()->away($redirectUrl);
    }

    // 5️⃣ Xử lý redirect khi user HỦY thanh toán
    public function handlePaymentCancel(Request $request)
    {
        $orderCode = $request->query('order_code');

        Log::info('Payment Cancelled', ['order_code' => $orderCode]);

        // Optional: Update order status thành 'cancelled' nếu muốn
        // Nhưng thường nên để pending và chờ webhook timeout

        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
        $redirectUrl = "{$frontendUrl}/payment/cancel?order_code={$orderCode}";

        return redirect()->away($redirectUrl);
    }














    public function exportGiaoDich(Request $request)
    {
        $format = $request->input('format', 'csv'); // default: csv
        $filename = "giao_dich_" . now()->format('Ymd_His');

        // Build query (dùng chung cho cả 3 định dạng)
        $query = GiaoDich::query()->with(['moiGioi:id,ten,so_dien_thoai,email', 'goiTin:id,ten_goi']);

        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->filled('status')) $query->where('trang_thai', $request->status);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ma_giao_dich', 'like', "%{$request->search}%")
                    ->orWhereHas('moiGioi', fn($q) => $q->where('ten', 'like', "%{$request->search}%"));
            });
        }

        // Route sang đúng hàm export theo format
        return match ($format) {
            'excel' => $this->exportExcel($query, $filename),
            'pdf'   => $this->exportPdf($query, $filename),
            default => $this->exportCsv($query, $filename)
        };
    }

    /** CSV: Native PHP, siêu nhanh, nhẹ RAM */
    private function exportCsv($query, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        return response()->stream(function () use ($query) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Mã GD', 'Môi giới', 'SĐT', 'Gói tin', 'Số tiền', 'Phương thức', 'Trạng thái', 'Ngày tạo'], ';');

            $query->chunk(500, function ($items) use ($file) {
                foreach ($items as $item) {
                    // ✅ Fix: Kiểm tra cả 2 tên cột
                    $phuongThuc = $item->phuong_thuc_thanh_toan
                        ?? $item->phuong_thuc
                        ?? $item->payment_method
                        ?? 'N/A';

                    fputcsv($file, [
                        $item->ma_giao_dich,
                        $item->moiGioi->ten ?? '',
                        $item->moiGioi->so_dien_thoai ?? '',
                        $item->goiTin->ten_goi ?? '',
                        number_format($item->so_tien, 0, ',', '.'),
                        $phuongThuc, // ✅ Đã fix
                        match ($item->trang_thai) {
                            'success' => 'Thành công',
                            'pending' => 'Chờ xử lý',
                            'failed' => 'Thất bại',
                            'cancelled' => 'Đã hủy',
                            default => $item->trang_thai
                        },
                        $item->created_at->format('d/m/Y H:i')
                    ], ';');
                }
            });
            fclose($file);
        }, 200, $headers);
    }

    /** Excel: Dùng PhpSpreadsheet, format đẹp, hỗ trợ formula */
    private function exportExcel($query, $filename)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Mã GD', 'Môi giới', 'SDT', 'Email', 'Gói tin', 'Số tiền', 'Phương thức', 'Trạng thái', 'Ngày tạo'];
        $sheet->fromArray([$headers], null, 'A1');
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        $row = 2;
        $query->chunk(500, function ($items) use ($sheet, &$row) {
            foreach ($items as $item) {
                // ✅ Fix: Kiểm tra nhiều tên cột
                $phuongThuc = $item->phuong_thuc_thanh_toan
                    ?? $item->phuong_thuc
                    ?? $item->payment_method
                    ?? 'N/A';

                $sheet->fromArray([
                    $item->ma_giao_dich,
                    $item->moiGioi->ten ?? '',
                    $item->moiGioi->so_dien_thoai ?? '',
                    $item->moiGioi->email ?? '',
                    $item->goiTin->ten_goi ?? '',
                    $item->so_tien,
                    $phuongThuc, // ✅ Đã fix
                    match ($item->trang_thai) {
                        'success' => 'Thành công',
                        'pending' => 'Chờ xử lý',
                        'failed' => 'Thất bại',
                        'cancelled' => 'Đã hủy',
                        default => $item->trang_thai
                    },
                    $item->created_at->format('d/m/Y H:i')
                ], null, 'A' . $row);
                $row++;
            }
        });

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        return response()->stream(fn() => $writer->save('php://output'), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xlsx\"",
        ]);
    }

    /** PDF: Dùng DomPDF, phù hợp in ấn/báo cáo ngắn */
    private function exportPdf($query, $filename)
    {
        // Lấy data (PDF không nên stream chunk lớn, giới hạn ~2000 dòng)
        $data = $query->limit(1000)->get()->map(fn($item) => [
            'ma_gd' => $item->ma_giao_dich,
            'moi_gioi' => $item->moiGioi->ten ?? '',
            'sdt' => $item->moiGioi->so_dien_thoai ?? '',
            'goi_tin' => $item->goiTin->ten_goi ?? '',
            'so_tien' => number_format($item->so_tien, 0, ',', '.') . ' đ',
            // ✅ SỬA: Kiểm tra nhiều tên cột khác nhau
            'phuong_thuc' => $item->phuong_thuc_thanh_toan
                ?? $item->phuong_thuc
                ?? $item->payment_method
                ?? 'N/A',
            'trang_thai' => match ($item->trang_thai) {
                'success' => 'Thành công',
                'pending' => 'Chờ xử lý',
                'failed' => 'Thất bại',
                'cancelled' => 'Đã hủy',
                default => $item->trang_thai
            },
            'ngay_tao' => $item->created_at->format('d/m/Y H:i')
        ]);

        $html = view('giao_dich_pdf', compact('data'))->render();
        return \PDF::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->download("{$filename}.pdf");
    }
}
