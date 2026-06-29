<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechnicalSpecs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use function Symfony\Component\Clock\now;

class TechnicalSpecsController extends Controller
{
    protected function adminId()
    {
        return session('ADMIN_ID');
    }

    protected function adminName()
    {
        return session('ADMIN_NAME');
    }
    public function index()
    {
        $data = DB::table('technical_specs')
            ->leftJoin('products', 'technical_specs.product_id', '=', 'products.id')
            ->select('technical_specs.*', 'products.name as product_name')
            ->where('technical_specs.is_deleted', 0)
            ->get()
            ->each(function ($data) {
                $data->locked = $data->is_vendor === 'VENDOR';
            });
        $deletedData = DB::table('technical_specs')
            ->leftJoin('products', 'technical_specs.product_id', '=', 'products.id')
            ->select('technical_specs.*', 'products.name as product_name')
            ->where('technical_specs.is_deleted', 1)
            ->get()
            ->each(function ($data) {
                $data->locked = $data->is_vendor === 'VENDOR';
            });
        $info = config('field_info.technical_specification');

        return view('admin.product.technical_specs', compact('data', 'deletedData', 'info'));
    }
    public function addTechnicalSpecs(Request $request, $id = null)
    {
        if (!empty($id) && is_numeric($id)) {
            $teschspecs = DB::table('technical_specs')
                ->leftJoin('products', 'technical_specs.product_id', '=', 'products.id')
                ->select('technical_specs.*', 'products.name as product_name')
                ->where('technical_specs.id', $id)
                ->first();
            if (!$teschspecs) {
                abort(404);
            }
            $result =
                (object)[
                    'id' => $teschspecs->id,
                    'title' => $teschspecs->title,
                    'product_id' => $teschspecs->product_id,
                    'lead_time_from' => $teschspecs->lead_time_from,
                    'lead_time_to' => $teschspecs->lead_time_to,
                    'tax' => $teschspecs->tax,
                    'tax_type' => $teschspecs->tax_type,
                    'is_promo' => $teschspecs->is_promo,
                    'is_featured' => $teschspecs->is_featured,
                    'is_discounted' => $teschspecs->is_discounted,
                    'is_trending' => $teschspecs->is_trending,
                    'status' => $teschspecs->status,
                ];
        } else {
            $result =
                (object)[
                    'id' => 0,
                    'title' => '',
                    'product_id' => '',
                    'lead_time_from' => '',
                    'lead_time_to' => '',
                    'tax' => '',
                    'tax_type' => '',
                    'is_promo' => '',
                    'is_featured' => '',
                    'is_discounted' => '',
                    'is_trending' => '',
                    'status' => '',
                ];
        }
        $info = config('field_info.technical_spec');
        $products = DB::table('products')->where('status', 1)
            ->where('is_vendor', 'ADMIN')
            ->get();
        return view('admin.product.manage_technical_specs', compact('result', 'products', 'info'));
    }
    public function processTechnicalSpecs(Request $request)
    {
        // technical specs store and update code
        $id = $request->post('id');
        $request->validate([
            'product_id'     => [
                'required',
                Rule::unique('technical_specs', 'product_id')->ignore($request->id),  // ← unique per product, ignore on update
            ],
            'title'          => 'required|string|max:255',
            'lead_time_from' => 'nullable|date',
            'lead_time_to'   => 'nullable|date|after_or_equal:lead_time_from',
            'tax'            => 'nullable|numeric|min:0|max:100',
            'tax_type'       => 'nullable|in:inclusive,exclusive,other,none',
            'custom_tax_type' => 'nullable|string|max:100',
        ], [
            'product_id.unique' => 'This product already has a technical specification.',
        ]);
        $model = $id ? TechnicalSpecs::findOrFail($id) : new TechnicalSpecs();
        $model->title           = $request->title;
        $model->product_id      = $request->product_id;
        $model->lead_time_from  = $request->lead_time_from ?? null;
        $model->lead_time_to    = $request->lead_time_to    ?? null;
        $model->tax             = $request->tax             ?? 0;
        $model->tax_type        = $request->tax_type        ?? 'none';
        if ($request->tax_type === 'other') {
            $model->custom_tax_type = $request->custom_tax_type;
        } else {
            $model->custom_tax_type = null;
        }
        $model->is_promo        = $request->has('is_promo') ? 1 : 0;
        $model->is_featured     = $request->has('is_featured') ? 1 : 0;
        $model->is_discounted   = $request->has('is_discounted') ? 1 : 0;
        $model->is_trending     = $request->has('is_trending') ? 1 : 0;
        $model->status          = '1';
        $model->is_vendor       = 'ADMIN';
        if ($id) {
            $model->who_edited  = $this->adminName();
            $model->edited_by   =  $this->adminId();
            $model->edited_at   = now();
        } else {
            $model->who_create  = $this->adminName();
            $model->created_by  = $this->adminId();
            $model->created_at  = now();
        }
        $model->save();
        return redirect()->route('product.tecnicalspacs')
            ->with('success', $id ? 'Technical Specs Updated Successfully' : 'Technical Specs Inserted Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function delete(Request $request, $id)
    {
        // // it is get methode to performe delete
        // // we have post methode to delete
        // // TecnicalSpecs delete
        $teschspecs = TechnicalSpecs::find($id);
        if (!$teschspecs) return redirect()->back()->with('error', 'Tecnical Specs not found');
        $teschspecs->update([
            'is_deleted' => 1,
            'who_delete' => $this->adminId(),
            'deleted_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Tecnical Specs Deleted Successfully...');
    }
    public function restore($id)
    {
        $teschspecs = TechnicalSpecs::find($id);
        if (!$teschspecs) return redirect()->back()->with('error', 'Tecnical Specs not found');
        $teschspecs->update([
            'is_deleted' => 0,
            'who_delete' => null,
            'deleted_at' => null,
        ]);
        return redirect()->back()->with('success', 'Tecnical Specs Restored Successfully...');
    }
    public function permanentDelete($id)
    {
        // TechnicalSpecs::findOrFail($id)->delete();
        // return redirect()->back()->with('success', 'Tecnical Specs Permanently Deleted Successfully...');
        return back()->with('error', 'Delete action is not allowed ❌');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $teschspecs = TechnicalSpecs::find($id);
        if (!$teschspecs) {
            return back()->with('error', 'Coupon not found');
        }
        $newStatus = $teschspecs->status == 0 ? 1 : 0;
        $teschspecs->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? $this->adminId() : null,
            'statusupdate_at' => $newStatus == 0 ? now() : null,
        ]);
        return redirect()->back()->with('success', 'Status Updated');
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
                TechnicalSpecs::whereIn('id', $ids)->update([
                    'status' => 1,
                    'statusupdate_by' => $this->adminId(),
                    'statusupdate_at' => now(),
                ]);
                break;
            case 'deactivate':
                TechnicalSpecs::whereIn('id', $ids)->update([
                    'status' => 0,
                    'statusupdate_by' => null,
                    'statusupdate_at' => null,
                ]);
                break;
            case 'trash':
                TechnicalSpecs::whereIn('id', $ids)
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
                TechnicalSpecs::whereIn('id', $ids)->update([
                    'is_deleted' => 0,
                    'deleted_at' => null,
                    'who_delete' => null
                ]);
                break;
            case 'permanent_delete':
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
