@extends('layouts.app')

@section('title', 'Sipariş Sonucu — AhşapEvim Manisa')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-8 pb-16 max-w-4xl">

    <!-- Interactive Status Switcher Bar (Frontend Test Bar) -->
    <div class="bg-wood-dark p-3 rounded-2xl border border-stone-700 flex flex-wrap items-center justify-between gap-3 text-xs text-white">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-brand animate-pulse"></span>
            <span class="font-bold text-stone-300">Ödeme Sonuç Ekranı Durum Önizleyicisi:</span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="switchResultState('success')" id="stateBtn-success" class="px-3 py-1.5 rounded-xl font-bold bg-emerald-600 text-white transition">
                🟢 Başarılı (Kart)
            </button>
            <button type="button" onclick="switchResultState('waiting_eft')" id="stateBtn-waiting_eft" class="px-3 py-1.5 rounded-xl font-bold bg-stone-700 hover:bg-amber-600 text-white transition">
                🟡 Havale/EFT Bekliyor
            </button>
            <button type="button" onclick="switchResultState('failed')" id="stateBtn-failed" class="px-3 py-1.5 rounded-xl font-bold bg-stone-700 hover:bg-rose-600 text-white transition">
                🔴 Banka Ret / Hata
            </button>
        </div>
    </div>

    <!-- 1. SENARYO: BAŞARILI KART ÖDEMESİ -->
    <div id="stateArea-success" class="space-y-6">
        <div class="artisan-card p-8 bg-white text-center space-y-4 border-2 border-emerald-200 shadow-xl">
            <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto shadow-sm">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold font-mono">
                SİPARİŞ KODU: #AHS-849201
            </div>

            <h1 class="font-serif text-3xl font-bold text-wood-dark">
                Siparişiniz Başarıyla Alındı!
            </h1>

            <p class="text-sm text-stone-600 max-w-lg mx-auto leading-relaxed">
                Ödemeniz güvenle onaylandı. Masif ahşap çerçeveniz Manisa atölyemizde baskı ve montaj sırasına alınmıştır.
            </p>

            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200/80 max-w-md mx-auto text-xs text-emerald-950 flex items-center justify-center gap-3">
                <i class="fa-solid fa-truck-fast text-emerald-700 text-lg"></i>
                <span class="font-medium">Tahmini Kargoya Veriliş: <strong>Bugün Saat 16:00</strong></span>
            </div>

            <!-- Eylemler -->
            <div class="flex flex-wrap items-center justify-center gap-3 pt-4">
                <a href="{{ url('/siparis-takip?code=AHS-849201') }}" class="px-6 py-3.5 bg-brand hover:bg-brand-dark text-white text-xs font-bold rounded-xl shadow-lg shadow-brand/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-box-open"></i>
                    <span>Siparişi Takip Et</span>
                </a>

                <button type="button" onclick="window.print()" class="px-5 py-3.5 bg-stone-100 hover:bg-stone-200 text-wood-dark text-xs font-bold rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Sipariş Fişi Yazdır</span>
                </button>
            </div>
        </div>

        <!-- Sipariş Özeti Kartı -->
        <div class="artisan-card p-6 bg-white space-y-4">
            <h3 class="font-serif font-bold text-base text-wood-dark pb-2 border-b border-stone-200">
                Sipariş Detayları
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-stone-600">
                <div>
                    <span class="font-bold text-wood-dark block">Teslimat Adresi:</span>
                    <span>Ahmet Yılmaz, 1. Anafartalar Mah. Dr. Sadık Ahmet Cad. No:14 Şehzadeler / Manisa</span>
                    <span class="block mt-1 font-mono">+90 (553) 289 06 90</span>
                </div>
                <div>
                    <span class="font-bold text-wood-dark block">Ödeme Bilgisi:</span>
                    <span>Kredi Kartı (iyzico 3D Secure) • Tek Çekim</span>
                    <span class="block font-bold text-brand font-mono text-sm mt-1">₺999,00 (Ödendi)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. SENARYO: HAVALE / EFT BEKLİYOR -->
    <div id="stateArea-waiting_eft" class="hidden space-y-6">
        <div class="artisan-card p-8 bg-white text-center space-y-4 border-2 border-amber-300 shadow-xl">
            <div class="w-20 h-20 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-3xl mx-auto shadow-sm">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-900 text-xs font-bold font-mono">
                SİPARİŞ REFERANS KODU: #AHS-849201
            </div>

            <h1 class="font-serif text-3xl font-bold text-wood-dark">
                Havale / EFT Ödemesi Bekleniyor
            </h1>

            <p class="text-sm text-stone-600 max-w-lg mx-auto leading-relaxed">
                Siparişiniz oluşturuldu. Lütfen aşağıdaki Halkbank hesabımıza sipariş tutarını transfer ederken <strong>Açıklama alanına #AHS-849201</strong> kodunu yazınız.
            </p>

            <!-- IBAN KARTI -->
            <div class="max-w-lg mx-auto p-5 rounded-2xl bg-gradient-to-br from-amber-900 via-amber-800 to-stone-900 text-white shadow-xl space-y-3 text-left">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-amber-200">HALKBANK MANİSA ŞUBESİ</span>
                    <span class="font-mono font-bold text-brand-light">Tutar: ₺999,00</span>
                </div>

                <div class="p-3 bg-black/40 rounded-xl border border-white/10 flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <div class="text-[9px] uppercase text-amber-300 font-bold">IBAN Numarası:</div>
                        <div class="text-xs sm:text-sm font-mono font-bold text-white tracking-wider truncate">
                            TR84 0001 2009 8450 0006 1234 56
                        </div>
                    </div>
                    <button type="button" onclick="navigator.clipboard.writeText('TR840001200984500006123456'); showToast('IBAN kopyalandı!', 'success');" class="px-3 py-2 bg-brand hover:bg-brand-dark text-white rounded-lg text-xs font-bold shrink-0 transition flex items-center gap-1">
                        <i class="fa-regular fa-copy"></i>
                        <span>Kopyala</span>
                    </button>
                </div>
            </div>

            <!-- Eylemler -->
            <div class="flex items-center justify-center gap-3 pt-2">
                <a href="{{ url('/siparis-takip?code=AHS-849201') }}" class="px-6 py-3.5 bg-brand hover:bg-brand-dark text-white text-xs font-bold rounded-xl shadow-lg transition">
                    Sipariş Takip Ekranına Git
                </a>
            </div>
        </div>
    </div>

    <!-- 3. SENARYO: BAŞARISIZ / BANKA RET -->
    <div id="stateArea-failed" class="hidden space-y-6">
        <div class="artisan-card p-8 bg-white text-center space-y-4 border-2 border-rose-300 shadow-xl">
            <div class="w-20 h-20 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-3xl mx-auto shadow-sm">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <h1 class="font-serif text-3xl font-bold text-wood-dark">
                Ödeme Onaylanamadı
            </h1>

            <p class="text-sm text-stone-600 max-w-lg mx-auto leading-relaxed">
                Bankanız işlemi reddetti. Olası sebepler: Kart limiti yetersiz, 3D Secure SMS şifresi hatalı girildi veya internet alışveriş yetkisi kapalı.
            </p>

            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-900 max-w-md mx-auto">
                <strong>Banka Hata Kodu:</strong> 51 — Yetersiz Bakiye / İşlem Reddedildi
            </div>

            <div class="flex items-center justify-center gap-3 pt-2">
                <a href="{{ url('/odeme') }}" class="px-6 py-3.5 bg-brand hover:bg-brand-dark text-white text-xs font-bold rounded-xl shadow-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>Farklı Kartla Tekrar Dene</span>
                </a>
                <a href="https://wa.me/905532890690" target="_blank" class="px-5 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>WhatsApp Destek</span>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function switchResultState(state) {
        document.getElementById('stateArea-success').classList.add('hidden');
        document.getElementById('stateArea-waiting_eft').classList.add('hidden');
        document.getElementById('stateArea-failed').classList.add('hidden');

        document.getElementById('stateBtn-success').className = 'px-3 py-1.5 rounded-xl font-bold bg-stone-700 hover:bg-emerald-600 text-white transition';
        document.getElementById('stateBtn-waiting_eft').className = 'px-3 py-1.5 rounded-xl font-bold bg-stone-700 hover:bg-amber-600 text-white transition';
        document.getElementById('stateBtn-failed').className = 'px-3 py-1.5 rounded-xl font-bold bg-stone-700 hover:bg-rose-600 text-white transition';

        if (state === 'success') {
            document.getElementById('stateArea-success').classList.remove('hidden');
            document.getElementById('stateBtn-success').className = 'px-3 py-1.5 rounded-xl font-bold bg-emerald-600 text-white transition';
        } else if (state === 'waiting_eft') {
            document.getElementById('stateArea-waiting_eft').classList.remove('hidden');
            document.getElementById('stateBtn-waiting_eft').className = 'px-3 py-1.5 rounded-xl font-bold bg-amber-600 text-white transition';
        } else if (state === 'failed') {
            document.getElementById('stateArea-failed').classList.remove('hidden');
            document.getElementById('stateBtn-failed').className = 'px-3 py-1.5 rounded-xl font-bold bg-rose-600 text-white transition';
        }
    }

    // Check URL params on load
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        if (status === 'waiting_eft') {
            switchResultState('waiting_eft');
        } else if (status === 'failed') {
            switchResultState('failed');
        } else {
            switchResultState('success');
        }
    });
</script>
@endpush
