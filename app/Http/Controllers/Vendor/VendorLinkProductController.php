<?php

// app/Http/Controllers/Vendor/VendorLinkProductController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Linkproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VendorLinkProductController extends BaseVendorController
{
    private function authorise(Linkproduct $item): void
    {
        abort_if(
            (int) $item->created_by !== (int) $this->vendorId()
                || $item->is_vendor !== $this->vendor(),
            403
        );
    }

    public function index()
    {
        $data = DB::table('linkproducts')
            ->leftJoin('create_media_tables', 'linkproducts.media_id', '=', 'create_media_tables.id')
            ->leftJoin('products', 'linkproducts.product_id', '=', 'products.id')
            ->leftJoin('sizes', 'linkproducts.size_id', '=', 'sizes.id')
            ->leftJoin('colors', 'linkproducts.color_id', '=', 'colors.id')
            ->select(
                'linkproducts.*',
                'create_media_tables.file_name',
                'products.name as product_name',
                'sizes.size as size_name',
                'colors.color_name',
                'colors.hex_id',
            )
            ->where('linkproducts.is_deleted', 0)
            ->where('linkproducts.created_by', $this->vendorId())
            ->where('linkproducts.is_vendor', $this->vendor())
            ->latest('linkproducts.created_at')
            ->get();

        $deletedData = DB::table('linkproducts')
            ->leftJoin('create_media_tables', 'linkproducts.media_id', '=', 'create_media_tables.id')
            ->leftJoin('products', 'linkproducts.product_id', '=', 'products.id')
            ->leftJoin('sizes', 'linkproducts.size_id', '=', 'sizes.id')
            ->leftJoin('colors', 'linkproducts.color_id', '=', 'colors.id')
            ->select(
                'linkproducts.*',
                'create_media_tables.file_name',
                'products.name as product_name',
                'sizes.size as size_name',
                'colors.color_name',
                'colors.hex_id',
            )
            ->where('linkproducts.is_deleted', 1)
            ->where('linkproducts.created_by', $this->vendorId())
            ->where('linkproducts.is_vendor', $this->vendor())
            ->latest('linkproducts.created_at')
            ->get();

        $products = DB::table('products')
            ->where('status', 1)
            ->where('created_by', $this->vendorId())
            ->select('id', 'name')
            ->get();

        $sizes  = DB::table('sizes')->where('status', 1)
            ->where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->select('id', 'size')->get();
        $colors = DB::table('colors')->where('status', 1)
            ->where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->select('id', 'color_name', 'hex_id')->get();

        return Inertia::render(
            // C: \xampp\htdocs\project2nd\demoproject\resources\js\vendor\ . vue
            'Pages/LinkProduct/Index',
            compact('data', 'deletedData', 'products', 'sizes', 'colors')
        );
    }

    public function save(Request $request)
    {
        $id = $request->post('id');

        $request->validate([
            'product_id' => 'required',
            'sku'        =>  [
                'required',
                Rule::unique('linkproducts', 'sku')
                    ->ignore($id)
                    ->where(
                        fn($q) => $q
                            ->where('created_by', $this->vendorId())
                            ->where('is_vendor', $this->vendor())
                    ),
            ],
            'price'      => 'required|numeric|min:0',
            'mrp'        => 'nullable|numeric|min:0',
            'qty'        => 'required|integer|min:0',
            'size_id'    => 'nullable',
            'color_id'   => 'nullable',
            'media_id'   => 'nullable',
        ]);

        // $model = $id ? Linkproduct::findOrFail($id) : new Linkproduct();
        if ($id) {
            $model = Linkproduct::findOrFail($id);
            $this->authorise($model);
        } else {
            $model = new Linkproduct();
        }
        $model->product_id = $request->product_id;
        $model->sku        = $request->sku;
        $model->mrp        = $request->mrp;
        $model->price      = $request->price;
        $model->qty        = $request->qty;
        $model->size_id    = $request->size_id;
        $model->color_id   = $request->color_id;
        $model->media_id   = $request->media_id;
        $model->status     = 1;
        $model->is_vendor = $this->vendor();
        if ($id) {
            $model->who_edited = $this->vendorName();
            $model->edited_by  = $this->vendorId();
            $model->edited_at  = now();
        } else {
            $model->who_create = $this->vendorName();
            $model->created_by = $this->vendorId();
            $model->created_at = now();
        }

        $model->save();

        return back()->with(
            'success',
            $id ? 'Link product updated!' : 'Link product created!'
        );
    }

    public function bulkSave(Request $request)
    {
        $request->validate([
            'rows'             => 'required|array|min:1',
            'rows.*.product_id' => 'required',
            'rows.*.sku'       => 'required|string|max:255',
            'rows.*.price'     => 'required|numeric|min:0',
            'rows.*.qty'       => 'required|integer|min:0',
        ]);

        foreach ($request->rows as $row) {
            $model             = new Linkproduct();
            $model->product_id = $row['product_id'];
            $model->sku        = $row['sku'];
            $model->mrp        = $row['mrp']      ?? null;
            $model->price      = $row['price'];
            $model->qty        = $row['qty'];
            $model->size_id    = $row['size_id']  ?? null;
            $model->color_id   = $row['color_id'] ?? null;
            $model->media_id   = $row['media_id'] ?? null;
            $model->is_vendor  = 'VENDOR';
            $model->who_create = $this->vendorName();
            $model->created_by = $this->vendorId();
            $model->created_at = now();
            $model->status     = 1;
            $model->save();
        }

        return back()->with('success', count($request->rows) . ' variant(s) created!');
    }

    public function status(Linkproduct $linkProduct)
    {
        $this->authorise($linkProduct);
        $linkProduct->status = $linkProduct->status == 0 ? 1 : 0;
        $linkProduct->save();
        return back()->with('success', 'Status updated!');
    }

    public function destroy(Linkproduct $linkProduct)
    {
        $this->authorise($linkProduct);
        $linkProduct->update([
            'is_deleted' => 1,
            'who_delete' => $this->vendorId(),
            'deleted_at' => now(),
        ]);
        return back()->with('success', 'Moved to trash!');
    }

    public function restore(Linkproduct $linkProduct)
    {
        $this->authorise($linkProduct);
        $linkProduct->update([
            'is_deleted' => 0,
            'deleted_at' => null,
            'who_delete' => null,
        ]);
        return back()->with('success', 'Restored!');
    }

    public function permanentDelete(Linkproduct $linkProduct)
    {
        return back()->with('error', 'Permanent delete is not allowed!');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,trash,restore,permanent_delete',
            'ids'    => 'required|array',
        ]);

        $items   = Linkproduct::where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->whereIn('id', $request->ids);
        $count   = $items->count();
        $message = '';

        match ($request->action) {
            'activate'         => ($items->update([
                'status' => 1,
                'statusupdate_by' => $this->vendorId(),
                'statusupdate_at' => now(),
            ])
                && $message = "{$count} item(s) activated!"),
            'deactivate'       => ($items->update([
                'status' => 0,
                'statusupdate_by' => null,
                'statusupdate_at' => null,
            ])
                && $message = "{$count} item(s) deactivated!"),
            'trash'            => ($items->update([
                'is_deleted' => 1,
                'who_delete' => $this->vendorId(),
                'deleted_at' => now(),
            ])
                && $message = "{$count} item(s) moved to trash!"),
            'restore'          => ($items->update([
                'is_deleted' => 0,
                'deleted_at' => null,
                'who_delete' => null,
            ])
                && $message = "{$count} item(s) restored!"),
            'permanent_delete' => $message = 'Permanent delete is not allowed!',
        };

        if ($request->action === 'permanent_delete') {
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }
}
