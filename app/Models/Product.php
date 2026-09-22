<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'original_price',
        'discount_price',
        'stock',
        'main_image',
        'other_images',
        'instagram_short_link',
        'youtube_link',
        'tiktok_short_link',
        'is_active',
    ];

    protected $casts = [
        'other_images'   => 'array',
        'is_active'      => 'boolean',
        'stock'          => 'integer',
        'original_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Efektif Satış Fiyatı (İndirim varsa discount_price, yoksa original_price)
     */
    public function getPriceAttribute()
    {
        return $this->discount_price !== null && $this->discount_price > 0
            ? $this->discount_price
            : $this->original_price;
    }

    /**
     * İndirim Yüzdesi Hesabı
     */
    public function getDiscountPercentAttribute(): int
    {
        if ($this->discount_price && $this->original_price > $this->discount_price && $this->original_price > 0) {
            return (int) round((1 - ($this->discount_price / $this->original_price)) * 100);
        }
        return 0;
    }

    /**
     * Ana Görsel URL'i
     */
    public function getMainImageUrlAttribute(): string
    {
        if (!$this->main_image) {
            return url('/cerceve.png');
        }
        if (str_starts_with($this->main_image, 'http://') || str_starts_with($this->main_image, 'https://')) {
            return $this->main_image;
        }
        return url($this->main_image);
    }

    /**
     * Geriye dönük uyumluluk ($product->image)
     */
    public function getImageAttribute(): string
    {
        return $this->main_image_url;
    }

    /**
     * Tüm Galeri Görselleri URL Listesi
     */
    public function getGalleryUrlsAttribute(): array
    {
        $urls = [];
        if ($this->main_image) {
            $urls[] = $this->main_image_url;
        }

        if (is_array($this->other_images)) {
            foreach ($this->other_images as $img) {
                if (empty($img)) continue;
                if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                    $urls[] = $img;
                } else {
                    $urls[] = url($img);
                }
            }
        }

        return array_values(array_unique($urls));
    }

    /**
     * YouTube ID & Embed URL
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->youtube_link) return null;
        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/', $this->youtube_link, $matches);
        return $matches[1] ?? null;
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $id = $this->youtube_id;
        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }

    /**
     * Instagram Kodu & Embed URL
     */
    public function getInstagramCodeAttribute(): ?string
    {
        if (!$this->instagram_short_link) return null;
        preg_match('/(?:instagram\.com|instagr\.am)\/(?:p|reel|tv)\/([A-Za-z0-9_-]+)/i', $this->instagram_short_link, $matches);
        return $matches[1] ?? null;
    }

    public function getInstagramEmbedUrlAttribute(): ?string
    {
        $code = $this->instagram_code;
        return $code ? "https://www.instagram.com/p/{$code}/embed" : null;
    }

    /**
     * Otomatik SEO Uyumlu ve Benzersiz Slug Üretici
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = \Illuminate\Support\Str::slug($title);
        if (empty($slug)) {
            $slug = 'urun';
        }

        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    protected static function booted()
    {
        static::saving(function ($product) {
            if (empty($product->slug) && !empty($product->name)) {
                $product->slug = static::generateUniqueSlug($product->name, $product->id ?? null);
            }
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function getUrlAttribute(): string
    {
        return url('/urun/' . ($this->slug ?: $this->id));
    }
}
