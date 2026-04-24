<?php
//app/Services/SePayService.php
namespace App\Services;

use App\Models\GoiTin;
use Illuminate\Support\Facades\Log;
use SePay\Builders\CheckoutBuilder;
use SePay\SePayClient;
use Symfony\Component\HttpFoundation\Request;

class SePayService
{
    protected $client;

    public function __construct()
    {
        //dd(config('services.sepay'));

        $this->client = new SePayClient(
            config('services.sepay.merchant_id'),
            config('services.sepay.secret_key'),
            config('services.sepay.env', 'sandbox')
        );
    }

    public function verifyWebhook($request)
    {
        // Thử cả 2 cách lấy header
        $token = $request->header('Authorization')
            ?? $request->header('authorization')
            ?? $request->server->get('HTTP_AUTHORIZATION');

        $expected = 'Apikey ' . config('services.sepay.webhook_token');

        Log::info('Webhook Auth', [
            'received' => $token,
            'expected' => $expected,
            'match' => $token && hash_equals($expected, $token)
        ]);

        return $token && hash_equals($expected, $token);
    }

    public function createPaymentUrl($orderCode, $amount, $info, $successUrl = null, $errorUrl = null, $cancelUrl = null)
    {
        // Fallback về return URL cũ nếu không truyền 3 URL riêng
        $successUrl = $successUrl ?? $this->resolveSePayReturnUrl(request());
        $errorUrl = $errorUrl ?? $successUrl;
        $cancelUrl = $cancelUrl ?? $successUrl;

        $checkoutData = CheckoutBuilder::make()
            ->currency('VND')
            ->orderInvoiceNumber($orderCode)
            ->orderAmount($amount)
            ->operation('PURCHASE')
            ->orderDescription($info)
            ->successUrl($successUrl . '?order_code=' . $orderCode)
            ->errorUrl($errorUrl . '?order_code=' . $orderCode)
            ->cancelUrl($cancelUrl . '?order_code=' . $orderCode)
            ->build();

        return $this->client->checkout()->generateFormHtml($checkoutData);
    }
}
