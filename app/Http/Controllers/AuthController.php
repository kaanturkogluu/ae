<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Giriş Yap Sayfası
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('profile.index');
        }
        return view('auth.login');
    }

    /**
     * E-posta ve Şifre ile Giriş İşlemi
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Misafir oturumundaki favorileri kullanıcı hesabına aktar
            $this->syncGuestFavorites(Auth::id());

            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.revenue.index'));
            }

            return redirect()->intended(route('profile.index'))->with('success', 'Hoş geldiniz, ' . Auth::user()->name);
        }

        return back()->withErrors([
            'email' => 'Girdiğiniz e-posta adresi veya şifre hatalı.',
        ])->onlyInput('email');
    }

    /**
     * Kayıt Ol Sayfası
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('profile.index');
        }
        return view('auth.register');
    }

    /**
     * Yeni Kullanıcı Kaydı
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.unique'       => 'Bu e-posta adresi zaten kayıtlı.',
            'password.min'       => 'Şifreniz en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifreleriniz birbiriyle uyuşmuyor.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $this->syncGuestFavorites($user->id);

        return redirect()->route('profile.index')->with('success', 'Hesabınız başarıyla oluşturuldu!');
    }

    /**
     * Google ile Giriş Yönlendirmesi
     */
    public function googleLogin()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google ile giriş şu an yapılandırılamadı: ' . $e->getMessage()]);
        }
    }

    /**
     * Google Giriş Geri Dönüşü (Callback)
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar() ?: $user->avatar,
                ]);
            } else {
                $user = User::create([
                    'name'      => $googleUser->getName() ?: 'Müşteri',
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    'password'  => null,
                    'is_admin'  => false,
                ]);
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            $this->syncGuestFavorites($user->id);

            return redirect()->route('profile.index')->with('success', 'Google ile başarıyla giriş yapıldı!');
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google girişi sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Çıkış Yap
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Başarıyla çıkış yaptınız.');
    }

    /**
     * Misafir Favorilerini Hesaba Eşitle
     */
    protected function syncGuestFavorites(int $userId): void
    {
        $guestFavs = session()->get('favorites', []);
        if (!empty($guestFavs) && is_array($guestFavs)) {
            foreach ($guestFavs as $productId) {
                Favorite::firstOrCreate([
                    'user_id'    => $userId,
                    'product_id' => (int) $productId,
                ]);
            }
            session()->forget('favorites');
        }
    }
}
