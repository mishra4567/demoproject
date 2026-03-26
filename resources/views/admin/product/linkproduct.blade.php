@extends('admin.layout.layout')
@section('page_title', 'Link Product')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Product</h3>
            <a href="{{ route('product.addlinkproduct') }}">
                <button type="button" class="btn btn-success ">Add Link Product</button>
            </a>
            <div class="row">
                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <tr>
                                <th>date</th>
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
                            @foreach ($linkProduct as $list)
                                <tr>
                                    <td>2025-01-15 14:32</td>
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
                                            <button type="button" class="btn btn-outline-danger btn-sm">Delete </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
