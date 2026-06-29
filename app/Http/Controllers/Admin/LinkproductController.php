<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Linkproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LinkproductController extends Controller
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
     *  Link Product
     */
    public function linkproduct(Request $request)
    {
        // $result['data'] = ::all();
        // $linkProduct = DB::table('linkproducts')->get();
        $data = DB::table('linkproducts')
            ->leftJoin('create_media_tables', 'linkproducts.media_id', '=', 'create_media_tables.id')
            ->select('linkproducts.*', 'create_media_tables.file_name')
            ->where('linkproducts.is_deleted', 0)
            ->get()
            ->each(function ($model) {
                $model->locked = $model->is_vendor === 'VENDOR';
            });
        $deletedData = DB::table('linkproducts')
            ->leftJoin('create_media_tables', 'linkproducts.media_id', '=', 'create_media_tables.id')
            ->select('linkproducts.*', 'create_media_tables.file_name')
            ->where('linkproducts.is_deleted', 1)
            ->get()
            ->each(function ($model) {
                $model->locked = $model->is_vendor === 'VENDOR';
            });
        $info = config('field_info.linkproduct');
        return view('admin.product.linkproduct', compact('data', 'deletedData', 'info'));
    }
    /**
     *  Form link product
     */
    public function addlinkproduct(Request $request, $id = null)
    {
        if (!empty($id) && is_numeric($id)) {
            // $result = DB::table('linkproducts')->where('id', $id)->first();
            $linkproduct = DB::table('linkproducts')
                ->leftJoin('create_media_tables', 'linkproducts.media_id', '=', 'create_media_tables.id')
                ->select('linkproducts.*', 'create_media_tables.file_name')
                ->where('linkproducts.id', $id)
                ->first();
            if (!$linkproduct) {
                abort(404);
            }
            $productAttrArr = [
                (object)[
                    'product_id' => $linkproduct->product_id,
                    'sku'        => $linkproduct->sku,
                    'mrp'        => $linkproduct->mrp,
                    'price'      => $linkproduct->price,
                    'qty'        => $linkproduct->qty,
                    'size_id'    => $linkproduct->size_id,
                    'color_id'   => $linkproduct->color_id,
                    // 'media_id'   => $linkproduct->media_id,
                    'image'      => $linkproduct->file_name,
                    'status'     => $linkproduct->status,
                    'id'         => $linkproduct->id,
                ]
            ];
        } else {
            $productAttrArr = [
                (object)[
                    'product_id' => '',
                    'sku'        => '',
                    'mrp'        => '',
                    'price'      => '',
                    'qty'        => '',
                    'size_id'    => '',
                    'color_id'   => '',
                    // 'media_id'   => '',
                    'image'      => '',
                    'status'     => 1,
                    'id'         => 0,
                ]
            ];
        }
        $products = DB::table('products')->select('id')->where('status', 1)->get();
        $sizes    = DB::table('sizes')->where('status', 1)->get();
        $colors   = DB::table('colors')->where('status', 1)->get();
        $info     = config('field_info.linkproduct');

        return view('admin.product.addlinkproduct', compact('productAttrArr', 'products', 'sizes', 'colors', 'info'));
    }
    // public function processlinkproduct(Request $request)
    // {
    //     $paid = $request->paid;
    //     // echo "<pre>";
    //     // print_r($request->all());
    //     // echo "</pre>";
    //     // die();

    //     foreach ($paid as $key => $val) {

    //         $data = [
    //             'sku'      => $request->sku[$key],
    //             'mrp'      => $request->mrp[$key],
    //             'price'    => $request->price[$key],
    //             'media_id' => $request->media_id[$key],
    //             'product_id' => $request->product_id[$key],
    //             'size_id'  => $request->size_id[$key],
    //             'color_id' => $request->color_id[$key],
    //             'qty'      => $request->qty[$key],
    //             'who_create' => session('ADMIN_ID'),
    //             'created_at' => now(),
    //             'status'   => 1,
    //         ];

    //         // // Image upload
    //         // if ($request->hasFile("attr_image.$key")) {
    //         //     $file = $request->file("attr_image.$key");
    //         //     $filename = time() . '_' . $file->getClientOriginalName();
    //         //     $file->storeAs('public/media', $filename);
    //         //     $data['attr_image'] = $filename;
    //         // }
    //         // // Media Id upload

    //         // Update or Insert
    //         if ($val > 0) {
    //             DB::table('linkproducts')
    //                 ->where('id', $val)
    //                 ->update($data);
    //         } else {
    //             DB::table('linkproducts')
    //                 ->insert($data);
    //         }
    //     }
    //     return redirect('admin/product/linkproduct')->with('success', 'Product Attributes Saved Successfully');
    // }

    public function processlinkproduct(Request $request)
    {
        $request->validate([
            'sku.*'        => 'required|string|max:255',
            'mrp.*'        => 'required|numeric',
            'price.*'      => 'required|numeric',
            'media_id.*'   => 'nullable|integer',
            'product_id.*' => 'required|integer',
            'size_id.*'    => 'nullable|integer',
            'color_id.*'   => 'nullable|integer',
            'qty.*'        => 'required|integer|min:0',
        ]);

        foreach ($request->paid ?? [] as $key => $id) {
            $model = $id
                ? LinkProduct::findOrFail($id)
                : new LinkProduct();
            $model->sku        = $request->sku[$key];
            $model->mrp        = $request->mrp[$key];
            $model->price      = $request->price[$key];
            $model->media_id   = $request->media_id[$key] ?? null;
            $model->product_id = $request->product_id[$key];
            $model->size_id    = $request->size_id[$key] ?? null;
            $model->color_id   = $request->color_id[$key] ?? null;
            $model->qty        = $request->qty[$key];
            $model->is_vendor = 'ADMIN';
            $model->status     = 1;
            if ($id) {
                $model->who_edited = $this->adminName();
                $model->edited_by  = $this->adminId();
                $model->edited_at  = now();
            } else {
                $model->who_create = $this->adminName();
                $model->created_by = $this->adminId();
                $model->created_at = now();
            }
            $model->save();
        }
        return redirect('admin/product/linkproduct')
            ->with('success', 'Product Attributes Saved Successfully');
    }

    public function status($id)
    {
        $model = Linkproduct::find($id);
        if (!$model) {
            return back()->with('error', 'Coupon not found');
        }
        $newStatus = $model->status == 0 ? 1 : 0;
        $model->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? $this->adminId() : null,
            'statusupdate_at' => $newStatus == 0 ? now() : null,
        ]);
        return redirect()->back()->with('success', 'Status Updated');
    }
    public function delete(Request $request, $id)
    {
        $liproduct = Linkproduct::find($id);
        if (!$liproduct) return back()->with('error', 'Product not found');
        $liproduct->update([
            'is_deleted' => 1,
            'who_delete' => $this->adminId(),
            'deleted_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Linked Product Deleted Successfully...');
    }

    public function restore(Request $request, $id)
    {
        $liproduct = Linkproduct::find($id);
        if (!$liproduct) return back()->with('error', 'Product not found');
        $liproduct->update([
            'is_deleted' => 0,
            'who_delete' => null,
            'deleted_at' => null,
        ]);
        return redirect()->back()->with('success', 'Linked Product Restored Successfully...');
    }
    public function permanentDelete($id)
    {
        // Linkproduct::findOrFail($id)->delete();
        // return redirect()->route('linkproduct')
        //     ->with('success', 'Linked Product permanently deleted.');
        return back()->with('error', 'Delete action is not allowed ❌');
    }
    /**
     * Bulk Action
     */
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
                Linkproduct::whereIn('id', $ids)->update([
                    'status' => 1,
                    'statusupdate_by' => $this->adminId(),
                    'statusupdate_at' => now(),
                ]);
                break;
            case 'deactivate':
                Linkproduct::whereIn('id', $ids)->update([
                    'status' => 0,
                    'statusupdate_by' => null,
                    'statusupdate_at' => null,
                ]);
                break;
            case 'trash':
                Linkproduct::whereIn('id', $ids)
                    ->where(function ($q) {
                        $q->where('is_vendor', '!=', 'VENDOR')
                            ->orWhereNull('is_vendor');
                    })
                    ->update([
                        'is_deleted' => 1,
                        'deleted_at' => now(),
                        'who_delete' => $this->adminId()
                    ]);
                // return back()->with('error', 'Delete action is not allowed ❌');
                break;
            case 'restore':
                Linkproduct::whereIn('id', $ids)->update([
                    'is_deleted' => 0,
                    'deleted_at' => null,
                    'who_delete' => null
                ]);
                break;
            case 'permanent_delete':
                // Linkproduct::whereIn('id', $ids)->delete();
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
