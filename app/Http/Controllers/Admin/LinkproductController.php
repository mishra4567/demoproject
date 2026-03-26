<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Linkproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LinkproductController extends Controller
{
    /**
     *  Link Product
     */
    public function linkproduct(Request $request)
    {
        // $result['data'] = ::all();
        // $linkProduct = DB::table('linkproducts')->get();
        $linkProduct = DB::table('linkproducts')
            ->leftJoin('create_media_tables', 'linkproducts.media_id', '=', 'create_media_tables.id')
            ->select('linkproducts.*', 'create_media_tables.file_name')
            ->get();
        return view('admin.product.linkproduct', compact('linkProduct'));
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

        return view('admin.product.addlinkproduct', compact('productAttrArr', 'products', 'sizes', 'colors'));
    }
    public function processlinkproduct(Request $request)
    {
        $paid = $request->paid;
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // die();

        foreach ($paid as $key => $val) {

            $data = [
                'sku'      => $request->sku[$key],
                'mrp'      => $request->mrp[$key],
                'price'    => $request->price[$key],
                'media_id' => $request->media_id[$key],
                'product_id' => $request->product_id[$key],
                'size_id'  => $request->size_id[$key],
                'color_id' => $request->color_id[$key],
                'qty'      => $request->qty[$key],
                'status'   => 1,
            ];

            // // Image upload
            // if ($request->hasFile("attr_image.$key")) {
            //     $file = $request->file("attr_image.$key");
            //     $filename = time() . '_' . $file->getClientOriginalName();
            //     $file->storeAs('public/media', $filename);
            //     $data['attr_image'] = $filename;
            // }
            // // Media Id upload

            // Update or Insert
            if ($val > 0) {
                DB::table('linkproducts')
                    ->where('id', $val)
                    ->update($data);
            } else {
                DB::table('linkproducts')
                    ->insert($data);
            }
        }
        return redirect('admin/product/linkproduct')->with('success', 'Product Attributes Saved Successfully');
    }
    public function status($id)
    {
        $model = Linkproduct::find($id);

        // toggle between 1 and 0
        $model->status = ($model->status == 1) ? 0 : 1;
        $model->save();

        return redirect()->back()->with('success', 'Status Updated');
    }
    public function delete(Request $request, $id)
    {
        $product = Linkproduct::find($id);
        if (!$product) {
            return redirect('admin/product')
                ->with('error', 'Product not found');
        }
        $product->delete();
        return redirect()->back()->with('success', 'Linked Product Deleted Successfully...');
    }
}
