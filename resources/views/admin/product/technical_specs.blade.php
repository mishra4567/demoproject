@extends('admin.layout.layout')
@section('page_title', 'Product Technical Specs')
@section('technical_specs', 'active')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Product Technical Specs</h3>
            <a href="{{ route('product.addtecnicalspecs') }}">
                <button type="button" class="btn btn-success ">Add Technical Specs</button>
            </a>
            <div class="row">
                <form action="{{ route('tecnicalspecs.bulkAction') }}" method="POST">
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
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Product ID</th>
                                    <th>Lead Time</th>
                                    <th>Tax</th>
                                    <th>Promoted Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($technicalSpecs as $list)
                                    <tr>
                                        {{-- <td>2025-01-15 14:32</td> --}}
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}<br>
                                            <small class="text-muted">{{ $list->product_name ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $list->title }}</td>
                                        <td>{{ $list->product_id }}</td>
                                        <td>{{ $list->product_id }}</td>
                                        <td>{{ $list->tax }}</td>
                                        <td>
                                            <a href="{{ route('product.tecnicalspecsstatus', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm ">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            <a href="{{ route('product.addtecnicalspecs', $list->id) }}">
                                                <button type="button" class="btn btn-outline-success btn-sm">Edit </button>
                                            </a>
                                            <a href="{{ route('product.tecnicalspecsdelete', $list->id) }}">
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
