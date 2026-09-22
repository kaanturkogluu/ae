@extends('layouts.admin')

@section('header', 'Ürün Yönetimi')

@section('content')
<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs space-y-6">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-gray-100">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Ürün Listesi</h3>
            <p class="text-xs text-gray-500 mt-0.5">Mağazanızdaki tüm ahşap ürünleri, Cloudflare R2 görsellerini ve stok durumlarını yönetin.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('seo.sitemap') }}" target="_blank" class="py-2 px-3 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold rounded-xl text-xs transition flex items-center gap-1.5 border border-gray-200">
                <i class="fa-solid fa-sitemap text-amber-700"></i> sitemap.xml
            </a>
            <a href="{{ route('seo.urunler_xml') }}" target="_blank" class="py-2 px-3 bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold rounded-xl text-xs transition flex items-center gap-1.5 border border-amber-200">
                <i class="fa-solid fa-file-code text-amber-700"></i> urunler.xml
            </a>
            <a href="{{ route('admin.products.create') }}" class="py-2.5 px-4 bg-[#C87A53] hover:bg-[#A65F38] text-white font-bold rounded-xl text-xs transition flex items-center gap-1.5 shadow-md shadow-brand/20">
                <i class="fa-solid fa-plus"></i> Yeni Ürün Ekle
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-[10px] tracking-wider border-b border-gray-200">
                <tr>
                    <th class="p-3.5 w-16 text-center">Görsel</th>
                    <th class="p-3.5">Ürün Adı & Kategori</th>
                    <th class="p-3.5">Fiyatlandırma</th>
                    <th class="p-3.5 text-center">Stok</th>
                    <th class="p-3.5 text-center">Sosyal Videolar</th>
                    <th class="p-3.5 text-center">Durum</th>
                    <th class="p-3.5 text-right">Eylemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
                @forelse($products as $product)
                    <tr class="hover:bg-amber-50/40 transition">
                        <!-- Görsel -->
                        <td class="p-3.5 text-center">
                            <div class="w-12 h-14 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden mx-auto relative group">
                                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @if(!empty($product->other_images) && count($product->other_images) > 0)
                                    <span class="absolute bottom-0 inset-x-0 bg-black/70 text-white text-[8px] font-mono font-bold">
                                        +{{ count($product->other_images) }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Ürün Adı & Kategori -->
                        <td class="p-3.5">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="font-bold text-gray-800 hover:text-[#C87A53] transition block leading-snug">
                                {{ $product->name }}
                            </a>
                            <div class="flex items-center gap-2 mt-0.5 text-[11px] text-gray-400">
                                <span>{{ $product->category ? $product->category->name : 'Kategorisiz' }}</span>
                                <span>•</span>
                                <span class="font-mono text-gray-400">/urun/{{ $product->slug }}</span>
                            </div>
                        </td>

                        <!-- Fiyatlandırma -->
                        <td class="p-3.5">
                            @if($product->discount_price && $product->discount_price > 0)
                                <div class="font-mono font-bold text-emerald-700 text-sm">₺{{ number_format($product->discount_price, 2, ',', '.') }}</div>
                                <div class="text-[10px] text-gray-400 line-through font-mono">₺{{ number_format($product->original_price, 2, ',', '.') }}</div>
                            @else
                                <div class="font-mono font-bold text-gray-800 text-sm">₺{{ number_format($product->original_price, 2, ',', '.') }}</div>
                            @endif
                        </td>

                        <!-- Stok -->
                        <td class="p-3.5 text-center font-mono">
                            @if($product->stock > 0)
                                <span class="px-2.5 py-1 bg-green-50 text-green-700 font-bold rounded-lg border border-green-200">
                                    {{ $product->stock }} Adet
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 font-bold rounded-lg border border-red-200">
                                    Tükendi
                                </span>
                            @endif
                        </td>

                        <!-- Sosyal Medya Videoları -->
                        <td class="p-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5 text-base">
                                @if($product->instagram_short_link)
                                    <a href="{{ $product->instagram_short_link }}" target="_blank" class="text-pink-600 hover:scale-110 transition" title="Instagram Linki">
                                        <i class="fa-brands fa-instagram"></i>
                                    </a>
                                @endif
                                @if($product->youtube_link)
                                    <a href="{{ $product->youtube_link }}" target="_blank" class="text-red-600 hover:scale-110 transition" title="YouTube Linki">
                                        <i class="fa-brands fa-youtube"></i>
                                    </a>
                                @endif
                                @if($product->tiktok_short_link)
                                    <a href="{{ $product->tiktok_short_link }}" target="_blank" class="text-black hover:scale-110 transition" title="TikTok Linki">
                                        <i class="fa-brands fa-tiktok"></i>
                                    </a>
                                @endif
                                @if(!$product->instagram_short_link && !$product->youtube_link && !$product->tiktok_short_link)
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </div>
                        </td>

                        <!-- Durum -->
                        <td class="p-3.5 text-center">
                            @if($product->is_active)
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                    Yayında
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 text-[10px] font-bold">
                                    Gizli
                                </span>
                            @endif
                        </td>

                        <!-- Eylemler -->
                        <td class="p-3.5 text-right space-x-1 whitespace-nowrap">
                            <a href="{{ $product->url }}" target="_blank" class="p-2 rounded-lg bg-gray-100 hover:bg-[#C87A53] hover:text-white text-gray-600 transition inline-block" title="Sitede Görüntüle">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 rounded-lg bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-600 transition inline-block" title="Düzenle">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg bg-gray-100 hover:bg-red-600 hover:text-white text-gray-600 transition" title="Sil">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            <i class="fa-solid fa-box-open text-4xl mb-3 block text-gray-300"></i>
                            Henüz kayıtlı ürün bulunamadı. <br>
                            <a href="{{ route('admin.products.create') }}" class="inline-block mt-3 px-4 py-2 bg-[#C87A53] text-white rounded-xl font-bold text-xs">
                                + İlk Ürünü Ekle
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
