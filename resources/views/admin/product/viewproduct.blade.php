@extends('admin.layout.layout')
@section('page_title', 'Full Product View')
@section('product_select', 'active')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Product view</h3>
            <a href="{{ route('product.add_product') }}" target="_blank">
                <button type="button" class="btn btn-success ">Add Product</button>
            </a>
            <h2>Full Product View</h2>
        </div>
    </div>
@endsection
