<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Müşteri Profil & Hesap Paneli
     */
    public function index()
    {
        $user = Auth::user();

        // Siparişler (Tablo varsa getir, yoksa boş koleksiyon dön)
        try {
            $orders = Order::where('user_id', $user->id)->latest()->get();
        } catch (\Throwable $e) {
            $orders = collect();
        }

        // Favoriler
        $favorites = Product::whereIn('id', function ($query) use ($user) {
            $query->select('product_id')
                ->from('favorites')
                ->where('user_id', $user->id);
        })
        ->with('category')
        ->where('is_active', true)
        ->ordered()
        ->get();

        return view('profile.index', compact('user', 'orders', 'favorites'));
    }

    /**
     * Temel Kullanıcı Bilgilerini Güncelle (Ad Soyad, Telefon)
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('profile.index', ['tab' => 'bilgiler'])
            ->with('success', 'Kullanıcı bilgileriniz başarıyla güncellendi.');
    }

    /**
     * Teslimat Adresi Bilgilerini Güncelle
     */
    public function updateAddress(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'city'     => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'address'  => 'nullable|string|max:500',
        ]);

        $user->update([
            'city'     => $request->city,
            'district' => $request->district,
            'address'  => $request->address,
        ]);

        return redirect()->route('profile.index', ['tab' => 'adres'])
            ->with('success', 'Adres bilgileriniz başarıyla güncellendi.');
    }

    /**
     * Şifre Değiştirme
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Eğer kullanıcı sadece Google ile giriş yapmış ve şifresi yoksa current_password zorunlu olmasın
        $rules = [
            'password' => ['required', 'confirmed', Password::min(6)],
        ];

        if (!empty($user->password)) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $request->validate($rules, [
            'current_password.current_password' => 'Mevcut şifreniz hatalı.',
            'password.confirmed'                => 'Yeni şifreleriniz birbiriyle uyuşmuyor.',
            'password.min'                      => 'Yeni şifreniz en az 6 karakter olmalıdır.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index', ['tab' => 'sifre'])
            ->with('success', 'Şifreniz başarıyla değiştirildi.');
    }

    /**
     * Sipariş İptal Talebi
     */
    public function cancelOrder(Request $request, $id)
    {
        try {
            $order = Order::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

            if (in_array($order->status, ['pending', 'processing', 'beklemede', 'hazirlaniyor'])) {
                $order->update(['status' => 'cancelled']);
                return redirect()->route('profile.index', ['tab' => 'siparisler'])
                    ->with('success', 'Siparişiniz başarıyla iptal edildi.');
            }

            return redirect()->route('profile.index', ['tab' => 'siparisler'])
                ->with('error', 'Bu sipariş kargoya verildiği veya tamamlandığı için iptal edilemez.');
        } catch (\Throwable $e) {
            return redirect()->route('profile.index', ['tab' => 'siparisler'])
                ->with('error', 'Sipariş bulunamadı.');
        }
    }
}
