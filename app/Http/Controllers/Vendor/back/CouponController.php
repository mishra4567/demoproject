<?php
// app/Http/Controllers/Vendor/CouponController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CouponController extends Controller
{
    private function vendorId()
    {
        return Auth::guard('vendor')->id();
    }

    public function index()
    {
        $data = Coupon::where('vendor_id', $this->vendorId())
            ->where('is_deleted', 0)
            ->latest()->get();

        $deletedData = Coupon::where('vendor_id', $this->vendorId())
            ->where('is_deleted', 1)
            ->latest()->get();

        return Inertia::render('Coupons/Index', compact('data', 'deletedData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|unique:coupons,code|max:50',
            'value' => 'required|numeric|min:1',
            'type' => 'required|in:flat,percent',
            'expiry' => 'nullable|date',
        ]);

        Coupon::create([
            ...$request->only('title', 'code', 'value', 'type', 'expiry'),
            'vendor_id' => $this->vendorId(),
        ]);

        return back()->with('success', 'Coupon created!');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $this->authorise($coupon);

        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|unique:coupons,code,' . $coupon->id . '|max:50',
            'value' => 'required|numeric|min:1',
            'type' => 'required|in:flat,percent',
            'expiry' => 'nullable|date',
        ]);

        $coupon->update($request->only('title', 'code', 'value', 'type', 'expiry'));

        return back()->with('success', 'Coupon updated!');
    }

    public function status(Coupon $coupon)
    {
        $this->authorise($coupon);
        $coupon->update(['status' => !$coupon->status]);
        return back()->with('success', 'Status updated!');
    }

    public function destroy(Coupon $coupon)
    {
        $this->authorise($coupon);
        $coupon->update(['is_deleted' => 1]);
        return back()->with('success', 'Coupon moved to trash!');
    }

    public function restore(Coupon $coupon)
    {
        $this->authorise($coupon);
        $coupon->update(['is_deleted' => 0]);
        return back()->with('success', 'Coupon restored!');
    }

    public function permanentDelete(Coupon $coupon)
    {
        $this->authorise($coupon);
        $coupon->delete();
        return back()->with('success', 'Coupon permanently deleted!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,trash,restore,permanent_delete',
            'ids' => 'required|array',
        ]);

        $coupons = Coupon::where('vendor_id', $this->vendorId())
            ->whereIn('id', $request->ids);

        match ($request->action) {
            'activate' => $coupons->update(['status' => 1]),
            'deactivate' => $coupons->update(['status' => 0]),
            'trash' => $coupons->update(['is_deleted' => 1]),
            'restore' => $coupons->update(['is_deleted' => 0]),
            'permanent_delete' => $coupons->delete(),
        };

        return back()->with('success', 'Bulk action applied!');
    }

    private function authorise(Coupon $coupon)
    {
        abort_if($coupon->vendor_id !== $this->vendorId(), 403);
    }
}
