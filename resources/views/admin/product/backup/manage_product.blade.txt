@extends('admin.layout.layout')
@section('page_title', 'Manage Product')
@section('manage_product_select', 'active')
@section('container')
    @php
        $required = $id > 0 ? '' : 'required';
        $AllRequired = 'required';
    @endphp
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Product</h3>
            <a href="{{ route('product') }}">
                <button type="button" class="btn btn-success " disabled="">Back to Product</button>
            </a>
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('product.manage_product_process') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            {{-- <div class="card-header">Manage Product</div> --}}
                            <div class="card-body">
                                {{-- <div class="card-title">
                                <h3 class="text-center title-2">Pay Invoice</h3>
                            </div>
                            <hr> --}}

                                {{-- {{session('message')}} --}}
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label for="name" class="control-label mb-1">Product
                                            Name</label>
                                        <input id="name" name="name" value="{{ $name }}" type="text"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="slug" class="control-label mb-1">Product Slug
                                        </label>
                                        <input id="slug" name="slug" value="{{ $slug }}" type="text"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="category_id" class="control-label mb-1">Select Category
                                                </label>
                                                <select name="category_id" id="category_id" class="form-control">
                                                    <option value="">select categories</option>
                                                    @foreach ($category as $cat)
                                                        @if ($category_id == $cat->id)
                                                            <option selected value="{{ $cat->id }}">
                                                                {{ $cat->category_name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $cat->id }}"> {{ $cat->category_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="brand" class="control-label mb-1">Product brand
                                                    </label>
                                                    <input id="brand" name="brand" value="{{ $brand }}"
                                                        type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="model" class="control-label mb-1">Product model
                                                    </label>
                                                    <input id="model" name="model" value="{{ $model }}"
                                                        type="text" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="mb-3">
                                        <label for="image" class="control-label mb-1">Product image
                                        </label>
                                        <input id="image" name="image" type="file" class="form-control"
                                            {{ $required }}>
                                    </div> --}}
                                    <div class="mb-3">
                                        {{-- Media Modal Start --}}
                                        {{-- <button type="button" class="btn btn-outline-primary open-media-modal"
                                            onclick=" addMedia() ">
                                            Select Image
                                        </button>
                                        <!-- Hidden input to save media ID -->
                                        <input type="hidden" name="media_id" id="product_media_id">
                                        <!-- Selected Image Preview -->
                                        <div id="product_media_preview" class="mt-3">
                                            @if (!empty($image))
                                                <img src="{{ asset('storage/media/' . $image) }}" width="120">
                                            @endif
                                        </div> --}}
                                        <button type="button" class="btn btn-outline-primary"
                                            onclick="addMedia('product')">
                                            Select Image
                                        </button>
                                        <input type="hidden" name="media_id" id="media_id_product"
                                            value="{{ $media_id ?? '' }}">
                                        <div id="preview_product">
                                            @if (!empty($image))
                                                <img src="{{ asset('storage/media/' . $image) }}"
                                                    width="120">
                                            @endif
                                        </div>
                                        {{-- Media Modal End --}}
                                    </div>

                                    <div class="mb-3">
                                        <label for="short_desc" class="control-label mb-1">Product short desc
                                        </label>
                                        <textarea name="short_desc" id="short_desc" cols="3" rows="3" class="form-control" {{ $required }}>{{ $short_desc }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="desc" class="control-label mb-1">Product desc
                                        </label>
                                        <textarea name="desc" id="desc" cols="3" rows="3" class="form-control" {{ $required }}>{{ $desc }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="keywords" class="control-label mb-1">Product keywords
                                        </label>
                                        <textarea name="keywords" id="keywords" cols="3" rows="3" class="form-control" {{ $required }}>{{ $keywords }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="technical_specification" class="control-label mb-1">Product technical
                                            specification
                                        </label>
                                        <textarea name="technical_specification" id="technical_specification" cols="3" rows="3"
                                            class="form-control" {{ $required }}>{{ $technical_specification }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="uses" class="control-label mb-1">Product uses
                                        </label>
                                        <textarea name="uses" id="uses" cols="3" rows="3" class="form-control" {{ $required }}>{{ $uses }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="warranty" class="control-label mb-1">Product warranty
                                        </label>
                                        <textarea name="warranty" id="warranty" cols="3" rows="3" class="form-control" {{ $required }}>{{ $warranty }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="card">
                            <div class="card-body">
                                <h3 class="title-5 m-b-5">Product Images</h3>
                                <input type="hidden" name="paid[]" id="paid">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <label class="control-label mb-2">Product Attributes</label>

                                            @php
                                                $loop_count_num = 1;
                                            @endphp

                                            @foreach ($productAttrArr as $key => $val)
                                                <div class="row mb-3" id="product_attr_{{ $loop_count_num }}">

                                                    <input type="hidden" name="paid[]" value="{{ $val->id ?? 0 }}">

                                                    <div class="col-md-2">
                                                        <label>SKU</label>
                                                        <input type="text" name="sku[]" class="form-control"
                                                            value="{{ $val->sku ?? '' }}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>MRP</label>
                                                        <input type="text" name="mrp[]" class="form-control"
                                                            value="{{ $val->mrp ?? '' }}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Price</label>
                                                        <input type="text" name="price[]" class="form-control"
                                                            value="{{ $val->price ?? '' }}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Size</label>
                                                        <select name="size_id[]" id="size_id" class="form-control">
                                                            @foreach ($sizes as $siz)
                                                                <option value="{{ $siz->id }}"
                                                                    {{ ($val->size_id ?? '') == $siz->id ? 'selected' : '' }}>
                                                                    {{ $siz->size }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Color</label>
                                                        <select name="color_id[]" id="color_id" class="form-control">
                                                            @foreach ($colors as $colo)
                                                                <option value="{{ $colo->id }}"
                                                                    {{ ($val->color_id ?? '') == $colo->id ? 'selected' : '' }}>
                                                                    {{ $colo->color }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Qty</label>
                                                        <input type="text" name="qty[]" class="form-control"
                                                            value="{{ $val->qty ?? '' }}">
                                                    </div>

                                                    <div class="col-md-3 mt-2">
                                                        <label>Image</label>
                                                        <input type="file" name="attr_image[]" class="form-control">
                                                    </div>

                                                    <div class="col-md-2 mt-4">

                                                        @if ($loop_count_num == 1)
                                                            <button type="button" class="btn btn-sm btn-success"
                                                                onclick="add_more()">
                                                                Add Attribute
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="remove_more({{ $loop_count_num }})">
                                                                Remove
                                                            </button>
                                                        @endif

                                                    </div>

                                                </div>

                                                @php
                                                    $loop_count_num++;
                                                @endphp
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="card">
                            <div class="card-body">
                                {{-- <h3 class="title-5 m-b-5">Product Attribute</h3>
                                @php
                                    $loop_count_num = 1;
                                @endphp
                                @foreach ($productAttrArr as $key => $val)
                                    <?php
                                    $loop_count_prev = $loop_count_num;
                                    ?>
                                    <input type="text" name="paid[]" id="paid" value="{{ $val->id ?? 0 }}">
                                    <div class="mb-3" id="product_attr_{{ $loop_count_num++ }}">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="sku" class="control-label mb-1">Sku
                                                </label>
                                                <input type="text" class="form-control" name="sku[]"
                                                    value="{{ $val->sku ?? '' }}" {{ $AllRequired }}>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="mrp" class="control-label mb-1">mrp
                                                </label>
                                                <input type="text" class="form-control" name="mrp[]" id="mrp"
                                                    value="{{ $val->mrp ?? '' }}" {{ $required }}>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="price" class="control-label mb-1">price
                                                </label>
                                                <input type="text" class="form-control" name="price[]" id="price"
                                                    value="{{ $val->price ?? '' }}" {{ $required }}>
                                            </div>
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
                                                    <label for="attr_image" class="control-label mb-1">Product images
                                                    </label>
                                                    <input id="attr_image" name="attr_image[]" type="file"
                                                        class="form-control" {{ $required }}>
                                                    @if (!empty($val->attr_image))
                                                        <img src="{{ asset('storage/media/' . $val->attr_image) }}"
                                                            width="100" height="100"
                                                            style="object-fit:cover; border:1px solid #ddd;">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-3 mt-4">
                                                    @if ($loop_count_num == 2)
                                                        <button id="" type="button"
                                                            class="btn btn-sm btn-success" onclick="add_more()">
                                                            <i class="fa fa-magic"></i>
                                                            Add Attribute
                                                        </button>
                                                    @else
                                                        <a
                                                            href="{{ route('product.attr_delete', ['paid' => $val->id, 'pid' => $id]) }}">
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
                                @endforeach --}}
                                {{-- hgfhg --}}

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
