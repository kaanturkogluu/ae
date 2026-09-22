<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\IyzicoService;
use App\Services\NetgsmService;
use App\Jobs\SendNewOrderNotificationJob;

class CheckoutController extends Controller
{
    protected $iyzico;

    public function __construct(IyzicoService $iyzico)
    {
        $this->iyzico = $iyzico;
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
        // If cart is empty, try restoring items from failed/pending order in session
        if (empty($cart)) {
            $pendingId = session()->get('pending_order_id') ?: session()->get('order_id');
            if ($pendingId) {
                $failedOrder = Order::with('items.product')->find($pendingId);
                if ($failedOrder && in_array($failedOrder->status, ['pending', 'failed'])) {
                    $restoredCart = [];
                    foreach ($failedOrder->items as $item) {
                        $features = is_array($item->features) ? $item->features : (json_decode($item->features, true) ?: []);
                        $fImg = $features['front_image'] ?? ($features['custom_image'] ?? null);
                        $bImg = $features['back_image'] ?? null;
                        $preview = $features['custom_preview'] ?? null;
                        
                        $uniqueSeed = ($fImg ?: '') . ($bImg ?: '') . ($preview ?: '');
                        $cartKey = $item->product_id . ($uniqueSeed ? '_' . md5($uniqueSeed) : '');
                        
                        $restoredCart[$cartKey] = [
                            'product_id' => $item->product_id,
                            'name' => $item->product ? $item->product->name : 'Ahşap Ürün',
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'image' => $preview ? url($preview) : ($fImg ? url($fImg) : ($item->product ? $item->product->image : null)),
                            'custom_image_front' => $fImg ? url($fImg) : null,
                            'custom_image_back' => $bImg ? url($bImg) : null,
                            'custom_image' => $fImg ? url($fImg) : null,
                            'custom_preview' => $preview ? url($preview) : null,
                        ];
                    }
                    if (!empty($restoredCart)) {
                        session()->put('cart', $restoredCart);
                        $cart = $restoredCart;
                    }
                }
            }
        }

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş olduğu için ödeme sayfasına gidemezsiniz.');
        }

        return view('checkout.index', compact('cart'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:1000',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
        ]);

        $tc = $request->input('identity_number');
        if (!empty($tc) && !$this->isValidTcNo($tc)) {
            return redirect()->back()->with('error', 'Girdiğiniz T.C. Kimlik Numarası matematiksel olarak geçersizdir. Lütfen kontrol ediniz.')->withInput();
        }

        // Calculate total amount
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $paymentMethod = $request->input('payment_method', 'card');
        $isEft = in_array($paymentMethod, ['eft', 'cod']);

        // Create temporary order with unique tracking code and payment method stamp
        $order = Order::create([
            'tracking_code'   => Order::generateTrackingCode(),
            'user_id'         => auth()->id(),
            'name'            => $request->name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'address'         => $request->address,
            'city'            => $request->city ?: 'Manisa',
            'district'        => $request->district ?: 'Merkez',
            'identity_number' => $request->identity_number ?: '11111111111',
            'note'            => $request->note,
            'total_amount'    => $totalAmount,
            'status'          => 'pending',
            'payment_id'      => $isEft ? ('EFT_' . time()) : ('CARD_INIT_' . time()),
        ]);

        // Save order items with front and back customization images
        foreach ($cart as $key => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'features' => [
                    'front_image' => $item['custom_image_front'] ?? ($item['custom_image'] ?? null),
                    'back_image' => $item['custom_image_back'] ?? null,
                    'custom_image' => $item['custom_image'] ?? null,
                    'custom_preview' => $item['custom_preview'] ?? null,
                    'is_gift' => $item['is_gift'] ?? false,
                    'gift_note' => $item['gift_note'] ?? null,
                ]
            ]);
        }

        // Save pending order ID in session as fallback
        session()->put('pending_order_id', $order->id);

        \Illuminate\Support\Facades\Log::channel('payment')->info("=== SIPARIS OLUSTURULDU [Order #{$order->id}] ===", [
            'order_id'       => $order->id,
            'tracking_code'  => $order->tracking_code,
            'payment_method' => $paymentMethod,
            'total_amount'   => $totalAmount,
            'name'           => $order->name,
            'email'          => $order->email,
            'phone'          => $order->phone,
            'city'           => $order->city,
            'district'       => $order->district,
            'identity_number'=> $order->identity_number ? '***' . substr($order->identity_number, -4) : null,
            'user_id'        => $order->user_id,
            'ip'             => $request->ip(),
            'cart_items'     => array_map(fn($item) => [
                'product_id' => $item['product_id'] ?? null,
                'name'       => $item['name'] ?? null,
                'price'      => $item['price'] ?? null,
                'quantity'   => $item['quantity'] ?? null,
            ], array_values($cart)),
        ]);

        // If customer selected Havale / EFT
        if ($isEft) {
            $order->update([
                'status'     => 'pending',
                'payment_id' => 'EFT_' . time(),
            ]);

            session()->forget('cart');
            session()->forget('pending_order_id');

            // Decrement stock for each item (EFT)
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            // Dispatch queued notification job for admin (email & SMS) and customer
            try {
                SendNewOrderNotificationJob::dispatch($order->id);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('SendNewOrderNotificationJob EFT dispatch error: ' . $e->getMessage());
            }

            \Illuminate\Support\Facades\Log::channel('payment')->info("=== EFT/HAVALE ODEME [Order #{$order->id}] ===", [
                'order_id'    => $order->id,
                'total'       => $order->total_amount,
                'payment_id'  => $order->payment_id,
            ]);

            return redirect()->route('checkout.result')->with([
                'status'   => 'success',
                'order_id' => $order->id,
                'is_eft'   => true,
            ]);
        }

        // Standard: Kredi / Banka Kartı Ödemesi (Iyzico Güvenli Formu Göster)
        try {
            $callbackUrl = route('checkout.callback', ['order_id' => $order->id]);
            $iyzicoForm = $this->iyzico->initializeCheckoutForm($order, $cart, $callbackUrl);

            if ($iyzicoForm && strtolower($iyzicoForm->getStatus() ?? '') === 'success') {
                return view('checkout.payment', [
                    'formContent' => $iyzicoForm->getCheckoutFormContent(),
                    'order' => $order
                ]);
            } else {
                $errorCode  = $iyzicoForm ? $iyzicoForm->getErrorCode() : 'N/A';
                $errorMsg   = $iyzicoForm ? ($iyzicoForm->getErrorMessage() ?: $iyzicoForm->getErrorGroup()) : 'Ödeme kapısına erişilemedi.';

                \Illuminate\Support\Facades\Log::channel('payment')->error("=== IYZICO FORM INIT BASARISIZ [Order #{$order->id}] ===", [
                    'error_code' => $errorCode,
                    'error_msg'  => $errorMsg,
                    'raw'        => $iyzicoForm ? $iyzicoForm->getRawResult() : null,
                ]);

                $order->update([
                    'status'               => 'failed',
                    'payment_error_reason' => 'Form başlatılamadı: ' . $errorMsg,
                ]);

                return redirect()->back()->with('error', 'Kredi Kartı ödeme formu yüklenemedi: ' . $errorMsg . ' (Hata Kodu: ' . $errorCode . ')')->withInput();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::channel('payment')->error("=== IYZICO INIT EXCEPTION [Order #{$order->id}] ===", [
                'exception' => $e->getMessage(),
                'file'      => $e->getFile() . ':' . $e->getLine(),
            ]);
            \Illuminate\Support\Facades\Log::error('Iyzico Init Exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ödeme sistemi başlatılırken bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    public function callback(Request $request)
    {
        $token = $request->input('token') ?: $request->query('token');
        $orderIdParam = $request->input('order_id') ?: $request->query('order_id');

        if (empty($token)) {
            \Illuminate\Support\Facades\Log::channel('payment')->error("=== IYZICO CALLBACK: TOKEN MISSING ===", [
                'query' => $request->query(),
                'post'  => $request->post(),
                'ip'    => $request->ip(),
            ]);

            return redirect()->route('checkout.result')->with([
                'status'  => 'error',
                'message' => 'Geçersiz ödeme isteği (Ödeme sağlayıcı tokenı bulunamadı).'
            ]);
        }

        try {
            $payment = $this->iyzico->retrieveCheckoutForm($token);

            // 5-Tier Robust Order Lookup Strategy
            $order = null;

            // Tier 1: Order ID directly from callback URL param
            if (!empty($orderIdParam)) {
                $order = Order::find($orderIdParam);
            }

            // Tier 2: Conversation ID from Iyzico
            if (!$order && $payment && !empty($payment->getConversationId())) {
                $convId = preg_replace('/[^0-9]/', '', (string)$payment->getConversationId());
                if (!empty($convId)) {
                    $order = Order::find($convId);
                }
            }

            // Tier 3: Basket ID from Iyzico (e.g. B123 -> 123)
            if (!$order && $payment && !empty($payment->getBasketId())) {
                $basketId = preg_replace('/[^0-9]/', '', (string)$payment->getBasketId());
                if (!empty($basketId)) {
                    $order = Order::find($basketId);
                }
            }

            // Tier 4: Session pending_order_id
            if (!$order && session()->has('pending_order_id')) {
                $order = Order::find(session()->get('pending_order_id'));
            }

            // Tier 5: Authenticated user latest pending card order
            if (!$order && auth()->check()) {
                $order = Order::where('user_id', auth()->id())
                    ->where('payment_id', 'like', 'CARD_%')
                    ->whereIn('status', ['pending', 'failed'])
                    ->latest()
                    ->first();
            }

            if (!$order) {
                \Illuminate\Support\Facades\Log::channel('payment')->error("=== IYZICO CALLBACK: ORDER NOT FOUND ===", [
                    'order_id_param'  => $orderIdParam,
                    'conversation_id' => $payment ? $payment->getConversationId() : null,
                    'basket_id'       => $payment ? $payment->getBasketId() : null,
                    'token'           => $token,
                ]);

                return redirect()->route('checkout.result')->with([
                    'status'  => 'error',
                    'message' => 'Sipariş kaydı bulunamadı (Referans: #' . ($orderIdParam ?: ($payment ? $payment->getConversationId() : 'Sistem')) . ').'
                ]);
            }

            // Restore user login session if cross-site Iyzico POST stripped session cookie
            if ($order->user_id && !auth()->check()) {
                \Illuminate\Support\Facades\Auth::loginUsingId($order->user_id);
            }

            // Robust Payment Validation (Both API status and payment status must indicate success)
            $rawStatus = strtolower(trim($payment ? ($payment->getStatus() ?? '') : ''));
            $rawPayStat = strtoupper(trim($payment ? ($payment->getPaymentStatus() ?? '') : ''));

            $isSuccess = $payment &&
                ($rawStatus === 'success') &&
                ($rawPayStat === 'SUCCESS');

            if ($isSuccess) {
                // Calculate merchant payout total from items if available
                $merchantPayout = 0;
                if ($payment->getPaymentItems()) {
                    foreach ($payment->getPaymentItems() as $pItem) {
                        $merchantPayout += (float)($pItem->getMerchantPayoutAmount() ?? 0);
                    }
                }
                $paidPrice = (float)($payment->getPaidPrice() ?: $order->total_amount);
                if ($merchantPayout <= 0) {
                    $merchantPayout = $paidPrice;
                }

                $paymentId = $payment->getPaymentId() ?: ('IYZ_' . time());

                // Update order status to paid with financial details
                $order->update([
                    'status'                 => 'paid',
                    'payment_id'             => $paymentId,
                    'paid_price'             => $paidPrice,
                    'installment'            => (int)($payment->getInstallment() ?: 1),
                    'merchant_payout_amount' => round($merchantPayout, 2),
                    'card_family'            => $payment->getCardFamily(),
                    'card_last_four'         => $payment->getLastFourDigits(),
                    'payment_error_reason'   => null,
                ]);

                \Illuminate\Support\Facades\Log::channel('payment')->info("=== ODEME BASARILI [Order #{$order->id}] ===", [
                    'order_id'        => $order->id,
                    'payment_id'      => $paymentId,
                    'paid_price'      => $paidPrice,
                    'total_amount'    => $order->total_amount,
                    'installment'     => $payment->getInstallment(),
                    'card_family'     => $payment->getCardFamily(),
                    'card_last4'      => $payment->getLastFourDigits(),
                    'merchant_payout' => round($merchantPayout, 2),
                ]);

                // Decrement stock for each item
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->decrement('stock', $item->quantity);
                    }
                }

                // Dispatch queued notification job for admin (email & SMS) and customer
                try {
                    SendNewOrderNotificationJob::dispatch($order->id);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('SendNewOrderNotificationJob Iyzico dispatch error: ' . $e->getMessage());
                }

                // Clear Cart Session ONLY on successful payment
                session()->forget('cart');
                session()->forget('pending_order_id');

                return redirect()->route('checkout.result')->with([
                    'status'   => 'success',
                    'order_id' => $order->id,
                    'is_eft'   => false,
                ]);
            } else {
                $rawMsg = $payment ? ($payment->getErrorMessage() ?: $payment->getErrorGroup()) : null;
                if (empty($rawMsg)) {
                    $rawMsg = '3D Güvenlik doğrulaması kart sahibi tarafından iptal edildi veya tamamlanamadı.';
                }
                $errorMessage = str_starts_with($rawMsg, 'Banka Yanıtı :') ? $rawMsg : 'Banka Yanıtı : ' . $rawMsg;

                // Ensure cart session is NEVER lost on payment failure by restoring cart items from order
                if ($order && $order->items->count() > 0) {
                    $restoredCart = [];
                    foreach ($order->items as $item) {
                        $features = is_array($item->features) ? $item->features : (json_decode($item->features, true) ?: []);
                        $fImg = $features['front_image'] ?? ($features['custom_image'] ?? null);
                        $bImg = $features['back_image'] ?? null;
                        $preview = $features['custom_preview'] ?? null;
                        
                        $uniqueSeed = ($fImg ?: '') . ($bImg ?: '') . ($preview ?: '');
                        $cartKey = $item->product_id . ($uniqueSeed ? '_' . md5($uniqueSeed) : '');
                        
                        $restoredCart[$cartKey] = [
                            'product_id'          => $item->product_id,
                            'name'                => $item->product ? $item->product->name : 'Ahşap Ürün',
                            'price'               => $item->price,
                            'quantity'            => $item->quantity,
                            'image'               => $preview ? url($preview) : ($fImg ? url($fImg) : ($item->product ? $item->product->image : null)),
                            'custom_image_front'  => $fImg ? url($fImg) : null,
                            'custom_image_back'   => $bImg ? url($bImg) : null,
                            'custom_image'        => $fImg ? url($fImg) : null,
                            'custom_preview'      => $preview ? url($preview) : null,
                            'is_gift'             => $features['is_gift'] ?? false,
                            'gift_note'           => $features['gift_note'] ?? null,
                        ];
                    }
                    if (!empty($restoredCart)) {
                        session()->put('cart', $restoredCart);
                    }
                }

                \Illuminate\Support\Facades\Log::channel('payment')->error("=== ODEME BASARISIZ [Order #{$order->id}] ===", [
                    'order_id'     => $order->id,
                    'error_msg'    => $errorMessage,
                    'raw_status'   => $payment ? $payment->getStatus() : null,
                    'raw_pay_stat' => $payment ? $payment->getPaymentStatus() : null,
                    'error_code'   => $payment ? $payment->getErrorCode() : null,
                    'raw_result'   => $payment ? $payment->getRawResult() : null,
                ]);

                // Update order status to failed with failure reason
                $order->update([
                    'status'               => 'failed',
                    'payment_id'           => ($payment && $payment->getPaymentId()) ? $payment->getPaymentId() : ('CARD_FAILED_' . time()),
                    'payment_error_reason' => $errorMessage,
                ]);

                return redirect()->route('checkout.result')->with([
                    'status'   => 'error',
                    'message'  => $errorMessage,
                    'order_id' => $order->id,
                    'is_eft'   => false,
                ]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::channel('payment')->error('=== CALLBACK EXCEPTION ===', [
                'exception' => $e->getMessage(),
                'file'      => $e->getFile() . ':' . $e->getLine(),
            ]);
            \Illuminate\Support\Facades\Log::error('Ödeme Callback Hatası: ' . $e->getMessage());
            return redirect()->route('checkout.result')->with([
                'status'  => 'error',
                'message' => 'Ödeme doğrulama sırasında bir sistem hatası oluştu: ' . $e->getMessage()
            ]);
        }
    }

    public function result()
    {
        $status = session('status');
        if (empty($status)) {
            return redirect()->route('cart.index');
        }

        return view('checkout.result');
    }

    private function isValidTcNo($tc)
    {
        $tc = preg_replace('/[^0-9]/', '', $tc);
        if (strlen($tc) !== 11 || $tc[0] === '0') {
            return false;
        }

        $digits = array_map('intval', str_split($tc));

        $oddSum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8];
        $evenSum = $digits[1] + $digits[3] + $digits[5] + $digits[7];

        $d10 = (($oddSum * 7) - $evenSum) % 10;
        if ($d10 < 0) $d10 += 10;
        if ($d10 !== $digits[9]) {
            return false;
        }

        $totalSum = array_sum(array_slice($digits, 0, 10));
        if (($totalSum % 10) !== $digits[10]) {
            return false;
        }

        return true;
    }

    protected function formatOrderItemsHtml($order)
    {
        $html = '<table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px;">';
        $html .= '<thead><tr style="background-color: #F5F2EB; text-align: left; color: #666;"><th style="padding: 8px; border-bottom: 1px solid #EFEAE0;">Ürün</th><th style="padding: 8px; text-align: center; border-bottom: 1px solid #EFEAE0;">Adet</th><th style="padding: 8px; text-align: right; border-bottom: 1px solid #EFEAE0;">Fiyat</th></tr></thead>';
        $html .= '<tbody>';

        foreach ($order->items as $item) {
            $pName = e($item->product ? $item->product->name : 'Ahşap Ürün');
            $qty = intval($item->quantity);
            $price = number_format($item->price * $qty, 2, ',', '.');

            $giftHtml = '';
            if (!empty($item->features['is_gift']) || !empty($item->features['gift_note'])) {
                $gNote = e($item->features['gift_note'] ?? 'Hediye Paketi');
                $giftHtml = "<br><span style=\"color: #C87A53; font-size: 11px; font-weight: bold;\">🎁 Hediye Notu: {$gNote}</span>";
            }

            $html .= "<tr><td style=\"padding: 8px; border-bottom: 1px solid #EFEAE0;\">{$pName}{$giftHtml}</td><td style=\"padding: 8px; text-align: center; border-bottom: 1px solid #EFEAE0;\">{$qty}</td><td style=\"padding: 8px; text-align: right; border-bottom: 1px solid #EFEAE0;\">₺{$price}</td></tr>";
        }

        $html .= '</tbody></table>';
        return $html;
    }
}
