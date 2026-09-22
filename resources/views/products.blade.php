@extends('layouts.app')

@section('title', 'El Yapımı Masif Ahşap Ürünler — AhşapEvim Manisa')
@section('meta_description', 'Manisa atölyemizde üretilen kişiye özel dönen çerçeveler, sokak lambalı ahşap modeller ve hediyelik tasarımları keşfedin.')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-8 pb-16">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-stone-500 font-medium">
        <a href="{{ url('/') }}" class="hover:text-brand transition">Anasayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-stone-400"></i>
        <span class="text-wood-dark font-bold">Tüm Ahşap Ürünler</span>
    </nav>

    <!-- Page Header & Banner -->
    <div class="artisan-card p-6 sm:p-8 bg-gradient-to-r from-brand-light via-wood-warm to-stone-100 border border-[#EAE3D9] flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand/10 text-brand text-xs font-bold mb-2">
                <i class="fa-solid fa-tree"></i>
                <span>MANİSA EL İŞÇİLİĞİ KOLEKSİYONU</span>
            </div>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-wood-dark">
                Kişiye Özel Masif Ahşap Çerçeveler
            </h1>
            <p class="text-xs sm:text-sm text-stone-600 mt-1.5 max-w-xl">
                Doğal masif ağaçtan üretilen 360° dönebilen ve sokak lambası aydınlatmalı çerçeve modellerimizi inceleyin. Sipariş adımında fotoğrafınızı yükleyerek anında kişiselleştirin.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <div class="px-4 py-3 bg-white rounded-2xl border border-stone-200 shadow-xs text-center">
                <span class="block text-xs font-bold text-stone-400 uppercase">Toplam</span>
                <span class="text-lg font-bold text-wood-dark font-mono">12 Model</span>
            </div>
            <div class="px-4 py-3 bg-white rounded-2xl border border-stone-200 shadow-xs text-center">
                <span class="block text-xs font-bold text-emerald-600 uppercase">Kargo</span>
                <span class="text-lg font-bold text-emerald-700 font-mono">Bedava</span>
            </div>
        </div>
    </div>

    <!-- Filter Bar & Sorting -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-stone-200 shadow-xs">
        
        <!-- Category Filter Chips -->
        <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto scrollbar-none pb-1 sm:pb-0">
            <a href="{{ url('/urunler') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !request('category') ? 'bg-brand text-white shadow-sm' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
                Tüm Ürünler
            </a>
            <a href="{{ url('/urunler?category=donen-cerceve') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('category') == 'donen-cerceve' ? 'bg-brand text-white shadow-sm' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
                Dönen Çerçeveler
            </a>
            <a href="{{ url('/urunler?category=isikli-panolar') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('category') == 'isikli-panolar' ? 'bg-brand text-white shadow-sm' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
                Sokak Lambalı Modeller
            </a>
            <a href="{{ url('/urunler?category=hediyelik') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('category') == 'hediyelik' ? 'bg-brand text-white shadow-sm' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
                Hediye Setleri
            </a>
        </div>

        <!-- Sorting Selector -->
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <span class="text-xs font-bold text-stone-500 whitespace-nowrap">Sırala:</span>
            <select class="bg-stone-100 border border-stone-200 text-wood-dark text-xs font-bold rounded-xl px-3 py-2 outline-none focus:border-brand cursor-pointer">
                <option value="popular">Çok Satanlar</option>
                <option value="price_asc">Fiyat: Düşükten Yükseğe</option>
                <option value="price_desc">Fiyat: Yüksekten Düşüğe</option>
                <option value="newest">En Yeniler</option>
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        <!-- Product 1: Sokak Lambalı Dönen Çerçeve (Ana Ürün) -->
        <div class="artisan-card overflow-hidden flex flex-col justify-between group relative">
            <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                <span class="bg-brand text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                    Çift Yüzlü / 2 Fotoğraf
                </span>
                <span class="bg-emerald-700 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                    Aynı Gün Kargo
                </span>
            </div>

            <button type="button" onclick="showToast('Favorilerinize eklendi!', 'info')" class="absolute top-3 right-3 z-10 w-8 h-8 rounded-xl bg-white/90 hover:bg-white text-stone-600 hover:text-rose-600 flex items-center justify-center transition shadow-md">
                <i class="fa-regular fa-heart text-xs"></i>
            </button>

            <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}" class="block aspect-4/3 bg-stone-100 overflow-hidden relative">
                <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=600&auto=format&fit=crop&q=80" 
                     alt="Kişiye Özel El Yapımı Ahşap Dönen Çerçeve Yeni Sokak Lambalı" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </a>

            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[10px] font-bold text-stone-600 uppercase tracking-wider block">Dönen Çerçeveler</span>
                    <h3 class="font-serif font-bold text-sm text-wood-dark mt-1 group-hover:text-brand transition leading-snug">
                        <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}">
                            Kişiye Özel El Yapımı Ahşap Dönen Çerçeve Yeni Sokak Lambalı
                        </a>
                    </h3>
                </div>

                <div class="pt-2 border-t border-stone-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-stone-600 line-through font-mono block">₺1.299,00</span>
                        <span class="text-base font-mono font-extrabold text-brand">₺999,00</span>
                    </div>

                    <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}" class="px-3 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1">
                        <i class="fa-solid fa-camera text-[11px]"></i>
                        <span>Kişiselleştir</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Product 2: Masif Masaüstü Stand -->
        <div class="artisan-card overflow-hidden flex flex-col justify-between group relative">
            <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                <span class="bg-wood-dark text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                    Masif Doğal Çam
                </span>
            </div>

            <button type="button" onclick="showToast('Favorilerinize eklendi!', 'info')" class="absolute top-3 right-3 z-10 w-8 h-8 rounded-xl bg-white/90 hover:bg-white text-stone-600 hover:text-rose-600 flex items-center justify-center transition shadow-md">
                <i class="fa-regular fa-heart text-xs"></i>
            </button>

            <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}" class="block aspect-4/3 bg-stone-100 overflow-hidden relative">
                <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?w=600&auto=format&fit=crop&q=80" 
                     alt="Masif Ahşap Masa Üstü Dönen Hatıra Çerçevesi" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </a>

            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[10px] font-bold text-stone-600 uppercase tracking-wider block">Masaüstü Stand</span>
                    <h3 class="font-serif font-bold text-sm text-wood-dark mt-1 group-hover:text-brand transition leading-snug">
                        <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}">
                            Masif Ahşap Masa Üstü Dönen Hatıra Çerçevesi
                        </a>
                    </h3>
                </div>

                <div class="pt-2 border-t border-stone-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-stone-600 line-through font-mono block">₺1.050,00</span>
                        <span class="text-base font-mono font-extrabold text-brand">₺849,00</span>
                    </div>

                    <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}" class="px-3 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1">
                        <i class="fa-solid fa-camera text-[11px]"></i>
                        <span>Kişiselleştir</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Product 3: Hediye Seti -->
        <div class="artisan-card overflow-hidden flex flex-col justify-between group relative">
            <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                <span class="bg-amber-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                    Özel Ahşap Kutulu
                </span>
            </div>

            <button type="button" onclick="showToast('Favorilerinize eklendi!', 'info')" class="absolute top-3 right-3 z-10 w-8 h-8 rounded-xl bg-white/90 hover:bg-white text-stone-600 hover:text-rose-600 flex items-center justify-center transition shadow-md">
                <i class="fa-regular fa-heart text-xs"></i>
            </button>

            <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}" class="block aspect-4/3 bg-stone-100 overflow-hidden relative">
                <img src="https://images.unsplash.com/photo-1534349762230-e0cadf78f5da?w=600&auto=format&fit=crop&q=80" 
                     alt="Nostaljik Sokak Lambalı Premium Ahşap Hediye Seti" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </a>

            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[10px] font-bold text-stone-600 uppercase tracking-wider block">Hediye Setleri</span>
                    <h3 class="font-serif font-bold text-sm text-wood-dark mt-1 group-hover:text-brand transition leading-snug">
                        <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}">
                            Nostaljik Sokak Lambalı Premium Ahşap Hediye Seti
                        </a>
                    </h3>
                </div>

                <div class="pt-2 border-t border-stone-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-stone-600 line-through font-mono block">₺1.450,00</span>
                        <span class="text-base font-mono font-extrabold text-brand">₺1.199,00</span>
                    </div>

                    <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}" class="px-3 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1">
                        <i class="fa-solid fa-camera text-[11px]"></i>
                        <span>Kişiselleştir</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Product 4: Ceviz Masif Çerçeve -->
        <div class="artisan-card overflow-hidden flex flex-col justify-between group relative">
            <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                <span class="bg-wood-dark text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                    Koyu Ceviz Tonu
                </span>
            </div>

            <button type="button" onclick="showToast('Favorilerinize eklendi!', 'info')" class="absolute top-3 right-3 z-10 w-8 h-8 rounded-xl bg-white/90 hover:bg-white text-stone-600 hover:text-rose-600 flex items-center justify-center transition shadow-md">
                <i class="fa-regular fa-heart text-xs"></i>
            </button>

            <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}" class="block aspect-4/3 bg-stone-100 overflow-hidden relative">
                <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=600&auto=format&fit=crop&q=80" 
                     alt="Masif Koyu Ceviz Dönen Çerçeve" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </a>

            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[10px] font-bold text-stone-600 uppercase tracking-wider block">Dönen Çerçeveler</span>
                    <h3 class="font-serif font-bold text-sm text-wood-dark mt-1 group-hover:text-brand transition leading-snug">
                        <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}">
                            Masif Koyu Ceviz Dönen Ahşap Çerçeve
                        </a>
                    </h3>
                </div>

                <div class="pt-2 border-t border-stone-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-stone-600 line-through font-mono block">₺1.150,00</span>
                        <span class="text-base font-mono font-extrabold text-brand">₺899,00</span>
                    </div>

                    <a href="{{ url('/urun/kisiye-ozel-el-yapimi-ahsap-donen-cerceve-yeni-sokak-lambali-2') }}" class="px-3 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1">
                        <i class="fa-solid fa-camera text-[11px]"></i>
                        <span>Kişiselleştir</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
