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
            $result['id'] = $coupon->id;
        } else {

            $result['title'] = '';
            $result['code'] = '';
            $result['value'] = '';
            $result['id'] = 0;
        }

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        //
    }
}
