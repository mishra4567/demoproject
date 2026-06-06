<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VendorCouponController extends Controller
{
    private function vendorId()
    {
        return Auth::guard('vendor')->id();
    }
    private function vendorName()
    {
        return Auth::guard('vendor')->user()->name;
    }
    private function authorise(Coupon $coupon): void
    {
        abort_if($coupon->created_by !== $this->vendorId(), 403);
    }
    public function index()
    {
        $data = Coupon::where('created_by', $this->vendorId())
            ->where('is_deleted', 0)
            ->latest()->get();

        $deletedData = Coupon::where('created_by', $this->vendorId())
            ->where('is_deleted', 1)
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
                Rule::unique('coupons', 'code')->ignore($id),
            ],
            'value' => 'required|numeric|min:1',
            'type'  => 'required|in:flat,percent',
            'expiry' => 'nullable|date',
        ], [
            'code.unique' => 'This coupon code already exists',
        ]);

        $model = $id ? Coupon::findOrFail($id) : new Coupon();

        $model->title      = $request->title;
        $model->code       = strtoupper($request->code);
        $model->value      = $request->value;
        $model->type       = $request->type;
        $model->expiry     = $request->expiry;
        $model->is_vendor   = 'VENDOR';
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
    public function destroy(Coupon $coupon)
    {
        $this->authorise($coupon);
        $coupon->update(['is_deleted' => 1]);
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
        $coupon->update(['is_deleted' => 0]);
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
            'activate'   => ($coupons->update(['status' => 1])
                && $message = "{$count} coupon(s) activated successfully!"),

            'deactivate' => ($coupons->update(['status' => 0])
                && $message = "{$count} coupon(s) deactivated successfully!"),

            'trash'      => ($coupons->update(['is_deleted' => 1])
                && $message = "{$count} coupon(s) moved to trash!"),

            'restore'    => ($coupons->update(['is_deleted' => 0])
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
