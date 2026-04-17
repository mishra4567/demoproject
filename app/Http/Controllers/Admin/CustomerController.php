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
        $custodetails = DB::table('customers')->get();
        return view('admin.customer.customer', compact('custodetails'));
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
        $custo_add = DB::table('cust_addresses')->where('customer_id', $id)->get();
        return view('admin.customer.manage_customer', compact('result', 'custo_add'));
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

        $data->delete();

        return back()->with('success', 'Customer Deleted Successfully...');
        // echo "data deleted" ;
        // echo "this is for data delete";
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
        $data->delete();

        return redirect()->back()->with('success', 'Address Deleted Successfully');
    }
}
