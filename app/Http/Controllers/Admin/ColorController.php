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
        $result['data'] = Color::all();
        $result['info'] = config('field_info.color');
        return view('admin.color.color', $result);
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

            $result['color']  = $color->color;
            $result['status'] = $color->status;
            $result['id']     = $color->id;
        } else {

            $result['color']  = '';
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
        // Code frome chatgpt
        $id = $request->post('id');
        $request->validate([
            'color' => [
                'required',
                Rule::unique('colors', 'color')->ignore($id),
            ],
        ], [
            'color.unique' => 'This color already exists',
        ]);
        $model = $id ? Color::findOrFail($id) : new Color();
        $model->color = $request->color;
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
        // // it is get methode to performe delete
        // // we have post methode to delete
        // // color delete
        $color = Color::find($id);
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
