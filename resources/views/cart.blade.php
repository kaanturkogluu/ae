@extends('layouts.app')

@section('title', 'Alışveriş Sepetim — AhşapEvim Manisa')
@section('meta_description', 'Sepetinizdeki kişiselleştirilmiş masif ahşap ürünleri inceleyin ve güvenle siparişinizi tamamlayın.')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-8 pb-16">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-stone-500 font-medium">
        <a href="{{ url('/') }}" class="hover:text-brand transition">Anasayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-stone-400"></i>
        <span class="text-wood-dark font-bold">Alışveriş Sepeti</span>
    </nav>

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-wood-dark">Alışveriş Sepetiniz</h1>
            <p class="text-xs sm:text-sm text-stone-500 mt-1">Siparişinizi tamamlamak için ürünlerinizi kontrol ediniz.</p>
        </div>
        <a href="{{ url('/urunler') }}" class="text-xs font-bold text-brand hover:underline flex items-center gap-1">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Alışverişe Devam Et</span>
        </a>
    </div>

    <!-- Free Shipping Progress Bar -->
    <div class="artisan-card p-4 sm:p-5 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200">
        <div class="flex items-center justify-between text-xs font-bold text-amber-950 mb-2">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-truck-fast text-brand text-sm"></i>
                <span>Tebrikler! Bu siparişinizde <strong>Kargo Ücretsiz!</strong></span>
            </span>
            <span class="text-emerald-700 font-mono">%100</span>
        </div>
        <div class="w-full bg-amber-200/80 h-3 rounded-full overflow-hidden">
            <div class="bg-gradient-to-r from-brand to-emerald-500 h-full rounded-full w-full"></div>
        </div>
    </div>

    <!-- Main Grid: Items (8 cols) & Summary (4 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Cart Items List (8 cols) -->
        <div class="lg:col-span-8 space-y-4">
            
            <div class="artisan-card overflow-hidden bg-white">
                <div class="p-4 bg-wood-warm border-b border-stone-200 flex items-center justify-between text-xs font-bold text-stone-600">
                    <span>Ürün Detayı</span>
                    <span class="hidden sm:inline">Adet & Tutar</span>
                </div>

                <!-- Sample Item with Custom Uploaded Photos -->
                <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-stone-100" id="fullCartItem-1">
                    <div class="flex items-center gap-4">
                        <!-- Dual Photo Thumbnail View -->
                        <div class="w-20 h-24 rounded-2xl bg-stone-100 border border-stone-200 overflow-hidden shrink-0 relative group">
                            <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=200&auto=format&fit=crop&q=80" alt="Masif Çerçeve" class="w-full h-full object-cover">
                            <div class="absolute bottom-1 right-1 bg-wood-dark text-white text-[9px] font-mono px-1.5 py-0.5 rounded">
                                2 Foto
                            </div>
                        </div>

                        <div class="space-y-1 min-w-0">
                            <span class="text-[10px] font-bold text-stone-600 uppercase tracking-wider block">Kişiye Özel Seri</span>
                            <h3 class="font-serif font-bold text-sm sm:text-base text-wood-dark leading-snug">
                                Kişiye Özel El Yapımı Ahşap Dönen Çerçeve Yeni Sokak Lambalı
                            </h3>
                            <div class="flex flex-wrap gap-1.5 pt-1 text-[11px]">
                                <span class="bg-brand-light text-brand px-2 py-0.5 rounded-md font-medium">Ön & Arka Fotoğraf Basılı</span>
                                <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded-md font-medium">🎁 Özel Hediye Paketi</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-t-0 border-stone-100">
                        <!-- Qty Selector -->
                        <div class="flex items-center border border-stone-200 rounded-xl bg-stone-50 overflow-hidden">
                            <button type="button" onclick="updateFullCartQty(-1)" class="w-8 h-8 flex items-center justify-center text-stone-600 hover:bg-stone-200 font-bold transition">-</button>
                            <span class="w-9 text-center text-xs font-bold font-mono text-wood-dark" id="fullCartItemQty">1</span>
                            <button type="button" onclick="updateFullCartQty(1)" class="w-8 h-8 flex items-center justify-center text-stone-600 hover:bg-stone-200 font-bold transition">+</button>
                        </div>

                        <!-- Price -->
                        <div class="text-right">
                            <div class="text-base font-extrabold text-brand font-mono" id="fullCartItemPrice">₺999,00</div>
                            <div class="text-[10px] text-stone-600 line-through font-mono">₺1.299,00</div>
                        </div>

                        <!-- Remove -->
                        <button type="button" onclick="removeFullCartItem()" class="text-stone-600 hover:text-rose-600 p-2 transition" title="Sepetten Kaldır">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Kupon ve Satıcı Notu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="artisan-card p-4 bg-white space-y-2">
                    <label class="block text-xs font-bold text-wood-dark">İndirim Kuponu / Hediye Çeki</label>
                    <div class="flex gap-2">
                        <input type="text" placeholder="Kupon kodunuz..." class="w-full text-xs p-2.5 rounded-xl border border-stone-200 bg-stone-50 outline-none uppercase font-mono">
                        <button type="button" onclick="showToast('Kupon kodu uygulandı!', 'success')" class="px-4 py-2.5 bg-wood-dark hover:bg-black text-white text-xs font-bold rounded-xl shrink-0 transition">Uygula</button>
                    </div>
                </div>

                <div class="artisan-card p-4 bg-white space-y-2">
                    <label class="block text-xs font-bold text-wood-dark">Atölyeye Özel Sipariş Notu</label>
                    <textarea rows="2" placeholder="Ahşap işçiliği veya paketlemeyle ilgili notunuz..." class="w-full text-xs p-2 rounded-xl border border-stone-200 bg-stone-50 outline-none resize-none"></textarea>
                </div>
            </div>

        </div>

        <!-- Order Summary Sidebar (4 cols) -->
        <div class="lg:col-span-4">
            <div class="artisan-card p-6 bg-white space-y-5 sticky top-28 border-2 border-stone-200">
                <h3 class="font-serif font-bold text-lg text-wood-dark pb-3 border-b border-stone-200">
                    Sipariş Özeti
                </h3>

                <div class="space-y-3 text-xs text-stone-600">
                    <div class="flex justify-between">
                        <span>Ürünler Toplamı</span>
                        <span class="font-bold text-wood-dark font-mono" id="fullCartSubtotal">₺999,00</span>
                    </div>

                    <div class="flex justify-between text-emerald-700">
                        <span class="flex items-center gap-1"><i class="fa-solid fa-truck-fast"></i> Kargo Ücreti</span>
                        <span class="font-bold font-mono">ÜCRETSİZ</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Hediye Paketi & Kartı</span>
                        <span class="font-bold text-emerald-700 font-mono">Ücretsiz</span>
                    </div>

                    <div class="flex justify-between">
                        <span>KDV (%20 Dahil)</span>
                        <span class="font-mono text-stone-500">₺166,50</span>
                    </div>

                    <div class="flex justify-between text-base font-extrabold text-wood-dark pt-3 border-t border-stone-200">
                        <span>Toplam Tutar</span>
                        <span class="text-brand text-xl font-mono" id="fullCartTotal">₺999,00</span>
                    </div>
                </div>

                <a href="{{ url('/odeme') }}" class="w-full py-4 bg-brand hover:bg-brand-dark text-white font-bold text-sm rounded-2xl shadow-xl shadow-brand/25 hover:shadow-2xl transition-all transform active:scale-95 flex items-center justify-center gap-2">
                    <span>Ödemeye Geç</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>

                <div class="pt-2 text-center space-y-2">
                    <div class="text-[11px] text-stone-400 font-medium flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-lock text-emerald-600"></i>
                        <span>256-Bit SSL Güvenli 3D Ödeme</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    let fullQty = 1;
    const fullUnitPrice = 999;

    function updateFullCartQty(change) {
        fullQty = Math.max(1, fullQty + change);
        const qtyElem = document.getElementById('fullCartItemQty');
        const priceElem = document.getElementById('fullCartItemPrice');
        const subtotalElem = document.getElementById('fullCartSubtotal');
        const totalElem = document.getElementById('fullCartTotal');

        const total = fullQty * fullUnitPrice;
        const formatted = '₺' + total.toLocaleString('tr-TR', { minimumFractionDigits: 2 });

        if (qtyElem) qtyElem.textContent = fullQty;
        if (priceElem) priceElem.textContent = formatted;
        if (subtotalElem) subtotalElem.textContent = formatted;
        if (totalElem) totalElem.textContent = formatted;
    }

    function removeFullCartItem() {
        const item = document.getElementById('fullCartItem-1');
        if (item) {
            item.remove();
            showToast('Ürün sepetten kaldırıldı', 'info');
        }
    }
</script>
@endpush
