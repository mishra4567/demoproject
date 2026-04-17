<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class ProductController extends Controller
{
    /**
     * Generate Unique Barcode
     */
    private function generateBarcode()
    {
        do {
            $barcode = rand(100000000000, 999999999999);
            $exists = DB::table('products')
                ->where('barcode', $barcode)
                ->exists();
        } while ($exists);
        return $barcode;
    }

    /**
     * Index Page for Product
     */
    public function index()
    {
        // $result['data'] = Product::all();
        $result['data'] = DB::table('products')
            ->leftJoin('create_media_tables', 'products.media_ids', '=', 'create_media_tables.id')
            ->select('products.*', 'create_media_tables.file_name')
            // ->where('products.status', 1)
            ->get();
        return view('admin.product.product', $result);
        // return view('admin.product.product', );
    }

    /**
     * Index Pages for Manage product
     */

    public function manageproduct(Request $request, $id = null)
    {
        if (!empty($id) && is_numeric($id)) {

            $product = DB::table('products')
                ->leftJoin('create_media_tables', 'products.media_ids', '=', 'create_media_tables.id')
                ->select('products.*', 'create_media_tables.file_name')
                ->where('products.id', $id)
                ->first();

            if (!$product) {
                abort(404);
            }

            // ✅ Fetch existing gallery images for this product
            $gallery_images = DB::table('product_gallery')
                ->leftJoin('create_media_tables', 'product_gallery.media_id', '=', 'create_media_tables.id')
                ->select(
                    'product_gallery.media_id as id',
                    'create_media_tables.file_name'
                )
                ->where('product_gallery.product_id', $id)
                ->where('product_gallery.status', 1)
                ->get();

            $result = [
                'category_id' => $product->category_id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->file_name,
                'brand' => $product->brand,
                'model' => $product->model,
                'short_desc' => $product->short_desc,
                'desc' => $product->desc,
                'keywords' => $product->keywords,
                'technical_specification' => $product->technical_specification,
                'uses' => $product->uses,
                'warranty' => $product->warranty,
                'status' => $product->status,
                'id' => $product->id,
                'gallery_images' => $gallery_images,
            ];
        } else {

            $result = [
                'category_id' => '',
                'name' => '',
                'slug' => '',
                'image' => '',
                'brand' => '',
                'model' => '',
                'short_desc' => '',
                'desc' => '',
                'keywords' => '',
                'technical_specification' => '',
                'uses' => '',
                'warranty' => '',
                'status' => '',
                'id' => 0,
                'gallery_images' => [],
            ];
        }



        // dropdown data
        $result['category'] = DB::table('categories')->where('status', 1)->get();
        $result['sizes']    = DB::table('sizes')->where('status', 1)->get();
        $result['colors']   = DB::table('colors')->where('status', 1)->get();
        $result['brands']   = DB::table('brands')->where('status', 1)->get();
        $result['media']    = DB::table('create_media_tables')->where('status', 1)->get();
        $result['coupon_select']      = DB::table('coupons')->where('status', 1)->get();
        // echo "<pre>";
        // print_r($result['brands']);
        // echo "</pre";
        return view('admin.product.manage_product', $result);
    }


    /**
     * Validate and Insert Product Data
     *
     * Later i want to add activate or deactivate
     */
    public function manageproductprocess(Request $request)
    {
        // return $request->post();
        // die();
        // echo "<pre>";
        // print_r($request->post());
        // echo "</pre>";
        // die();
        $id = $request->id;

        $request->validate([
            'name' => 'required',
            'media_id' => $id ? 'nullable' : 'required',
            'attr_image.*' => 'nullable|mimes:png,jpg,jpeg,webp',
        ], [
            'slug.unique' => 'This Product already exists',
        ]);

        // Insert Or Update
        $product = $id ? Product::findOrFail($id) : new Product();

        //  Image Media Id upload
        if ($request->media_id) {
            $product->media_ids = $request->media_id;
        }
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->brand = $request->brand_id;
        $product->model = $request->model;
        $product->coupon_id = $request->coupon_id;
        $product->short_desc = $request->short_desc;
        $product->desc = $request->desc;
        $product->keywords = $request->keywords;
        $product->technical_specification = $request->technical_specification;
        $product->uses = $request->uses;
        $product->warranty = $request->warranty;
        $product->status = 1;
        // Auto Generate Barcode if not exists
        if (!$product->barcode) {
            $product->barcode = $this->generateBarcode();
        }
        $product->save();

        // Gallery Images
        if ($request->gallery_media_id && count($request->gallery_media_id) > 0) {
            // Delete old gallery on update
            if ($id) {
                DB::table('product_gallery')->where('product_id', $product->id)->delete();
            }

            // Insert new gallery images
            $galleryData = [];
            foreach ($request->gallery_media_id as $mediaId) {
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


        return redirect('admin/product')
            ->with('success', $id ? 'Product Updated Successfully' : 'Product Inserted Successfully');


        // return $request->post();
        // die();
        // echo "<pre>";
        // print_r($request->post());
        // echo "</pre>";
        // die();
    }


    /**
     * Display the specified resource.
     *
     * Later i want add delete to show or not show
     */
    public function delete(Request $request, $id)
    {

        $product = Product::find($id);

        if (!$product) {
            return redirect('admin/product')
                ->with('error', 'Product not found');
        }

        //  1. Delete Main Product Image
        // if ($product->image && file_exists(public_path('/storage/media/' . $product->image))) {
        //     unlink(public_path('/storage/media/' . $product->image));
        // }

        //  2. Get All Product Attributes
        // $attributes = DB::table('product_attr')
        //     ->where('product_id', $id)
        //     ->get();

        // 3. Delete Attribute Images
        // foreach ($attributes as $attr) {
        //     if ($attr->attr_image && file_exists(public_path('/storage/media/' . $attr->attr_image))) {
        //         unlink(public_path('/storage/media/' . $attr->attr_image));
        //     }
        // }

        // 4. Delete Attribute Records
        // DB::table('product_attr')
        //     ->where('product_id', $id)
        //     ->delete();

        // 5. Delete Product
        $product->delete();

        return redirect('admin/product')
            ->with('success', 'Product Deleted Successfully...');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $model = Product::find($id);

        // toggle between 1 and 0
        $model->status = ($model->status == 1) ? 0 : 1;
        $model->save();

        return redirect()->back()->with('success', 'Status Updated');
    }
    public function bulkAction(Request $request)
    {
        // $ids = (array) $request->ids;
        $ids = $request->ids ?? [];
        $action = $request->action;
        if (!$ids || !$action) {
            return back()->with('error', 'Select items and action');
        }
        switch ($action) {
            case 'activate':
                Product::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'deactivate':
                Product::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'delete':
                // CreateMediaTable::whereIn('id', $ids)->delete();
                return back()->with('error', 'Delete action is not allowed ❌');
                break;
        }
        return back()->with([
            'bulk-success' => $request->action,
            'ids' => is_array($ids) ? $ids : [$ids], // ✅ FIX
        ]);
        // return back()->with('success', 'Bulk action applied');
    }


    // public function product_attr_delete(Request $request, $paid, $pid)
    // {
    //     // Get attribute
    //     $attr = DB::table('product_attr')->where('id', $paid)->first();

    //     // Delete image if exists
    //     if ($attr && $attr->attr_image) {

    //         $imagePath = public_path('/storage/media/' . $attr->attr_image);

    //         if (file_exists($imagePath)) {
    //             unlink($imagePath);
    //         }
    //     }

    //     // Delete database record
    //     DB::table('product_attr')->where('id', $paid)->delete();

    //     return redirect('admin/product/manageproduct/' . $pid)
    //         ->with('success', 'Product Attribute Deleted Successfully...');

    //     // echo "product deleted" ;
    // }
    // public function product_images_delete(Request $request, $piid, $pid)
    // {
    //     // Get Product images
    //     $images = DB::table('product_images')->where('id', $piid)->first();

    //     // Delete image if exists
    //     if ($images && $images->image) {

    //         $imagePath = public_path('/storage/media/' . $images->image);

    //         if (file_exists($imagePath)) {
    //             unlink($imagePath);
    //         }
    //     }

    //     // Delete database record
    //     DB::table('product_images')->where('id', $piid)->delete();

    //     return redirect('admin/product/manageproduct/' . $pid)
    //         ->with('success', 'Product Image Deleted Successfully...');

    //     // echo "product deleted" ;
    // }
}
