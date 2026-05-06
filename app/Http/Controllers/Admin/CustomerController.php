<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cust_address;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $data = Customer::where('is_deleted', 0)->get();
        $deletedData = Customer::where('is_deleted', 1)->get();
        $info = config('field_info.customer');
        return view('admin.customer.customer', compact('data', 'deletedData', 'info'));
    }
    public function managecustomer(Request $request, $id = null)
    {
        // $id= $request->get('id');
        if (!empty($id) && is_numeric($id)) {
            $cusview = DB::table('customers')
                // ->where('status', 1)
                ->where('id', $id)
                ->first();
            if (!$cusview) abort(404);
            $result = (object)[
                'id' => $cusview->id,
                'name' => $cusview->name,
                'email' => $cusview->email,
                // 'password' => $cusview->password,
                'phone' => $cusview->phone,
                'status' => $cusview->status,
            ];
        } else {
            $result = (object)[
                'id' => 0,
                'name' => '',
                'email' => '',
                // 'password' => '',
                'phone' => '',
                'status' => '',
            ];
        }
        // echo "<pre>";
        // print_r($request->get('id'));
        // print_r($result);
        // echo "</pre";
        // die();
        $info = config('field_info.customer');
        $custo_add = DB::table('cust_addresses')->where('customer_id', $id)->get();
        return view('admin.customer.manage_customer', compact('result', 'custo_add','info'));
    }
    public function managecustomerprocess(Request $request, $id)
    {
        $id = $request->post('id');
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre";
        // die();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('customers', 'email')->ignore($id),
            ],
            'phone' => 'required',
        ]);


        $data = $id ? Customer::findOrFail($id) : new Customer();

        // ✅ Assign values
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->status = $request->status ? 1 : 0;
        $data->who_created = session('ADMIN_ID') ?? 0; // Assuming you have admin authentication
        $data->created_at = now();
        // ✅ Password only if filled
        if (!empty($request->password)) {
            $data->password = bcrypt($request->password);
        }
        // echo "<pre>";
        // print_r($data-> toArray());
        // echo "</pre";
        // die();
        $data->save();
        return redirect('admin/customers')
            ->with('success', $id ? 'Customer Updated Successfully' : 'Customer Created Successfully');
    }
    public function saveAddress(Request $request)
    {
        $id = $request->post('id');
        $request->validate([
            'customer_id' => 'required|numeric',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'zipcode' => 'required',
        ]);
        $data = $id ? Cust_address::findOrFail($id) : new Cust_address();
        // ✅ Assign values
        $data->customer_id = $request->customer_id;
        $data->address = $request->address;
        $data->landmark = $request->landmark;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->zipcode = $request->zipcode;
        $data->country = $request->country;
        $data->label = $request->label;
        $data->is_default = $request->is_default ? 1 : 0;
        $data->who_created = session('ADMIN_ID') ?? 0;
        $data->created_at = now();
        $data->save();
        // If this address is being set as default,
        // remove default from all other addresses of the same customer
        if ($data->is_default) {
            Cust_address::where('customer_id', $request->customer_id)
                ->where('id', '!=', $data->id)
                ->update(['is_default' => 0]);
        }

        return redirect()->back()
            ->with('success', $id ? 'Customer Updated Successfully' : 'Customer Created Successfully');
    }
    /**
     * Display the specified resource.
     */
    public function delete(Request $request, $id)
    {
        // // it is get methode to performe delete
        // // we have post methode to delete
        // // Customer Add delete
        $data = Customer::find($id);
        if (!$data) return back()->with('error', 'Customer not found');
        $data->is_deleted = 1;
        $data->deleted_at = now();
        $data->who_delete = session('ADMIN_ID') ?? 0;
        $data->save();
        return back()->with('success', 'Customer Deleted Successfully...');
        // echo "data deleted" ;
        // echo "this is for data delete";
    }
    public function restore(Request $request, $id)
    {
        $data = Customer::find($id);
        if (!$data) return back()->with('error', 'Customer not found');
        $data->is_deleted = 0;
        $data->deleted_at = null;
        $data->who_delete = null;
        $data->save();
        return back()->with('success', 'Customer Restored Successfully...');
    }
    public function permanentDelete($id)
    {
        // Customer::findOrFail($id)->delete();
        // return back()->with('success', 'Customer permanently deleted.');
        return back()->with('error', 'Delete action is not allowed ❌');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $data = Customer::find($id);

        // toggle between 1 and 0
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->save();

        return redirect()->back()->with('success', 'Status Updated');
        // echo "this is for data status";
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
                Customer::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'deactivate':
                Customer::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'trash':
                Customer::whereIn('id', $ids)->update([
                    'is_deleted' => 1,
                    'deleted_at' => now(),
                    'who_delete' => session('ADMIN_ID')
                ]);
                // return back()->with('error', 'Delete action is not allowed ❌');
                break;
            case 'restore':
                Customer::whereIn('id', $ids)->update([
                    'is_deleted' => 0,
                    'deleted_at' => null,
                    'who_delete' => null
                ]);
                break;
            case 'permanent_delete':
                // ::whereIn('id', $ids)->delete();
                return back()->with('error', 'Permanent delete is not allowed ❌');
                break;
        }
        return back()->with([
            'bulk-success' => $request->action,
            'ids' => is_array($ids) ? $ids : [$ids], // ✅ FIX
        ]);
        // return back()->with('success', 'Bulk action applied');
    }
    public function deleteAddress(Request $request, $id = null, $addid = null)
    {
        // $id = customer_id
        // $addid = address_id

        $data = Cust_address::where('id', $addid)
            ->where('customer_id', $id)
            ->first();
        if (!$data) {
            return redirect()->back()->with('error', 'Address not found');
        }
        $data->is_deleted = 1;
        $data->deleted_at = now();
        $data->who_delete = session('ADMIN_ID') ?? 0;
        $data->save();
        return redirect()->back()->with('success', 'Address Deleted Successfully');
    }
    public function restoreAddress(Request $request, $id = null, $addid = null)
    {
        // $id = customer_id
        // $addid = address_id

        $data = Cust_address::where('id', $addid)
            ->where('customer_id', $id)
            ->first();
        if (!$data) {
            return redirect()->back()->with('error', 'Address not found');
        }
        $data->is_deleted = 0;
        $data->deleted_at = null;
        $data->who_delete = null;
        $data->save();
        return redirect()->back()->with('success', 'Address Restored Successfully');
    }
    public function permanentDeleteAddress(Request $request, $id = null, $addid = null)
    {
        // $id = customer_id
        // $addid = address_id

        // Cust_address::findOrFail($addid)->delete();
        // return redirect()->back()->with('success', 'Address permanently deleted.');
        return redirect()->back()->with('error', 'Delete action is not allowed ❌');
    }
}
