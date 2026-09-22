@extends('layouts.app')

@section('title', 'Ödeme & Teslimat — AhşapEvim Manisa')
@section('meta_description', 'Güvenli 256-Bit SSL korumasıyla kişiye özel ahşap siparişinizi tamamlayın.')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-8 pb-16">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-stone-500 font-medium">
        <a href="{{ url('/') }}" class="hover:text-brand transition">Anasayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-stone-400"></i>
        <a href="{{ url('/sepet') }}" class="hover:text-brand transition">Sepetim</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-stone-400"></i>
        <span class="text-wood-dark font-bold">Ödeme & Teslimat</span>
    </nav>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-wood-dark">Güvenli Ödeme & Teslimat</h1>
            <p class="text-xs sm:text-sm text-stone-500 mt-1">Lütfen teslimat ve fatura bilgilerinizi eksiksiz giriniz.</p>
        </div>
        <div class="hidden sm:flex items-center gap-2 text-xs font-bold text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-xl border border-emerald-200">
            <i class="fa-solid fa-shield-halved text-sm"></i>
            <span>256-Bit SSL Koruma</span>
        </div>
    </div>

    <!-- Main Checkout Form & Summary Grid -->
    <form id="checkoutForm" onsubmit="handleCheckoutSubmit(event)" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Sol Kolon: Teslimat Bilgileri & Ödeme Yöntemi (8 Kolon) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- 1. İletişim ve Teslimat Bilgileri -->
            <div class="artisan-card p-6 bg-white space-y-5">
                <div class="flex items-center gap-3 pb-3 border-b border-stone-200">
                    <div class="w-8 h-8 rounded-xl bg-brand text-white flex items-center justify-center font-bold text-xs">
                        1
                    </div>
                    <div>
                        <h2 class="font-serif font-bold text-base text-wood-dark">Teslimat ve İletişim Bilgileri</h2>
                        <span class="text-xs text-stone-500">Kargonuzun ulaştırılacağı adresi belirtiniz.</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Ad Soyad -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-wood-dark">Ad Soyad *</label>
                        <input type="text" id="custName" required placeholder="Adınız Soyadınız" class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand focus:bg-white transition">
                    </div>

                    <!-- E-Posta -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-wood-dark">E-Posta Adresi *</label>
                        <input type="email" id="custEmail" required placeholder="ornek@domain.com" class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand focus:bg-white transition">
                    </div>

                    <!-- Telefon (Maskeli +90 5XX...) -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-wood-dark">Cep Telefonu *</label>
                        <input type="tel" id="custPhone" required placeholder="+90 (5__) ___ __ __" maxlength="19" oninput="maskPhone(this)" class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand focus:bg-white transition font-mono">
                    </div>

                    <!-- T.C. Kimlik No -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-wood-dark">T.C. Kimlik No *</label>
                            <span class="text-[10px] text-stone-600">Fatura zorunluluğu</span>
                        </div>
                        <input type="text" id="custTC" required maxlength="11" placeholder="11 haneli T.C. No" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand focus:bg-white transition font-mono">
                    </div>

                    <!-- İl Seçimi (Dinamik) -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-wood-dark">İl *</label>
                        <select id="citySelect" required onchange="populateDistricts(this.value)" class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand focus:bg-white transition cursor-pointer">
                            <option value="">İl Seçiniz...</option>
                            <option value="Manisa">Manisa</option>
                            <option value="İzmir">İzmir</option>
                            <option value="İstanbul">İstanbul</option>
                            <option value="Ankara">Ankara</option>
                            <option value="Bursa">Bursa</option>
                            <option value="Antalya">Antalya</option>
                        </select>
                    </div>

                    <!-- İlçe Seçimi (Dinamik) -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-wood-dark">İlçe *</label>
                        <select id="districtSelect" required class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand focus:bg-white transition cursor-pointer">
                            <option value="">Önce İl Seçiniz...</option>
                        </select>
                    </div>

                    <!-- Açık Adres -->
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-wood-dark">Açık Adres (Mahalle, Cadde, Sokak, Bina No, Daire) *</label>
                        <textarea id="custAddress" required rows="2" placeholder="Kargo kuryesinin kolayca bulabilmesi için detaylı adresinizi yazınız..." class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand focus:bg-white transition"></textarea>
                    </div>

                    <!-- Sipariş Notu -->
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-wood-dark">Satıcıya Özel Sipariş Notu (Opsiyonel)</label>
                        <input type="text" placeholder="Varsa teslimat veya zanaat notunuz..." class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand focus:bg-white transition">
                    </div>
                </div>
            </div>

            <!-- 2. Ödeme Yöntemi Seçimi -->
            <div class="artisan-card p-6 bg-white space-y-5">
                <div class="flex items-center gap-3 pb-3 border-b border-stone-200">
                    <div class="w-8 h-8 rounded-xl bg-brand text-white flex items-center justify-center font-bold text-xs">
                        2
                    </div>
                    <div>
                        <h2 class="font-serif font-bold text-base text-wood-dark">Ödeme Yöntemi</h2>
                        <span class="text-xs text-stone-500">Tercih ettiğiniz güvenli ödeme seçeneğini belirleyin.</span>
                    </div>
                </div>

                <!-- Payment Method Tabs -->
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="selectPaymentMethod('card')" id="payTab-card" class="p-4 rounded-2xl border-2 border-brand bg-brand-light text-wood-dark text-left transition flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-brand text-white flex items-center justify-center text-sm">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold">Kredi / Banka Kartı</div>
                                <div class="text-[10px] text-stone-500">iyzico 256-Bit SSL 3D</div>
                            </div>
                        </div>
                        <i class="fa-solid fa-circle-check text-brand text-base"></i>
                    </button>

                    <button type="button" onclick="selectPaymentMethod('eft')" id="payTab-eft" class="p-4 rounded-2xl border-2 border-stone-200 bg-white hover:border-stone-300 text-wood-dark text-left transition flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold">Banka Havalesi / EFT</div>
                                <div class="text-[10px] text-stone-500">Halkbank Doğrudan Transfer</div>
                            </div>
                        </div>
                        <i class="fa-regular fa-circle text-stone-300 text-base" id="eftCheckIcon"></i>
                    </button>
                </div>

                <!-- KART ÖDEME FORMU ALANI -->
                <div id="paymentArea-card" class="space-y-4 pt-2">
                    
                    <!-- İnteraktif Kart Önizleme (Interactive Card Mockup) -->
                    <div class="w-full max-w-sm mx-auto h-48 rounded-2xl bg-gradient-to-tr from-wood-dark via-wood-active to-brand p-5 text-white shadow-xl flex flex-col justify-between relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <i class="fa-solid fa-microchip text-2xl text-amber-300"></i>
                            <span class="text-xs font-mono font-bold tracking-widest uppercase">iyzico SSL</span>
                        </div>

                        <div class="text-lg sm:text-xl font-mono tracking-widest font-extrabold text-stone-100 text-center" id="cardMockNumber">
                            •••• •••• •••• ••••
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <div>
                                <div class="text-[9px] uppercase text-stone-400 font-bold">Kart Sahibi</div>
                                <div class="font-bold tracking-wide uppercase truncate max-w-[180px]" id="cardMockHolder">AD SOYAD</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[9px] uppercase text-stone-400 font-bold">Son Kullanma</div>
                                <div class="font-mono font-bold" id="cardMockExpiry">MM/YY</div>
                            </div>
                        </div>
                    </div>

                    <!-- Kart Form Alanları -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="sm:col-span-2 space-y-1">
                            <label class="block text-xs font-bold text-wood-dark">Kart Üzerindeki İsim *</label>
                            <input type="text" id="cardHolder" oninput="updateMockCard()" placeholder="Kart Üzerindeki İsim" class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand uppercase">
                        </div>

                        <div class="sm:col-span-2 space-y-1">
                            <label class="block text-xs font-bold text-wood-dark">Kart Numarası *</label>
                            <input type="text" id="cardNumber" maxlength="19" oninput="maskCardNumber(this); updateMockCard();" placeholder="•••• •••• •••• ••••" class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand font-mono">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-wood-dark">Son Kullanma Tarihi (AA/YY) *</label>
                            <input type="text" id="cardExpiry" maxlength="5" oninput="maskExpiry(this); updateMockCard();" placeholder="MM/YY" class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand font-mono">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-wood-dark">Güvenlik Kodu (CVV) *</label>
                            <input type="password" id="cardCVV" maxlength="3" placeholder="•••" class="w-full text-xs p-3 rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-brand font-mono">
                        </div>
                    </div>

                    <!-- Taksit Seçenekleri -->
                    <div class="p-3 bg-stone-50 rounded-xl border border-stone-200 space-y-2">
                        <label class="block text-xs font-bold text-wood-dark">Taksit Seçenekleri:</label>
                        <div class="grid grid-cols-3 gap-2 text-xs">
                            <label class="p-2.5 rounded-lg border-2 border-brand bg-white flex flex-col items-center cursor-pointer">
                                <input type="radio" name="installment" value="1" checked class="hidden">
                                <span class="font-bold text-wood-dark">Tek Çekim</span>
                                <span class="text-brand font-mono font-bold mt-0.5">₺999,00</span>
                            </label>
                            <label class="p-2.5 rounded-lg border border-stone-200 bg-white flex flex-col items-center cursor-pointer hover:border-brand">
                                <input type="radio" name="installment" value="3" class="hidden">
                                <span class="font-bold text-wood-dark">3 Taksit</span>
                                <span class="text-stone-600 font-mono text-[11px] mt-0.5">3 x ₺333,00</span>
                            </label>
                            <label class="p-2.5 rounded-lg border border-stone-200 bg-white flex flex-col items-center cursor-pointer hover:border-brand">
                                <input type="radio" name="installment" value="6" class="hidden">
                                <span class="font-bold text-wood-dark">6 Taksit</span>
                                <span class="text-stone-600 font-mono text-[11px] mt-0.5">6 x ₺166,50</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- HAVALE / EFT BİLGİ ALANI (Halkbank Masif Ahşap IBAN Kartı) -->
                <div id="paymentArea-eft" class="hidden space-y-4 pt-2">
                    <div class="p-5 rounded-2xl bg-gradient-to-br from-amber-900 via-amber-800 to-stone-900 text-white shadow-xl space-y-4 border border-amber-700/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-amber-300 font-bold">
                                    <i class="fa-solid fa-building-columns"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-white">HALKBANK</div>
                                    <div class="text-[10px] text-amber-200">Manisa Şubesi</div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold bg-amber-600/60 px-2 py-1 rounded-md text-amber-200">Doğrulanmış Hesap</span>
                        </div>

                        <div>
                            <div class="text-[10px] uppercase text-stone-300 font-bold">Alıcı / Hesap Sahibi:</div>
                            <div class="text-sm font-bold text-white tracking-wide">AhşapEvim Manisa — İbrahim ...</div>
                        </div>

                        <div class="p-3 bg-black/40 rounded-xl border border-white/10 flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <div class="text-[9px] uppercase text-amber-300 font-bold">IBAN Numarası:</div>
                                <div class="text-xs sm:text-sm font-mono font-bold text-white tracking-wider truncate" id="ibanText">
                                    TR84 0001 2009 8450 0006 1234 56
                                </div>
                            </div>
                            <button type="button" onclick="copyIBAN()" class="px-3 py-2 bg-brand hover:bg-brand-dark text-white rounded-lg text-xs font-bold shrink-0 transition flex items-center gap-1">
                                <i class="fa-regular fa-copy"></i>
                                <span>Kopyala</span>
                            </button>
                        </div>
                    </div>

                    <!-- Havale Talimatları -->
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-950 space-y-2">
                        <div class="font-bold flex items-center gap-1.5 text-amber-900">
                            <i class="fa-solid fa-circle-exclamation text-brand"></i>
                            <span>Havale / EFT Yaparken Dikkat Edilmesi Gerekenler:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-amber-900/90 text-[11.5px]">
                            <li>Açıklama kısmına sipariş onayından sonra verilecek <strong>Sipariş Kodunu</strong> yazınız.</li>
                            <li>Ödemeniz onaylandığı anda siparişiniz atölyede baskı kuyruğuna alınacaktır.</li>
                            <li>24 saat içinde ödemesi yapılmayan siparişler otomatik iptal edilir.</li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

        <!-- Sağ Kolon: Yapışkan Sipariş Özeti (4 Kolon) -->
        <div class="lg:col-span-4">
            <div class="artisan-card p-6 bg-white space-y-5 sticky top-28 border-2 border-stone-200">
                <h3 class="font-serif font-bold text-lg text-wood-dark pb-3 border-b border-stone-200">
                    Sipariş Özeti
                </h3>

                <!-- Ürün Kartı -->
                <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl border border-stone-200">
                    <div class="w-14 h-16 rounded-lg bg-stone-200 overflow-hidden shrink-0 relative">
                        <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=150&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 right-0 bg-brand text-white text-[8px] font-bold px-1 rounded-tl">2 Foto</div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-wood-dark truncate">Kişiye Özel El Yapımı Ahşap Dönen Çerçeve</h4>
                        <div class="text-[10px] text-stone-500 mt-0.5">1 Adet • 23x21 cm</div>
                        <div class="text-xs font-extrabold text-brand font-mono mt-1">₺999,00</div>
                    </div>
                </div>

                <!-- Tutar Özeti -->
                <div class="space-y-2 text-xs text-stone-600">
                    <div class="flex justify-between">
                        <span>Ara Toplam</span>
                        <span class="font-bold text-wood-dark font-mono">₺999,00</span>
                    </div>
                    <div class="flex justify-between text-emerald-700">
                        <span>Kargo</span>
                        <span class="font-bold font-mono">ÜCRETSİZ</span>
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-wood-dark pt-3 border-t border-stone-200">
                        <span>Toplam</span>
                        <span class="text-brand text-xl font-mono">₺999,00</span>
                    </div>
                </div>

                <!-- Onay Kutusu -->
                <div class="space-y-2 text-[11px] text-stone-500">
                    <label class="flex items-start gap-2 cursor-pointer select-none">
                        <input type="checkbox" required checked class="w-4 h-4 text-brand rounded border-stone-300 focus:ring-brand mt-0.5">
                        <span><a href="#" class="text-brand underline">Mesafeli Satış Sözleşmesi</a> ve Ön Bilgilendirme Koşullarını okudum, onaylıyorum.</span>
                    </label>
                </div>

                <!-- Siparişi Tamamla Butonu -->
                <button type="submit" id="submitOrderBtn" class="w-full py-4 bg-brand hover:bg-brand-dark text-white font-bold text-sm rounded-2xl shadow-xl shadow-brand/25 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-lock text-xs"></i>
                    <span id="submitOrderBtnText">Siparişi Güvenle Tamamla (₺999,00)</span>
                </button>

                <div class="text-center text-[10px] text-stone-400 space-y-1">
                    <div>Ödemeniz 256-bit SSL şifreleme ile güvence altındadır.</div>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    // İl / İlçe dinamik dropdown
    const districtsData = {
        'Manisa': ['Şehzadeler', 'Yunusemre', 'Akhisar', 'Salihli', 'Turgutlu', 'Soma', 'Alaşehir', 'Kırkağaç'],
        'İzmir': ['Konak', 'Karşıyaka', 'Bornova', 'Buca', 'Çeşme', 'Urla', 'Bayraklı'],
        'İstanbul': ['Kadıköy', 'Beşiktaş', 'Üsküdar', 'Şişli', 'Bakırköy', 'Sarıyer', 'Maltepe'],
        'Ankara': ['Çankaya', 'Yenimahalle', 'Keçiören', 'Etimesgut', 'Mamak'],
        'Bursa': ['Nilüfer', 'Osmangazi', 'Yıldırım', 'Mudanya', 'İnegöl'],
        'Antalya': ['Muratpaşa', 'Konyaaltı', 'Kepez', 'Alanya', 'Manavgat']
    };

    function populateDistricts(city) {
        const districtSelect = document.getElementById('districtSelect');
        if (!districtSelect) return;

        districtSelect.innerHTML = '<option value="">İlçe Seçiniz...</option>';
        if (city && districtsData[city]) {
            districtsData[city].forEach(d => {
                const opt = document.createElement('option');
                opt.value = d;
                opt.textContent = d;
                districtSelect.appendChild(opt);
            });
        }
    }

    // Phone Mask
    function maskPhone(input) {
        let val = input.value.replace(/\D/g, '');
        if (val.startsWith('90')) val = val.substring(2);
        if (val.length > 10) val = val.substring(0, 10);

        let formatted = '+90 ';
        if (val.length > 0) formatted += '(' + val.substring(0, 3);
        if (val.length >= 4) formatted += ') ' + val.substring(3, 6);
        if (val.length >= 7) formatted += ' ' + val.substring(6, 8);
        if (val.length >= 9) formatted += ' ' + val.substring(8, 10);

        input.value = formatted.trim();
    }

    // Card Number Mask
    function maskCardNumber(input) {
        let val = input.value.replace(/\D/g, '');
        if (val.length > 16) val = val.substring(0, 16);
        let parts = [];
        for (let i = 0; i < val.length; i += 4) {
            parts.push(val.substring(i, i + 4));
        }
        input.value = parts.join(' ');
    }

    // Expiry Mask
    function maskExpiry(input) {
        let val = input.value.replace(/\D/g, '');
        if (val.length > 4) val = val.substring(0, 4);
        if (val.length >= 2) {
            input.value = val.substring(0, 2) + '/' + val.substring(2, 4);
        } else {
            input.value = val;
        }
    }

    // Update Interactive Card Preview
    function updateMockCard() {
        const num = document.getElementById('cardNumber').value || '•••• •••• •••• ••••';
        const holder = document.getElementById('cardHolder').value || 'AD SOYAD';
        const exp = document.getElementById('cardExpiry').value || 'MM/YY';

        document.getElementById('cardMockNumber').textContent = num;
        document.getElementById('cardMockHolder').textContent = holder.toUpperCase();
        document.getElementById('cardMockExpiry').textContent = exp;
    }

    // Select Payment Method
    let selectedMethod = 'card';

    function selectPaymentMethod(method) {
        selectedMethod = method;
        const cardTab = document.getElementById('payTab-card');
        const eftTab = document.getElementById('payTab-eft');
        const cardArea = document.getElementById('paymentArea-card');
        const eftArea = document.getElementById('paymentArea-eft');
        const btnText = document.getElementById('submitOrderBtnText');

        if (method === 'card') {
            cardTab.className = 'p-4 rounded-2xl border-2 border-brand bg-brand-light text-wood-dark text-left transition flex items-center justify-between';
            eftTab.className = 'p-4 rounded-2xl border-2 border-stone-200 bg-white hover:border-stone-300 text-wood-dark text-left transition flex items-center justify-between';
            cardArea.classList.remove('hidden');
            eftArea.classList.add('hidden');
            if (btnText) btnText.textContent = '3D Secure ile Güvenle Öde (₺999,00)';
        } else {
            eftTab.className = 'p-4 rounded-2xl border-2 border-brand bg-brand-light text-wood-dark text-left transition flex items-center justify-between';
            cardTab.className = 'p-4 rounded-2xl border-2 border-stone-200 bg-white hover:border-stone-300 text-wood-dark text-left transition flex items-center justify-between';
            eftArea.classList.remove('hidden');
            cardArea.classList.add('hidden');
            if (btnText) btnText.textContent = 'Havale Bildirimiyle Siparişi Onayla (₺999,00)';
        }
    }

    // Copy IBAN
    function copyIBAN() {
        const iban = 'TR84 0001 2009 8450 0006 1234 56';
        navigator.clipboard.writeText(iban.replace(/\s/g, '')).then(() => {
            showToast('Halkbank IBAN numarası kopyalandı!', 'success');
        });
    }

    // Handle Form Submit
    function handleCheckoutSubmit(e) {
        e.preventDefault();

        // Check TC
        const tc = document.getElementById('custTC').value;
        if (tc.length !== 11) {
            alert('⚠️ Lütfen 11 haneli geçerli T.C. Kimlik Numaranızı giriniz.');
            return false;
        }

        // Redirect to order result page based on payment method
        if (selectedMethod === 'card') {
            window.location.href = "{{ url('/odeme/sonuc?status=success&type=card') }}";
        } else {
            window.location.href = "{{ url('/odeme/sonuc?status=waiting_eft&type=eft') }}";
        }
        return false;
    }
</script>
@endpush
