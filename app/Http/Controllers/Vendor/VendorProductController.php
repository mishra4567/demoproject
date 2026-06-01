<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class VendorProductController extends Controller
{
    public function index()
    {
        // $data = DB::table('products')
        //     ->leftJoin('create_media_tables', 'products.media_ids', '=', 'create_media_tables.id')
        //     ->select('products.*', 'create_media_tables.file_name')
        //     ->where('products.is_deleted', 0)
        //     ->get();

        $products = DB::table('products')
            ->leftJoin(
                'create_media_tables',
                'products.media_ids',
                '=',
                'create_media_tables.id'
            )
            ->select(
                'products.*',
                'create_media_tables.file_name'
            )
            ->where('products.is_deleted', 0)
            ->where('products.created_by', Auth::guard('vendor')->id())
            ->paginate(12);

        // For now, just return a simple view. You can replace this with actual product listing logic later.
        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }
    public function manageproduct(Request $request, $id = null)
    {
        $result = [
            'id'                     => 0,
            'category_id'            => '',
            'name'                   => '',
            'slug'                   => '',
            'media_id'               => '',
            'image'                  => '',
            'brand'                  => '',
            'price'                  => '',
            'mrp'                    => '',
            'model'                  => '',
            'short_desc'             => '',
            'desc'                   => '',
            'keywords'               => '',
            'technical_specification' => '',
            'uses'                   => '',
            'warranty'               => '',
            'status'                 => 1,
            'gallery_images'         => [],
        ];

        if ($id) {
            $product = DB::table('products')
                ->leftJoin('create_media_tables', 'products.media_ids', '=', 'create_media_tables.id')
                ->select('products.*', 'create_media_tables.file_name')
                ->where('products.id', $id)
                ->where('products.created_by', Auth::guard('vendor')->id())
                ->first();

            if (!$product) abort(404);

            $galleryImages = DB::table('product_gallery')
                ->leftJoin('create_media_tables', 'product_gallery.media_id', '=', 'create_media_tables.id')
                ->select('product_gallery.media_id as id', 'create_media_tables.file_name')
                ->where('product_gallery.product_id', $id)
                ->where('product_gallery.status', 1)
                ->get();

            $result = [
                'id'                     => $product->id,
                'category_id'            => $product->category_id,
                'name'                   => $product->name,
                'slug'                   => $product->slug,
                'media_id'               => $product->media_ids,
                'image'                  => $product->file_name,
                'brand'                  => $product->brand,
                'price'                  => $product->price,
                'mrp'                    => $product->mrp,
                'model'                  => $product->model,
                'short_desc'             => $product->short_desc,
                'desc'                   => $product->desc,
                'keywords'               => $product->keywords,
                'technical_specification' => $product->technical_specification,
                'uses'                   => $product->uses,
                'warranty'               => $product->warranty,
                'status'                 => $product->status,
                'gallery_images'         => $galleryImages,
            ];
        }

        return Inertia::render('Products/ManageProduct', [  // ← fixed path
            'product'    => $result,
            'categories' => DB::table('categories')->where('status', 1)->get(),
            'brands'     => DB::table('brands')->where('status', 1)->get(),
            'sizes'      => DB::table('sizes')->where('status', 1)->get(),
            'colors'     => DB::table('colors')->where('status', 1)->get(),
            'media'      => DB::table('create_media_tables')->where('status', 1)->get(),
            'coupons'    => DB::table('coupons')->where('status', 1)->get(),
            'isEdit'     => (bool) $id,
        ]);
    }

    public function manageproductprocess(Request $request)
    {
        $id = $request->id;

        $request->validate([
            'name'     => 'required',
            'slug'     => 'required',
            'price'    => 'required|numeric|min:0',
            'media_id' => $id ? 'nullable' : 'required',
        ], [
            'name.required'     => 'Product name is required',
            'price.required'    => 'Product price is required',
            'media_id.required' => 'Product image is required',
        ]);

        // ── Insert or Update ──────────────────────────────────────────
        $product = $id ? Product::findOrFail($id) : new Product();

        // Security — vendor can only edit their own product
        if ($id && $product->created_by !== (string) Auth::guard('vendor')->id()) {
            abort(403);
        }

        // Image
        if ($request->media_id) {
            $product->media_ids = $request->media_id;
        }

        $product->category_id             = $request->category_id;
        $product->name                    = $request->name;
        $product->slug                    = $request->slug;
        $product->brand                   = $request->brand_id;
        $product->price                   = $request->price;
        $product->mrp                     = $request->mrp;
        $product->model                   = $request->model;
        $product->coupon_id               = $request->coupon_id;
        $product->short_desc              = $request->short_desc;
        $product->desc                    = $request->desc;
        $product->keywords                = $request->keywords;
        $product->technical_specification = $request->technical_specification;
        $product->uses                    = $request->uses;
        $product->warranty                = $request->warranty;
        $product->status                  = 1;

        if ($id) {
            // Update
            $product->is_vendor   = 'VENDOR';
            $product->who_edited  = Auth::guard('vendor')->user()->name;
            $product->edited_by   = Auth::guard('vendor')->id();
            $product->edited_at   = now();
        } else {
            // Create
            $product->is_vendor   = 'VENDOR';
            $product->who_create  = Auth::guard('vendor')->user()->name;
            $product->created_by  = Auth::guard('vendor')->id();
            $product->created_at  = now();

            // Auto generate barcode
            if (!$product->barcode) {
                $product->barcode = time() . rand(1000, 9999);
            }
        }

        $product->save();

        // ── Gallery Images ────────────────────────────────────────────
        if ($request->gallery_media_ids && count($request->gallery_media_ids) > 0) {
            // Delete old gallery on update
            if ($id) {
                DB::table('product_gallery')
                    ->where('product_id', $product->id)
                    ->delete();
            }

            // Insert new
            $galleryData = [];
            foreach ($request->gallery_media_ids as $mediaId) {
                $galleryData[] = [
                    'product_id' => $product->id,
                    'media_id'   => $mediaId,
                    'status'     => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('product_gallery')->insert($galleryData);
        }

        // ── Inertia redirect back to products list ────────────────────
        session()->flash('success', $id ? 'Product updated successfully' : 'Product created successfully');
        return Inertia::location('/vendor/products');
    }
}
