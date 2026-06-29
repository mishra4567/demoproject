<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VendorBrandController extends BaseVendorController
{
    private function authorise(Brands $brand): void
    {
        abort_if(
            (int) $brand->created_by !== (int) $this->vendorId()
                || $brand->is_vendor !== $this->vendor(),
            403
        );
    }

    public function index()
    {
        $data = DB::table('brands')
            ->leftJoin('create_media_tables', 'brands.media_ids', '=', 'create_media_tables.id')
            ->select('brands.*', 'create_media_tables.file_name')
            ->where('brands.is_deleted', 0)
            ->where('brands.is_vendor', $this->vendor())
            ->where('brands.created_by', $this->vendorId())
            ->latest('brands.created_at')
            ->get();

        $deletedData = DB::table('brands')
            ->leftJoin('create_media_tables', 'brands.media_ids', '=', 'create_media_tables.id')
            ->select('brands.*', 'create_media_tables.file_name')
            ->where('brands.is_deleted', 1)
            ->where('brands.is_vendor', $this->vendor())
            ->where('brands.created_by', $this->vendorId())
            ->latest('brands.created_at')
            ->get();
        // Pages / Coupons / Index
        return Inertia::render('Pages/Brands/Index', compact('data', 'deletedData'));
    }

    public function save(Request $request)
    {
        $id = $request->post('id');

        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('brands', 'name')->ignore($id),
            ],
        ], [
            'name.required' => 'Brand name is required',
            'name.unique'   => 'This brand already exists',
        ]);

        // $model = $id ? Brands::findOrFail($id) : new Brands();
        if ($id) {
            $model = Brands::findOrFail($id);
            $this->authorise($model);
        } else {
            $model = new Brands();
        }
        $model->name      = $request->name;
        $model->media_ids = $request->media_ids;
        $model->is_vendor = $this->vendor();
        $model->status    = 1;

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
            $id ? 'Brand updated successfully!' : 'Brand created successfully!'
        );
    }

    public function status(Brands $brand)
    {
        $this->authorise($brand);
        $newStatus = $brand->status == 0 ? 1 : 0;
        $brand->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? $this->vendorId() : null,
            'statusupdate_at' => $newStatus == 0 ? now()             : null,
        ]);
        return back()->with('success', 'Status updated!');
    }

    public function destroy(Brands $brand)
    {
        $this->authorise($brand);
        $brand->update([
            'is_deleted' => 1,
            'who_delete' => $this->vendorId(),
            'deleted_at' => now(),
        ]);
        return back()->with('success', 'Brand moved to trash!');
    }

    public function restore(Brands $brand)
    {
        $this->authorise($brand);
        $brand->update([
            'is_deleted' => 0,
            'deleted_at' => null,
            'who_delete' => null,
        ]);
        return back()->with('success', 'Brand restored!');
    }

    public function permanentDelete(Brands $brand)
    {
        // $this->authorise($brand);
        // $brand->delete();
        return back()->with('error', 'Brand permanently delete not allowed!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,trash,restore,permanent_delete',
            'ids'    => 'required|array',
        ]);

        $brands = Brands::where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->whereIn('id', $request->ids);
        $count   = $brands->count();
        $message = '';

        match ($request->action) {
            'activate'   => ($brands->update([
                'status' => 1,
                'statusupdate_by' => $this->vendorId(),
                'statusupdate_at' => now(),
            ])
                && $message = "{$count} brand(s) activated successfully!"),

            'deactivate' => ($brands->update([
                'status' => 0,
                'statusupdate_by' => null,
                'statusupdate_at' => null,
            ])
                && $message = "{$count} brand(s) deactivated successfully!"),

            'trash'      => ($brands->update([
                'is_deleted' => 1,
                'who_delete' => $this->vendorId(),
                'deleted_at' => now(),
            ])
                && $message = "{$count} brand(s) moved to trash!"),

            'restore'    => ($brands->update([
                'is_deleted' => 0,
                'deleted_at' => null,
                'who_delete' => null,
            ])
                && $message = "{$count} brand(s) restored successfully!"),

            'permanent_delete' => $message = 'Brand permanently delete not allowed!',
        };

        if ($request->action === 'permanent_delete') {
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }
}
