<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class brandsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('brands')
            ->leftJoin('create_media_tables', 'brands.media_ids', '=', 'create_media_tables.id')
            ->select('brands.*', 'create_media_tables.file_name')
            ->where('brands.is_deleted', 0)
            ->get();

        $deleteData = DB::table('brands')
            ->leftJoin('create_media_tables', 'brands.media_ids', '=', 'create_media_tables.id')
            ->select('brands.*', 'create_media_tables.file_name')
            ->where('brands.is_deleted', 1)
            ->get();

        return view('admin.brands.brands', compact('data', 'deleteData'));
        // echo "This is for brands" ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function managebrands(Request $request, $id = null)
    {
        if (!empty($id) && is_numeric($id)) {

            // $brands = Brands::where('id', $id)->first();
            $brands = DB::table('brands')
                ->leftJoin('create_media_tables', 'brands.media_ids', '=', 'create_media_tables.id')
                ->select('brands.*', 'create_media_tables.file_name')
                ->where('brands.id', $id)
                ->first();

            if (!$brands) {
                abort(404);
            }

            $result['name']  = $brands->name;
            $result['image']  = $brands->file_name;
            $result['status'] = $brands->status;
            $result['id']     = $brands->id;
        } else {

            $result['name']  = '';
            $result['image']  = '';
            $result['status'] = '';
            $result['id']     = 0;
        }

        return view('admin.brands.manage_brands', $result);

        // echo "This is for manage brands";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function managebrandsprocess(Request $request)
    {
        // Code frome chatgpt
        $id = $request->post('id');
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('brands', 'name')->ignore($id),
            ],
        ], [
            'name.required' => 'Brand name is required',
            'name.unique' => 'This brand already exists',
        ]);
        $model = $id ? Brands::findOrFail($id) : new Brands();
        $model->name = $request->name;
        $model->media_ids = $request->media_ids;
        $model->who_create = session('ADMIN_ID');
        $model->created_at = now();
        $model->status = 1;
        $model->save();

        return redirect('admin/brands')
            ->with('success', $id ? 'brands Updated Successfully' : 'brands Inserted Successfully');
        // return $request->post();

        // echo "this is for brands manage";
    }

    /**
     * Display the specified resource.
     */
    public function delete(Request $request, $id)
    {
        // // it is get methode to performe delete
        // // we have post methode to delete
        // // brands delete
        $brands = Brands::find($id);
        if (!$brands) return redirect('admin/brands')->with('error', 'brands not found');
        $brands->is_deleted = 1;
        $brands->who_delete = session('ADMIN_ID');
        $brands->deleted_at = now();
        $brands->save();
        return redirect('admin/brands')->with('success', 'brands Deleted Successfully...');
        // echo "brands deleted" ;
        // echo "this is for brands delete";
    }
    public function restore(Request $request, $id)
    {
        $brands = Brands::find($id);
        if (!$brands) return redirect('admin/brands')->with('error', 'brands not found');
        $brands->is_deleted = 0;
        $brands->deleted_at = null;
        $brands->who_delete = null;
        $brands->save();
        return redirect()->route('brands')->with('success', 'brands Restored Successfully...');
    }

    public function permanentDelete(Request $request, $id)
    {
        // $brands = Brands::find($id);
        // if (!$brands) return redirect('admin/brands')->with('error', 'brands not found');
        // $brands->delete();
        // return redirect()->route('brands')->with('success', 'brands Permanently Deleted Successfully...');
        return back()->with('error', 'Delete action is not allowed ❌');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $brands = Brands::find($id);

        // toggle between 1 and 0
        $brands->status = ($brands->status == 1) ? 0 : 1;
        $brands->save();

        return redirect()->back()->with('success', 'Status Updated');
        // echo "this is for brands status";
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
                Brands::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'deactivate':
                Brands::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'trash':
                Brands::whereIn('id', $ids)->update([
                    'is_deleted' => 1,
                    'who_delete' => session('ADMIN_ID'),
                    'deleted_at' => now(),
                ]);
                // return back()->with('error', 'Delete  action is not allowed ❌');
                break;
            case 'restore':
                Brands::whereIn('id', $ids)->update([
                    'is_deleted' => 0,
                    'deleted_at' => null,
                    'who_delete' => null
                ]);
                break;
            case 'permanent_delete':
                // Brands::whereIn('id', $ids)->delete();
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
