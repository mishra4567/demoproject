<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class ProductController extends Controller
{
    protected function adminId()
    {
        return session('ADMIN_ID');
    }

    protected function adminName()
    {
        return session('ADMIN_NAME');
    }
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
        $data = DB::table('products')
            ->leftJoin('create_media_tables', 'products.media_ids', '=', 'create_media_tables.id')
            ->select('products.*', 'create_media_tables.file_name')
            ->where('products.is_deleted', 0)
            ->get()
            ->each(function ($model) {
                $model->locked = $model->is_vendor === 'VENDOR';
            });
        $deletedData = DB::table('products')
            ->leftJoin('create_media_tables', 'products.media_ids', '=', 'create_media_tables.id')
            ->select('products.*', 'create_media_tables.file_name')
            ->where('products.is_deleted', 1)
            ->get()
            ->each(function ($model) {
                $model->locked = $model->is_vendor === 'VENDOR';
            });
        $info = config('field_info.product');
        return view('admin.product.product', compact('data', 'deletedData', 'info'));
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
                'price' => $product->price,
                'mrp' => $product->mrp,
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
                'price' => '',
                'mrp' => '',
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
        $result['info'] = config('field_info.product');
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
            'slug' => [
                'required',
                Rule::unique('products', 'slug')->ignore($id),
            ],
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
        $product->price = $request->price;
        $product->mrp = $request->mrp;
        $product->model = $request->model;
        $product->coupon_id = $request->coupon_id;
        $product->short_desc = $request->short_desc;
        $product->desc = $request->desc;
        $product->keywords = $request->keywords;
        $product->technical_specification = $request->technical_specification;
        $product->uses = $request->uses;
        $product->warranty = $request->warranty;
        $product->is_vendor = 'ADMIN';
        $product->status = 1;
        if ($id) {
            $product->who_edited = $this->adminName();
            $product->edited_by =  $this->adminId();
            $product->edited_at = now();
        } else {
            $product->who_create = $this->adminName();
            $product->created_by = $this->adminId();
            $product->created_at = now();
        }
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
        $product->update([
            'is_deleted' => 1,
            'who_delete' => $this->adminId(),
            'deleted_at' => now(),
        ]);
        return redirect('admin/product')
            ->with('success', 'Product moved to trash...');
    }
    // ─── Restore ───────────────────────────────────────────
    public function restore(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect('admin/product')
                ->with('error', 'Product not found');
        }
        $product->update([
            'is_deleted' => 0,
            'who_delete' => null,
            'deleted_at' => null,
        ]);
        return redirect('admin/product')
            ->with('success', 'Product Restored Successfully');
    }
    public function permanentDelete(Request $request, $id)
    {
        // $product = Product::find($id);
        // if (!$product) {
        //     return redirect('admin/product')
        //         ->with('error', 'Product not found');
        // }
        // $product->delete();
        // return redirect('admin/product')
        //     ->with('success', 'Product Deleted Permanently');
        return back()->with('error', 'Delete action is not allowed ❌');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $model = Product::find($id);
        if (!$model) {
            return back()->with('error', 'Product not found');
        }
        // toggle between 1 and 0
        $newStatus = $model->status == 0 ? 1 : 0;
        $model->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? $this->adminId() : null,
            'statusupdate_at' => $newStatus == 0 ? now() : null,
        ]);
        return redirect()->back()->with('success', 'Status Updated');
    }
    /**
     * Show Product is publish or draft.
     */
    public function publish($id)
    {
        $model = Product::findOrFail($id); // ← 404 if not found

        // ✅ toggle is_publish correctly
        $model->is_publish = ($model->is_publish == 1) ? 0 : 1;
        $model->save();

        $message = $model->is_publish == 1 ? 'Product is Published' : 'Product is Draft';

        return redirect()->back()->with('success', $message);
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
                Product::whereIn('id', $ids)->update([
                    'status' => 1,
                    'statusupdate_by' => $this->adminId(),
                    'statusupdate_at' => now(),
                ]);
                break;
            case 'deactivate':
                Product::whereIn('id', $ids)->update([
                    'status' => 0,
                    'statusupdate_by' => null,
                    'statusupdate_at' => null,
                ]);
                break;
            case 'trash':
                Product::whereIn('id', $ids)
                    ->where(function ($q) {
                        $q->where('is_vendor', '!=', 'VENDOR')
                            ->orWhereNull('is_vendor');
                    })
                    ->update([
                        'is_deleted' => 1,
                        'deleted_at' => now(),
                        'who_delete' => session('ADMIN_ID'),
                    ]);
                // return back()->with('error', 'Delete action is not allowed ❌');
                break;
            case 'restore':
                Product::whereIn('id', $ids)->update([
                    'is_deleted' => 0,
                    'deleted_at' => null,
                    'who_delete' => null
                ]);
                break;
            case 'permanent_delete':
                // ::whereIn('id', $ids)->delete();
                return back()->with('error', 'Permanent delete is not allowed ❌');
                break;
        }
        return back()->with([
            'bulk-success' => $request->action,
            'ids' => is_array($ids) ? $ids : [$ids], // ✅ FIX
        ]);
        // return back()->with('success', 'Bulk action applied');
    }
}
