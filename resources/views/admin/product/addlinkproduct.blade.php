@extends('admin.layout.layout')
@section('page_title', 'Link Product')
@section('addlinkproduct_select', 'active')
@section('container')
    @php
        $id = 1;
        $required = $id > 0 ? '' : 'required';
        $AllRequired = 'required';
    @endphp

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
                                                    <label>Product ID</label>
                                                    <select name="product_id[]" class="form-control">
                                                        <option value="">Select Product</option>
                                                        {{-- @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            {{ $product->id == $val->product_id ? 'selected' : '' }}>
                                                            {{ $product->id }}
                                                        </option>
                                                    @endforeach --}}
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
                                                    </label>
                                                    <input type="text" class="form-control" name="sku[]"
                                                        value="{{ $val->sku ?? '' }}" {{ $AllRequired }}>
                                                </div>
                                                {{-- mrp --}}
                                                <div class="col-md-2">
                                                    <label for="mrp" class="control-label mb-1">mrp
                                                    </label>
                                                    <input type="text" class="form-control" name="mrp[]" id="mrp"
                                                        value="{{ $val->mrp ?? '' }}" {{ $required }}>
                                                </div>
                                                {{-- price --}}
                                                <div class="col-md-2">
                                                    <label for="price" class="control-label mb-1">price
                                                    </label>
                                                    <input type="text" class="form-control" name="price[]" id="price"
                                                        value="{{ $val->price ?? '' }}" {{ $required }}>
                                                </div>
                                                {{-- size --}}
                                                <div class="col-md-2">
                                                    <label for="size_id" class="control-label mb-1">Select size
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
                                                    </label>
                                                    <select name="color_id[]" id="color_id" class="form-control">
                                                        <option value="">select color</option>
                                                        @foreach ($colors as $colo)
                                                            <option value="{{ $colo->id }}"
                                                                {{ isset($val->color_id) && $val->color_id == $colo->id ? 'selected' : '' }}>
                                                                {{ $colo->color }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="qty" class="control-label mb-1">qty
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
                                                                class="btn btn-sm btn-success" onclick="add_more('attribute')">
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
@endsection
