<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
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

// Route::get('admin', [AdminController::class, 'index']);
Route::get('admin', [AdminController::class, 'index']);
Route::post('admin/auth', [AdminController::class, 'auth'])
    ->name('admin.auth');
Route::get('admin/search-routes', [RouteSearchController::class, 'ajaxSearch'])
    ->name('admin.ajax.search');
Route::group(['middleware' => 'admin_auth'], function () {
    /**
     * Admin Routes
     */
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard')->setDefaults(['label' => 'Go to Dashboard', 'role' => 0]);
    Route::get('admin/updatepassword', [AdminController::class, 'updatepassword']);
    Route::get('/admin/logout', [AdminController::class, 'logout']);
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
     * Route For Media
     */
    Route::get('admin/media', [CreateMediaTableController::class, 'index'])
        ->name('media')->setDefaults(['label' => 'View Media', 'role' => 0]);
    Route::get('admin/media/managemedia', [CreateMediaTableController::class, 'managemedia'])
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
    /**
     * Product Routes
     */
    Route::get('admin/product', [ProductController::class, 'index'])
        ->name('product')->setDefaults(['label' => 'View Product', 'role' => 0]);
    // insert product
    Route::get('admin/product/manageproduct', [ProductController::class, 'manageproduct'])
        ->name('product.add_product')->setDefaults(['label' => 'Add Product', 'role' => 0]);
    Route::post('admin/product/manageproductprocess', [ProductController::class, 'manageproductprocess'])
        ->name('product.manage_product_process')->setDefaults(['label' => '', 'role' => 1]);
    // edit product
    Route::get('admin/product/manageproduct/{id}', [ProductController::class, 'manageproduct'])
        ->name('product.edit_product')->setDefaults(['label' => '', 'role' => 1]);
    // Status change product
    Route::get('admin/product/status/{id}', [ProductController::class, 'status'])
        ->name('product.status')->setDefaults(['label' => '', 'role' => 1]);
    // delete product
    Route::get('admin/product/delete/{id}', [ProductController::class, 'delete'])
        ->name('product.delete')->setDefaults(['label' => '', 'role' => 1]);
    // Bulk Action Product
    Route::post('admin/product/bulkaction', [ProductController::class, 'bulkAction'])
        ->name('product.bulkAction')->setDefaults(['label' => '', 'role' => 1]);
    // delete product Images
    Route::get('admin/product/images_delete/{piid}/{pid}', [ProductController::class, 'product_images_delete'])
        ->name('product.images_delete')->setDefaults(['label' => '', 'role' => 1]);
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
     * Calender to do work
     */
    Route::get('admin/calendar', [CalendarEventController::class, 'index'])
        ->name('calender')->setDefaults(['label' => 'Calender', 'role' => 0]);
    Route::get('admin/calendar/fetch', [CalendarEventController::class, 'fetch']);
    Route::post('admin/calendar/store/{id?}', [CalendarEventController::class, 'store'])
        ->name('calendar.store');
    Route::post('/admin/calendar/delete/{id}', [CalendarEventController::class, 'deleteEvent'])
        ->name('calendar.delete');
});
