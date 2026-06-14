<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VendorSizeController extends Controller
{
    private function vendorId()
    {
        return Auth::guard('vendor')->id();
    }

    private function vendorName()
    {
        return Auth::guard('vendor')->user()->name;
    }

    private function authorise(Size $size): void
    {
        abort_if((int) $size->created_by !== (int) $this->vendorId(), 403);
    }

    public function index()
    {
        $data = Size::where('is_deleted', 0)
            ->where('created_by', $this->vendorId())
            ->latest()
            ->get();

        $deletedData = Size::where('is_deleted', 1)
            ->where('created_by', $this->vendorId())
            ->latest()
            ->get();

        return Inertia::render(
            'Pages/Sizes/Index',
            compact('data', 'deletedData')
        );
    }

    public function save(Request $request)
    {
        $id = $request->id;

        $request->validate([
            'size' => [
                'required',
                Rule::unique('sizes', 'size')->ignore($id),
            ],
        ], [
            'size.required' => 'Size is required',
            'size.unique' => 'Size already exists',
        ]);

        $model = $id
            ? Size::findOrFail($id)
            : new Size();

        $model->size = $request->size;

        if ($id) {
            $model->who_edited = $this->vendorName();
            $model->edited_by  = $this->vendorId();
            $model->edited_at  = now();
        } else {
            $model->who_create = $this->vendorName();
            $model->created_by = $this->vendorId();
            $model->created_at = now();
            $model->status     = 1;
        }

        $model->save();

        return back()->with(
            'success',
            $id
                ? 'Size updated successfully!'
                : 'Size created successfully!'
        );
    }

    public function status(Size $size)
    {
        $this->authorise($size);

        $size->status = $size->status ? 0 : 1;
        $size->save();

        return back()->with('success', 'Status updated!');
    }

    public function destroy(Size $size)
    {
        $this->authorise($size);

        $size->update([
            'is_deleted' => 1,
            'who_delete' => $this->vendorId(),
            'deleted_at' => now(),
        ]);

        return back()->with('success', 'Moved to trash!');
    }

    public function restore(Size $size)
    {
        $this->authorise($size);

        $size->update([
            'is_deleted' => 0,
            'deleted_at' => null,
            'who_delete' => null,
        ]);

        return back()->with('success', 'Restored!');
    }

    public function permanentDelete(Size $size)
    {
        return back()->with(
            'error',
            'Permanent delete is not allowed!'
        );
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required',
            'ids'    => 'required|array',
        ]);

        $sizes = Size::where('created_by', $this->vendorId())
            ->whereIn('id', $request->ids);

        $count = $sizes->count();

        match ($request->action) {
            'activate' =>
            $sizes->update([
                'status' => 1,
                'statusupdate_by' => $this->vendorId(),
                'statusupdate_at' => now(),
            ]),
            'deactivate' =>
            $sizes->update([
                'status' => 0,
                'statusupdate_by' => null,
                'statusupdate_at' => null,
            ]),
            'trash' =>
            $sizes->update([
                'is_deleted' => 1,
                'who_delete' => $this->vendorId(),
                'deleted_at' => now(),
            ]),
            'restore' =>
            $sizes->update([
                'is_deleted' => 0,
                'deleted_at' => null,
                'who_delete' => null,
            ]),
        };

        return back()->with(
            'success',
            "{$count} size(s) updated successfully!"
        );
    }
}
