<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Kullanıcının favori ürünlerini listeler
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $favorites = Product::whereIn('id', function ($query) {
                $query->select('product_id')
                    ->from('favorites')
                    ->where('user_id', Auth::id());
            })
            ->with('category')
            ->where('is_active', true)
            ->ordered()
            ->get();
        } else {
            $favIds = session()->get('favorites', []);
            $favorites = empty($favIds)
                ? collect()
                : Product::whereIn('id', $favIds)
                    ->with('category')
                    ->where('is_active', true)
                    ->ordered()
                    ->get();
        }

        return view('favorites.index', compact('favorites'));
    }

    /**
     * AJAX ile ürün favori durumunu açıp kapatır (Toggle)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = (int) $request->input('product_id');

        if (Auth::check()) {
            $userId = Auth::id();
            $favorite = Favorite::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($favorite) {
                $favorite->delete();
                $action = 'removed';
                $message = 'Ürün favorilerinizden çıkarıldı.';
            } else {
                Favorite::create([
                    'user_id'    => $userId,
                    'product_id' => $productId,
                ]);
                $action = 'added';
                $message = 'Ürün favorilerinize eklendi.';
            }

            $count = Favorite::where('user_id', $userId)->count();
        } else {
            $favorites = session()->get('favorites', []);

            if (in_array($productId, $favorites)) {
                $favorites = array_values(array_diff($favorites, [$productId]));
                $action = 'removed';
                $message = 'Ürün favorilerinizden çıkarıldı.';
            } else {
                $favorites[] = $productId;
                $action = 'added';
                $message = 'Ürün favorilerinize eklendi.';
            }

            session()->put('favorites', $favorites);
            $count = count($favorites);
        }

        return response()->json([
            'status'  => 'success',
            'action'  => $action,
            'count'   => $count,
            'message' => $message,
        ]);
    }

    /**
     * Favori sayısını ve durumunu döndüren yardımcı API
     */
    public function getFavorites(Request $request)
    {
        if (Auth::check()) {
            $productIds = Favorite::where('user_id', Auth::id())->pluck('product_id')->toArray();
        } else {
            $productIds = session()->get('favorites', []);
        }

        return response()->json([
            'status'     => 'success',
            'count'      => count($productIds),
            'productIds' => $productIds,
        ]);
    }
}
