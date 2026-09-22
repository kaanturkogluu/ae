<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Ürün Listesi
     */
    public function index()
    {
        $products = Product::with('category')->ordered()->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Yeni Ürün Ekleme Formu
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Yeni Ürün Kaydı (Cloudflare R2 Destekli)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'slug'                 => 'nullable|string|max:255',
            'category_id'          => 'nullable|exists:categories,id',
            'original_price'       => 'required|numeric|min:0',
            'discount_price'       => 'nullable|numeric|min:0',
            'stock'                => 'required|integer|min:0',
            'description'          => 'nullable|string',
            'main_image'           => 'nullable|image|max:10240',
            'other_images.*'       => 'nullable|image|max:10240',
            'instagram_short_link' => 'nullable|string|max:500',
            'youtube_link'         => 'nullable|string|max:500',
            'tiktok_short_link'    => 'nullable|string|max:500',
        ]);

        $disk = config('filesystems.default') === 'r2' ? 'r2' : (config('filesystems.disks.r2.key') ? 'r2' : 'public');

        // 1. Ana Kapak Görselini Yükle
        $mainImageUrl = null;
        if ($request->hasFile('main_image')) {
            $mainPath = Storage::disk($disk)->putFile('products/main', $request->file('main_image'));
            $mainImageUrl = $disk === 'r2' 
                ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . $mainPath 
                : '/storage/' . $mainPath;
        }

        // 2. Çoklu Galeri Görsellerini Yükle
        $galleryUrls = [];
        if ($request->hasFile('other_images')) {
            foreach ($request->file('other_images') as $file) {
                $galPath = Storage::disk($disk)->putFile('products/gallery', $file);
                $galleryUrls[] = $disk === 'r2' 
                    ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . $galPath 
                    : '/storage/' . $galPath;
            }
        }

        // 3. Slug Oluştur
        $slugCandidate = $request->filled('slug') ? $request->slug : $request->name;
        $slug = Product::generateUniqueSlug($slugCandidate);

        $product = Product::create([
            'category_id'          => $request->category_id,
            'name'                 => $request->name,
            'slug'                 => $slug,
            'description'          => $request->description,
            'original_price'       => $request->original_price,
            'discount_price'       => $request->filled('discount_price') ? $request->discount_price : null,
            'stock'                => $request->stock ?? 0,
            'main_image'           => $mainImageUrl,
            'other_images'         => $galleryUrls,
            'instagram_short_link' => $request->instagram_short_link,
            'youtube_link'         => $request->youtube_link,
            'tiktok_short_link'    => $request->tiktok_short_link,
            'is_active'            => $request->has('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla eklendi (SEO: /urun/' . $product->slug . ').');
    }

    /**
     * Ürün Düzenleme Formu
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Ürün Güncelleme (Cloudflare R2 Destekli)
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'                 => 'required|string|max:255',
            'slug'                 => 'nullable|string|max:255',
            'category_id'          => 'nullable|exists:categories,id',
            'original_price'       => 'required|numeric|min:0',
            'discount_price'       => 'nullable|numeric|min:0',
            'stock'                => 'required|integer|min:0',
            'description'          => 'nullable|string',
            'main_image'           => 'nullable|image|max:10240',
            'other_images.*'       => 'nullable|image|max:10240',
            'instagram_short_link' => 'nullable|string|max:500',
            'youtube_link'         => 'nullable|string|max:500',
            'tiktok_short_link'    => 'nullable|string|max:500',
        ]);

        $disk = config('filesystems.default') === 'r2' ? 'r2' : (config('filesystems.disks.r2.key') ? 'r2' : 'public');

        $mainImageUrl = $product->main_image;
        if ($request->hasFile('main_image')) {
            $mainPath = Storage::disk($disk)->putFile('products/main', $request->file('main_image'));
            $mainImageUrl = $disk === 'r2' 
                ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . $mainPath 
                : '/storage/' . $mainPath;
        }

        $existingGallery = $request->input('keep_other_images', []);
        if (!is_array($existingGallery)) {
            $existingGallery = [];
        }

        $newGalleryUrls = [];
        if ($request->hasFile('other_images')) {
            foreach ($request->file('other_images') as $file) {
                $galPath = Storage::disk($disk)->putFile('products/gallery', $file);
                $newGalleryUrls[] = $disk === 'r2' 
                    ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . $galPath 
                    : '/storage/' . $galPath;
            }
        }

        $allOtherImages = array_values(array_merge($existingGallery, $newGalleryUrls));

        $slug = $product->slug;
        if ($request->filled('slug') && $request->slug !== $product->slug) {
            $slug = Product::generateUniqueSlug($request->slug, $product->id);
        } elseif ($request->name !== $product->name && !$request->filled('slug')) {
            $slug = Product::generateUniqueSlug($request->name, $product->id);
        }

        $product->update([
            'category_id'          => $request->category_id,
            'name'                 => $request->name,
            'slug'                 => $slug,
            'description'          => $request->description,
            'original_price'       => $request->original_price,
            'discount_price'       => $request->filled('discount_price') ? $request->discount_price : null,
            'stock'                => $request->stock ?? 0,
            'main_image'           => $mainImageUrl,
            'other_images'         => $allOtherImages,
            'instagram_short_link' => $request->instagram_short_link,
            'youtube_link'         => $request->youtube_link,
            'tiktok_short_link'    => $request->tiktok_short_link,
            'is_active'            => $request->has('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla güncellendi.');
    }

    /**
     * Ürün Silme
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla silindi.');
    }
}
