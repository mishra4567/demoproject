<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class VendorProductController extends BaseVendorController
{
    private function authorise(Product $product): void
    {
        abort_if(
            (int) $product->created_by !== (int) $this->vendorId()
                || $product->is_vendor !== $this->vendor(),
            403
        );
    }
    public function index()
    {
        $products = DB::table('products')
            ->leftJoin('create_media_tables', 'products.media_ids', '=', 'create_media_tables.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('brands', 'products.brand', '=', 'brands.id')
            ->select(
                'products.*',
                'create_media_tables.file_name',
                'categories.category_name',                                      // ← now included
                'brands.name as brand_name',
            )
            ->where('products.created_by', $this->vendorId())
            ->where('products.is_vendor', $this->vendor())
            ->where(function ($q) {
                $q->where('products.is_deleted', 0)
                    ->orWhereNull('products.is_deleted');
            })
            ->latest('products.created_at')
            ->paginate(12);
        $deleteProducts = DB::table('products')
            ->leftJoin('create_media_tables', 'products.media_ids', '=', 'create_media_tables.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('brands', 'products.brand', '=', 'brands.id')
            ->select(
                'products.*',
                'create_media_tables.file_name',
                'categories.category_name',
                'brands.name as brand_name',
            )
            ->where('products.created_by', $this->vendorId())
            ->where('products.is_vendor', $this->vendor())
            ->where('products.is_deleted', 1)              // ← strict, no orWhereNull
            ->latest('products.created_at')
            ->get();                                        // ← get(), not paginate()

        // For now, just return a simple view. You can replace this with actual product listing logic later.
        return Inertia::render('Pages/Products/Index', compact('products', 'deleteProducts'));
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
                ->where('products.created_by', $this->vendorId())
                ->where('products.is_vendor', $this->vendor())
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

        return Inertia::render('Pages/Products/ManageProduct', [  // ← fixed path
            'product'    => $result,
            'categories' => DB::table('categories')->where('status', 1)
                ->where('created_by', $this->vendorId())
                ->where('is_vendor', $this->vendor())
                ->get(),
            'brands'     => DB::table('brands')->where('status', 1)
                ->where('created_by', $this->vendorId())
                ->where('is_vendor', $this->vendor())
                ->get(),
            'sizes'      => DB::table('sizes')->where('status', 1)
                ->where('created_by', $this->vendorId())
                ->where('is_vendor', $this->vendor())
                ->get(),
            'colors'     => DB::table('colors')->where('status', 1)
                ->where('created_by', $this->vendorId())
                ->where('is_vendor', $this->vendor())
                ->get(),
            'media'      => DB::table('create_media_tables')->where('status', 1)
                ->where('created_by', $this->vendorId())
                ->where('is_vendor', $this->vendor())
                ->get(),
            'coupons'    => DB::table('coupons')->where('status', 1)
                ->where('created_by', $this->vendorId())
                ->where('is_vendor', $this->vendor())
                ->get(),
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
        // $product = $id ? Product::findOrFail($id) : new Product();
        if ($id) {
            $product = Product::findOrFail($id);
            $this->authorise($product);
        } else {
            $product = new Product();
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
        $product->is_vendor               = $this->vendor();
        if ($id) {
            // Update
            $product->who_edited = $this->vendorName();
            $product->edited_by  = $this->vendorId();
            $product->edited_at  = now();
        } else {
            // Create
            $product->who_create = $this->vendorName();
            $product->created_by = $this->vendorId();
            $product->created_at = now();

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
    public function status(Product $product)
    {
        $this->authorise($product);

        $newStatus = $product->status == 0 ? 1 : 0;

        $product->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 1 ? $this->vendorId() : null,
            'statusupdate_at' => $newStatus == 1 ? now() : null,
        ]);

        return back()->with('success', 'Status updated!');
    }
    public function destroy(Product $product)
    {
        $this->authorise($product);

        $product->update([
            'is_deleted' => 1,
            'who_delete' => $this->vendorName(),
            'deleted_at' => now(),
        ]);

        return back()->with('success', 'Product moved to trash!');
    }
    public function restore(Product $product)
    {
        $this->authorise($product);

        $product->update([
            'is_deleted' => 0,
            'deleted_at' => null,
            'who_delete' => null,
        ]);

        return back()->with('success', 'Product restored!');
    }
    public function permanentDelete(Product $product)
    {
        return back()->with(
            'error',
            'Product permanently delete not allowed!'
        );
    }
    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,trash,restore,permanent_delete',
            'ids'    => 'required|array',
        ]);

        $products = Product::where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->whereIn('id', $request->ids);

        $count   = $products->count();
        $message = '';

        match ($request->action) {

            'activate' => (
                $products->update([
                    'status' => 1,
                    'statusupdate_by' => $this->vendorId(),
                    'statusupdate_at' => now(),
                ])
                && $message = "{$count} product(s) activated!"
            ),

            'deactivate' => (
                $products->update([
                    'status' => 0,
                    'statusupdate_by' => null,
                    'statusupdate_at' => null,
                ])
                && $message = "{$count} product(s) deactivated!"
            ),

            'trash' => (
                $products->update([
                    'is_deleted' => 1,
                    'who_delete' => $this->vendorName(),
                    'deleted_at' => now(),
                ])
                && $message = "{$count} product(s) moved to trash!"
            ),

            'restore' => (
                $products->update([
                    'is_deleted' => 0,
                    'who_delete' => null,
                    'deleted_at' => null,
                ])
                && $message = "{$count} product(s) restored!"
            ),

            'permanent_delete' => (
                $message = 'Product permanently delete not allowed!'
            ),
        };

        if ($request->action === 'permanent_delete') {
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }
}
