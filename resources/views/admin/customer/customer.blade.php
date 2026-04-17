@extends('admin.layout.layout')
@section('page_title', 'Customer')
@section('customer_select', 'active')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Customers</h3>
            <a href="{{ route('customer.view_customer') }}">
                <button type="button" class="btn btn-success ">Add Customer</button>
            </a>
            <div class="row">
                <form action="{{ route('customer.bulkAction') }}" method="POST">
                    @csrf
                    <!-- Bulk Action Dropdown -->
                    <div class="mb-3 d-flex">
                        <select name="action" class="form-control w-auto mr-2" required>
                            <option value="">Bulk Action</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>

                        <button type="submit" class="btn btn-primary">
                            Apply
                        </button>
                    </div>
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    {{-- <th>date</th> --}}
                                    <th>
                                        <input type="checkbox" id="select_all">
                                    </th>
                                    <th>order ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    {{-- <th>Image</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($custodetails as $list)
                                    <tr>
                                        {{-- <td>2025-01-15 14:32</td> --}}
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>
                                            <a href="{{ route('customer.view_customer', ['id' => $list->id]) }}"
                                                target="_blank">{{ $list->id }} <i class="fa-regular fa-eye"></i> </a>
                                            <a {{-- onclick="downloadBarcode({{$list->id}})" --}} class="btn btn-success btn-sm">
                                                <i class="zmdi zmdi-download"></i>
                                            </a>
                                        </td>
                                        <td>{{ $list->name }}</td>
                                        <td>{{ $list->email }}</td>
                                        <td>{{ $list->phone }}</td>
                                        {{-- <td><img src="{{ asset('storage/media/' . $list->file_name) }}" alt=""></td> --}}
                                        <td>
                                            <a href="{{ route('customer.status', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm ">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            <a href="{{ route('customer.view_customer', $list->id) }}">
                                                <button type="button" class="btn btn-outline-success btn-sm">Edit </button>
                                            </a>
                                            <a href="{{ route('customer.delete', $list->id) }}">
                                                <button type="button" class="btn btn-outline-danger btn-sm">Delete
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
