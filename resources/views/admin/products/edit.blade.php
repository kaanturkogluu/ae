@extends('layouts.admin')

@section('header', 'Ürün Düzenle')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Ürünü Düzenle: <span class="text-[#C87A53]">{{ $product->name }}</span></h2>
            <p class="text-xs text-gray-500 mt-0.5">Ürün detaylarını, R2 görsellerini ve video bağlantılarını güncelleyin.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ $product->url }}" target="_blank" class="py-2 px-3.5 bg-brand-light text-[#C87A53] hover:bg-[#C87A53] hover:text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                <i class="fa-solid fa-eye"></i> Sitede Gör
            </a>
            <a href="{{ route('admin.products.index') }}" class="py-2 px-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Listeye Dön
            </a>
        </div>
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

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Temel Bilgiler -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs space-y-4">
            <h3 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-box text-[#C87A53]"></i> Temel Bilgiler
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Ürün Adı -->
                <div class="md:col-span-2 space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Ürün Adı *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition">
                </div>

                <!-- Kategori -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Kategori</label>
                    <select name="category_id" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition cursor-pointer">
                        <option value="">Kategori Seçiniz (Opsiyonel)...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- SEO URL / Slug -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">SEO URL / Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition">
                </div>
            </div>

            <!-- Açıklama -->
            <div class="space-y-1 pt-2">
                <label class="block text-xs font-bold text-gray-700 uppercase">Ürün Açıklaması</label>
                <textarea name="description" rows="5" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- 2. Fiyatlandırma & Stok -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs space-y-4">
            <h3 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-tags text-[#C87A53]"></i> Fiyatlandırma & Stok
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Orijinal Fiyat -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Orijinal Fiyat (TL) *</label>
                    <input type="number" step="0.01" name="original_price" value="{{ old('original_price', $product->original_price) }}" required class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition font-mono font-bold">
                </div>

                <!-- İndirimli Fiyat -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">İndirimli Fiyat (TL)</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" placeholder="Opsiyonel" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition font-mono font-bold text-emerald-700">
                </div>

                <!-- Stok -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Stok Adedi *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0" class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-[#C87A53] focus:bg-white transition font-mono">
                </div>
            </div>
        </div>

        <!-- 3. Görseller (R2 Depolama) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs space-y-5">
            <h3 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up text-[#C87A53]"></i> Ürün Görselleri
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ana Kapak Görseli -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Ana Kapak Görseli (main_image)</label>
                    
                    @if($product->main_image)
                        <div class="relative w-full h-40 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden mb-2">
                            <img src="{{ $product->main_image_url }}" class="w-full h-full object-contain">
                            <span class="absolute top-2 left-2 bg-black/60 text-white text-[9px] px-2 py-0.5 rounded font-bold">Mevcut Kapak</span>
                        </div>
                    @endif

                    <div class="border-2 border-dashed border-gray-300 hover:border-[#C87A53] rounded-xl p-3 text-center cursor-pointer bg-gray-50 transition" onclick="document.getElementById('editMainImageInput').click()">
                        <input type="file" name="main_image" id="editMainImageInput" accept="image/*" class="hidden" onchange="previewEditMainImage(this)">
                        <div class="text-xs text-gray-600 font-bold">Yeni Kapak Seç (Değiştirmek için)</div>
                        <span class="text-[10px] text-gray-400">JPG, PNG, WEBP</span>
                        <img id="editMainImagePreview" class="w-full h-28 object-contain rounded-lg hidden mt-2">
                    </div>
                </div>

                <!-- Galeri Görselleri -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Galeri Görselleri (other_images)</label>

                    @if(!empty($product->other_images) && is_array($product->other_images))
                        <div class="grid grid-cols-4 gap-2 mb-3">
                            @foreach($product->other_images as $index => $img)
                                <div class="relative aspect-square rounded-lg bg-gray-100 border border-gray-200 overflow-hidden group" id="galBox-{{ $index }}">
                                    <img src="{{ str_starts_with($img, 'http') ? $img : url($img) }}" class="w-full h-full object-cover">
                                    <input type="hidden" name="keep_other_images[]" value="{{ $img }}" id="keepGal-{{ $index }}">
                                    <button type="button" onclick="removeGalleryImg('{{ $index }}')" class="absolute top-1 right-1 w-5 h-5 rounded-full bg-red-600 text-white text-[10px] flex items-center justify-center opacity-90 hover:opacity-100 shadow-sm" title="Fotoğrafı Kaldır">
                                        &times;
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="border-2 border-dashed border-gray-300 hover:border-[#C87A53] rounded-xl p-3 text-center cursor-pointer bg-gray-50 transition" onclick="document.getElementById('editOtherImagesInput').click()">
                        <input type="file" name="other_images[]" id="editOtherImagesInput" multiple accept="image/*" class="hidden" onchange="previewEditOtherImages(this)">
                        <div class="text-xs text-gray-600 font-bold">+ Yeni Galeri Fotoğrafları Ekle</div>
                        <span class="text-[10px] text-gray-400">Birden fazla resim seçebilirsiniz</span>
                        <div id="editOtherImagesPreviewGrid" class="grid grid-cols-4 gap-2 w-full mt-2 hidden"></div>
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
                <!-- Instagram Reels -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase flex items-center gap-1.5">
                        <i class="fa-brands fa-instagram text-pink-600"></i> Instagram Reels Linki
                    </label>
                    <input type="url" name="instagram_short_link" value="{{ old('instagram_short_link', $product->instagram_short_link) }}" placeholder="https://www.instagram.com/reel/..." class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-pink-500 focus:bg-white transition">
                </div>

                <!-- YouTube Video -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase flex items-center gap-1.5">
                        <i class="fa-brands fa-youtube text-red-600"></i> YouTube Linki
                    </label>
                    <input type="url" name="youtube_link" value="{{ old('youtube_link', $product->youtube_link) }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-red-500 focus:bg-white transition">
                </div>

                <!-- TikTok Video -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase flex items-center gap-1.5">
                        <i class="fa-brands fa-tiktok text-black"></i> TikTok Linki
                    </label>
                    <input type="url" name="tiktok_short_link" value="{{ old('tiktok_short_link', $product->tiktok_short_link) }}" placeholder="https://www.tiktok.com/@.../video/..." class="w-full text-xs p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none focus:border-black focus:bg-white transition">
                </div>
            </div>
        </div>

        <!-- 5. Durum & Güncelle Butonları -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <label class="flex items-center gap-3 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-5 h-5 text-[#C87A53] rounded border-gray-300 focus:ring-[#C87A53]">
                <div>
                    <span class="text-xs font-bold text-gray-800 block">Ürün Satışta / Yayında</span>
                    <span class="text-[11px] text-gray-400">İşareti kaldırırsanız ürün sitede gizlenir.</span>
                </div>
            </label>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('admin.products.index') }}" class="py-3 px-5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold transition text-center flex-1 sm:flex-initial">
                    İptal
                </a>
                <button type="submit" class="py-3 px-7 bg-[#C87A53] hover:bg-[#A65F38] text-white text-xs font-bold rounded-xl shadow-lg shadow-brand/20 transition flex items-center justify-center gap-2 flex-1 sm:flex-initial">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Değişiklikleri Kaydet</span>
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    function removeGalleryImg(index) {
        const box = document.getElementById('galBox-' + index);
        const input = document.getElementById('keepGal-' + index);
        if (box) box.remove();
        if (input) input.remove();
    }

    function previewEditMainImage(input) {
        const preview = document.getElementById('editMainImagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewEditOtherImages(input) {
        const grid = document.getElementById('editOtherImagesPreviewGrid');
        grid.innerHTML = '';
        if (input.files && input.files.length > 0) {
            grid.classList.remove('hidden');
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
