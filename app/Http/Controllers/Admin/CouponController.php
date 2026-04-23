<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result['data'] = Coupon::all();
        return view('admin.coupon.coupon', $result);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function managecoupons(Request $request, $id = null)
    {
        if (!empty($id) && is_numeric($id)) {

            $coupon = Coupon::where('id', $id)->first();

            if (!$coupon) {
                abort(404);
            }

            $result['title'] = $coupon->title;
            $result['code'] = $coupon->code;
            $result['value'] = $coupon->value;
            $result['type'] = $coupon->type;
            $result['min_order_amt'] = $coupon->min_order_amt;
            $result['is_one_time'] = $coupon->is_one_time;
            $result['id'] = $coupon->id;
        } else {

            $result['title'] = '';
            $result['code'] = '';
            $result['value'] = '';
            $result['type'] = '';
            $result['min_order_amt'] = '';
            $result['is_one_time'] = '';
            $result['id'] = 0;
        }
        $result['info'] = config('field_info.coupon');

        return view('admin.coupon.manage_coupon', $result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function managecouponsprocess(Request $request)
    {
        // Code frome chatgpt
        $id = $request->post('id');
        $request->validate([
            'title' => 'required',
            'code' => [
                'required',
                Rule::unique('coupons', 'code')->ignore($id),
            ],
            'value' => 'required',
        ], [
            'coupon.unique' => 'This coupon already exists',
        ]);
        $model = $id ? Coupon::findOrFail($id) : new Coupon();
        $model->title = $request->title;
        $model->code = $request->code;
        $model->value = $request->value;
        $model->type = $request->type;
        $model->min_order_amt = $request->min_order_amt;
        $model->is_one_time = $request->has('is_one_time') ? 1 : 0;
        $model->save();

        return redirect('admin/coupons')
            ->with('success', $id ? 'Coupon Updated Successfully' : 'Coupon Inserted Successfully');
        // return $request->post();
    }

    /**
     * Display the specified resource.
     */
    public function delete(Request $request, $id)
    {
        // it is get methode to performe delete
        // we have post methode to delete
        // Coupon delete
        $coupon = Coupon::find($id);
        if (!$coupon) return redirect('admin/coupons')->with('error', 'Coupon not found');

        $coupon->delete();

        return redirect('admin/coupons')->with('success', 'Coupon Deleted Successfully...');

        // echo "Coupon deleted" ;
    }

    public function status($id)
    {
        $model = Coupon::find($id);

        // toggle between 1 and 0
        $model->status = ($model->status == 1) ? 0 : 1;
        $model->save();

        return redirect()->back()->with('success', 'Status Updated');
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
                Coupon::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'deactivate':
                Coupon::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'delete':
                // Category::whereIn('id', $ids)->delete();
                return back()->with('error', 'Delete action is not allowed ❌');
                break;
        }
        return back()->with([
            'bulk-success' => $request->action,
            'ids' => is_array($ids) ? $ids : [$ids], // ✅ FIX
        ]);
        // return back()->with('success', 'Bulk action applied');
    }
}
