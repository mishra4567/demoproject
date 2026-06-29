<?php

// app/Http/Controllers/Vendor/VendorColorController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VendorColorController extends BaseVendorController
{
    private function authorise(Color $color): void
    {
        abort_if(
            (int) $color->created_by !== (int) $this->vendorId()
                || $color->is_vendor !== $this->vendor(),
            403
        );
    }

    public function index()
    {
        $data = Color::where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->where(function ($q) {
                $q->where('is_deleted', 0)
                    ->orWhereNull('is_deleted');
            })
            ->latest()
            ->get();

        $deletedData = Color::where('is_deleted', 1)
            ->where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->latest()
            ->get();

        return Inertia::render('Pages/Colors/Index', compact('data', 'deletedData'));
    }

    public function save(Request $request)
    {
        $id = $request->post('id');

        $request->validate([
            'color_name' => 'required|string|max:255',
            'hex_id'     => [
                'required',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
                // Rule::unique('colors', 'hex_id')->ignore($id),
            ],
        ], [
            'hex_id.regex'  => 'Please enter a valid hex color (e.g. #FF0000)',
            'hex_id.unique' => 'This color already exists',
        ]);

        // $model = $id ? Color::findOrFail($id) : new Color();
        if ($id) {
            $model = Color::findOrFail($id);
            $this->authorise($model);
        } else {
            $model = new Color();
        }

        $model->color_name = $request->color_name;
        $model->hex_id     = $request->hex_id;
        $model->is_vendor    = $this->vendor();
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
            $id ? 'Color updated successfully!' : 'Color created successfully!'
        );
    }

    public function status(Color $color)
    {
        // $this->authorise($color);
        // $color->status = $color->status == 1 ? 0 : 1;
        // $color->save();
        $this->authorise($color);
        $newStatus = $color->status == 0 ? 1 : 0;
        $color->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? $this->vendorId() : null,
            'statusupdate_at' => $newStatus == 0 ? now()             : null,
        ]);
        return back()->with('success', 'Status updated!');
    }

    public function destroy(Color $color)
    {
        $this->authorise($color);
        $color->update([
            'is_deleted' => 1,
            'who_delete' => $this->vendorId(),
            'deleted_at' => now(),
        ]);
        return back()->with('success', 'Color moved to trash!');
    }

    public function restore(Color $color)
    {
        $this->authorise($color);
        $color->update([
            'is_deleted' => 0,
            'deleted_at' => null,
            'who_delete' => null,
        ]);
        return back()->with('success', 'Color restored!');
    }

    public function permanentDelete(Color $color)
    {
        return back()->with('error', 'Color permanently delete not allowed!');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,trash,restore,permanent_delete',
            'ids'    => 'required|array',
        ]);

        $colors  = Color::where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->whereIn('id', $request->ids);
        $count   = $colors->count();
        $message = '';

        match ($request->action) {
            'activate'         => ($colors->update([
                'status' => 1,
                'statusupdate_by' => $this->vendorId(),
                'statusupdate_at' => now(),
            ])
                && $message = "{$count} color(s) activated!"),
            'deactivate'       => ($colors->update([
                'status' => 0,
                'statusupdate_by' => null,
                'statusupdate_at' => null,
            ])
                && $message = "{$count} color(s) deactivated!"),
            'trash'            => ($colors->update([
                'is_deleted' => 1,
                'who_delete' => $this->vendorId(),
                'deleted_at' => now(),
            ])
                && $message = "{$count} color(s) moved to trash!"),
            'restore'          => ($colors->update([
                'is_deleted' => 0,
                'deleted_at' => null,
                'who_delete' => null,
            ])
                && $message = "{$count} color(s) restored!"),
            'permanent_delete' => $message = 'Color permanently delete not allowed!',
        };

        if ($request->action === 'permanent_delete') {
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }
}
