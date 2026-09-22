@extends('layouts.admin')

@section('title', 'Sipariş Yönetimi — AhşapEvim Atölye Paneli')
@section('page_title', 'Atölye Sipariş & Üretim Yönetimi')

@section('content')
<div class="space-y-6 pb-12">

    <!-- 1. KPI İstatistik Kartları -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Bugünkü Siparişler</span>
                <div class="text-2xl font-bold font-mono text-wood-dark mt-1">8 Adet</div>
                <span class="text-[11px] text-emerald-600 font-bold mt-1 inline-block">₺7.992,00 Ciro</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand/10 text-brand flex items-center justify-center text-xl">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Havale Onayı Bekleyen</span>
                <div class="text-2xl font-bold font-mono text-amber-600 mt-1">3 Adet</div>
                <span class="text-[11px] text-stone-500 font-medium mt-1 inline-block">Halkbank Doğrulama</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Kargolanacaklar</span>
                <div class="text-2xl font-bold font-mono text-wood-dark mt-1">5 Paket</div>
                <span class="text-[11px] text-brand font-bold mt-1 inline-block">Saat 16:00 Teslimi</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Aylık Toplam Hacim</span>
                <div class="text-2xl font-bold font-mono text-wood-dark mt-1">₺142.500</div>
                <span class="text-[11px] text-emerald-600 font-bold mt-1 inline-block">↑ %18 Artış</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-stone-100 text-stone-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
        </div>

    </div>

    <!-- 2. Filtreleme & Arama Çubuğu -->
    <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto scrollbar-none pb-1 sm:pb-0">
            <button type="button" class="px-3.5 py-1.5 rounded-xl bg-wood-dark text-white text-xs font-bold">Tüm Siparişler (8)</button>
            <button type="button" class="px-3.5 py-1.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold">Havale Bekleyenler (3)</button>
            <button type="button" class="px-3.5 py-1.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold">Atölyede Hazırlanan (2)</button>
            <button type="button" class="px-3.5 py-1.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold">Kargolandı (3)</button>
        </div>

        <div class="w-full sm:w-64 relative">
            <input type="text" placeholder="Sipariş no, müşteri veya tel..." class="w-full text-xs py-2 pl-9 pr-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand">
            <i class="fa-solid fa-search absolute left-3 top-2.5 text-stone-400 text-xs"></i>
        </div>
    </div>

    <!-- 3. Siparişler Tablosu -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-600">
                <thead class="bg-stone-50 text-stone-700 uppercase font-bold text-[10px] tracking-wider border-b border-stone-200">
                    <tr>
                        <th class="p-4">Sipariş No & Tarih</th>
                        <th class="p-4">Müşteri</th>
                        <th class="p-4">Yüklenen Fotoğraflar</th>
                        <th class="p-4">Ürün & Paket</th>
                        <th class="p-4">Tutar & Ödeme</th>
                        <th class="p-4">Durum</th>
                        <th class="p-4 text-right">Eylemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 font-medium">
                    
                    <!-- Satır 1: Hazırlanan Sipariş (Kart) -->
                    <tr class="hover:bg-brand-soft/50 transition">
                        <td class="p-4">
                            <span class="font-mono font-bold text-wood-dark block text-xs">#AHS-849201</span>
                            <span class="text-[10px] text-stone-400 block">{{ date('d.m.Y H:i') }}</span>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-wood-dark">Ahmet Yılmaz</div>
                            <div class="text-[10px] text-stone-400 font-mono">+90 (553) 289 06 90</div>
                            <div class="text-[10px] text-stone-500">Manisa / Şehzadeler</div>
                        </td>
                        <td class="p-4">
                            <!-- Dual photo badges -->
                            <div class="flex items-center gap-1.5">
                                <div class="w-9 h-11 rounded-lg bg-stone-200 overflow-hidden border border-stone-300 relative group cursor-pointer" onclick="openOrderDetailModal()">
                                    <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=100&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                                    <span class="absolute bottom-0 inset-x-0 bg-black/70 text-[7px] text-white text-center font-bold">ÖN</span>
                                </div>
                                <div class="w-9 h-11 rounded-lg bg-stone-200 overflow-hidden border border-stone-300 relative group cursor-pointer" onclick="openOrderDetailModal()">
                                    <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?w=100&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                                    <span class="absolute bottom-0 inset-x-0 bg-black/70 text-[7px] text-white text-center font-bold">ARKA</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-wood-dark truncate max-w-[200px]">Dönen Çerçeve Sokak Lambalı</div>
                            <div class="text-[10px] text-amber-700 font-bold">🎁 Hediye Paketi Dahil</div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold font-mono text-brand text-xs">₺999,00</div>
                            <span class="inline-block bg-emerald-100 text-emerald-800 text-[9px] font-bold px-1.5 py-0.5 rounded">
                                Kart (iyzico 3D)
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-900 text-[10px] font-bold px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-hammer text-[9px]"></i> Atölyede Hazırlanıyor
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <button type="button" onclick="openOrderDetailModal()" class="px-3 py-1.5 bg-stone-100 hover:bg-brand hover:text-white rounded-lg text-xs font-bold transition">
                                Detay & Etiket
                            </button>
                        </td>
                    </tr>

                    <!-- Satır 2: Havale Bekleyen Sipariş -->
                    <tr class="hover:bg-brand-soft/50 transition">
                        <td class="p-4">
                            <span class="font-mono font-bold text-wood-dark block text-xs">#AHS-849195</span>
                            <span class="text-[10px] text-stone-400 block">{{ date('d.m.Y H:i', strtotime('-1 hour')) }}</span>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-wood-dark">Merve Kaya</div>
                            <div class="text-[10px] text-stone-400 font-mono">+90 (542) 123 45 67</div>
                            <div class="text-[10px] text-stone-500">İzmir / Karşıyaka</div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-1.5">
                                <div class="w-9 h-11 rounded-lg bg-stone-200 overflow-hidden border border-stone-300 relative">
                                    <img src="https://images.unsplash.com/photo-1534349762230-e0cadf78f5da?w=100&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                                    <span class="absolute bottom-0 inset-x-0 bg-black/70 text-[7px] text-white text-center font-bold">ÖN</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-wood-dark truncate max-w-[200px]">Masaüstü Ahşap Stand</div>
                            <div class="text-[10px] text-stone-400">Standart Paket</div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold font-mono text-brand text-xs">₺849,00</div>
                            <span class="inline-block bg-amber-100 text-amber-900 text-[9px] font-bold px-1.5 py-0.5 rounded">
                                Halkbank Havale
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1 bg-amber-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-clock text-[9px]"></i> Havale Onayı Bekliyor
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <button type="button" onclick="showToast('Havale onaylandı!', 'success')" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition" title="Havale Onayla">
                                <i class="fa-solid fa-check"></i> Onayla
                            </button>
                            <button type="button" onclick="openOrderDetailModal()" class="px-3 py-1.5 bg-stone-100 hover:bg-brand hover:text-white rounded-lg text-xs font-bold transition">
                                Detay
                            </button>
                        </td>
                    </tr>

                    <!-- Satır 3: Kargolanan Sipariş -->
                    <tr class="hover:bg-brand-soft/50 transition">
                        <td class="p-4">
                            <span class="font-mono font-bold text-wood-dark block text-xs">#AHS-849180</span>
                            <span class="text-[10px] text-stone-400 block">{{ date('d.m.Y H:i', strtotime('-3 hours')) }}</span>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-wood-dark">Caner Erdem</div>
                            <div class="text-[10px] text-stone-400 font-mono">+90 (532) 987 65 43</div>
                            <div class="text-[10px] text-stone-500">İstanbul / Kadıköy</div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-1.5">
                                <div class="w-9 h-11 rounded-lg bg-stone-200 overflow-hidden border border-stone-300 relative">
                                    <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=100&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                                    <span class="absolute bottom-0 inset-x-0 bg-black/70 text-[7px] text-white text-center font-bold">2 FOTO</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-wood-dark truncate max-w-[200px]">Nostaljik Hediye Seti</div>
                            <div class="text-[10px] text-amber-700 font-bold">🎁 Ahşap Kutu & Not</div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold font-mono text-brand text-xs">₺1.199,00</div>
                            <span class="inline-block bg-emerald-100 text-emerald-800 text-[9px] font-bold px-1.5 py-0.5 rounded">
                                Kart (iyzico 3D)
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-truck-fast text-[9px]"></i> Yurtiçi Kargoda (YK-984210)
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <button type="button" onclick="openOrderDetailModal()" class="px-3 py-1.5 bg-stone-100 hover:bg-brand hover:text-white rounded-lg text-xs font-bold transition">
                                Detay & Etiket
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 4. Sipariş Detay & Kargo Etiketi Yazdırma Modalı -->
<div id="orderDetailModal" class="fixed inset-0 z-[99999] bg-black/80 hidden items-center justify-center p-4 backdrop-blur-xs no-print" onclick="closeOrderDetailModal(event)">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 shadow-2xl border border-stone-200 space-y-6" onclick="event.stopPropagation()">
        
        <div class="flex items-center justify-between pb-4 border-b border-stone-200">
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase">Sipariş Detayı</span>
                <h3 class="font-serif font-bold text-lg text-wood-dark">Sipariş #AHS-849201</h3>
            </div>
            <button type="button" onclick="closeOrderDetailModal()" class="w-8 h-8 rounded-full bg-stone-100 hover:bg-stone-200 flex items-center justify-center text-stone-500">
                &times;
            </button>
        </div>

        <!-- Müşteri ve Adres Bilgisi -->
        <div class="grid grid-cols-2 gap-4 text-xs">
            <div class="p-3 bg-stone-50 rounded-xl border border-stone-200">
                <span class="font-bold text-wood-dark block mb-1">Müşteri Bilgileri</span>
                <div>Ahmet Yılmaz</div>
                <div class="font-mono text-stone-500">+90 (553) 289 06 90</div>
                <div class="text-stone-500">ahmet.yilmaz@gmail.com</div>
                <div class="text-stone-400 font-mono mt-1">TC: 12345678901</div>
            </div>

            <div class="p-3 bg-stone-50 rounded-xl border border-stone-200">
                <span class="font-bold text-wood-dark block mb-1">Teslimat Adresi</span>
                <div>1. Anafartalar Mah. Dr. Sadık Ahmet Cad. No:14 Daire:3</div>
                <div class="font-bold text-wood-dark">Şehzadeler / Manisa</div>
                <div class="mt-1 text-amber-800 font-bold text-[11px]">🎁 Hediye Notu: "Nice mutlu yıllara birtanem!"</div>
            </div>
        </div>

        <!-- Yüklenen Yüksek Çözünürlüklü Fotoğraflar (HD Download) -->
        <div class="space-y-2">
            <h4 class="font-bold text-xs text-wood-dark">Basılacak Yüksek Çözünürlüklü Fotoğraflar (Atölye Baskı)</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=150&auto=format&fit=crop&q=80" class="w-12 h-14 rounded-lg object-cover border border-stone-300">
                        <div>
                            <div class="text-xs font-bold text-wood-dark">1. Fotoğraf (Ön Yüz)</div>
                            <span class="text-[10px] text-stone-400">10x15 cm • 300 DPI</span>
                        </div>
                    </div>
                    <a href="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=1600&auto=format&fit=crop&q=100" target="_blank" download class="px-3 py-1.5 bg-brand text-white text-xs font-bold rounded-lg shadow-sm">
                        HD İndir
                    </a>
                </div>

                <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?w=150&auto=format&fit=crop&q=80" class="w-12 h-14 rounded-lg object-cover border border-stone-300">
                        <div>
                            <div class="text-xs font-bold text-wood-dark">2. Fotoğraf (Arka Yüz)</div>
                            <span class="text-[10px] text-stone-400">10x15 cm • 300 DPI</span>
                        </div>
                    </div>
                    <a href="https://images.unsplash.com/photo-1544816155-12df9643f363?w=1600&auto=format&fit=crop&q=100" target="_blank" download class="px-3 py-1.5 bg-brand text-white text-xs font-bold rounded-lg shadow-sm">
                        HD İndir
                    </a>
                </div>
            </div>
        </div>

        <!-- Yazdırılabilir Yurtiçi Kargo Barkodlu Kargo Fişi -->
        <div id="shippingLabelArea" class="p-4 border-2 border-dashed border-stone-400 rounded-2xl bg-stone-50 space-y-3">
            <div class="flex items-center justify-between border-b border-stone-300 pb-2">
                <div class="flex items-center gap-2">
                    <span class="font-black text-sm text-wood-dark">YURTİÇİ KARGO</span>
                    <span class="text-xs font-mono bg-stone-200 px-2 py-0.5 rounded">GÖNDERİ ETİKETİ</span>
                </div>
                <span class="font-mono text-xs font-bold">TAKİP: YK-984210582</span>
            </div>

            <div class="grid grid-cols-2 gap-2 text-xs">
                <div>
                    <span class="text-[10px] font-bold text-stone-500 uppercase block">GÖNDERİCİ:</span>
                    <strong class="text-wood-dark">AhşapEvim Manisa Zanaat Atölyesi</strong>
                    <div class="text-[11px] text-stone-600">1. Anafartalar Mah. Dr. Sadık Ahmet Cad. Şehzadeler / Manisa</div>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-stone-500 uppercase block">ALICI:</span>
                    <strong class="text-wood-dark">Ahmet Yılmaz (+90 553 289 06 90)</strong>
                    <div class="text-[11px] text-stone-600">1. Anafartalar Mah. Dr. Sadık Ahmet Cad. No:14 Şehzadeler / Manisa</div>
                </div>
            </div>

            <div class="text-center py-2 bg-white rounded-lg border border-stone-300">
                <div class="font-mono tracking-widest text-2xl font-black">||| | ||||| |||| || ||||| | |||</div>
                <div class="text-[10px] font-mono text-stone-500">YK-984210582-AHS849201</div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-wood-dark hover:bg-black text-white text-xs font-bold rounded-xl flex items-center gap-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Kargo Etiketi Yazdır</span>
            </button>
            <button type="button" onclick="closeOrderDetailModal()" class="px-4 py-2.5 bg-stone-200 hover:bg-stone-300 text-stone-700 text-xs font-bold rounded-xl">
                Kapat
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function openOrderDetailModal() {
        const modal = document.getElementById('orderDetailModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeOrderDetailModal(e) {
        const modal = document.getElementById('orderDetailModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>
@endpush
