@extends('layouts.app')

@section('title', 'Kişiye Özel El Yapımı Ahşap Dönen Çerçeve Yeni Sokak Lambalı — AhşapEvim Manisa')
@section('meta_description', 'Manisa atölyemizde üretilen 360° dönebilen, çift fotoğraflı ve sokak lambası LED aydınlatmalı kişiye özel masif ahşap çerçeve.')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-8 pb-16">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-stone-500 font-medium">
        <a href="{{ url('/') }}" class="hover:text-brand transition">Anasayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-stone-400"></i>
        <a href="{{ url('/urunler?category=donen-cerceve') }}" class="hover:text-brand transition">Dönen Çerçeveler</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-stone-400"></i>
        <span class="text-wood-dark font-bold truncate">Kişiye Özel El Yapımı Ahşap Dönen Çerçeve Yeni Sokak Lambalı</span>
    </nav>

    <!-- Main Product Layout (3 Columns on Desktop: Gallery / Info / Buybox) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- 1. SOL KOLON: Galeri & İnteraktif Canlı 3D Önizleme (5 Kolon) -->
        <div class="lg:col-span-5 space-y-4">
            
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Sol Dikey Thumbnails (Desktop) -->
                <div class="hidden sm:flex flex-col gap-2.5 w-20 shrink-0">
                    <div onclick="switchGalleryImage(this, 'main')" class="thumb-item w-20 h-24 rounded-xl border-2 border-brand bg-white p-1 cursor-pointer overflow-hidden shadow-xs hover:border-brand transition">
                        <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-lg">
                    </div>
                    <div onclick="switchGalleryImage(this, 'wood')" class="thumb-item w-20 h-24 rounded-xl border border-stone-200 bg-white p-1 cursor-pointer overflow-hidden shadow-xs hover:border-brand transition">
                        <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-lg">
                    </div>
                    <div onclick="switchGalleryImage(this, 'lamp')" class="thumb-item w-20 h-24 rounded-xl border border-stone-200 bg-white p-1 cursor-pointer overflow-hidden shadow-xs hover:border-brand transition">
                        <img src="https://images.unsplash.com/photo-1534349762230-e0cadf78f5da?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-lg">
                    </div>
                    <!-- Instagram Reel Trigger Thumbnail -->
                    <div onclick="openReelModal()" class="w-20 h-24 rounded-xl border-2 border-pink-400 bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 p-1 cursor-pointer overflow-hidden shadow-xs relative flex flex-col items-center justify-center text-white group">
                        <i class="fa-brands fa-instagram text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[9px] font-black uppercase mt-1">REEL</span>
                    </div>
                </div>

                <!-- Büyük Görsel / 3D Dönen Çerçeve Simülasyon Alanı -->
                <div class="flex-1">
                    <div class="artisan-card p-4 relative bg-white overflow-hidden flex flex-col items-center justify-center min-h-[420px] sm:min-h-[480px]">
                        
                        <!-- 3D Döndürme Modu Aç/Kapa Rozeti -->
                        <div class="absolute top-4 left-4 z-20 flex items-center gap-2">
                            <span class="bg-wood-dark/85 backdrop-blur-xs text-white text-[10px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-md">
                                <span class="w-2 h-2 rounded-full bg-brand animate-ping"></span>
                                <span id="faceIndicator">ÖN YÜZ</span>
                            </span>
                            <button type="button" onclick="flipFrame3D()" class="bg-brand hover:bg-brand-dark text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md transition flex items-center gap-1.5 active:scale-95">
                                <i class="fa-solid fa-arrows-rotate"></i>
                                <span>360° Çevir</span>
                            </button>
                        </div>

                        <!-- 3D Dönen Masif Çerçeve Sahnesi -->
                        <div class="perspective-1000 w-full max-w-[320px] aspect-[4/5] relative py-4">
                            <div id="rotatingFrame3D" class="w-full h-full relative transform-style-3d transition-transform duration-700 cursor-pointer shadow-2xl rounded-2xl" onclick="flipFrame3D()">
                                
                                <!-- ÖN YÜZ (Front Face) -->
                                <div class="absolute inset-0 backface-hidden rounded-2xl p-4 bg-gradient-to-b from-[#EFEAE2] to-[#D8CEBE] border-8 border-[#C87A53]/30 shadow-inner flex flex-col items-center justify-center">
                                    <div class="w-full h-full rounded-xl overflow-hidden bg-white border-2 border-stone-300 relative shadow-md">
                                        <img id="previewFrontImage" src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=600&auto=format&fit=crop&q=80" alt="Ön Yüz Fotoğrafı" class="w-full h-full object-cover">
                                        
                                        <!-- Watermark / Overlay info -->
                                        <div class="absolute bottom-2 left-2 right-2 bg-black/60 backdrop-blur-xs text-white text-[10px] font-bold py-1 px-2 rounded text-center">
                                            Ön Yüz Baskı Alanı (10x15 cm)
                                        </div>
                                    </div>
                                    <!-- Ahşap Çerçeve Ayaklık / Lamba Süsü -->
                                    <div class="absolute -bottom-3 w-16 h-3 bg-amber-900 rounded-full shadow-md"></div>
                                </div>

                                <!-- ARKA YÜZ (Back Face - Rotate Y 180) -->
                                <div class="absolute inset-0 backface-hidden rotate-y-180 rounded-2xl p-4 bg-gradient-to-b from-[#EFEAE2] to-[#D8CEBE] border-8 border-[#C87A53]/30 shadow-inner flex flex-col items-center justify-center">
                                    <div class="w-full h-full rounded-xl overflow-hidden bg-white border-2 border-stone-300 relative shadow-md">
                                        <img id="previewBackImage" src="https://images.unsplash.com/photo-1544816155-12df9643f363?w=600&auto=format&fit=crop&q=80" alt="Arka Yüz Fotoğrafı" class="w-full h-full object-cover">
                                        
                                        <!-- Watermark / Overlay info -->
                                        <div class="absolute bottom-2 left-2 right-2 bg-black/60 backdrop-blur-xs text-white text-[10px] font-bold py-1 px-2 rounded text-center">
                                            Arka Yüz Baskı Alanı (10x15 cm)
                                        </div>
                                    </div>
                                    <div class="absolute -bottom-3 w-16 h-3 bg-amber-900 rounded-full shadow-md"></div>
                                </div>

                            </div>
                        </div>

                        <!-- 360 İpucu -->
                        <div class="text-[11px] text-stone-500 font-semibold mt-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-hand-pointer text-brand"></i>
                            <span>Çerçeveye tıklayarak veya butona basarak 360° diğer yüzü inceleyin</span>
                        </div>
                    </div>

                    <!-- Temsili Görsel Uyarısı -->
                    <div class="mt-3 p-3.5 bg-amber-50 border border-amber-200/90 rounded-2xl text-amber-950 text-xs flex items-start gap-3 shadow-2xs">
                        <i class="fa-solid fa-circle-info text-brand text-base shrink-0 mt-0.5"></i>
                        <div class="leading-relaxed">
                            <strong class="font-black text-amber-900 block mb-0.5">📌 Bilgilendirme: Ürün Görselleri Temsilidir</strong>
                            Sipariş edeceğiniz masif çerçeveye sağdaki alandan <strong>yüklediğiniz kendi fotoğraflarınız</strong> basılarak gönderilecektir.
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 2. ORTA KOLON: Ürün Detayları, Özellikler ve Sekmeler (4 Kolon) -->
        <div class="lg:col-span-4 space-y-5">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-brand/10 text-brand text-xs font-bold px-2.5 py-1 rounded-lg">Masif Zanaat Serisi</span>
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-lg flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Stokta Mevcut
                    </span>
                </div>

                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-wood-dark leading-snug">
                    Kişiye Özel El Yapımı Ahşap Dönen Çerçeve Yeni Sokak Lambalı
                </h1>

                <div class="flex items-center gap-4 mt-3 text-xs text-stone-500 pb-4 border-b border-stone-200">
                    <div class="flex items-center text-amber-500">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <span class="text-stone-700 font-bold ml-1.5 text-xs">5.0</span>
                    </div>
                    <span>•</span>
                    <span class="font-bold text-stone-700">128 Değerlendirme</span>
                    <span>•</span>
                    <span class="text-emerald-700 font-bold font-mono">1.450+ Satış</span>
                </div>
            </div>

            <!-- Teknik Özellik Rozetleri -->
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-xl bg-white border border-stone-200 flex items-center gap-2.5">
                    <i class="fa-solid fa-ruler-combined text-brand text-base"></i>
                    <div>
                        <div class="text-stone-400 text-[10px] uppercase font-bold">Ölçüler</div>
                        <div class="font-bold text-wood-dark">23 x 21 cm</div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-white border border-stone-200 flex items-center gap-2.5">
                    <i class="fa-solid fa-tree text-brand text-base"></i>
                    <div>
                        <div class="text-stone-400 text-[10px] uppercase font-bold">Malzeme</div>
                        <div class="font-bold text-wood-dark">%100 Masif Çam</div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-white border border-stone-200 flex items-center gap-2.5">
                    <i class="fa-solid fa-lightbulb text-amber-500 text-base"></i>
                    <div>
                        <div class="text-stone-400 text-[10px] uppercase font-bold">Aydınlatma</div>
                        <div class="font-bold text-wood-dark">Çift Renk LED</div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-white border border-stone-200 flex items-center gap-2.5">
                    <i class="fa-solid fa-images text-purple-600 text-base"></i>
                    <div>
                        <div class="text-stone-400 text-[10px] uppercase font-bold">Fotoğraf Ebat</div>
                        <div class="font-bold text-wood-dark">10 x 15 cm (2 Adet)</div>
                    </div>
                </div>
            </div>

            <!-- Bilgi Sekmeleri (Tabs) -->
            <div class="artisan-card p-4 bg-white">
                <div class="flex items-center gap-2 border-b border-stone-200 pb-2 mb-3 text-xs font-bold">
                    <button type="button" onclick="switchTab('desc')" id="tabBtn-desc" class="tab-btn px-3 py-1.5 rounded-lg text-brand bg-brand-light">Açıklama</button>
                    <button type="button" onclick="switchTab('craft')" id="tabBtn-craft" class="tab-btn px-3 py-1.5 rounded-lg text-stone-600 hover:text-brand">Ahşap Zanaat</button>
                    <button type="button" onclick="switchTab('delivery')" id="tabBtn-delivery" class="tab-btn px-3 py-1.5 rounded-lg text-stone-600 hover:text-brand">Kargo & İade</button>
                </div>

                <!-- Tab 1: Açıklama -->
                <div id="tabContent-desc" class="tab-content text-xs text-stone-600 space-y-2 leading-relaxed">
                    <p>
                        Evinize zarafet ve nostalji katacak bu ahşap el yapımı dönen çerçeve ile sevdiklerinizin fotoğraflarını en şık şekilde sergileyin.
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-stone-700">
                        <li><strong>Dönen Mekanizma:</strong> 2 farklı fotoğrafı tek bir dokunuşla sergileyin.</li>
                        <li><strong>Nostaljik Sokak Lambası:</strong> Gün ışığı ve beyaz LED modlu lamba.</li>
                        <li><strong>El Yapımı ve Dayanıklı:</strong> Doğal fırınlanmış ahşaptan özel el işçiliği.</li>
                    </ul>
                </div>

                <!-- Tab 2: Ahşap Zanaat -->
                <div id="tabContent-craft" class="tab-content hidden text-xs text-stone-600 space-y-2 leading-relaxed">
                    <p>
                        Manisa atölyemizde her çerçeve tek tek zımparalanarak su bazlı organik vernikle kaplanır. Kimyasal koku barındırmaz ve yıllar boyu çatlama yapmaz.
                    </p>
                </div>

                <!-- Tab 3: Kargo -->
                <div id="tabContent-delivery" class="tab-content hidden text-xs text-stone-600 space-y-2 leading-relaxed">
                    <p>
                        Siparişiniz özel havalı köpük ve masif koruma kutusunda darbelere karşı %100 korumalı şekilde Yurtiçi Kargo güvencesiyle gönderilir.
                    </p>
                </div>
            </div>
        </div>

        <!-- 3. SAĞ KOLON: Fotoğraf Yükleme, Hediye Paketi & Satın Alma Kutusu (3 Kolon) -->
        <div class="lg:col-span-3">
            <form id="productCustomForm" onsubmit="handleAddToCartCustom(event)" class="artisan-card p-5 bg-white space-y-4 sticky top-28 border-2 border-stone-200">
                
                <!-- Fiyat Alanı -->
                <div class="pb-3 border-b border-stone-200">
                    <div class="text-[11px] text-stone-400 font-bold uppercase tracking-wider">İndirimli Fiyat</div>
                    <div class="flex items-baseline gap-2 mt-0.5">
                        <span class="text-3xl font-extrabold text-brand font-mono">₺999,00</span>
                        <span class="text-xs text-stone-400 line-through font-mono">₺1.299,00</span>
                    </div>
                </div>

                <!-- FOTOĞRAF YÜKLEME ALANI (Zorunlu & Canlı Önizlemeli) -->
                <div class="p-3.5 bg-brand-soft border-2 border-dashed border-brand/40 rounded-2xl space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-black text-wood-dark uppercase tracking-wide flex items-center gap-1.5">
                            <i class="fa-solid fa-camera text-brand"></i>
                            <span>Fotoğraflarınızı Seçin *</span>
                        </label>
                        <span class="text-[9px] font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded-full uppercase">Zorunlu</span>
                    </div>

                    <p class="text-[11px] text-stone-500 leading-tight">
                        Çerçevenin her iki yüzüne basılacak fotoğrafları yükleyiniz. Canlı önizleme hemen yansıyacaktır.
                    </p>

                    <!-- Hidden Inputs -->
                    <input type="file" id="frontPhotoInput" accept="image/*" class="hidden" onchange="previewUpload(this, 'front')">
                    <input type="file" id="backPhotoInput" accept="image/*" class="hidden" onchange="previewUpload(this, 'back')">

                    <!-- 1. Fotoğraf / Ön Yüz -->
                    <div onclick="document.getElementById('frontPhotoInput').click()" 
                         class="p-2.5 bg-white rounded-xl border border-stone-200 hover:border-brand cursor-pointer transition flex items-center justify-between shadow-2xs group">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-brand-light text-brand flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                                <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-wood-dark truncate">1. Fotoğraf (Ön Yüz)</div>
                                <div class="text-[10px] text-stone-400 truncate" id="frontUploadLabel">Fotoğraf seçin...</div>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold text-brand bg-brand-light px-2.5 py-1 rounded-lg group-hover:bg-brand group-hover:text-white transition shrink-0">Seç</span>
                    </div>

                    <!-- 2. Fotoğraf / Arka Yüz -->
                    <div onclick="document.getElementById('backPhotoInput').click()" 
                         class="p-2.5 bg-white rounded-xl border border-stone-200 hover:border-brand cursor-pointer transition flex items-center justify-between shadow-2xs group">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                                <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-wood-dark truncate">2. Fotoğraf (Arka Yüz)</div>
                                <div class="text-[10px] text-stone-400 truncate" id="backUploadLabel">Fotoğraf seçin...</div>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold text-brand bg-brand-light px-2.5 py-1 rounded-lg group-hover:bg-brand group-hover:text-white transition shrink-0">Seç</span>
                    </div>
                </div>

                <!-- Saat 16:00 Kargo Kuralı Sayacı -->
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-950 text-xs flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-600 text-white flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-truck-fast text-xs"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-black uppercase text-amber-800 tracking-wider">📦 AYNI GÜN ATÖLYEDEN KARGO</div>
                        <div class="text-[11px] font-bold leading-tight mt-0.5">Saat 16:00'a kadar verilen siparişler bugün kargoda!</div>
                    </div>
                </div>

                <!-- Hediye Paketi & Notu Seçeneği -->
                <div class="bg-wood-warm p-3.5 rounded-xl border border-stone-200 space-y-2">
                    <label class="flex items-center gap-2 text-xs font-bold text-wood-dark cursor-pointer select-none">
                        <input type="checkbox" id="giftOptionCheckbox" onchange="toggleGiftBox(this)" class="w-4 h-4 text-brand rounded border-stone-300 focus:ring-brand">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-gift text-brand"></i> Hediye Paketi İstiyorum</span>
                    </label>

                    <div id="giftNoteArea" class="hidden pt-2 border-t border-stone-200">
                        <label class="block text-[11px] font-bold text-wood-dark mb-1">🎁 Hediye Notunuz (Opsiyonel):</label>
                        <textarea id="giftNoteText" rows="2" maxlength="200" placeholder="Paketin içine özel mühürlü kartla eklenmesini istediğiniz not..." class="w-full text-xs p-2 rounded-lg border border-stone-300 bg-white outline-none focus:border-brand"></textarea>
                    </div>
                </div>

                <!-- Eylemler -->
                <div class="space-y-2 pt-1">
                    <button type="submit" class="w-full py-4 bg-brand hover:bg-brand-dark text-white font-bold text-sm rounded-2xl shadow-xl shadow-brand/25 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Kişiselleştir & Sepete Ekle</span>
                    </button>

                    <button type="button" onclick="showToast('Favorilerinize eklendi!', 'info')" class="w-full py-2.5 bg-stone-100 hover:bg-rose-50 text-stone-700 hover:text-rose-600 font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5">
                        <i class="fa-regular fa-heart text-red-500"></i>
                        <span>Favorilere Ekle</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Instagram Reel Modal -->
<div id="reelModal" class="fixed inset-0 z-[99999] bg-black/90 hidden items-center justify-center p-4 backdrop-blur-md" onclick="closeReelModal(event)">
    <div class="relative w-full max-w-sm h-[80vh] bg-black rounded-3xl overflow-hidden shadow-2xl border border-stone-800 flex flex-col" onclick="event.stopPropagation()">
        <button type="button" onclick="closeReelModal()" class="absolute top-4 right-4 text-white text-2xl font-bold z-30 bg-black/60 w-8 h-8 rounded-full flex items-center justify-center hover:text-brand transition">&times;</button>
        <div class="flex-1 flex items-center justify-center p-6 text-center text-white space-y-4">
            <div>
                <i class="fa-brands fa-instagram text-5xl text-pink-500 mb-3 block"></i>
                <h4 class="font-serif text-lg font-bold">Atölye Masif İşçilik Videosu</h4>
                <p class="text-xs text-stone-400 mt-2">Dönen çerçevenin gerçek hareketini ve sokak lambası ışık geçişlerini Instagram sayfamızda keşfedin.</p>
                <a href="https://instagram.com/ahsapevimmanisa" target="_blank" class="inline-block mt-4 px-6 py-2.5 bg-gradient-to-r from-amber-500 via-rose-500 to-purple-600 text-white font-bold text-xs rounded-xl shadow-lg">
                    Instagram'da İzle (@ahsapevimmanisa)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 3D Flip Engine
    let isFlipped = false;

    function flipFrame3D() {
        const frame = document.getElementById('rotatingFrame3D');
        const indicator = document.getElementById('faceIndicator');
        if (!frame) return;

        isFlipped = !isFlipped;
        if (isFlipped) {
            frame.style.transform = 'rotateY(180deg)';
            if (indicator) indicator.textContent = 'ARKA YÜZ';
        } else {
            frame.style.transform = 'rotateY(0deg)';
            if (indicator) indicator.textContent = 'ÖN YÜZ';
        }
    }

    // Dynamic Photo Upload & Live 3D Frame Texture Update
    function previewUpload(input, face) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                if (face === 'front') {
                    const img = document.getElementById('previewFrontImage');
                    const label = document.getElementById('frontUploadLabel');
                    if (img) img.src = e.target.result;
                    if (label) {
                        label.textContent = file.name;
                        label.classList.add('text-brand', 'font-bold');
                    }
                    if (isFlipped) flipFrame3D(); // switch to front view
                    showToast('1. Fotoğraf (Ön Yüz) başarıyla yüklendi!', 'success');
                } else if (face === 'back') {
                    const img = document.getElementById('previewBackImage');
                    const label = document.getElementById('backUploadLabel');
                    if (img) img.src = e.target.result;
                    if (label) {
                        label.textContent = file.name;
                        label.classList.add('text-purple-600', 'font-bold');
                    }
                    if (!isFlipped) flipFrame3D(); // switch to back view to demonstrate
                    showToast('2. Fotoğraf (Arka Yüz) başarıyla yüklendi!', 'success');
                }
            };

            reader.readAsDataURL(file);
        }
    }

    // Toggle Gift Box
    function toggleGiftBox(checkbox) {
        const giftArea = document.getElementById('giftNoteArea');
        if (giftArea) {
            if (checkbox.checked) {
                giftArea.classList.remove('hidden');
            } else {
                giftArea.classList.add('hidden');
            }
        }
    }

    // Switch Tabs
    function switchTab(tabId) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-brand', 'bg-brand-light');
            btn.classList.add('text-stone-600');
        });
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        const activeBtn = document.getElementById('tabBtn-' + tabId);
        const activeContent = document.getElementById('tabContent-' + tabId);

        if (activeBtn) {
            activeBtn.classList.add('text-brand', 'bg-brand-light');
            activeBtn.classList.remove('text-stone-600');
        }
        if (activeContent) {
            activeContent.classList.remove('hidden');
        }
    }

    // Handle Form Submit & Cart Drawer Trigger
    function handleAddToCartCustom(e) {
        e.preventDefault();

        const frontInput = document.getElementById('frontPhotoInput');
        const backInput = document.getElementById('backPhotoInput');

        const hasFront = frontInput && frontInput.files && frontInput.files.length > 0;
        const hasBack = backInput && backInput.files && backInput.files.length > 0;

        // Validation: If no photos uploaded, kindly notify
        if (!hasFront && !hasBack) {
            alert('⚠️ Lütfen siparişinizi tamamlamadan önce ahşap çerçevenize basılacak fotoğrafınızı yükleyiniz.');
            return false;
        }

        showToast('Kişiselleştirilmiş ahşap çerçeveniz sepete eklendi!', 'success');
        openCartDrawer();
        return false;
    }

    // Reel Modal
    function openReelModal() {
        const modal = document.getElementById('reelModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeReelModal(e) {
        const modal = document.getElementById('reelModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>
@endpush
