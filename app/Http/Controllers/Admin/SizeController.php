<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result['data'] = Size::all();
        return view('admin.size.size', $result);
        // echo "This is for size" ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function manageSize(Request $request, $id = null)
    {
        if (!empty($id) && is_numeric($id)) {

            $size = Size::where('id', $id)->first();

            if (!$size) {
                abort(404);
            }
            $result = [
                'size'   => $size->size,
                'status' => $size->status,
                'id'     => $size->id,
            ];
        } else {
            $result = [
                'size'   => '',
                'status' => '',
                'id'     => 0,
            ];
        }

        return view('admin.size.manage_size', $result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function managesizeprocess(Request $request)
    {
        // Code frome chatgpt
        $id = $request->post('id');
        $request->validate([
            'size' => [
                'required',
                Rule::unique('sizes', 'size')->ignore($id),
            ],
        ], [
            'size.unique' => 'This size already exists',
        ]);
        $model = $id ? Size::findOrFail($id) : new Size();
        $model->size = $request->size;
        $model->status = 1;
        $model->save();

        return redirect('admin/size')
            ->with('success', $id ? 'Size Updated Successfully' : 'Size Inserted Successfully');
        // return $request->post();

        // echo "this is for size manage";
    }

    /**
     * Display the specified resource.
     */
    public function delete(Request $request, $id)
    {
        // // it is get methode to performe delete
        // // we have post methode to delete
        // // size delete
        $size = Size::find($id);
        if (!$size) return redirect('admin/size')->with('error', 'size not found');

        $size->delete();

        return redirect('admin/size')->with('success', 'size Deleted Successfully...');
        // echo "size deleted" ;
        // echo "this is for size delete";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $size = Size::find($id);

        // toggle between 1 and 0
        $size->status = ($size->status == 1) ? 0 : 1;
        $size->save();

        return redirect()->back()->with('success', 'Status Updated');
        // echo "this is for size status";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Size $size)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        //
    }
}
