<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null)
    {
        $product = DB::table('products')
            ->leftJoin('create_media_tables', function ($join) {
                $join->on('products.media_ids', '=', 'create_media_tables.id')
                    ->where('create_media_tables.status', 1);
            })
            ->leftJoin('categories', function ($join) {
                $join->on('products.category_id', '=', 'categories.id');
                // ->where('categories.status', 1);
            })
            ->leftJoin('brands', function ($join) {
                $join->on('products.brand', '=', 'brands.id');
                // ->where('brands.status',1);
            })
            ->select(
                'products.*',
                'create_media_tables.file_name as media_url',
                'categories.category_name as category_name',
                'brands.name as brand_name',
            )
            ->where('products.id', $id)
            ->where('products.status', 1)
            ->first();

        // dd(
        //     DB::getSchemaBuilder()->getColumnListing('categories'),
        //     DB::getSchemaBuilder()->getColumnListing('brands'),
        //     DB::getSchemaBuilder()->getColumnListing('products'),
        // );

        if (!$product) {
            abort(404);
        }

        $attributes = DB::table('linkproducts')
            ->leftJoin('sizes', function ($join) {
                $join->on('linkproducts.size_id', '=', 'sizes.id')
                    ->where('sizes.status', 1);
            })
            ->leftJoin('colors', function ($join) {
                $join->on('linkproducts.color_id', '=', 'colors.id')
                    ->where('colors.status', 1);
            })
            ->leftJoin('create_media_tables as attr_media', function ($join) {
                $join->on('linkproducts.media_id', '=', 'attr_media.id')
                    ->where('attr_media.status', 1);
            })
            ->select(
                'linkproducts.*',
                'sizes.size as size_name',
                'colors.color as color_name',
                'attr_media.file_name as media_url',
            )
            ->where('linkproducts.product_id', $id)
            ->where('linkproducts.status', 1)
            ->get();

        // ✅ Gallery Images
        $gallery = DB::table('product_gallery')
            ->leftJoin('create_media_tables', function ($join) {
                $join->on('product_gallery.media_id', '=', 'create_media_tables.id')
                    ->where('create_media_tables.status', 1);
            })
            ->select(
                'product_gallery.id',
                'product_gallery.media_id',
                'create_media_tables.file_name as media_url',
            )
            ->where('product_gallery.product_id', $id)
            ->where('product_gallery.status', 1)
            ->get();

        // Defined variables
        // $categoryName = $product->category_name;
        $categoryName = $product->category_name ?? 'N/A';
        $brand_name = $product->brand_name;
        $media_url     = $product->media_url
            ? asset('storage/media/' . $product->media_url)
            : asset('storage/default/no-product-image.PNG');

        /**
         * Old code for view product
         */
        // $product = DB::table('products')
        //     ->leftJoin('create_media_tables', function ($join) {
        //         $join->on('products.media_ids', '=', 'create_media_tables.id')
        //             ->where('create_media_tables.status', 1);
        //     })
        //     ->select('products.*', 'create_media_tables.file_name as image')
        //     ->where('products.id', $id)
        //     ->where('products.status', 1)
        //     ->first();

        // if (!$product) {
        //     abort(404);
        // }

        // $attributes = DB::table('linkproducts')
        //     ->where('product_id', $id)
        //     ->where('status', 1)
        //     ->get();
        /**
         * Old code for view product
         */

        echo "<pre>";
        print_r($product);
        print_r($brand_name.'<br>');
        print_r($attributes);
        print_r($gallery);
        print_r($categoryName.'<br>');
        print_r($media_url);
        echo "</pre>";
        die();
        return view('admin.product.viewproduct');
        // echo "This is for size" ;
    }
}
