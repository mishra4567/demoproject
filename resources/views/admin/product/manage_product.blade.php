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
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label for="name" class="control-label mb-1">Product
                                            Name
                                            @include('admin.partials.field_info', [
                                                'info' => $info['name'] ?? '',
                                            ])
                                        </label>
                                        <input id="name" name="name" value="{{ $name }}" type="text"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="slug" class="control-label mb-1">Product Slug
                                            @include('admin.partials.field_info', [
                                                'info' => $info['slug'] ?? '',
                                            ])
                                        </label>
                                        <input id="slug" name="slug" value="{{ $slug }}" type="text"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="category_id" class="control-label mb-1">Select Category
                                                    @include('admin.partials.field_info', [
                                                        'info' => $info['category_id'] ?? '',
                                                    ])
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
                                                    <label for="brand_id" class="control-label mb-1">Product brand
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['brand'] ?? '',
                                                        ])
                                                    </label>
                                                    <select name="brand_id" id="brand_id" class="form-control">
                                                        <option value="">select Brand</option>
                                                        @foreach ($brands as $band)
                                                            @if ($brand == $band->id)
                                                                <option selected value="{{ $band->id }}">
                                                                    {{ $band->name }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $band->id }}"> {{ $band->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    {{-- <input id="brand" name="brand" value="{{ $brand }}"
                                                        type="text" class="form-control" required> --}}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="model" class="control-label mb-1">Product model
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['model'] ?? '',
                                                        ])
                                                    </label>
                                                    <input id="model" name="model" value="{{ $model }}"
                                                        type="text" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="col-md-6">
                                            <label for="price" class="control-label mb-1">Product Price
                                            @include('admin.partials.field_info', [
                                                'info' => $info['price'] ?? '',
                                            ])
                                        </label>
                                        <input id="price" name="price" value="{{ $price }}" type="text"
                                            class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mrp" class="control-label mb-1">Product MRP
                                            @include('admin.partials.field_info', [
                                                'info' => $info['mrp'] ?? '',
                                            ])
                                        </label>
                                        <input id="mrp" name="mrp" value="{{ $mrp }}" type="text"
                                            class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <label for="media" class="control-label mb-1">Product Image
                                                @include('admin.partials.field_info', [
                                                    'info' => $info['media_id'] ?? '',
                                                ])
                                            </label>
                                            <div class="col-md-4 d-flex align-items-center">
                                                {{-- Media Modal Start --}}
                                                <button type="button" class="btn btn-outline-primary mb-3"
                                                    onclick="addMedia('product')">
                                                    Select Image
                                                </button>
                                                <input type="hidden" name="media_id" id="media_id_product"
                                                    value="{{ $media_id ?? '' }}">
                                                <div id="preview_product" class="d-flex flex-wrap gap-2">
                                                    <div class="position-relative m-2">
                                                        @if (!empty($image))
                                                            <img src="{{ asset('storage/media/' . $image) }}"
                                                                width="120">
                                                        @endif
                                                    </div>
                                                </div>
                                                {{-- Media Modal End --}}
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="coupon_id" class="control-label mb-1">Coupon Add ?
                                                        @include('admin.partials.field_info', [
                                                            'info' => $info['coupon_id'] ?? '',
                                                        ])
                                                    </label>
                                                    <select name="coupon_id" id="coupon_id" class="form-control">
                                                        <option value="">select Coupon</option>
                                                        @foreach ($coupon_select as $coupon)
                                                            @if ($brand == $coupon->id)
                                                                <option selected value="{{ $coupon->id }}">
                                                                    {{ $coupon->title }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $coupon->id }}"> {{ $coupon->title }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="short_desc" class="control-label mb-1">Product short desc
                                            @include('admin.partials.field_info', [
                                                'info' => $info['short_desc'] ?? '',
                                            ])
                                        </label>
                                        <textarea name="short_desc" id="short_desc" cols="3" rows="3" class="form-control" {{ $required }}>{{ $short_desc }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="desc" class="control-label mb-1">Product desc
                                            @include('admin.partials.field_info', [
                                                'info' => $info['desc'] ?? '',
                                            ])
                                        </label>
                                        <textarea name="desc" id="desc" cols="3" rows="3" class="form-control" {{ $required }}>{{ $desc }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="keywords" class="control-label mb-1">Product keywords
                                            @include('admin.partials.field_info', [
                                                'info' => $info['keywords'] ?? '',
                                            ])
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
                                            @include('admin.partials.field_info', [
                                                'info' => $info['uses'] ?? '',
                                            ])
                                        </label>
                                        <textarea name="uses" id="uses" cols="3" rows="3" class="form-control" {{ $required }}>{{ $uses }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="warranty" class="control-label mb-1">Product warranty
                                            @include('admin.partials.field_info', [
                                                'info' => $info['warranty'] ?? '',
                                            ])
                                        </label>
                                        <textarea name="warranty" id="warranty" cols="3" rows="3" class="form-control" {{ $required }}>{{ $warranty }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Product Gallery Images
                                        @include('admin.partials.field_info', [
                                            'info' => $info['gallery'] ?? '',
                                        ])
                                    </h5>

                                    <!-- Add Button -->
                                    <button type="button" class="btn btn-outline-primary mb-3"
                                        onclick="addMedia('gallery')">
                                        + Add Gallery Image
                                    </button>

                                    <!-- Gallery Preview Container -->
                                    <div id="gallery_preview" class="d-flex flex-wrap gap-2">
                                        @if (!empty($gallery_images))
                                            @foreach ($gallery_images as $gImg)
                                                <div class="gallery-item position-relative m-2">
                                                    <input type="hidden" name="gallery_media_id[]"
                                                        value="{{ $gImg->id }}">
                                                    <img src="{{ asset('storage/media/' . $gImg->file_name) }}"
                                                        width="120" class="rounded">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                        onclick="removeGalleryImage(this)">✕</button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <input type="hidden" name="id" value="{{ $id ?? '' }}">
                            <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
