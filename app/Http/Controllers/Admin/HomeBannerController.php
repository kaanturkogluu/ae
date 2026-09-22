<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeBannerController extends Controller
{
    public function index()
    {
        try {
            $banners = HomeBanner::orderBy('order', 'asc')->get();
        } catch (\Throwable $e) {
            $banners = collect();
        }
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|max:10240',
            'link'  => 'nullable|string|max:500',
        ]);

        $disk = config('filesystems.default') === 'r2' ? 'r2' : (config('filesystems.disks.r2.key') ? 'r2' : 'public');
        $path = Storage::disk($disk)->putFile('banners', $request->file('image'));
        $imageUrl = $disk === 'r2' ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . $path : '/storage/' . $path;

        HomeBanner::create([
            'title'     => $request->title,
            'image'     => $imageUrl,
            'link'      => $request->link,
            'order'     => $request->input('order', 0),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Afiş/Banner başarıyla eklendi.');
    }

    public function destroy($id)
    {
        $banner = HomeBanner::findOrFail($id);
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner silindi.');
    }
}
