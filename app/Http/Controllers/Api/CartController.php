<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CreateMediaTable;
use Illuminate\Http\Request;

class CartController extends Controller
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
    // GET /api/cart
    public function index(Request $request)
    {
        $items = Cart::where('user_id', $request->user()->id)
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
        // image_url to each product
        $items->each(function ($item) {
            if ($item->product) {
                $item->product->image_url = $this->resolveImageUrl($item->product);
            }
        });

        //  Calculate totals
        $subtotal = $items->sum(
            fn($item) => ($item->product->price ?? 0) * $item->quantity
        );

        $savings = $items->sum(
            fn($item) => (($item->product->mrp ?? 0) - ($item->product->price ?? 0))
                * $item->quantity
        );

        return response()->json([
            'status'   => true,
            'data'     => $items,
            'total'    => $items->count(),
            'subtotal' => round($subtotal, 2),
            'savings'  => round($savings, 2),
        ]);
    }

    // POST /api/cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1|max:100',
            'options'    => 'nullable|array',
        ]);

        $userId = $request->user()->id;

        $cart = Cart::where('user_id',    $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            //  Already in cart — increase qty
            $cart->update([
                'quantity' => $cart->quantity + ($request->quantity ?? 1),
                'options'  => $request->options ?? $cart->options,
            ]);
            $message = 'Cart updated';
        } else {
            $cart = Cart::create([
                'user_id'    => $userId,
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity ?? 1,
                'options'    => $request->options,
            ]);
            $message = 'Added to cart';
        }

        //  Load product with media_ids
        $cart->load(['product' => function ($q) {
            $q->select('id', 'name', 'slug', 'price', 'mrp', 'media_ids');
        }]);
        //  Append image_url
        if ($cart->product) {
            $cart->product->image_url = $this->resolveImageUrl($cart->product);
        }

        return response()->json([
            'status'  => true,
            'data'    => $cart,
            'message' => $message,
        ], 201);
    }

    // PUT /api/cart/{product}
    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cart = Cart::where('user_id',    $request->user()->id)
            ->where('product_id', $productId)
            ->firstOrFail();

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'status'  => true,
            'data'    => $cart,
            'message' => 'Cart updated',
        ]);
    }

    // DELETE /api/cart/{product}
    public function remove(Request $request, $productId)
    {
        Cart::where('user_id',    $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['status' => true, 'message' => 'Removed from cart']);
    }

    // DELETE /api/cart — clear all
    public function clear(Request $request)
    {
        Cart::where('user_id', $request->user()->id)->delete();
        return response()->json(['status' => true, 'message' => 'Cart cleared']);
    }
}
