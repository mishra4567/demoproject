<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VendorCouponController extends BaseVendorController
{
    private function authorise(Coupon $coupon): void
    {
        abort_if(
            (int) $coupon->created_by !== (int) $this->vendorId()
                || $coupon->is_vendor !== $this->vendor(),
            403
        );
    }
    public function index()
    {
        $data = Coupon::where('is_deleted', 0)
            ->where('is_vendor', $this->vendor())
            ->where('created_by', $this->vendorId())
            ->latest()->get();

        $deletedData = Coupon::where('is_deleted', 1)
            ->where('is_vendor', $this->vendor())
            ->where('created_by', $this->vendorId())
            ->latest()->get();

        return Inertia::render('Pages/Coupons/Index', compact('data', 'deletedData'));
    }
    // app/Http/Controllers/Vendor/CouponController.php

    public function save(Request $request)
    {
        $id = $request->post('id');

        $request->validate([
            'title' => 'required',
            'code'  => [
                'required',
                Rule::unique('coupons', 'code')->where(fn($q) => $q
                    ->where('created_by', $this->vendorId())
                    ->where('is_vendor', $this->vendor()))
                    ->ignore($id),
            ],
            'value' => 'required|numeric|min:1',
            'type'  => 'required|in:flat,percent',
            'expiry' => 'nullable|date',
        ], [
            'code.unique' => 'This coupon code already exists',
        ]);

        // $model = $id ? Coupon::findOrFail($id) : new Coupon();
        if ($id) {
            $model = Coupon::findOrFail($id);
            $this->authorise($model);
        } else {
            $model = new Coupon();
        }
        $model->title      = $request->title;
        $model->code       = strtoupper($request->code);
        $model->value      = $request->value;
        $model->type       = $request->type;
        $model->min_order_amt = $request->min_order_amt;
        $model->is_one_time = $request->is_one_time ? 1 : 0;
        $model->expiry     = $request->expiry;
        $model->is_vendor   = $this->vendor();
        if ($id) {
            // Update
            $model->who_edited  = $this->vendorName();
            $model->edited_by   = $this->vendorId();
            $model->edited_at   = now();
        } else {
            // Create
            $model->who_create  = $this->vendorName();
            $model->created_by  = $this->vendorId();
            $model->created_at  = now();
        }
        $model->save();

        return back()->with(
            'success',
            $id
                ? 'Coupon updated successfully!'
                : 'Coupon created successfully!'
        );
    }
    public function status(Coupon $coupon)
    {
        $this->authorise($coupon);
        $newStatus = $coupon->status == 0 ? 1 : 0;
        $coupon->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? $this->vendorId() : null,
            'statusupdate_at' => $newStatus == 0 ? now()             : null,
        ]);
        return back()->with('success', 'Status updated!');
    }
    public function destroy(Coupon $coupon)
    {
        $this->authorise($coupon);
        $coupon->update([
            'is_deleted' => 1,
            'who_delete' => $this->vendorId(),
            'deleted_at' => now(),
        ]);
        return back()->with('success', 'Coupon moved to trash!');
        // dd([
        //     'coupon_created_by' => $coupon->created_by,
        //     'vendor_id'         => $this->vendorId(),
        //     'match'             => $coupon->created_by === $this->vendorId(),
        // ]);
    }
    public function restore(Coupon $coupon)
    {
        $this->authorise($coupon);
        $coupon->update([
            'is_deleted' => 0,
            'deleted_at' => null,
            'who_delete' => null
        ]);
        return back()->with('success', 'Coupon restored!');
    }
    public function permanentDelete(Coupon $coupon)
    {
        // $this->authorise($coupon);
        // $coupon->delete();
        return back()->with('error', 'Coupon permanently Delete Not allowed!');
    }
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,trash,restore,permanent_delete',
            'ids'    => 'required|array',
        ]);

        $coupons = Coupon::where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->whereIn('id', $request->ids);

        // match ($request->action) {
        //     'activate'         => $coupons->update(['status'     => 1]),
        //     'deactivate'       => $coupons->update(['status'     => 0]),
        //     'trash'            => $coupons->update(['is_deleted' => 1]),
        //     'restore'          => $coupons->update(['is_deleted' => 0]),
        //     'permanent_delete' => $coupons->delete(),
        // };
        $count   = $coupons->count();
        $message = '';

        match ($request->action) {
            'activate'   => ($coupons->update([
                'status' => 1,
                'statusupdate_by' => $this->vendorId(),
                'statusupdate_at' => now(),
            ])
                && $message = "{$count} coupon(s) activated successfully!"),

            'deactivate' => ($coupons->update([
                'status' => 0,
                'statusupdate_by' => null,
                'statusupdate_at' => null,
            ])
                && $message = "{$count} coupon(s) deactivated successfully!"),

            'trash'      => ($coupons->update([
                'is_deleted' => 1,
                'who_delete' => $this->vendorId(),
                'deleted_at' => now(),
            ])
                && $message = "{$count} coupon(s) moved to trash!"),

            'restore'    => ($coupons->update([
                'is_deleted' => 0,
                'deleted_at' => null,
                'who_delete' => null,
            ])
                && $message = "{$count} coupon(s) restored successfully!"),

            'permanent_delete' => $message = 'Coupon permanently delete not allowed!',
        };

        // permanent_delete blocked — return error
        if ($request->action === 'permanent_delete') {
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }
}
