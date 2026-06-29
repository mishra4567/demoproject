<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
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
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Coupon::where('is_deleted', 0)
            ->latest()
            ->get()
            ->each(function ($color) {
                $color->locked = $color->is_vendor === 'VENDOR';
            });
        $deletedData = Coupon::where('is_deleted', 1)
            ->get()
            ->each(function ($color) {
                $color->locked = $color->is_vendor === 'VENDOR';
            });
        $info = config('field_info.coupon');
        return view('admin.coupon.coupon', compact('data', 'deletedData', 'info'));
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
            $result['expiry'] = $coupon->expiry;
            $result['min_order_amt'] = $coupon->min_order_amt;
            $result['is_one_time'] = $coupon->is_one_time;
            $result['id'] = $coupon->id;
        } else {

            $result['title'] = '';
            $result['code'] = '';
            $result['value'] = '';
            $result['type'] = '';
            $result['expiry'] = '';
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
            'type' => 'required|in:Percentage,Fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amt' => 'nullable|numeric|min:0',
            'expiry' => 'nullable|date',
        ], [
            'coupon.unique' => 'This coupon already exists',
            'code.unique' => 'This coupon code already exists',
        ]);
        $model = $id ? Coupon::findOrFail($id) : new Coupon();
        $model->title = $request->title;
        $model->code = strtoupper($request->code);
        $model->value = $request->value;
        $model->type = $request->type;
        $model->min_order_amt = $request->min_order_amt;
        $model->is_one_time = $request->has('is_one_time') ? 1 : 0;
        // $model->who_created = session('ADMIN_ID');
        // $model->created_at = now();
        $model->expiry = $request->expiry
            ? date('Y-m-d H:i:s', strtotime($request->expiry))
            : null;
        $model->is_vendor = 'ADMIN';
        if ($id) {
            $model->who_edited = $this->adminName();
            $model->edited_by =  $this->adminId();
            $model->edited_at = now();
        } else {
            $model->who_create = $this->adminName();
            $model->created_by = $this->adminId();
            $model->created_at = now();
        }
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
        $coupon->update([
            'is_deleted' => 1,
            'who_delete' => $this->adminId(),
            'deleted_at' => now(),
        ]);
        return redirect('admin/coupons')->with('success', 'Coupon Deleted Successfully...');

        // echo "Coupon deleted" ;
    }
    public function restore(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) return redirect('admin/coupon')->with('error', 'coupon not found');
        $coupon->update([
            'is_deleted' => 0,
            'who_delete' => null,
            'deleted_at' => null,
        ]);
        return redirect()->route('coupons')
            ->with('success', 'Coupon restored successfully.');
    }
    public function permanentDelete($id)
    {
        // Coupon::findOrFail($id)->delete();
        // return redirect()->route('coupons')
        // ->with('success', 'Coupon permanently deleted.');
        return back()->with('error', 'Delete action is not allowed ❌');
    }
    public function status($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return back()->with('error', 'Coupon not found');
        }
        $newStatus = $coupon->status == 1 ? 0 : 1;
        $coupon->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 1 ? $this->adminId() : null,
            'statusupdate_at' => $newStatus == 1 ? now() : null,
        ]);
        return back()->with('success', 'Status Updated Successfully');
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
                Coupon::whereIn('id', $ids)->update([
                    'status' => 1,
                    'statusupdate_by' => $this->adminId(),
                    'statusupdate_at' => now(),
                ]);
                break;
            case 'deactivate':
                Coupon::whereIn('id', $ids)->update([
                    'status' => 0,
                    'statusupdate_by' => null,
                    'statusupdate_at' => null,
                ]);
                break;
            case 'trash':
                Coupon::whereIn('id', $ids)
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
                Coupon::whereIn('id', $ids)->update([
                    'is_deleted' => 0,
                    'deleted_at' => null,
                    'who_delete' => null
                ]);
                break;
            case 'permanent_delete':
                // Color::whereIn('id', $ids)->delete();
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
