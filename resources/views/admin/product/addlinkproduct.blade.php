@extends('admin.layout.layout')
@section('page_title', 'Link Product')
@section('addlinkproduct_select', 'active')
@section('container')
    @php
        $id = 1;
        $required = $id > 0 ? '' : 'required';
        $AllRequired = 'required';
    @endphp
    <style>
        .color-preview-box {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #ccc;
            background: #f1f1f1;
        }
    </style>
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Add Link Product</h3>
            <a href="{{ route('product.linkproduct') }}">
                <button type="button" class="btn btn-success " disabled="">Back to Product</button>
            </a>
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('product.processlinkproduct') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{-- hgvh --}}
                        <div class="card">
                            <div class="card-body">
                                <h3 class="title-5 m-b-5">Product Attribute</h3>
                                <div id="product_attr_container">
                                    @php
                                        $loop_count_num = 1;
                                    @endphp
                                    @foreach ($productAttrArr as $val)
                                        <?php
                                        $loop_count_prev = $loop_count_num;
                                        ?>
                                        <div class="mb-3" id="product_attr_{{ $loop_count_num++ }}">
                                            <div class="row">
                                                <input type="hidden" name="paid[]" id="paid"
                                                    value="{{ $val->id ?? 0 }}">
                                                {{-- product id --}}
                                                <div class="col-md-2">
                                                    <label>Product ID
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['product_id'] ?? '',
                                                        ])
                                                    </label>
                                                    <select name="product_id[]" class="form-control">
                                                        <option value="">Select Product</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                {{ isset($val->product_id) && $val->product_id == $product->id ? 'selected' : '' }}>
                                                                {{ $product->id }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- sku --}}
                                                <div class="col-md-2">
                                                    <label for="sku" class="control-label mb-1">Sku
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['sku'] ?? '',
                                                        ])
                                                    </label>
                                                    <input type="text" class="form-control" name="sku[]"
                                                        value="{{ $val->sku ?? '' }}" {{ $AllRequired }}>
                                                </div>
                                                {{-- mrp --}}
                                                <div class="col-md-2">
                                                    <label for="mrp" class="control-label mb-1">mrp
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['mrp'] ?? '',
                                                        ])
                                                    </label>
                                                    <input type="text" class="form-control" name="mrp[]" id="mrp"
                                                        value="{{ $val->mrp ?? '' }}" {{ $required }}>
                                                </div>
                                                {{-- price --}}
                                                <div class="col-md-2">
                                                    <label for="price" class="control-label mb-1">price
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['price'] ?? '',
                                                        ])
                                                    </label>
                                                    <input type="text" class="form-control" name="price[]" id="price"
                                                        value="{{ $val->price ?? '' }}" {{ $required }}>
                                                </div>
                                                {{-- size --}}
                                                <div class="col-md-2">
                                                    <label for="size_id" class="control-label mb-1">Select size
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['size_id'] ?? '',
                                                        ])
                                                    </label>
                                                    <select name="size_id[]" id="size_id" class="form-control">
                                                        <option value="">select size</option>
                                                        @foreach ($sizes as $siz)
                                                            <option value="{{ $siz->id }}"
                                                                {{ isset($val->size_id) && $val->size_id == $siz->id ? 'selected' : '' }}>
                                                                {{ $siz->size }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- color --}}
                                                <div class="col-md-2">
                                                    <label for="color_id" class="control-label mb-1">Select Color
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['color_id'] ?? '',
                                                        ])
                                                    </label>
                                                    <select name="color_id[]" class="form-control color-select">
                                                        <option value="">select color</option>
                                                        @foreach ($colors as $colo)
                                                            <option value="{{ $colo->id }}"
                                                                data-color="{{ $colo->hex_id }}"
                                                                {{ isset($val->color_id) && $val->color_id == $colo->id ? 'selected' : '' }}>
                                                                {{ $colo->color_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="color-preview-box mt-2"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="qty" class="control-label mb-1">qty
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['qty'] ?? '',
                                                        ])
                                                    </label>
                                                    <input type="text" class="form-control" name="qty[]" id="qty"
                                                        value="{{ $val->qty ?? '' }}" {{ $required }}>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <button type="button"
                                                            class="btn btn-outline-primary open-media-modal"
                                                            onclick=" addMedia('attr_{{ $loop_count_prev }}' )">
                                                            Select Image
                                                        </button>
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['media_id'] ?? '',
                                                        ])
                                                        <!-- Hidden input to save media ID -->
                                                        <input type="hidden" name="media_id[]"
                                                            value="{{ $val->id ?? '' }} "
                                                            id="media_id_attr_{{ $loop_count_prev }}">
                                                        <!-- Selected Image Preview -->
                                                        <div id="preview_attr_{{ $loop_count_prev }}" class="mt-3">
                                                            @if (!empty($val->image))
                                                                <img src="{{ asset('storage/media/' . $val->image) }}"
                                                                    width="120">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="mb-3 mt-4">
                                                        @if ($loop_count_num == 2)
                                                            <button id="" type="button"
                                                                class="btn btn-sm btn-success"
                                                                onclick="add_more('attribute')">
                                                                <i class="fa fa-magic"></i>
                                                                Add Attribute
                                                            </button>
                                                        @else
                                                            <a href="{{ route('product.attr_delete', $val->id) }}">
                                                                <button id="" type="button"
                                                                    class="btn btn-sm btn-danger"
                                                                    onclick="remove_more('{{ $loop_count_prev }}')">
                                                                    <i class="fa fa-magic"></i>
                                                                    Remove Atribute
                                                                </button>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    {{-- hgfhg --}}
                                </div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <input type="hidden" name="id" value="{{ $id ?? '' }}">
                            <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                Submit
                            </button>
                        </div>
                        {{-- b --}}
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 🔹 ON CHANGE
            document.addEventListener('change', function(e) {

                if (e.target.classList.contains('color-select')) {

                    const select = e.target;
                    const selectedOption = select.options[select.selectedIndex];
                    const color = selectedOption.getAttribute('data-color');

                    const box = select.closest('.col-md-2').querySelector('.color-preview-box');

                    if (box) {
                        box.style.background = color ? color : '#f1f1f1';
                    }
                }

            });

            // 🔹 AUTO LOAD (EDIT MODE)
            document.querySelectorAll('.color-select').forEach(function(select) {

                const selectedOption = select.options[select.selectedIndex];
                const color = selectedOption.getAttribute('data-color');

                const box = select.closest('.col-md-2').querySelector('.color-preview-box');

                if (box) {
                    box.style.background = color ? color : '#f1f1f1';
                }

            });

        });
    </script>
@endsection
