<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Ödeme & Sipariş Tamamlama Sayfası
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('info', 'Ödeme adımına geçmeden önce sepetinize ürün eklemelisiniz.');
        }

        $user = Auth::user();

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += floatval($item['price'] ?? 0) * intval($item['quantity'] ?? 1);
        }

        $freeShippingThreshold = 500;
        $shippingFee = $subtotal >= $freeShippingThreshold ? 0 : 49.90;
        $grandTotal = $subtotal + $shippingFee;

        return view('checkout.index', compact('cart', 'user', 'subtotal', 'shippingFee', 'grandTotal'));
    }

    /**
     * Sipariş İşleme (Havale/EFT & Kart Ödemesi)
     */
    public function process(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'city'           => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'address'        => 'required|string|max:500',
            'payment_method' => 'required|in:credit_card,bank_transfer',
            'order_note'     => 'nullable|string|max:1000',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += floatval($item['price'] ?? 0) * intval($item['quantity'] ?? 1);
        }

        $freeShippingThreshold = 500;
        $shippingFee = $subtotal >= $freeShippingThreshold ? 0 : 49.90;
        $grandTotal = $subtotal + $shippingFee;

        // DB Transaction ile Siparişi Kaydet
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id'        => Auth::id(),
                'order_number'   => 'AHS-' . strtoupper(uniqid()),
                'customer_name'  => $request->name,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'city'           => $request->city,
                'district'       => $request->district,
                'address'        => $request->address,
                'subtotal'       => $subtotal,
                'shipping_fee'   => $shippingFee,
                'total_amount'   => $grandTotal,
                'payment_method' => $request->payment_method,
                'status'         => 'pending',
                'order_note'     => $request->order_note,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item['product_id'] ?? null,
                    'product_name'       => $item['name'] ?? 'Ahşap Ürün',
                    'price'              => $item['price'] ?? 0,
                    'quantity'           => $item['quantity'] ?? 1,
                    'total'              => ($item['price'] ?? 0) * ($item['quantity'] ?? 1),
                    'custom_image'       => $item['custom_image'] ?? null,
                    'custom_image_front' => $item['custom_image_front'] ?? null,
                    'custom_image_back'  => $item['custom_image_back'] ?? null,
                    'custom_preview'     => $item['custom_preview'] ?? null,
                    'gift_note'          => $item['gift_note'] ?? null,
                    'is_gift'            => !empty($item['is_gift']),
                ]);
            }

            DB::commit();

            session()->forget('cart');
            session()->put('order_id', $order->id);
            session()->put('status', 'success');
            session()->put('is_eft', $request->payment_method === 'bank_transfer');

            return redirect()->route('checkout.result');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Sipariş oluşturulurken bir hata meydana geldi: ' . $e->getMessage());
        }
    }

    /**
     * Sipariş Sonuç Ekranı
     */
    public function result()
    {
        return view('checkout.result');
    }
}
