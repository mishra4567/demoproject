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

Route::prefix('admin')->group(function () {
    Route::get('/',                          [AdminController::class, 'index'])
        ->name('admin.index');
    Route::post('/auth',                     [AdminController::class, 'auth'])
        ->name('admin.auth');
    Route::get('/logout',                    [AdminController::class, 'logout'])
        ->name('admin.logout');
    Route::get('/register',                  [AdminController::class, 'register'])
        ->name('admin.register');
    Route::post('/register',                 [AdminController::class, 'registerProcess'])
        ->name('admin.register.process');
    Route::get('/verify-email/{token}',      [AdminController::class, 'verifyEmail'])
        ->name('admin.verify.email');
    Route::get('/forgot-password',           [AdminController::class, 'forgotPassword'])
        ->name('admin.forgot.password');
    Route::post('/forgot-password',          [AdminController::class, 'forgotPasswordSend'])
        ->name('admin.forgot.password.send');
    Route::get('/reset-password/{token}',    [AdminController::class, 'resetPasswordForm'])
        ->name('admin.reset.password.form');
    Route::post('/reset-password',           [AdminController::class, 'resetPassword'])
        ->name('admin.reset.password');
});
// Route::get('admin', [AdminController::class, 'index']);
// Route::get('admin', [AdminController::class, 'index'])
//     ->name('admin.index');
// // Registration process Routes
// Route::get('admin/register', [AdminController::class, 'register'])
//     ->name('admin.register');
// Route::post('admin/register/process', [AdminController::class, 'registerProcess'])
//     ->name('admin.register.process');
// Route::get('admin/verify-email/{token}',     [AdminController::class, 'verifyEmail'])
//     ->name('admin.verify.email');
// // Registration process Routes End
// // Forgot Password process Routes
// Route::get('admin/forgot-password', [AdminController::class, 'forgotPassword'])
//     ->name('admin.forgot.password');
// Route::post('admin/forgot-password',            [AdminController::class, 'forgotPasswordSend'])
//     ->name('admin.forgot.password.send');
// Route::get('admin/reset-password/{token}',      [AdminController::class, 'resetPasswordForm'])
//     ->name('admin.reset.password.form');
// Route::post('admin/reset-password',             [AdminController::class, 'resetPassword'])
//     ->name('admin.reset.password');
// // Registration process Routes End
// Route::post('admin/auth', [AdminController::class, 'auth'])
//     ->name('admin.auth');
Route::fallback(function () {
    // Admin logged in
    if (session()->has('ADMIN_LOGIN')) {

        return response()->view(
            'admin.partials.not_found_page',
            [
                'type'    => '404',
                'icon'    => 'fa-exclamation-triangle',
                'title'   => 'Page Not Found',
                'message' => 'The page you are looking for does not exist.',
                'btnText' => 'Go Dashboard',
                'btnUrl'  => url('admin/dashboard'),
            ],
            404
        );
    }
    // Guest user
    return response()->view('admin.errors.404', [
        'type'    => '404',
        'icon'    => 'fa-exclamation-triangle',
        'title'   => 'Page Not Found',
        'message' => 'The page you are looking for does not exist.',
        'btnText' => 'Dashboard',
        'btnUrl'  => url('admin/dashboard'),
    ], 404);
});
Route::group(['middleware' => 'admin_auth'], function () {

    Route::get('admin/search-routes', [RouteSearchController::class, 'ajaxSearch'])
        ->name('admin.ajax.search')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Admin / Settings ──────────────────────────
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('role:dashboard,view')
        ->name('dashboard')->setDefaults(['label' => 'Go to Dashboard', 'role' => 0]);

    Route::get('admin/updatepassword', [AdminController::class, 'updatepassword']);

    Route::get('/admin/logout', [AdminController::class, 'logout']);
    // Admin Profile view
    Route::get('admin/settings', [AdminController::class, 'settings'])
        ->middleware('role:settings,view')
        ->name('admin.settings')->setDefaults(['label' => 'Admin Settings', 'role' => 0]);
    Route::get('admin/manageprofile/{id?}', [AdminController::class, 'update'])
        ->middleware('role:settings,edit')
        ->name('admin.profile.edite')->setDefaults(['label' => 'Add Admin', 'role' => 0]);
    Route::get('admin/profileupdate/{id?}', [AdminController::class, 'profileupdate'])
        ->middleware('role:settings,edit')
        ->name('admin.profile.update')->setDefaults(['label' => 'Add Admin', 'role' => 0]);
    // Admin Profile view end
    Route::get('/settings/change-password', [AdminController::class, 'changePasswordForm'])
        ->name('admin.change.password')->setDefaults(['label' => 'Change Password', 'role' => 0]);
    Route::post('/settings/change-password', [AdminController::class, 'changePassword'])
        ->name('admin.change.password.update')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Calendar ──────────────────────────────────
    Route::get('admin/calendar', [CalendarEventController::class, 'index'])
        ->middleware('role:calendar,view')
        ->name('calendar')->setDefaults(['label' => 'Calender', 'role' => 0]);

    Route::get('admin/calendar/fetch', [CalendarEventController::class, 'fetch']);

    Route::post('admin/calendar/store/{id?}', [CalendarEventController::class, 'store'])
        ->middleware('role:calendar,create')
        ->name('calendar.store');

    Route::post('/admin/calendar/delete/{id}', [CalendarEventController::class, 'deleteEvent'])
        ->middleware('role:calendar,delete')
        ->name('calendar.delete');

    // ─── Category ──────────────────────────────────
    Route::get('admin/category', [CategoryController::class, 'index'])
        ->middleware('role:category,view')
        ->name('category')->setDefaults(['label' => 'view Categorys', 'role' => 0]);

    Route::get('admin/category/managecategory', [CategoryController::class, 'managecategory'])
        ->middleware('role:category,create')
        ->name('category.add_category')->setDefaults(['label' => 'Add Categorys', 'role' => 0]);

    Route::post('admin/category/managecategoryprocess', [CategoryController::class, 'managecategoryprocess'])
        ->middleware('role:category,create')
        ->name('category.manage_category_process')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/category/managecategory/{id}', [CategoryController::class, 'managecategory'])
        ->middleware('role:category,edit')
        ->name('category.edit_category')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/category/status/{id}', [CategoryController::class, 'status'])
        ->middleware('role:category,edit')
        ->name('category.status')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/category/delete/{id}', [CategoryController::class, 'delete'])
        ->middleware('role:category,delete')
        ->name('category.delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('admin/category/bulkaction', [CategoryController::class, 'bulkAction'])
        ->middleware('role:category,edit')
        ->name('category.bulkAction')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/admin/categoty/restore/{id}', [CategoryController::class, 'restore'])
        ->middleware('role:category,delete')
        ->name('categoty.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/admin/category/permanent-delete/{id}', [CategoryController::class, 'permanentDelete'])
        ->middleware('role:category,delete')
        ->name('category.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Coupon ────────────────────────────────────
    Route::get('admin/coupons', [CouponController::class, 'index'])
        ->middleware('role:coupon,view')
        ->name('coupons')->setDefaults(['label' => 'View Coupons', 'role' => 0]);

    Route::get('admin/coupons/managecoupons/{id?}', [CouponController::class, 'managecoupons'])
        ->middleware('role:coupon,create')
        ->name('coupons.add_coupons')->setDefaults(['label' => 'Add Coupons', 'role' => 0]);

    Route::post('admin/coupons/managecouponsprocess', [CouponController::class, 'managecouponsprocess'])
        ->middleware('role:coupon,create')
        ->name('coupons.manage_coupons_process')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/coupons/managecoupons/{id}', [CouponController::class, 'managecoupons'])
        ->middleware('role:coupon,edit')
        ->name('coupons.edit_coupons')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/coupons/status/{id}', [CouponController::class, 'status'])
        ->middleware('role:coupon,edit')
        ->name('coupons.status')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/coupons/delete/{id}', [CouponController::class, 'delete'])
        ->middleware('role:coupon,delete')
        ->name('coupons.delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('admin/coupons/bulkaction', [CouponController::class, 'bulkAction'])
        ->middleware('role:coupon,edit')
        ->name('coupons.bulkAction')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/admin/coupons/restore/{id}', [CouponController::class, 'restore'])
        ->middleware('role:coupon,delete')
        ->name('coupons.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/admin/coupons/permanent-delete/{id}', [CouponController::class, 'permanentDelete'])
        ->middleware('role:coupon,delete')
        ->name('coupons.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Size ──────────────────────────────────────
    Route::get('admin/size', [SizeController::class, 'index'])
        ->middleware('role:size,view')
        ->name('size')->setDefaults(['label' => 'view sizes', 'role' => 0]);

    Route::get('admin/size/managesize/{id?}', [SizeController::class, 'managesize'])
        ->middleware('role:size,create')
        ->name('size.add_size')->setDefaults(['label' => 'Add Sizes', 'role' => 0]);

    Route::post('admin/size/managesizeprocess', [SizeController::class, 'managesizeprocess'])
        ->middleware('role:size,create')
        ->name('size.manage_size_process')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/size/status/{id}', [SizeController::class, 'status'])
        ->middleware('role:size,edit')
        ->name('size.status')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/size/delete/{id}', [SizeController::class, 'delete'])
        ->middleware('role:size,delete')
        ->name('size.delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('admin/size/bulkaction', [SizeController::class, 'bulkAction'])
        ->middleware('role:size,edit')
        ->name('size.bulkAction')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/size/restore/{id}', [SizeController::class, 'restore'])
        ->middleware('role:size,delete')
        ->name('size.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/size/permanent-delete/{id}', [SizeController::class, 'permanentDelete'])
        ->middleware('role:size,delete')
        ->name('size.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Color ─────────────────────────────────────
    Route::get('admin/color', [ColorController::class, 'index'])
        ->middleware('role:color,view')
        ->name('color')->setDefaults(['label' => 'View Colors', 'role' => 0]);

    Route::get('admin/color/managecolor', [ColorController::class, 'managecolor'])
        ->middleware('role:color,create')
        ->name('color.add_color')->setDefaults(['label' => 'Add Color', 'role' => 0]);

    Route::post('admin/color/managecolorprocess', [ColorController::class, 'managecolorprocess'])
        ->middleware('role:color,create')
        ->name('color.manage_color_process')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/color/managecolor/{id}', [ColorController::class, 'managecolor'])
        ->middleware('role:color,edit')
        ->name('color.edit_color')->setDefaults(['label' => ' ', 'role' => 1]);

    Route::get('admin/color/status/{id}', [ColorController::class, 'status'])
        ->middleware('role:color,edit')
        ->name('color.status')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/color/delete/{id}', [ColorController::class, 'delete'])
        ->middleware('role:color,delete')
        ->name('color.delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/color/restore/{id}', [ColorController::class, 'restore'])
        ->middleware('role:color,delete')
        ->name('color.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/color/permanent-delete/{id}', [ColorController::class, 'permanentDelete'])
        ->middleware('role:color,delete')
        ->name('color.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('admin/color/bulkaction', [ColorController::class, 'bulkAction'])
        ->middleware('role:color,edit')
        ->name('color.bulkAction')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Brands ────────────────────────────────────
    Route::get('admin/brands', [BrandsController::class, 'index'])
        ->middleware('role:brands,view')
        ->name('brands')->setDefaults(['label' => 'View Brands', 'role' => 0]);

    Route::post('admin/brands/managebrandsprocess', [BrandsController::class, 'managebrandsprocess'])
        ->middleware('role:brands,create')
        ->name('brands.manage_brands_process')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/brands/managebrands/{id?}', [BrandsController::class, 'managebrands'])
        ->middleware('role:brands,create')
        ->name('brands.add_brands')->setDefaults(['label' => 'Add Brands', 'role' => 0]);

    Route::get('admin/brands/status/{id}', [BrandsController::class, 'status'])
        ->middleware('role:brands,edit')
        ->name('brands.status')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/brands/delete/{id}', [BrandsController::class, 'delete'])
        ->middleware('role:brands,delete')
        ->name('brands.delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('admin/brands/bulkaction', [BrandsController::class, 'bulkAction'])
        ->middleware('role:brands,edit')
        ->name('brands.bulkAction')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/brands/restore/{id}', [BrandsController::class, 'restore'])
        ->middleware('role:brands,delete')
        ->name('brands.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/brands/permanent-delete/{id}', [BrandsController::class, 'permanentDelete'])
        ->middleware('role:brands,delete')
        ->name('brands.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Media ─────────────────────────────────────
    Route::get('admin/media', [CreateMediaTableController::class, 'index'])
        ->middleware('role:media,view')
        ->name('media')->setDefaults(['label' => 'View Media', 'role' => 0]);

    Route::get('admin/media/managemedia/{id?}', [CreateMediaTableController::class, 'managemedia'])
        ->middleware('role:media,create')
        ->name('media.managemedia')->setDefaults(['label' => 'Store Media', 'role' => 0]);

    Route::post('admin/media/store', [CreateMediaTableController::class, 'store'])
        ->middleware('role:media,create')
        ->name('media.store')->setDefaults(['label' => 'Store Media', 'role' => 1]);

    Route::get('admin/media/delete/{id}', [CreateMediaTableController::class, 'delete'])
        ->middleware('role:media,delete')
        ->name('media.delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/media/status/{id}', [CreateMediaTableController::class, 'status'])
        ->middleware('role:media,edit')
        ->name('media.status')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('/media/bulkaction', [CreateMediaTableController::class, 'bulkAction'])
        ->middleware('role:media,edit')
        ->name('media.bulkAction')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/admin/media/search', [CreateMediaTableController::class, 'mediasearch'])
        ->middleware('role:media,view')
        ->name('media.search')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/media/restore/{id}', [CreateMediaTableController::class, 'restore'])
        ->middleware('role:media,delete')
        ->name('media.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/media/permanent-delete/{id}', [CreateMediaTableController::class, 'permanentDelete'])
        ->middleware('role:media,delete')
        ->name('media.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Product ───────────────────────────────────
    Route::get('admin/product', [ProductController::class, 'index'])
        ->middleware('role:product,view')
        ->name('product')->setDefaults(['label' => 'View Product', 'role' => 0]);

    Route::get('admin/product/manageproduct/{id?}', [ProductController::class, 'manageproduct'])
        ->middleware('role:product,create')
        ->name('product.manage')->setDefaults(['label' => 'Add Product', 'role' => 0]);

    Route::post('admin/product/manageproductprocess', [ProductController::class, 'manageproductprocess'])
        ->middleware('role:product,create')
        ->name('product.manage_product_process')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/product/status/{id}', [ProductController::class, 'status'])
        ->middleware('role:product,edit')
        ->name('product.status')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/product/publish/{id}', [ProductController::class, 'publish'])
        ->middleware('role:product,edit')
        ->name('product.publish')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/product/delete/{id}', [ProductController::class, 'delete'])
        ->middleware('role:product,delete')
        ->name('product.delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('admin/product/bulkaction', [ProductController::class, 'bulkAction'])
        ->middleware('role:product,edit')
        ->name('product.bulkAction')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/product/restore/{id}', [ProductController::class, 'restore'])
        ->middleware('role:product,delete')
        ->name('product.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/product/permanent-delete/{id}', [ProductController::class, 'permanentDelete'])
        ->middleware('role:product,delete')
        ->name('product.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Link Product ──────────────────────────────
    Route::get('admin/product/linkproduct', [LinkproductController::class, 'linkproduct'])
        ->middleware('role:linkproduct,view')
        ->name('product.linkproduct')->setDefaults(['label' => 'View linked product', 'role' => 0]);

    Route::get('admin/product/addlinkproduct/{id?}', [LinkproductController::class, 'addlinkproduct'])
        ->middleware('role:linkproduct,create')
        ->name('product.addlinkproduct')->setDefaults(['label' => 'Add linked product', 'role' => 0]);

    Route::post('admin/product/processlinkproduct/', [LinkproductController::class, 'processlinkproduct'])
        ->middleware('role:linkproduct,create')
        ->name('product.processlinkproduct')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/product/linkproductstatus/{id}', [LinkproductController::class, 'status'])
        ->middleware('role:linkproduct,edit')
        ->name('product.linkproductstatus')->setDefaults(['label' => 'linked product status', 'role' => 1]);

    Route::get('admin/product/linkproductdelete/{id}', [LinkproductController::class, 'delete'])
        ->middleware('role:linkproduct,delete')
        ->name('product.linkproductdelete')->setDefaults(['label' => 'linked product delete', 'role' => 1]);

    Route::get('/product/linkproductdelete/restore/{id}', [LinkproductController::class, 'restore'])
        ->middleware('role:linkproduct,delete')
        ->name('linkproduct.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/product/linkproductdelete/permanent-delete/{id}', [LinkproductController::class, 'permanentDelete'])
        ->middleware('role:linkproduct,delete')
        ->name('linkproduct.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('admin/product/linkproductdelete/bulkaction', [LinkproductController::class, 'bulkAction'])
        ->middleware('role:linkproduct,edit')
        ->name('product.linkbulkAction')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Technical Specs ───────────────────────────
    Route::get('admin/product/tecnicalspecs', [TechnicalSpecsController::class, 'index'])
        ->middleware('role:technical_spec,view')
        ->name('product.tecnicalspacs')->setDefaults(['label' => 'Product Tecnical Spesc', 'role' => 0]);

    Route::get('admin/product/addtecnicalaspecs/{id?}', [TechnicalSpecsController::class, 'addTechnicalSpecs'])
        ->middleware('role:technical_spec,create')
        ->name('product.addtecnicalspecs')->setDefaults(['label' => 'Add Product Tecnical Spesc', 'role' => 0]);

    Route::post('admin/product/processTechnicalSpecs/', [TechnicalSpecsController::class, 'processTechnicalSpecs'])
        ->middleware('role:technical_spec,create')
        ->name('product.processTechnicalSpecs')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/product/tecnicalspecsstatus/{id}', [TechnicalSpecsController::class, 'status'])
        ->middleware('role:technical_spec,edit')
        ->name('product.tecnicalspecsstatus')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/product/tecnicalspecsdelete/{id}', [TechnicalSpecsController::class, 'delete'])
        ->middleware('role:technical_spec,delete')
        ->name('product.tecnicalspecsdelete')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('admin/tecnicalspecs/bulkaction', [TechnicalSpecsController::class, 'bulkAction'])
        ->middleware('role:technical_spec,edit')
        ->name('tecnicalspecs.bulkAction')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/product/tecnicalspecsdelete/restore/{id}', [TechnicalSpecsController::class, 'restore'])
        ->middleware('role:technical_spec,delete')
        ->name('product.tecnicalspec.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/product/tecnicalspecsdelete/permanent-delete/{id}', [TechnicalSpecsController::class, 'permanentDelete'])
        ->middleware('role:technical_spec,delete')
        ->name('product.tecnicalspec.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/product/attr_delete/{paid}/{pid}', [ProductController::class, 'product_attr_delete'])
        ->middleware('role:linkproduct,delete')
        ->name('product.attr_delete')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Product View ──────────────────────────────
    Route::get('admin/product/productview/{id?}', [ViewProductController::class, 'index'])
        ->middleware('role:product,view')
        ->name('product.productview')->setDefaults(['label' => '', 'role' => 0]);

    // ─── Customer ──────────────────────────────────
    Route::get('admin/customers', [CustomerController::class, 'index'])
        ->middleware('role:customer,view')
        ->name('customer')->setDefaults(['label' => 'View customer', 'role' => 0]);

    Route::get('admin/customer/managecustomer/{id?}', [CustomerController::class, 'managecustomer'])
        ->middleware('role:customer,view')
        ->name('customer.view_customer')->setDefaults(['label' => 'Add customer', 'role' => 0]);

    Route::post('admin/customer/managecustomerprocess', [CustomerController::class, 'managecustomerprocess'])
        ->middleware('role:customer,edit')
        ->name('customer.manage_customer_process')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/customer/status/{id}', [CustomerController::class, 'status'])
        ->middleware('role:customer,edit')
        ->name('customer.status')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/customer/delete/{id}', [CustomerController::class, 'delete'])
        ->middleware('role:customer,delete')
        ->name('customer.delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('admin/customer/bulkaction', [CustomerController::class, 'bulkAction'])
        ->middleware('role:customer,edit')
        ->name('customer.bulkAction')->setDefaults(['label' => '', 'role' => 1]);

    Route::post('admin/customer/saveaddress', [CustomerController::class, 'saveAddress'])
        ->middleware('role:customer,edit')
        ->name('customer.save_address')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('admin/customer/deleteaddress/{id?}/{addid?}', [CustomerController::class, 'deleteAddress'])
        ->middleware('role:customer,edit')
        ->name('customer.delete_address')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/admin/customer/restore/{id}', [CustomerController::class, 'restore'])
        ->middleware('role:customer,delete')
        ->name('customer.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/admin/customer/permanent-delete/{id}', [CustomerController::class, 'permanentDelete'])
        ->middleware('role:customer,delete')
        ->name('customer.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/admin/customer/address/restore/{id}', [CustomerController::class, 'restoreAddress'])
        ->middleware('role:customer,edit')
        ->name('customer.address.restore')->setDefaults(['label' => '', 'role' => 1]);

    Route::get('/admin/customer/address/permanent-delete/{id}', [CustomerController::class, 'permanentDeleteAddress'])
        ->middleware('role:customer,delete')
        ->name('customer.address.permanent_delete')->setDefaults(['label' => '', 'role' => 1]);

    // ─── Reports ───────────────────────────────────
    Route::get('admin/reports/view', [ReportController::class, 'index'])
        ->middleware('role:dashboard,view')
        ->name('admin.reportsView')->setDefaults(['label' => 'Add Report', 'role' => 0]);

    Route::get('admin/report/new', [ReportController::class, 'create'])
        ->middleware('role:dashboard,view')
        ->name('admin.reports')->setDefaults(['label' => 'Add Report', 'role' => 0]);
});
