<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Color::where('is_deleted', 0)->get();
        $deletedData = Color::where('is_deleted', 1)->get();
        $info = config('field_info.color');
        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";
        // die();
        return view('admin.color.color', compact('data', 'deletedData', 'info'));
        // echo "This is for color" ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function manageColor(Request $request, $id = null)
    {
        if (!empty($id) && is_numeric($id)) {

            $color = Color::where('id', $id)->first();

            if (!$color) {
                abort(404);
            }

            $result['color_name']  = $color->color_name;
            $result['hex_id'] = $color->hex_id;
            $result['status'] = $color->status;
            $result['id']     = $color->id;
        } else {

            $result['color_name']  = '';
            $result['hex_id'] = '';
            $result['status'] = '';
            $result['id']     = 0;
        }

        $result['info'] = config('field_info.color');
        return view('admin.color.manage_color', $result);

        // echo "This is for manage color";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function managecolorprocess(Request $request)
    {
        // echo "<pre>";
        // print_r($request->post());
        // echo "</pre>";
        // die();
        // Code frome chatgpt
        $id = $request->post('id');
        $request->validate([
            'color' => [
                'required',
                // Rule::unique('colors', 'color')->ignore($id),
            ],
        ], [
            'color.unique' => 'This color already exists',
        ]);
        $model = $id ? Color::findOrFail($id) : new Color();
        $model->color_name = $request->color_name;
        $model->hex_id = $request->color;
        $model->who_create = session('ADMIN_ID');
        $model->created_at = now();
        $model->status = 1;
        $model->save();

        return redirect('admin/color')
            ->with('success', $id ? 'Color Updated Successfully' : 'Color Inserted Successfully');
        // return $request->post();

        // echo "this is for color manage";
    }

    /**
     * Display the specified resource.
     */
    public function delete(Request $request, $id)
    {
        $color = Color::find($id);
        if (!$color) return redirect('admin/color')->with('error', 'color not found');
        $color->is_deleted = 1;
        $color->deleted_at = now();
        $color->who_delete = session('ADMIN_ID');
        $color->save();
        return redirect('admin/color')->with('success', 'Color moved to trash...');
        // echo "color deleted" ;
        // echo "this is for color delete";
    }
    // ─── Restore ───────────────────────────────────────────
    public function restore(Request $request, $id)
    {
        $color = Color::find($id);
        if (!$color) return redirect('admin/color')->with('error', 'color not found');
        $color->is_deleted = 0;
        $color->deleted_at = null;
        $color->who_delete = null;
        $color->save();
        return redirect()->route('color')
            ->with('success', 'Color restored successfully.');
    }
    // ─── Permanent Delete ──────────────────────────────────
    public function permanentDelete($id)
    {
        // Color::findOrFail($id)->delete();
        // return redirect()->route('color')
        // ->with('success', 'Color permanently deleted.');
        return back()->with('error', 'Delete action is not allowed ❌');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $color = Color::find($id);

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
                Color::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'deactivate':
                Color::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'trash':
                Color::whereIn('id', $ids)->update([
                    'is_deleted' => 1,
                    'deleted_at' => now(),
                    'who_delete' => session('ADMIN_ID')
                ]);
                // return back()->with('error', 'Delete action is not allowed ❌');
                break;
            case 'restore':
                Color::whereIn('id', $ids)->update([
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
