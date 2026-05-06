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
    public function index()
    {
        $data = DB::table('technical_specs')
            ->leftJoin('products', 'technical_specs.product_id', '=', 'products.id')
            ->select('technical_specs.*', 'products.name as product_name')
            ->where('technical_specs.is_deleted', 0)
            ->get();
        $deletedData = DB::table('technical_specs')
            ->leftJoin('products', 'technical_specs.product_id', '=', 'products.id')
            ->select('technical_specs.*', 'products.name as product_name')
            ->where('technical_specs.is_deleted', 1)
            ->get();
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
        $products = DB::table('products')->where('status', 1)->get();
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
            'tax_type'       => 'nullable|in:inclusive,exclusive,none',
        ], [
            'product_id.unique' => 'This product already has a technical specification.',
        ]);
        $proTechnicalSpecs = $id ? TechnicalSpecs::findOrFail($id) : new TechnicalSpecs();

        $proTechnicalSpecs->title = $request->title;
        $proTechnicalSpecs->product_id = $request->product_id;
        $proTechnicalSpecs->lead_time_from = $request->lead_time_from ?? null;
        $proTechnicalSpecs->lead_time_to    = $request->lead_time_to    ?? null;
        $proTechnicalSpecs->tax             = $request->tax             ?? 0;
        $proTechnicalSpecs->tax_type        = $request->tax_type        ?? 'none';
        $proTechnicalSpecs->is_promo = $request->has('is_promo') ? 1 : 0;
        $proTechnicalSpecs->is_featured = $request->has('is_featured') ? 1 : 0;
        $proTechnicalSpecs->is_discounted = $request->has('is_discounted') ? 1 : 0;
        $proTechnicalSpecs->is_trending = $request->has('is_trending') ? 1 : 0;
        $proTechnicalSpecs->who_create = session('ADMIN_ID');
        $proTechnicalSpecs->status = '1';
        $proTechnicalSpecs->created_at = now();
        if ($id) {
            $proTechnicalSpecs->updated_at  = now();
        }
        $proTechnicalSpecs->save();

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
        $teschspecs->is_deleted = 1;
        $teschspecs->deleted_at = now();
        $teschspecs->who_delete = session('ADMIN_ID');
        $teschspecs->save();
        return redirect()->back()->with('success', 'Tecnical Specs Deleted Successfully...');
    }
    public function restore($id)
    {
        $teschspecs = TechnicalSpecs::find($id);
        if (!$teschspecs) return redirect()->back()->with('error', 'Tecnical Specs not found');
        $teschspecs->is_deleted = 0;
        $teschspecs->deleted_at = null;
        $teschspecs->who_delete = null;
        $teschspecs->save();
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
        $color = TechnicalSpecs::find($id);

        // toggle between 1 and 0
        $color->status = ($color->status == 1) ? 0 : 1;
        $color->save();

        return redirect()->back()->with('success', 'Status Updated');
        // echo "this is for Color status";
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
                TechnicalSpecs::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'deactivate':
                TechnicalSpecs::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'trash':
                TechnicalSpecs::whereIn('id', $ids)->update([
                    'is_deleted' => 1,
                    'deleted_at' => now(),
                    'who_delete' => session('ADMIN_ID')
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
