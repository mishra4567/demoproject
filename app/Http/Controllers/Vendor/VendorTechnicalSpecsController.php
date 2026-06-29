<?php
// app/Http/Controllers/Vendor/VendorTechnicalSpecsController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\TechnicalSpecs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VendorTechnicalSpecsController extends BaseVendorController
{
    private function authorise(TechnicalSpecs $spec): void
    {
        abort_if(
            (int) $spec->created_by !== (int) $this->vendorId()
                || $spec->is_vendor !== $this->vendor(),
            403
        );
    }

    public function index()
    {
        $data = DB::table('technical_specs')
            ->leftJoin('products', 'technical_specs.product_id', '=', 'products.id')
            ->select('technical_specs.*', 'products.name as product_name')
            ->where('technical_specs.created_by', $this->vendorId())
            ->where('technical_specs.is_vendor', $this->vendor())
            ->where(function ($q) {
                $q->where('technical_specs.is_deleted', 0)
                    ->orWhereNull('technical_specs.is_deleted');
            })
            ->latest('technical_specs.created_at')
            ->get();

        $deletedData = DB::table('technical_specs')
            ->leftJoin('products', 'technical_specs.product_id', '=', 'products.id')
            ->select('technical_specs.*', 'products.name as product_name')
            ->where('technical_specs.created_by', $this->vendorId())
            ->where('technical_specs.is_vendor', $this->vendor())
            ->where('technical_specs.is_deleted', 1)
            ->latest('technical_specs.created_at')
            ->get();

        $products = DB::table('products')
            ->where('status', 1)
            ->where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->select('id', 'name')
            ->get();

        return Inertia::render(
            'Pages/TechnicalSpecs/Index',
            compact('data', 'deletedData', 'products')
        );
    }

    public function save(Request $request)
    {
        $id = $request->post('id');

        $request->validate([
            'product_id' => [
                'required',
                Rule::unique('technical_specs', 'product_id')->ignore($id),
            ],
            'title'          => 'required|string|max:255',
            'lead_time_from' => 'nullable|date',
            'lead_time_to'   => 'nullable|date|after_or_equal:lead_time_from',
            'tax'            => 'nullable|numeric|min:0|max:100',
            'tax_type'       => 'nullable|in:inclusive,exclusive,none',
        ], [
            'product_id.unique' => 'This product already has a technical specification.',
        ]);

        // $model = $id ? TechnicalSpecs::findOrFail($id) : new TechnicalSpecs();
        if ($id) {
            $model = TechnicalSpecs::findOrFail($id);
            $this->authorise($model);
        } else {
            $model = new TechnicalSpecs();
        }
        $model->product_id     = $request->product_id;
        $model->title          = $request->title;
        $model->lead_time_from = $request->lead_time_from ?? null;
        $model->lead_time_to   = $request->lead_time_to   ?? null;
        $model->tax            = $request->tax            ?? 0;
        $model->tax_type       = $request->tax_type       ?? 'none';
        $model->is_promo       = $request->boolean('is_promo')      ? 1 : 0;
        $model->is_featured    = $request->boolean('is_featured')   ? 1 : 0;
        $model->is_discounted  = $request->boolean('is_discounted') ? 1 : 0;
        $model->is_trending    = $request->boolean('is_trending')   ? 1 : 0;
        $model->status         = 1;
        $model->is_vendor = $this->vendor();
        if ($id) {
            $model->who_edited = $this->vendorName();
            $model->edited_by  = $this->vendorId();
            $model->updated_at = now();
        } else {
            $model->who_create = $this->vendorName();
            $model->created_by = $this->vendorId();
            $model->created_at = now();
        }

        $model->save();

        return back()->with(
            'success',
            $id ? 'Technical spec updated!' : 'Technical spec created!'
        );
    }

    public function status(TechnicalSpecs $technicalSpec)
    {
        $this->authorise($technicalSpec);
        $newStatus = $technicalSpec->status == 0 ? 1 : 0;

        $technicalSpec->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 1 ? $this->vendorId() : null,
            'statusupdate_at' => $newStatus == 1 ? now() : null,
        ]);
        return back()->with('success', 'Status updated!');
    }

    public function destroy(TechnicalSpecs $technicalSpec)
    {
        $this->authorise($technicalSpec);
        $technicalSpec->update([
            'is_deleted' => 1,
            'who_delete' => $this->vendorId(),
            'deleted_at' => now(),
        ]);
        return back()->with('success', 'Moved to trash!');
    }

    public function restore(TechnicalSpecs $technicalSpec)
    {
        $this->authorise($technicalSpec);
        $technicalSpec->update([
            'is_deleted' => 0,
            'deleted_at' => null,
            'who_delete' => null,
        ]);
        return back()->with('success', 'Restored!');
    }

    public function permanentDelete(TechnicalSpecs $technicalSpec)
    {
        return back()->with('error', 'Permanent delete is not allowed!');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,trash,restore,permanent_delete',
            'ids'    => 'required|array',
        ]);

        $specs   = TechnicalSpecs::where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->whereIn('id', $request->ids);
        $count   = $specs->count();
        $message = '';

        match ($request->action) {
            'activate'         => ($specs->update([
                'status' => 1,
                'statusupdate_by' => $this->vendorId(),
                'statusupdate_at' => now(),
            ])
                && $message = "{$count} spec(s) activated!"),
            'deactivate'       => ($specs->update([
                'status' => 0,
                'statusupdate_by' => null,
                'statusupdate_at' => null,
            ])
                && $message = "{$count} spec(s) deactivated!"),
            'trash'            => ($specs->update([
                'is_deleted' => 1,
                'who_delete' => $this->vendorId(),
                'deleted_at' => now(),
            ])
                && $message = "{$count} spec(s) moved to trash!"),
            'restore'          => ($specs->update([
                'is_deleted' => 0,
                'deleted_at' => null,
                'who_delete' => null,
            ])
                && $message = "{$count} spec(s) restored!"),
            'permanent_delete' => $message = 'Permanent delete is not allowed!',
        };

        if ($request->action === 'permanent_delete') {
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }
}
