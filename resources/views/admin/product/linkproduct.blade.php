@extends('admin.layout.layout')
@section('page_title', 'Link Product')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            {{-- <h3 class="title-5 m-b-35">Product</h3>
            <a href="{{ route('product.addlinkproduct') }}">
                <button type="button" class="btn btn-success ">Add Link Product</button>
            </a> --}}
            {{-- Trash Toggle --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="title-5 m-b-0">Link Product</h3>
                <a href="{{ route('product.addlinkproduct') }}">
                    <button type="button" class="btn btn-success">
                        <i class="fa fa-plus me-1"></i> Add link Product
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
                <form action="{{ route('product.linkbulkAction') }}" method="POST">
                    @csrf
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
                                    <th>id</th>
                                    <th>Sku</th>
                                    <th>Product</th>
                                    <th>Image</th>
                                    {{-- <th>Tag</th> --}}
                                    {{-- <th>Description</th> --}}
                                    <th>Mrp</th>
                                    <th>Price</th>
                                    <th>Price</th>
                                    {{-- <th>Size</th> --}}
                                    {{-- <th>color</th> --}}
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
                                        <td>{{ $list->id }}</td>
                                        <td>{{ $list->sku }}</td>
                                        <td>{{ $list->product_id }}</td>
                                        <td><img src="{{ asset('storage/media/' . $list->file_name) }}" alt=""></td>
                                        <td>{{ $list->mrp }}</td>
                                        <td>{{ $list->price }}</td>
                                        <td>{{ $list->qty }}</td>
                                        <td>
                                            <a href="{{ route('product.linkproductstatus', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm ">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            <a href="{{ route('product.addlinkproduct', ['id' => $list->id]) }}">
                                                <button type="button" class="btn btn-outline-success btn-sm">Edit </button>
                                            </a>
                                            <a href="{{ route('product.linkproductdelete', $list->id) }}">
                                                <button type="button" class="btn btn-outline-danger btn-sm">Delete
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="active-row">
                                        <td colspan="10">
                                            @include('admin.partials.not_found', [
                                                'type' => 'product',
                                                'message' =>
                                                    'No linked products found. Please add some linked products.',
                                                'btnText' => 'Add Link Product',
                                                'btnUrl' => route('product.addlinkproduct'),
                                            ])
                                        </td>
                                    </tr>
                                @endforelse
                                @foreach ($deletedData as $list)
                                    <tr class="trash-row d-none" style="background: #fff3f3">
                                        {{-- <td>2025-01-15 14:32</td> --}}
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>{{ $list->sku }}</td>
                                        <td>{{ $list->product_id }}</td>
                                        <td><img src="{{ asset('storage/media/' . $list->file_name) }}" alt="">
                                        </td>
                                        <td>
                                            <a href="{{ route('linkproduct.restore', $list->id) }}"
                                                onclick="return confirm('Restore {{ $list->sku }}?')">
                                                <button type="button" class="btn btn-success btn-sm">
                                                    <i class="fa fa-undo me-1"></i> Restore
                                                </button>
                                            </a>
                                            <a href="{{ route('linkproduct.permanent_delete', $list->id) }}"
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
