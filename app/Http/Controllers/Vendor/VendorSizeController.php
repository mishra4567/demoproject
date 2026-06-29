<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VendorSizeController extends BaseVendorController
{
    private function authorise(Size $size): void
    {
        abort_if(
            (int) $size->created_by !== (int) $this->vendorId()
                || $size->is_vendor !== $this->vendor(),
            403
        );
    }

    public function index()
    {
        $data = Size::where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->where(function ($q) {
                $q->where('is_deleted', 0)
                    ->orWhereNull('is_deleted');
            })
            ->latest()
            ->get();

        $deletedData = Size::where('is_deleted', 1)
            ->where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
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
            'size'    => 'required|string|max:255',
            'type'    => 'nullable|string|max:50',
            'details' => 'nullable|string|max:255',
        ], [
            'size.required' => 'Size is required',
        ]);

        $type = $request->type;

        if ($type === 'Other' && !empty($request->custom_type)) {
            $type = $request->custom_type;
        }

        // $model = $id
        //     ? Size::findOrFail($id)
        //     : new Size();
        if ($id) {
            $model = Size::findOrFail($id);
            $this->authorise($model);
        } else {
            $model = new Size();
        }

        $model->size = $request->size;
        $model->type    = $type;
        $model->details = $request->details;
        $model->is_vendor = $this->vendor();
        $model->status     = 1;
        if ($id) {
            $model->who_edited = $this->vendorName();
            $model->edited_by  = $this->vendorId();
            $model->edited_at  = now();
        } else {
            $model->who_create = $this->vendorName();
            $model->created_by = $this->vendorId();
            $model->created_at = now();
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
        $newStatus = $size->status == 0 ? 1 : 0;
        $size->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 1 ? $this->vendorId() : null,
            'statusupdate_at' => $newStatus == 1 ? now() : null,
        ]);
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
