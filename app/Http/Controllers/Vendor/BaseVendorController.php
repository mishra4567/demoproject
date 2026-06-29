<?php
// Controllers/Vendor/BaseVendorController
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BaseVendorController extends Controller
{
    protected function vendor()
    {
        return 'VENDOR';
    }

    protected function vendorId()
    {
        return Auth::guard('vendor')->id();
    }

    protected function vendorName()
    {
        return Auth::guard('vendor')->user()->name;
    }

    protected function vendorUser()
    {
        return Auth::guard('vendor')->user();
    }
}
