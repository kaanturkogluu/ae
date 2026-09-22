<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\HomeBannerController as AdminHomeBannerController;
use App\Http\Controllers\Admin\ShippingCompanyController as AdminShippingCompanyController;
use App\Http\Controllers\Admin\MessageLogController as AdminMessageLogController;

// ─── Shared Search Helper ─────────────────────────────────────────────────────
function applyProductSearch($query, string $search): void
{
    $keywords = array_filter(explode(' ', $search), fn($w) => mb_strlen($w) >= 2);

    $query->where(function ($q) use ($search, $keywords) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%")
          ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$search}%"));

        foreach ($keywords as $word) {
            $q->orWhere('name', 'like', "%{$word}%")
              ->orWhere('description', 'like', "%{$word}%")
              ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$word}%"));
        }
    });
}

// ─── Frontend Anasayfa & Ürün Rotaları ─────────────────────────────────────────
Route::get('/', function (Request $request) {
    $query = Product::where('is_active', true)->with('category');

    if (request('category')) {
        $slug = request('category');
        $query->whereHas('category', fn($q) => $q->where('slug', $slug));
    }

    $search = trim(request('q') ?: request('search', ''));
    if ($search !== '') {
        applyProductSearch($query, $search);
    }

    $products   = $query->ordered()->get();
    $categories = Category::all();
    $homeBanners = collect();

    return view('home', compact('products', 'categories', 'homeBanners'));
})->name('home');

// Canlı Arama (AJAX)
Route::get('/canli-arama', function (Request $request) {
    $q = trim($request->input('q', ''));
    if (mb_strlen($q) < 2) {
        return response()->json(['status' => 'success', 'products' => []]);
    }

    $query = Product::where('is_active', true)->with('category');
    applyProductSearch($query, $q);
    $products = $query->take(6)->get();

    $data = $products->map(fn($p) => [
        'id'            => $p->id,
        'name'          => $p->name,
        'category_name' => $p->category ? $p->category->name : 'Ahşap Ürün',
        'price'         => number_format($p->price, 2, ',', '.') . ' ₺',
        'image'         => url($p->image ?: '/cerceve.png'),
        'url'           => $p->url,
    ]);

    return response()->json(['status' => 'success', 'products' => $data, 'count' => count($data)]);
})->name('search.live');

// Tüm Ürünler Kataloğu
Route::get('/urunler', function () {
    $query = Product::where('is_active', true)->with('category');

    if (request('category')) {
        $slug = request('category');
        $query->whereHas('category', fn($q) => $q->where('slug', $slug));
    }

    $search = trim(request('q') ?: request('search', ''));
    if ($search !== '') {
        applyProductSearch($query, $search);
    }

    $products   = $query->ordered()->get();
    $categories = Category::all();

    return view('products.index', compact('products', 'categories'));
})->name('products.index');

// Ürün Detay
Route::get('/urun/{id}', function ($id) {
    $product = Product::with('category')->where('id', $id)->orWhere('slug', $id)->firstOrFail();

    $similarProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('is_active', true)
        ->inRandomOrder()
        ->take(4)
        ->get();

    $recentlyViewedIds = session()->get('recently_viewed', []);
    $recentlyViewed    = collect();

    if (count($recentlyViewedIds) > 0) {
        $recentlyViewed = Product::whereIn('id', $recentlyViewedIds)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
    }

    if (!in_array($product->id, $recentlyViewedIds)) {
        array_unshift($recentlyViewedIds, $product->id);
        if (count($recentlyViewedIds) > 10) {
            array_pop($recentlyViewedIds);
        }
        session()->put('recently_viewed', $recentlyViewedIds);
    }

    return view('products.show', compact('product', 'similarProducts', 'recentlyViewed'));
})->name('product.show');

// ─── Müşteri Auth Rotaları ───────────────────────────────────────────────────
Route::get('/giris',                 [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/giris',                [AuthController::class, 'login'])->name('login.post')->middleware('throttle:10,1');
Route::get('/kayit',                 [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/kayit',                [AuthController::class, 'register'])->name('register.post')->middleware('throttle:5,1');
Route::get('/auth/google',           [AuthController::class, 'googleLogin']);
Route::post('/auth/google',          [AuthController::class, 'googleLogin'])->name('auth.google');
Route::get('/auth/google/callback',  [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/cikis',                [AuthController::class, 'logout'])->name('logout');

// ─── Müşteri Hesabı & Profil (Giriş Zorunlu) ──────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/hesabim',                       [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/hesabim/bilgiler',             [ProfileController::class, 'updateInfo'])->name('profile.updateInfo');
    Route::post('/hesabim/adres',                [ProfileController::class, 'updateAddress'])->name('profile.updateAddress');
    Route::post('/hesabim/sifre',                [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::put('/hesabim/siparisler/{id}/iptal', [ProfileController::class, 'cancelOrder'])->name('orders.cancel');
});

// ─── Favoriler (Favorites) ───────────────────────────────────────────────────
Route::get('/favoriler',             [FavoriteController::class, 'index'])->name('favorites.index');
Route::post('/favori-toggle',        [FavoriteController::class, 'toggle'])->name('favorites.toggle');
Route::get('/api/favoriler/liste',   [FavoriteController::class, 'getFavorites'])->name('favorites.list');

// ─── Sepet (Cart) ─────────────────────────────────────────────────────────────
Route::get('/sepet',                 [CartController::class, 'index'])->name('cart.index');
Route::get('/sepet/data',            [CartController::class, 'getCartData'])->name('cart.data');
Route::post('/sepet/ekle',           [CartController::class, 'add'])->name('cart.add');
Route::post('/sepet/guncelle',       [CartController::class, 'update'])->name('cart.update');
Route::post('/sepet/sil',            [CartController::class, 'remove'])->name('cart.remove');
Route::post('/sepet/temizle',        [CartController::class, 'clear'])->name('cart.clear');

// ─── Ödeme & Checkout ────────────────────────────────────────────────────────
Route::get('/odeme',                 [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/odeme',                [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/odeme/sonuc',           [CheckoutController::class, 'result'])->name('checkout.result');

// ─── Yönetici (Admin) Giriş & Çıkış ──────────────────────────────────────────
Route::get('/yonetim/giris',         [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/yonetim/giris',        [AdminLoginController::class, 'login'])->name('admin.login.post')->middleware('throttle:5,1');
Route::post('/yonetim/cikis',        [AdminLoginController::class, 'logout'])->name('admin.logout');
Route::get('/admin', fn() => redirect()->route('admin.revenue.index'));

// ─── Yönetici Paneli Rotaları (Admin Middleware Korumalı) ────────────────────
Route::prefix('yonetim')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', fn() => redirect()->route('admin.revenue.index'));
    Route::get('/gelir-tablosu', [RevenueController::class, 'index'])->name('admin.revenue.index');

    // Kategoriler
    Route::resource('kategoriler', AdminCategoryController::class)->except(['create', 'show', 'edit'])->names('admin.categories');

    // Ürünler
    Route::resource('urunler', AdminProductController::class)->parameters(['urunler' => 'product'])->names('admin.products');

    // Siparişler
    Route::resource('siparisler', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy'])->names('admin.orders');

    // Sayfalar & Afişler
    Route::resource('sayfalar', AdminPageController::class)->names('admin.pages');
    Route::resource('anasayfa-gorselleri', AdminHomeBannerController::class)->names('admin.banners');

    // Kargo Şirketleri
    Route::resource('kargo-sirketleri', AdminShippingCompanyController::class)->except(['create', 'show', 'edit'])->names('admin.shipping_companies');

    // Loglar
    Route::get('/loglar/mail',         [AdminMessageLogController::class, 'mailLogs'])->name('admin.mail_logs.index');
    Route::get('/loglar/sms',          [AdminMessageLogController::class, 'smsLogs'])->name('admin.sms_logs.index');
    Route::post('/manuel-mail-gonder', [AdminMessageLogController::class, 'sendManualMail'])->name('admin.mail.send_manual');
    Route::post('/manuel-sms-gonder',  [AdminMessageLogController::class, 'sendManualSms'])->name('admin.sms.send_manual');

    // Ayarlar
    Route::get('/ayarlar',             [AdminSettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/ayarlar',            [AdminSettingController::class, 'update'])->name('admin.settings.update');
});

// ─── Dinamik Sayfalar (Pages) ────────────────────────────────────────────────
Route::get('/{slug}', function ($slug) {
    $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();

    if ($slug === 'iletisim') {
        $contactData = json_decode($page->content, true);
        if (!is_array($contactData)) {
            $contactData = [
                'phone'                  => '0850 XXX XX XX',
                'whatsapp'               => '05XX XXX XX XX',
                'working_hours_weekdays' => '09:00 - 18:00',
                'working_hours_saturday' => '10:00 - 15:00',
                'address'                => "Şehzadeler Mevkii, Merkez\nManisa, Türkiye",
                'map_url'                => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d100001.32837311103!2d27.359288219030202!3d38.61867137839352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14b98d249f0322b7%3A0xc486be78a2e7c4f4!2sManisa%2C%20%C5%9Eehzadeler%2FManisa!5e0!3m2!1str!2str!4v1700000000000!5m2!1str!2str',
                'email'                  => 'info@ahsapevim.com',
                'note'                   => '',
            ];
        }
        return view('pages.contact', ['pageTitle' => $page->title, 'contactData' => $contactData, 'page' => $page]);
    }

    if ($slug === 'sikca-sorulanlar') {
        $faqItems = json_decode($page->content, true);
        if (!is_array($faqItems)) {
            $faqItems = [];
        }
        return view('pages.faq', ['pageTitle' => $page->title, 'faqItems' => $faqItems, 'rawContent' => $page->content, 'page' => $page]);
    }

    return view('pages.show', ['pageTitle' => $page->title, 'content' => $page->content]);
})->where('slug', '[a-zA-Z0-9_-]+')->name('pages.show');
