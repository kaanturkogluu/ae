<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    /**
     * Sepet Sayfası
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    /**
     * Sepet Çekmecesi (Drawer) & AJAX Verisi
     */
    public function getCartData()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $count = 0;

        foreach ($cart as $item) {
            $price = floatval($item['price'] ?? 0);
            $qty   = intval($item['quantity'] ?? 1);
            $total += ($price * $qty);
            $count += $qty;
        }

        return response()->json([
            'status'    => 'success',
            'cart'      => $cart,
            'count'     => $count,
            'total'     => number_format($total, 2, ',', '.') . ' TL',
            'total_raw' => $total,
        ]);
    }

    /**
     * Sepete Ürün Ekleme (Özelleştirme ve Görsel Destekli)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id'          => 'required|exists:products,id',
            'quantity'            => 'nullable|integer|min:1|max:100',
            'custom_image'        => 'nullable|file|image|max:10240',
            'custom_image_front'  => 'nullable|file|image|max:10240',
            'custom_image_back'   => 'nullable|file|image|max:10240',
            'custom_preview'      => 'nullable|string',
            'gift_note'           => 'nullable|string|max:500',
            'is_gift'             => 'nullable|boolean',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int) ($request->input('quantity', 1));
        if ($quantity < 1) $quantity = 1;

        $disk = config('filesystems.default') === 'r2' ? 'r2' : (config('filesystems.disks.r2.key') ? 'r2' : 'public');

        // Yüklenen özel fotoğrafları işle
        $customImage = null;
        if ($request->hasFile('custom_image')) {
            $path = Storage::disk($disk)->putFile('cart/custom', $request->file('custom_image'));
            $customImage = $disk === 'r2' ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . $path : '/storage/' . $path;
        }

        $customImageFront = null;
        if ($request->hasFile('custom_image_front')) {
            $path = Storage::disk($disk)->putFile('cart/front', $request->file('custom_image_front'));
            $customImageFront = $disk === 'r2' ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . $path : '/storage/' . $path;
        }

        $customImageBack = null;
        if ($request->hasFile('custom_image_back')) {
            $path = Storage::disk($disk)->putFile('cart/back', $request->file('custom_image_back'));
            $customImageBack = $disk === 'r2' ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . $path : '/storage/' . $path;
        }

        $customPreview = $request->input('custom_preview');
        $giftNote = $request->input('gift_note');
        $isGift = $request->boolean('is_gift') || !empty($giftNote);

        $cart = session()->get('cart', []);

        // Benzersiz sepet kalemi anahtarı (özel fotoğraflı ürünler ayrı kalem olsun)
        $uniquePayload = [
            'p'  => $product->id,
            'ci' => $customImage,
            'cf' => $customImageFront,
            'cb' => $customImageBack,
            'gn' => $giftNote,
        ];
        $cartKey = $product->id . '_' . substr(md5(json_encode($uniquePayload)), 0, 8);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'key'                => $cartKey,
                'product_id'         => $product->id,
                'name'               => $product->name,
                'slug'               => $product->slug,
                'price'              => (float) $product->price,
                'original_price'     => (float) $product->original_price,
                'image'              => $product->main_image_url,
                'quantity'           => $quantity,
                'custom_image'       => $customImage,
                'custom_image_front' => $customImageFront,
                'custom_image_back'  => $customImageBack,
                'custom_preview'     => $customPreview,
                'is_gift'            => $isGift,
                'gift_note'          => $giftNote,
            ];
        }

        session()->put('cart', $cart);

        $totalCount = array_sum(array_column($cart, 'quantity'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Ürün sepete eklendi!',
                'count'   => $totalCount,
                'cart'    => $cart,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Ürün sepete eklendi.');
    }

    /**
     * Sepetteki Ürün Adedini Güncelleme
     */
    public function update(Request $request)
    {
        $request->validate([
            'key'      => 'required|string',
            'quantity' => 'required|integer|min:0|max:100',
        ]);

        $key = $request->input('key');
        $quantity = (int) $request->input('quantity');

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $quantity;
            }
            session()->put('cart', $cart);
        }

        $totalCount = array_sum(array_column($cart, 'quantity'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Sepet güncellendi.',
                'count'   => $totalCount,
                'cart'    => $cart,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Sepetiniz güncellendi.');
    }

    /**
     * Sepetten Ürün Çıkarma
     */
    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $key = $request->input('key');
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        $totalCount = array_sum(array_column($cart, 'quantity'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Ürün sepetten çıkarıldı.',
                'count'   => $totalCount,
                'cart'    => $cart,
            ]);
        }

        return redirect()->route('cart.index')->with('info', 'Ürün sepetten çıkarıldı.');
    }

    /**
     * Sepeti Tamamen Temizleme
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('info', 'Sepetiniz temizlendi.');
    }
}
