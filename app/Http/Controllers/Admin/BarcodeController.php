<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Illuminate\Support\Facades\DB;

class BarcodeController extends Controller
{
    // 🔹 GENERATE UNIQUE BARCODE
    public function index(){
        $generator = new BarcodeGeneratorSVG();
        $barcode = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
        return view('admin.barcode.index', compact('barcode'));
    }
}
