<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CreateMediaTable;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // ✅ Helper — resolve image_url from product's media_ids
    private function resolveImageUrl($product): string
    {
        if (!$product || !$product->media_ids) {
            return asset('storage/default/no-product-image.PNG');
        }

        $media = CreateMediaTable::where('id', $product->media_ids)
            ->where('is_deleted', 0)
            ->value('file_name');

        return $media
            ? asset('storage/media/' . $media)
            : asset('storage/default/no-product-image.PNG');
    }
    // GET /api/wishlist
    public function index(Request $request)
    {
        $wishlist = Wishlist::where('user_id', $request->user()->id)
            ->with(['product' => function ($q) {
                $q->select(
                    'id',
                    'name',
                    'slug',
                    'price',
                    'mrp',
                    'media_ids',
                    'status',
                    'is_publish'
                );
            }])
            ->latest()
            ->get();
        // ✅ Append image_url
        $wishlist->each(function ($item) {
            if ($item->product) {
                $item->product->image_url = $this->resolveImageUrl($item->product);
            }
        });

        return response()->json([
            'status' => true,
            'data'  => $wishlist,
            'total' => $wishlist->count(),
        ]);
    }

    // POST /api/wishlist/{product} — toggle (add if not exists, remove if exists)
    public function toggle(Request $request, $productId)
    {
        $userId = $request->user()->id;

        $existing = Wishlist::where('user_id',   $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'wishlisted' => false,
                'message'    => 'Removed from wishlist',
            ]);
        }

        Wishlist::create([
            'user_id'    => $userId,
            'product_id' => $productId,
        ]);

        return response()->json([
            'status' => true,
            'wishlisted' => true,
            'message'    => 'Added to wishlist',
        ]);
    }

    // DELETE /api/wishlist/{product}
    public function remove(Request $request, $productId)
    {
        Wishlist::where('user_id',   $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['status' => true, 'message' => 'Removed from wishlist']);
    }
}
