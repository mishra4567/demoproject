<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\CalendarEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CreateMediaTableController;
use App\Http\Controllers\Admin\LinkproductController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RouteSearchController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\ViewProductController;
// use App\Http\Controllers\Admin\BarcodeController;
// use App\Http\Controllers\Admin\BarcodeController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TechnicalSpecsController;

// Route::get('admin', [AdminController::class, 'index']);
Route::get('admin', [AdminController::class, 'index'])
    ->name('admin.index');
// Registration process Routes
Route::get('admin/register', [AdminController::class, 'register'])
    ->name('admin.register');
Route::post('admin/register/process', [AdminController::class, 'registerProcess'])
    ->name('admin.register.process');
Route::get('admin/verify-email/{token}',     [AdminController::class, 'verifyEmail'])
    ->name('admin.verify.email');
// Registration process Routes End
// Forgot Password process Routes
Route::get('admin/forgot-password', [AdminController::class, 'forgotPassword'])
    ->name('admin.forgot.password');
Route::post('admin/forgot-password',            [AdminController::class, 'forgotPasswordSend'])
    ->name('admin.forgot.password.send');
Route::get('admin/reset-password/{token}',      [AdminController::class, 'resetPasswordForm'])
    ->name('admin.reset.password.form');
Route::post('admin/reset-password',             [AdminController::class, 'resetPassword'])
    ->name('admin.reset.password');
// Registration process Routes End
Route::post('admin/auth', [AdminController::class, 'auth'])
    ->name('admin.auth');
Route::group(['middleware' => 'admin_auth'], function () {
    Route::get('admin/search-routes', [RouteSearchController::class, 'ajaxSearch'])
        ->name('admin.ajax.search')->setDefaults(['label' => '', 'role' => 1]);
    /**
     * Admin Routes
     */
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard')->setDefaults(['label' => 'Go to Dashboard', 'role' => 0]);
    Route::get('admin/updatepassword', [AdminController::class, 'updatepassword']);
    Route::get('/admin/logout', [AdminController::class, 'logout']);
    Route::get('admin/settings', [AdminController::class, 'settings'])
        ->name('admin.settings')->setDefaults(['label' => 'Admin Settings', 'role' => 0]);
    Route::get('admin/manageprofile/{id?}', [AdminController::class, 'update'])
        ->name('admin.profile.edite')->setDefaults(['label' => 'Add Admin', 'role' => 0]);
    Route::get('admin/profileupdate/{id?}', [AdminController::class, 'profileupdate'])
        ->name('admin.profile.update')->setDefaults(['label' => 'Add Admin', 'role' => 0]);
    Route::get('/settings/change-password',  [AdminController::class, 'changePasswordForm'])
        ->name('admin.change.password')->setDefaults(['label' => 'Change Password', 'role' => 0]);
    Route::post('/settings/change-password', [AdminController::class, 'changePassword'])
        ->name('admin.change.password.update')->setDefaults(['label' => '', 'role' => 1]);
    /**
     * Calender to do work
     */
    Route::get('admin/calendar', [CalendarEventController::class, 'index'])
        ->name('calendar')->setDefaults(['label' => 'Calender', 'role' => 0]);
    Route::get('admin/calendar/fetch', [CalendarEventController::class, 'fetch']);
    Route::post('admin/calendar/store/{id?}', [CalendarEventController::class, 'store'])
        ->name('calendar.store');
    Route::post('/admin/calendar/delete/{id}', [CalendarEventController::class, 'deleteEvent'])
        ->name('calendar.delete');
    /**
     * Category Routes
     */
    Route::get('admin/category', [CategoryController::class, 'index'])
        ->name('category')->setDefaults(['label' => 'view Categorys', 'role' => 0]);
    Route::get('admin/category/managecategory', [CategoryController::class, 'managecategory'])
        ->name('category.add_category')->setDefaults(['label' => 'Add Categorys', 'role' => 0]);
    // insert category
    Route::post('admin/category/managecategoryprocess', [CategoryController::class, 'managecategoryprocess'])
        ->name('category.manage_category_process')->setDefaults(['label' => '', 'role' => 1]);
    // edit category
    Route::get('admin/category/managecategory/{id}', [CategoryController::class, 'managecategory'])
        ->name('category.edit_category')->setDefaults(['label' => '', 'role' => 1]);
    // Status change category
    Route::get('admin/category/status/{id}', [CategoryController::class, 'status'])
        ->name('category.status')->setDefaults(['label' => '', 'role' => 1]);
    // Delete category
    Route::get('admin/category/delete/{id}', [CategoryController::class, 'delete'])
        ->name('category.delete')->setDefaults(['label' => '', 'role' => 1]);
    // Bulk Action category
    Route::post('admin/category/bulkaction', [CategoryController::class, 'bulkAction'])
        ->name('category.bulkAction')->setDefaults(['label' => '', 'role' => 1]);
    /**
     * Coupon Routes
     */
    Route::get('admin/coupons', [CouponController::class, 'index'])
        ->name('coupons')->setDefaults(['label' => 'View Coupons', 'role' => 0]);
    // insert coupons
    Route::get('admin/coupons/managecoupons', [CouponController::class, 'managecoupons'])
        ->name('coupons.add_coupons')->setDefaults(['label' => 'Add Coupons', 'role' => 0]);
    Route::post('admin/coupons/managecouponsprocess', [CouponController::class, 'managecouponsprocess'])
        ->name('coupons.manage_coupons_process')->setDefaults(['label' => '', 'role' => 1]);
    // edit coupons
    Route::get('admin/coupons/managecoupons/{id}', [CouponController::class, 'managecoupons'])
        ->name('coupons.edit_coupons')->setDefaults(['label' => '', 'role' => 1]);
    // Status change coupons
    Route::get('admin/coupons/status/{id}', [CouponController::class, 'status'])
        ->name('coupons.status')->setDefaults(['label' => '', 'role' => 1]);
    // delete coupons
    Route::get('admin/coupons/delete/{id}', [CouponController::class, 'delete'])
        ->name('coupons.delete')->setDefaults(['label' => '', 'role' => 1]);
    // Bulk Action Copons
    Route::post('admin/coupons/bulkaction', [CouponController::class, 'bulkAction'])
        ->name('coupons.bulkAction')->setDefaults(['label' => '', 'role' => 1]);
    /**
     * Size Routes
     */
    Route::get('admin/size', [SizeController::class, 'index'])
        ->name('size')->setDefaults(['label' => 'view sizes', 'role' => 0]);
    // insert size
    Route::get('admin/size/managesize', [SizeController::class, 'managesize'])
        ->name('size.add_size')->setDefaults(['label' => 'Add Sizes', 'role' => 0]);
    Route::post('admin/size/managesizeprocess', [SizeController::class, 'managesizeprocess'])
        ->name('size.manage_size_process')->setDefaults(['label' => '', 'role' => 1]);
    // edit size
    Route::get('admin/size/managesize/{id}', [SizeController::class, 'managesize'])
        ->name('size.edit_size')->setDefaults(['label' => '', 'role' => 1]);
    // Status change size
    Route::get('admin/size/status/{id}', [SizeController::class, 'status'])
        ->name('size.status')->setDefaults(['label' => '', 'role' => 1]);
    // delete size
    Route::get('admin/size/delete/{id}', [SizeController::class, 'delete'])
        ->name('size.delete')->setDefaults(['label' => '', 'role' => 1]);
    // Bulk Action Copons
    Route::post('admin/size/bulkaction', [SizeController::class, 'bulkAction'])
        ->name('size.bulkAction')->setDefaults(['label' => '', 'role' => 1]);
    /**
     * Color Routes
     */
    Route::get('admin/color', [ColorController::class, 'index'])
        ->name('color')->setDefaults(['label' => 'View Colors', 'role' => 0]);
    // insert Color
    Route::get('admin/color/managecolor', [ColorController::class, 'managecolor'])
        ->name('color.add_color')->setDefaults(['label' => 'Add Color', 'role' => 0]);
    Route::post('admin/color/managecolorprocess', [ColorController::class, 'managecolorprocess'])
        ->name('color.manage_color_process')->setDefaults(['label' => '', 'role' => 1]);
    // edit Color
    Route::get('admin/color/managecolor/{id}', [ColorController::class, 'managecolor'])
        ->name('color.edit_color')->setDefaults(['label' => ' ', 'role' => 1]);
    // Status change Color
    Route::get('admin/color/status/{id}', [ColorController::class, 'status'])
        ->name('color.status')->setDefaults(['label' => '', 'role' => 1]);
    // delete Color
    Route::get('admin/color/delete/{id}', [ColorController::class, 'delete'])
        ->name('color.delete')->setDefaults(['label' => '', 'role' => 1]);
    // Bulk Action Product
    Route::post('admin/color/bulkaction', [ColorController::class, 'bulkAction'])
        ->name('color.bulkAction')->setDefaults(['label' => '', 'role' => 1]);
    /**
     * Brands Routes
     */
    Route::get('admin/brands', [BrandsController::class, 'index'])
        ->name('brands')->setDefaults(['label' => 'View Brands', 'role' => 0]);
    // insert brands
    Route::post('admin/brands/managebrandsprocess', [BrandsController::class, 'managebrandsprocess'])
        ->name('brands.manage_brands_process')->setDefaults(['label' => '', 'role' => 1]);
    // inset and edit brands
    Route::get('admin/brands/managebrands/{id?}', [BrandsController::class, 'managebrands'])
        ->name('brands.add_brands')->setDefaults(['label' => 'Add Brands', 'role' => 0]);
    // Status change brands
    Route::get('admin/brands/status/{id}', [BrandsController::class, 'status'])
        ->name('brands.status')->setDefaults(['label' => '', 'role' => 1]);
    // delete brands
    Route::get('admin/brands/delete/{id}', [BrandsController::class, 'delete'])
        ->name('brands.delete')->setDefaults(['label' => '', 'role' => 1]);
    // Bulk Action Product
    Route::post('admin/brands/bulkaction', [BrandsController::class, 'bulkAction'])
        ->name('brands.bulkAction')->setDefaults(['label' => '', 'role' => 1]);
    /**
     * Route For Media
     */
    Route::get('admin/media', [CreateMediaTableController::class, 'index'])
        ->name('media')->setDefaults(['label' => 'View Media', 'role' => 0]);
    Route::get('admin/media/managemedia/{id?}', [CreateMediaTableController::class, 'managemedia'])
        ->name('media.managemedia')->setDefaults(['label' => 'Store Media', 'role' => 0]);
    // Route::post('admin/media/managemediaprocess', [CreateMediaTableController::class, 'managemedia'])
    //     ->name('media.managemedia')->setDefaults(['label' => 'Store Media', 'role' => 0]);
    // for pop up
    Route::post('admin/media/store', [CreateMediaTableController::class, 'store'])
        ->name('media.store')->setDefaults(['label' => 'Store Media', 'role' => 1]);
    Route::get('admin/media/delete/{id}', [CreateMediaTableController::class, 'delete'])
        ->name('media.delete')->setDefaults(['label' => '', 'role' => 1]);
    Route::get('admin/media/status/{id}', [CreateMediaTableController::class, 'status'])
        ->name('media.status')->setDefaults(['label' => '', 'role' => 1]);
    Route::post('/media/bulkaction', [CreateMediaTableController::class, 'bulkAction'])
        ->name('media.bulkAction')->setDefaults(['label' => '', 'role' => 1]);
    Route::get('/admin/media/search', [CreateMediaTableController::class, 'mediasearch'])
        ->name('media.search')->setDefaults(['label' => '', 'role' => 1]);
    /**
     * Product Routes
     */
    Route::get('admin/product', [ProductController::class, 'index'])
        ->name('product')->setDefaults(['label' => 'View Product', 'role' => 0]);
    // insert product
    Route::get('admin/product/manageproduct/{id?}', [ProductController::class, 'manageproduct'])
        ->name('product.manage')->setDefaults(['label' => 'Add Product', 'role' => 0]);
    Route::post('admin/product/manageproductprocess', [ProductController::class, 'manageproductprocess'])
        ->name('product.manage_product_process')->setDefaults(['label' => '', 'role' => 1]);
    // Status change product
    Route::get('admin/product/status/{id}', [ProductController::class, 'status'])
        ->name('product.status')->setDefaults(['label' => '', 'role' => 1]);
    // Status publish product
    Route::get('admin/product/publish/{id}', [ProductController::class, 'publish'])
        ->name('product.publish')->setDefaults(['label' => '', 'role' => 1]);
    // delete product
    Route::get('admin/product/delete/{id}', [ProductController::class, 'delete'])
        ->name('product.delete')->setDefaults(['label' => '', 'role' => 1]);
    // Bulk Action Product
    Route::post('admin/product/bulkaction', [ProductController::class, 'bulkAction'])
        ->name('product.bulkAction')->setDefaults(['label' => '', 'role' => 1]);
    // delete product Images
    Route::get('admin/product/images_delete/{piid}/{pid}', [ProductController::class, 'product_images_delete'])
        ->name('product.images_delete')->setDefaults(['label' => '', 'role' => 1]);
    // Route::get('admin/barcode/product/{id}', [BarcodeController::class, 'prodBarcodeHelp'])
    //     ->name('barcode.product');
    // Route::get('/barcode/product-download/{id}', [BarcodeController::class, 'proBarcodeDawn'])
    //     ->name('barcode.product.download');
    /**
     * Linked Product Routes
     */
    Route::get('admin/product/linkproduct', [LinkproductController::class, 'linkproduct'])
        ->name('product.linkproduct')->setDefaults(['label' => 'View linked product', 'role' => 0]);
    // Route::get('admin/product/addlinkproduct/', [LinkproductController::class, 'addlinkproduct'])
    //     ->name('product.addlinkproduct')->setDefaults(['label' => 'Add linked product', 'role' => 0]);
    Route::get('admin/product/addlinkproduct/{id?}', [LinkproductController::class, 'addlinkproduct'])
        ->name('product.addlinkproduct')->setDefaults(['label' => 'Add linked product', 'role' => 0]);
    Route::post('admin/product/processlinkproduct/', [LinkproductController::class, 'processlinkproduct'])
        ->name('product.processlinkproduct')->setDefaults(['label' => '', 'role' => 1]);
    Route::get('admin/product/linkproductstatus/{id}', [LinkproductController::class, 'status'])
        ->name('product.linkproductstatus')->setDefaults(['label' => 'linked product status', 'role' => 1]);
    Route::get('admin/product/linkproductdelete/{id}', [LinkproductController::class, 'delete'])
        ->name('product.linkproductdelete')->setDefaults(['label' => 'linked product delete', 'role' => 1]);
    /**
     * Tecnical Specs Product Routes
     */
    Route::get('admin/product/tecnicalspecs', [TechnicalSpecsController::class, 'index'])
        ->name('product.tecnicalspacs')->setDefaults(['label' => 'Product Tecnical Spesc', 'role' => 0]);
    Route::get('admin/product/addtecnicalaspecs/{id?}', [TechnicalSpecsController::class, 'addTechnicalSpecs'])
        ->name('product.addtecnicalspecs')->setDefaults(['label' => 'Add Product Tecnical Spesc', 'role' => 0]);
    Route::post('admin/product/processTechnicalSpecs/', [TechnicalSpecsController::class, 'processTechnicalSpecs'])
        ->name('product.processTechnicalSpecs')->setDefaults(['label' => '', 'role' => 1]);
    Route::get('admin/product/tecnicalspecsstatus/{id}', [TechnicalSpecsController::class, 'status'])
        ->name('product.tecnicalspecsstatus')->setDefaults(['label' => '', 'role' => 1]);
    Route::get('admin/product/tecnicalspecsdelete/{id}', [TechnicalSpecsController::class, 'delete'])
        ->name('product.tecnicalspecsdelete')->setDefaults(['label' => '', 'role' => 1]);
    Route::post('admin/tecnicalspecs/bulkaction', [TechnicalSpecsController::class, 'bulkAction'])
        ->name('tecnicalspecs.bulkAction')->setDefaults(['label' => '', 'role' => 1]);
    // Linked Product
    // delete product attribute
    Route::get('admin/product/attr_delete/{paid}/{pid}', [ProductController::class, 'product_attr_delete'])
        ->name('product.attr_delete')->setDefaults(['label' => '', 'role' => 1]);
    // Bulk Action Product
    Route::post('admin/product/linkproductdelete/bulkaction', [LinkproductController::class, 'bulkAction'])
        ->name('product.linkbulkAction')->setDefaults(['label' => '', 'role' => 1]);
    /**
     *  Product View Route
     */
    Route::get('admin/product/productview/{id?}', [ViewProductController::class, 'index'])
        ->name('product.productview')->setDefaults(['label' => '', 'role' => 0]);
    /**
     * Full Product View Route End
     */
    /**
     * Product Routes
     */
    Route::get('admin/customers', [CustomerController::class, 'index'])
        ->name('customer')->setDefaults(['label' => 'View customer', 'role' => 0]);
    Route::get('admin/customer/managecustomer/{id?}', [CustomerController::class, 'managecustomer'])
        ->name('customer.view_customer')->setDefaults(['label' => 'Add customer', 'role' => 0]);
    Route::post('admin/customer/managecustomerprocess', [CustomerController::class, 'managecustomerprocess'])
        ->name('customer.manage_customer_process')->setDefaults(['label' => '', 'role' => 1]);
    Route::get('admin/customer/status/{id}', [CustomerController::class, 'status'])
        ->name('customer.status')->setDefaults(['label' => '', 'role' => 1]);
    Route::get('admin/customer/delete/{id}', [CustomerController::class, 'delete'])
        ->name('customer.delete')->setDefaults(['label' => '', 'role' => 1]);
    Route::post('admin/customer/bulkaction', [CustomerController::class, 'bulkAction'])
        ->name('customer.bulkAction')->setDefaults(['label' => '', 'role' => 1]);
    Route::post('admin/customer/saveaddress', [CustomerController::class, 'saveAddress'])
        ->name('customer.save_address')->setDefaults(['label' => '', 'role' => 1]);
    Route::get('admin/customer/deleteaddress/{id?}/{addid?}', [CustomerController::class, 'deleteAddress'])
        ->name('customer.delete_address')->setDefaults(['label' => '', 'role' => 1]);

    /**
     * Report Send
     */
    Route::get('admin/reports/view', [ReportController::class, 'index'])
        ->name('admin.reportsView')->setDefaults(['label' => 'Add Report', 'role' => 0]);
    Route::get('admin/report/new', [ReportController::class, 'create'])
        ->name('admin.reports')->setDefaults(['label' => 'Add Report', 'role' => 0]);
});
