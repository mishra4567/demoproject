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
            ->where('products.who_create', Auth::guard('vendor')->id())
            ->paginate(12);

        // For now, just return a simple view. You can replace this with actual product listing logic later.
        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }
    public function manageproduct($id = null)
    {
        $product = null;
        if ($id) {
            $product = Product::find($id);
        }
        return Inertia::render('Products/ManageProduct', [
            'product' => $product,
        ]);
    }
}
