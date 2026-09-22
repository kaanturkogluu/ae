@extends('layouts.app')

@section('title', 'Sipariş & Kargo Takibi — AhşapEvim Manisa')
@section('meta_description', 'Kişiye özel ahşap siparişinizin atölye üretim ve Yurtiçi Kargo aşamalarını canlı olarak sorgulayın.')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-8 pb-16 max-w-4xl">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-stone-500 font-medium">
        <a href="{{ url('/') }}" class="hover:text-brand transition">Anasayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-stone-400"></i>
        <span class="text-wood-dark font-bold">Sipariş & Kargo Takibi</span>
    </nav>

    <!-- Page Header & Search Input -->
    <div class="artisan-card p-6 sm:p-8 bg-white space-y-5 border-2 border-stone-200">
        <div class="text-center max-w-lg mx-auto">
            <div class="w-12 h-12 rounded-2xl bg-brand-light text-brand flex items-center justify-center text-xl mx-auto mb-3">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-wood-dark">Siparişinizin Durumunu Öğrenin</h1>
            <p class="text-xs sm:text-sm text-stone-500 mt-1">
                Sipariş numaranızı (<code class="font-mono font-bold text-brand">AHS-XXXXXX</code>) veya telefon numaranızı girerek atölye üretim ve kargo aşamalarını takip edin.
            </p>
        </div>

        <div class="max-w-md mx-auto flex gap-2">
            <input type="text" id="trackInput" value="AHS-849201" placeholder="AHS-849201 veya 05XX..." class="w-full text-xs p-3 rounded-xl border border-stone-300 bg-stone-50 outline-none focus:border-brand font-mono uppercase font-bold">
            <button type="button" onclick="showToast('Sipariş durumu güncellendi!', 'success')" class="px-6 py-3 bg-brand hover:bg-brand-dark text-white text-xs font-bold rounded-xl transition shrink-0 shadow-md">
                Sorgula
            </button>
        </div>
    </div>

    <!-- Active Tracking Timeline View -->
    <div class="artisan-card p-6 sm:p-8 bg-white space-y-8 border border-stone-200">
        
        <!-- Header Info -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-stone-200">
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Sipariş Kodu</span>
                <div class="font-mono text-xl font-bold text-wood-dark">#AHS-849201</div>
                <div class="text-xs text-stone-500 mt-0.5">Sipariş Tarihi: {{ date('d.m.Y — 14:20') }}</div>
            </div>

            <div class="text-left sm:text-right">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span>Kargoya Verildi</span>
                </span>
                <div class="text-xs font-mono text-stone-500 mt-1">Yurtiçi Kargo: <strong>YK-984210582</strong></div>
            </div>
        </div>

        <!-- 4-Step Visual Progress Bar -->
        <div class="relative py-4">
            <!-- Line Background -->
            <div class="absolute top-1/2 left-0 right-0 -translate-y-1/2 h-1.5 bg-stone-200 z-0"></div>
            <!-- Active Line Fill (75% completed) -->
            <div class="absolute top-1/2 left-0 w-3/4 -translate-y-1/2 h-1.5 bg-brand z-0 transition-all duration-700"></div>

            <div class="grid grid-cols-4 gap-2 relative z-10 text-center">
                <!-- Step 1 -->
                <div class="flex flex-col items-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-brand text-white flex items-center justify-center text-sm font-bold shadow-md">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-wood-dark">Sipariş Alındı</div>
                        <div class="text-[10px] text-stone-400">14:20</div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex flex-col items-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-brand text-white flex items-center justify-center text-sm font-bold shadow-md">
                        <i class="fa-solid fa-hammer"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-wood-dark">Atölyede Baskıda</div>
                        <div class="text-[10px] text-stone-400">15:10</div>
                    </div>
                </div>

                <!-- Step 3 (Current Active) -->
                <div class="flex flex-col items-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-brand ring-4 ring-brand/30 text-white flex items-center justify-center text-sm font-bold shadow-md animate-bounce">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-brand">Kargoya Verildi</div>
                        <div class="text-[10px] text-emerald-600 font-bold">16:45 (Yurtiçi)</div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex flex-col items-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-stone-200 text-stone-400 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-house-chimney"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-stone-400">Teslim Edildi</div>
                        <div class="text-[10px] text-stone-400">Tahmini Yarın</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Yurtiçi Kargo Canlı Entegrasyon Kartı -->
        <div class="p-4 bg-wood-warm rounded-2xl border border-stone-200 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-orange-500 text-white flex items-center justify-center font-black text-xs">
                        YK
                    </div>
                    <div>
                        <span class="text-xs font-bold text-wood-dark">Yurtiçi Kargo Canlı Takip</span>
                        <span class="text-[10px] text-stone-500 block">Takip Kodu: YK-984210582</span>
                    </div>
                </div>
                <a href="https://www.yurticikargo.com" target="_blank" class="text-xs font-bold text-brand hover:underline flex items-center gap-1">
                    <span>Kargo Sayfasına Git</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>

            <div class="text-xs text-stone-600 bg-white p-3 rounded-xl border border-stone-200/80">
                <strong>Son Hareket:</strong> Gönderi Manisa Şehzadeler Transfer Merkezinden yola çıkmıştır. (Hedef: İzmir / Konak Dağıtım Merkezi)
            </div>
        </div>

        <!-- Siparişteki Ürünler & Yüklenen Fotoğraflar -->
        <div class="space-y-3 pt-2">
            <h3 class="font-serif font-bold text-sm text-wood-dark">Siparişteki Ahşap Ürünler ve Fotoğraflar</h3>
            
            <div class="p-4 rounded-xl bg-white border border-stone-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-16 h-20 rounded-xl bg-stone-100 overflow-hidden border border-stone-200 shrink-0">
                        <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-wood-dark">Kişiye Özel El Yapımı Ahşap Dönen Çerçeve Yeni Sokak Lambalı</h4>
                        <div class="text-[11px] text-stone-500 mt-0.5">23x21 cm • Masif Çam • 2 Fotoğraf Basılı</div>
                        <div class="text-xs font-bold text-brand font-mono mt-1">1 Adet • ₺999,00</div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold bg-amber-100 text-amber-900 px-2.5 py-1 rounded-lg">
                        🎁 Hediye Paketi Dahil
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
