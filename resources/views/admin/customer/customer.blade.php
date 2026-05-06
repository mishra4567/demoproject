@extends('admin.layout.layout')
@section('page_title', 'Customer')
@section('customer_select', 'active')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            {{-- <h3 class="title-5 m-b-35">Customers</h3>
            <a href="{{ route('customer.view_customer') }}">
                <button type="button" class="btn btn-success "></button>
            </a> --}}
            {{-- Trash Toggle --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="title-5 m-b-0">Customer</h3>
                <a href="{{ route('customer.view_customer') }}">
                    <button type="button" class="btn btn-success">
                        <i class="fa fa-plus me-1"></i>Add Customer
                    </button>
                </a>
            </div>
            <div class="d-flex gap-2 mb-3">
                <button type="button" class="btn btn-secondary" id="trash_toggle_btn" onclick="toggleTrash()">
                    <i class="fa fa-trash me-1"></i> Show Deleted
                    @if ($deletedData->count() > 0)
                        <span class="badge bg-danger ms-1">{{ $deletedData->count() }}</span>
                    @endif
                </button>
            </div>
            {{-- Trash Toggle --}}
            <div class="row">
                <form action="{{ route('customer.bulkAction') }}" method="POST">
                    @csrf
                    <!-- Bulk Action Dropdown -->
                                       <input type="hidden" name="bulk_type" id="bulk_type" value="active">
                    {{-- Bulk Action --}}
                    <div class="mb-3 d-flex gap-2">
                        <select name="action" id="bulk_action" class="form-control w-auto">
                            <option value="">Bulk Action</option>
                            {{-- Active Section --}}
                            <option value="activate" class="active-option">Activate</option>
                            <option value="deactivate" class="active-option">Deactivate</option>
                            <option value="trash" class="active-option">Move to Trash</option>
                            {{-- Deleted Section --}}
                            <option value="restore" class="deleted-option d-none">Restore</option>
                            <option value="permanent_delete" class="deleted-option d-none">Delete</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Apply</button>
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
                                @forelse ($data as $list)
                                    <tr class="active-row">
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
                                @empty
                                <tr class="active-row">
                                    <td colspan="8">
                                        @include('admin.partials.not_found', [
                                            'type' => 'customer',
                                            'btnText' => 'Add Customer',
                                            'btnUrl' => route('customer.view_customer'),
                                        ])
                                    </td>
                                </tr>
                                @endforelse
                                @foreach ($deletedData as $list)
                                    <tr class="trash-row d-none" style="background:#fff3f3;">
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
                                            <a href="{{ route('customer.restore', $list->id) }}"
                                                onclick="return confirm('Restore {{ $list->name }}?')">
                                                <button type="button" class="btn btn-success btn-sm">
                                                    <i class="fa fa-undo me-1"></i> Restore
                                                </button>
                                            </a>
                                            <a href="{{ route('customer.permanent_delete', $list->id) }}"
                                                onclick="return confirm('Permanently delete? Cannot be undone.')">
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-times me-1"></i> Remove
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
