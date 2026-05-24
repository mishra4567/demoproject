<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('create_media_tables', 'products.media_ids', '=', 'create_media_tables.id')
            ->leftJoin('brands', 'products.brand', '=', 'brands.id')
            ->select(
                'products.id',
                'products.name',
                'products.slug',
                'products.short_desc',
                'products.keywords',
                'products.is_publish',
                'categories.category_name',
                'brands.name as brand_name',
                'create_media_tables.file_name as image',
            )
            ->where('products.status', 1)
            ->where('products.is_publish', 1)
            ->where('products.is_deleted', 0);

        // ─── Search ────────────────────────────────
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('products.name', 'like', '%' . $request->search . '%')
                    ->orWhere('products.keywords', 'like', '%' . $request->search . '%')
                    ->orWhere('products.short_desc', 'like', '%' . $request->search . '%');
            });
        }

        // ─── Filter by category ────────────────────
        if ($request->category_id) {
            $query->where('products.category_id', $request->category_id);
        }

        // ─── Filter by brand ───────────────────────
        if ($request->brand_id) {
            $query->where('products.brand', $request->brand_id);
        }

        // ─── Sort ──────────────────────────────────
        $sort = $request->sort ?? 'latest';
        match ($sort) {
            'oldest'     => $query->oldest('products.created_at'),
            'name_asc'   => $query->orderBy('products.name', 'asc'),
            'name_desc'  => $query->orderBy('products.name', 'desc'),
            default      => $query->latest('products.created_at'),
        };

        $products = $query->paginate($request->per_page ?? 12);

        // Add image URL
        $products->getCollection()->transform(function ($product) {
            $product->image_url = $product->image
                ? asset('storage/media/' . $product->image)
                : asset('storage/default/no-product-image.PNG');
            return $product;
        });

        return response()->json([
            'status'   => true,
            'products' => $products,
        ]);
    }

    /**
     * Display the specified resource.
     */
    // ─── Single Product ────────────────────────────
    public function show($id)
    {
        $product = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('create_media_tables', 'products.media_ids', '=', 'create_media_tables.id')
            ->leftJoin('brands', 'products.brand', '=', 'brands.id')
            ->select(
                'products.*',
                'categories.category_name',
                'brands.name as brand_name',
                'create_media_tables.file_name as image',
            )
            ->where('products.id', $id)
            ->where('products.status', 1)
            ->where('products.is_publish', 1)
            ->where('products.is_deleted', 0)
            ->first();

        if (!$product) {
            return response()->json([
                'status'  => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Gallery
        $gallery = DB::table('product_gallery')
            ->leftJoin('create_media_tables', 'product_gallery.media_id', '=', 'create_media_tables.id')
            ->select('create_media_tables.file_name as image')
            ->where('product_gallery.product_id', $id)
            ->where('product_gallery.status', 1)
            ->get()
            ->map(fn($img) => asset('storage/media/' . $img->image));

        // Variants
        $variants = DB::table('linkproducts')
            ->leftJoin('sizes', 'linkproducts.size_id', '=', 'sizes.id')
            ->leftJoin('colors', 'linkproducts.color_id', '=', 'colors.id')
            ->leftJoin('create_media_tables as vm', 'linkproducts.media_id', '=', 'vm.id')
            ->select(
                'linkproducts.*',
                'sizes.size as size_name',
                'colors.color_name',
                'colors.hex_id as color_hex',
                'vm.file_name as variant_image',
            )
            ->where('linkproducts.product_id', $id)
            ->where('linkproducts.status', 1)
            ->get()
            ->map(function ($v) {
                $v->variant_image_url = $v->variant_image
                    ? asset('storage/media/' . $v->variant_image)
                    : null;
                return $v;
            });

        $product->image_url    = $product->image
            ? asset('storage/media/' . $product->image)
            : asset('storage/default/no-product-image.PNG');
        $product->gallery      = $gallery;
        $product->variants     = $variants;

        return response()->json([
            'status'  => true,
            'product' => $product,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
