@extends('layouts.admin')

@section('header', 'Yeni Ürün Ekle')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Yeni Ürün Ekle</h2>
            <p class="text-xs text-gray-500 mt-0.5">Ürün detaylarını, fotoğraflarını ve sosyal medya video bağlantılarını giriniz.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="py-2 px-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Ürün Listesine Dön
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs space-y-1">
            <div class="font-bold">⚠️ Lütfen aşağıdaki hataları düzeltiniz:</div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. Temel Ürün Bilgileri -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs space-y-4">
            <h3 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-box text-[#C87A53]"></i> Temel Bilgiler
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Ürün Adı -->
                <div class="md:col-span-2 space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Ürün Adı *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Örn: Kişiye Özel El Yapımı Ahşap Dönen Çerçeve Sokak Lambalı" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition">
                </div>

                <!-- Kategori -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Kategori</label>
                    <select name="category_id" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition cursor-pointer">
                        <option value="">Kategori Seçiniz (Opsiyonel)...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Özel Slug (Opsiyonel) -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">SEO URL / Slug (Opsiyonel)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Boş bırakılırsa isimden otomatik üretilir" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition">
                </div>
            </div>

            <!-- Ürün Açıklaması -->
            <div class="space-y-1 pt-2">
                <label class="block text-xs font-bold text-gray-700 uppercase">Ürün Açıklaması</label>
                <textarea name="description" rows="5" placeholder="Masif ahşap işçiliği, boyutlar, LED aydınlatma ve kullanım detaylarını yazınız..." class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- 2. Fiyatlandırma & Stok -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs space-y-4">
            <h3 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-tags text-[#C87A53]"></i> Fiyatlandırma & Stok
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Orijinal Liste Fiyatı -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Orijinal Fiyat (TL) *</label>
                    <input type="number" step="0.01" name="original_price" value="{{ old('original_price', '999.00') }}" required placeholder="1299.00" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition font-mono font-bold">
                </div>

                <!-- İndirimli Satış Fiyatı -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">İndirimli Fiyat (TL)</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price') }}" placeholder="999.00 (Opsiyonel)" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition font-mono font-bold text-emerald-700">
                </div>

                <!-- Stok Miktarı -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Stok Adedi *</label>
                    <input type="number" name="stock" value="{{ old('stock', 100) }}" required min="0" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition font-mono">
                </div>
            </div>
        </div>

        <!-- 3. Görseller (Cloudflare R2 Depolama) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <h3 class="font-bold text-sm text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-[#C87A53]"></i> Ürün Görselleri (Cloudflare R2)
                </h3>
                <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">R2 Global CDN</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ana Kapak Görseli -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Ana Kapak Görseli (main_image)</label>
                    <div class="border-2 border-dashed border-gray-300 hover:border-[#C87A53] rounded-2xl p-4 text-center cursor-pointer bg-gray-50 transition" onclick="document.getElementById('mainImageInput').click()">
                        <input type="file" name="main_image" id="mainImageInput" accept="image/*" class="hidden" onchange="previewMainImage(this)">
                        <div id="mainImagePlaceholder" class="space-y-2 py-4">
                            <i class="fa-solid fa-image text-3xl text-gray-400"></i>
                            <div class="text-xs font-bold text-gray-600">Kapak Fotoğrafı Seç</div>
                            <span class="text-[10px] text-gray-400 block">JPG, PNG, WEBP (Maks 10MB)</span>
                        </div>
                        <img id="mainImagePreview" class="w-full h-44 object-contain rounded-xl hidden mx-auto shadow-xs">
                    </div>
                </div>

                <!-- Çoklu Galeri Görselleri -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Çoklu Galeri Görselleri (other_images)</label>
                    <div class="border-2 border-dashed border-gray-300 hover:border-[#C87A53] rounded-2xl p-4 text-center cursor-pointer bg-gray-50 transition min-h-[160px] flex flex-col items-center justify-center" onclick="document.getElementById('otherImagesInput').click()">
                        <input type="file" name="other_images[]" id="otherImagesInput" multiple accept="image/*" class="hidden" onchange="previewOtherImages(this)">
                        <div id="otherImagesPlaceholder" class="space-y-2 py-2">
                            <i class="fa-solid fa-images text-3xl text-gray-400"></i>
                            <div class="text-xs font-bold text-gray-600">Çoklu Galeri Fotoğrafları Seç</div>
                            <span class="text-[10px] text-gray-400 block">Birden fazla resim seçebilirsiniz (Ctrl + Tık)</span>
                        </div>
                        <div id="otherImagesPreviewGrid" class="grid grid-cols-4 gap-2 w-full mt-2 hidden"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Sosyal Medya & Video Bağlantıları -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs space-y-4">
            <h3 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-video text-[#C87A53]"></i> Sosyal Medya & Video Bağlantıları
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Instagram Reels Linki -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase flex items-center gap-1.5">
                        <i class="fa-brands fa-instagram text-pink-600"></i> Instagram Reels / Post Linki
                    </label>
                    <input type="url" name="instagram_short_link" value="{{ old('instagram_short_link') }}" placeholder="https://www.instagram.com/reel/CsqVIzTtTlh/" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-pink-500 focus:bg-white transition">
                </div>

                <!-- YouTube Video / Shorts Linki -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase flex items-center gap-1.5">
                        <i class="fa-brands fa-youtube text-red-600"></i> YouTube Video / Shorts Linki
                    </label>
                    <input type="url" name="youtube_link" value="{{ old('youtube_link') }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-red-500 focus:bg-white transition">
                </div>

                <!-- TikTok Video Linki -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase flex items-center gap-1.5">
                        <i class="fa-brands fa-tiktok text-black"></i> TikTok Video Linki
                    </label>
                    <input type="url" name="tiktok_short_link" value="{{ old('tiktok_short_link') }}" placeholder="https://www.tiktok.com/@.../video/..." class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-black focus:bg-white transition">
                </div>
            </div>
        </div>

        <!-- 5. Durum & Kaydet Butonları -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <label class="flex items-center gap-3 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-[#C87A53] rounded border-gray-300 focus:ring-[#C87A53]">
                <div>
                    <span class="text-xs font-bold text-gray-800 block">Ürün Satışta / Yayında Olsun</span>
                    <span class="text-[11px] text-gray-400">İşareti kaldırırsanız ürün sitede görünmez.</span>
                </div>
            </label>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('admin.products.index') }}" class="py-3 px-5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold transition text-center flex-1 sm:flex-initial">
                    İptal
                </a>
                <button type="submit" class="py-3 px-7 bg-[#C87A53] hover:bg-[#A65F38] text-white text-xs font-bold rounded-xl shadow-lg shadow-brand/20 transition flex items-center justify-center gap-2 flex-1 sm:flex-initial">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Ürünü Kaydet & R2'ye Yükle</span>
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewMainImage(input) {
        const placeholder = document.getElementById('mainImagePlaceholder');
        const preview = document.getElementById('mainImagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewOtherImages(input) {
        const grid = document.getElementById('otherImagesPreviewGrid');
        const placeholder = document.getElementById('otherImagesPlaceholder');
        grid.innerHTML = '';
        if (input.files && input.files.length > 0) {
            grid.classList.remove('hidden');
            placeholder.classList.add('hidden');
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const thumb = document.createElement('div');
                    thumb.className = 'w-full aspect-square rounded-lg bg-gray-100 overflow-hidden border border-gray-200 relative';
                    thumb.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    grid.appendChild(thumb);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endpush
