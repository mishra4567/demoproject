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
        $technicalSpecs = DB::table('technical_specs')
            ->leftJoin('products', 'technical_specs.product_id', '=', 'products.id')
            ->select('technical_specs.*', 'products.name as product_name')
            ->get();

        return view('admin.product.technical_specs', compact('technicalSpecs'));
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
        $products = DB::table('products')->where('status', 1)->get();
        return view('admin.product.manage_technical_specs', compact('result', 'products'));
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
        // // color delete
        $color = TechnicalSpecs::find($id);
        if (!$color) return redirect('admin/color')->with('error', 'color not found');

        $color->delete();

        return redirect('admin/color')->with('success', 'color Deleted Successfully...');
        // echo "color deleted" ;
        // echo "this is for color delete";
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
            case 'delete':
                // Category::whereIn('id', $ids)->delete();
                return back()->with('error', 'Delete action is not allowed ❌');
                break;
        }
        return back()->with([
            'bulk-success' => $request->action,
            'ids' => is_array($ids) ? $ids : [$ids], // ✅ FIX
        ]);
        // return back()->with('success', 'Bulk action applied');
    }
}
