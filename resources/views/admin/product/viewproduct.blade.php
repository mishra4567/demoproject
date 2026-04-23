@extends('admin.layout.layout')
@section('page_title', 'Full Product View')
@section('product_select', 'active')
@section('container')

    <div class="section__content section__content--p30">
        <div class="container-fluid">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="title-5 m-b-0">Product View</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('product') }}">
                        <button type="button" class="btn btn-secondary">← Back</button>
                    </a>
                    <a href="{{ route('product.publish', $product->id) }}"
                        class="btn btn-{{ $product->is_publish == 1 ? 'info' : 'warning' }} btn-sm ">
                        {{ $product->is_publish == 1 ? 'Publish' : 'Draft' }}
                    </a>
                    <a href="{{ route('product.manage', $product->id) }}">
                        <button type="button" class="btn btn-warning">Edit Product</button>
                    </a>
                </div>
            </div>
            <div class="row">
                {{-- LEFT — Main Image + Gallery --}}
                <div class="col-md-4">
                    {{-- Main Image --}}
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <img src="{{ $media_url }}" alt="{{ $product->name }}" class="img-fluid rounded"
                                style="max-height: 300px; object-fit: cover; width:100%;">
                        </div>
                    </div>
                    {{-- Gallery --}}
                    <div class="card mb-3">
                        <div class="card-header fw-semibold">Gallery Images</div>
                        <div class="card-body">
                            @if ($gallery->isEmpty())
                                <div class="d-flex flex-wrap gap-2">
                                    @include('admin.partials.not_found', [
                                        'type' => 'media',
                                        'btnText' => 'Add Gallery Images',
                                        'btnUrl' => route('product.manage', $product->id),
                                    ])
                                </div>
                            @else
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($gallery as $img)
                                        <img src="{{ asset('storage/media/' . $img->media_url) }}" width="80"
                                            height="80" class="rounded border" style="object-fit:cover;">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    {{-- Product Details --}}
                    <div class="card mb-3">
                        <div class="card-header fw-semibold">Product Details</div>
                        <div class="card-body">
                            {{-- No variants — show product info instead --}}
                            <table class="table table-bordered table-sm mb-0">
                                <tr>
                                    <th width="35%">Product Name</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ $categoryName }}</td>
                                </tr>
                                <tr>
                                    <th>Brand</th>
                                    <td>{{ $brand_name }}</td>
                                </tr>
                                <tr>
                                    <th>Model</th>
                                    <td>{{ $product->model ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Short Description</th>
                                    <td>{{ $product->short_desc ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Uses</th>
                                    <td>{{ $product->uses ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Warranty</th>
                                    <td>{{ $product->warranty ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($product->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    {{-- Variants / Attributes --}}
                    @if ($attributes->isEmpty())
                        {{-- No variants — show add variant prompt --}}
                        <div class="card mb-3">
                            <div class="card-header fw-semibold">Product Variants</div>
                            <div class="card-body">
                                @include('admin.partials.not_found', [
                                    'type' => 'linkproduct',
                                    'btnText' => 'Add Variant for this Product',
                                    'btnUrl' => route('product.addlinkproduct'),
                                ])
                            </div>
                        </div>
                    @else
                        {{-- @if ($attributes->isEmpty()) --}}
                        <div class="card mb-3">
                            <div class="card-header fw-semibold">Product Variants</div>
                            <div class="card-body">
                                {{-- Has variants — show variants table --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>SKU</th>
                                                <th>Size</th>
                                                <th>Color</th>
                                                <th>MRP</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($attributes as $index => $attr)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if ($attr->media_url)
                                                            <img src="{{ asset('storage/media/' . $attr->media_url) }}"
                                                                width="60" height="60" class="rounded"
                                                                style="object-fit:cover;">
                                                        @else
                                                            <img src="{{ asset('storage/default/no-product-image.PNG') }}"
                                                                width="60" height="60" class="rounded">
                                                        @endif
                                                    </td>
                                                    <td>{{ $attr->sku }}</td>
                                                    <td>{{ $attr->size_name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($attr->color_name)
                                                            <span class="badge"
                                                                style="background-color: {{ $attr->color_name }};">
                                                                {{ $attr->color_name }}
                                                            </span>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>₹{{ number_format($attr->mrp) }}</td>
                                                    <td>₹{{ number_format($attr->price) }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $attr->qty > 0 ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $attr->qty > 0 ? $attr->qty . ' in stock' : 'Out of Stock' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('product.addlinkproduct') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-plus me-1"></i> Add Variant for this Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    {{-- Descriptions --}}
                    <div class="card mb-3">
                        <div class="card-header fw-semibold">Descriptions</div>
                        <div class="card-body">
                            <p><strong>Short Description:</strong><br>{{ $product->short_desc ?? 'N/A' }}</p>
                            <hr>
                            <p><strong>Full Description:</strong><br>{!! $product->desc ?? 'N/A' !!}</p>
                            <hr>
                            <p><strong>Keywords:</strong><br>{{ $product->keywords ?? 'N/A' }}</p>
                            <hr>
                            <p><strong>Uses:</strong><br>{{ $product->uses ?? 'N/A' }}</p>
                            <hr>
                            <p><strong>Warranty:</strong><br>{{ $product->warranty ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @if ($technical_specification)
                <div class="card mb-3">
                    <div class="card-header fw-semibold">Technical Specification</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm mb-0">
                            <tr>
                                <th width="35%">Title</th>
                                <td>{{ $technical_specification->title ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Lead Time From</th>
                                <td>{{ $technical_specification->lead_time_from ? \Carbon\Carbon::parse($technical_specification->lead_time_from)->format('d M Y, h:i A') : 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Lead Time To</th>
                                <td>{{ $technical_specification->lead_time_to ? \Carbon\Carbon::parse($technical_specification->lead_time_to)->format('d M Y, h:i A') : 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Tax</th>
                                <td>{{ $technical_specification->tax ?? '0' }}%</td>
                            </tr>
                            <tr>
                                <th>Tax Type</th>
                                <td>{{ ucfirst($technical_specification->tax_type ?? 'None') }}</td>
                            </tr>
                            <tr>
                                <th>Promotional</th>
                                <td>
                                    @if ($technical_specification->is_promo == 1)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Featured</th>
                                <td>
                                    @if ($technical_specification->is_featured == 1)
                                        <span class="badge bg-primary">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Discounted</th>
                                <td>
                                    @if ($technical_specification->is_discounted == 1)
                                        <span class="badge bg-warning text-dark">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Trending</th>
                                <td>
                                    @if ($technical_specification->is_trending == 1)
                                        <span class="badge bg-danger">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($technical_specification->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ \Carbon\Carbon::parse($technical_specification->created_at)->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @else
                {{-- No technical specification --}}
                <div class="card mb-3">
                    <div class="card-header fw-semibold">Technical Specification</div>
                    <div class="card-body">
                        @include('admin.partials.not_found', [
                            'type' => 'empty',
                            'icon' => 'fa-file-alt',
                            'title' => 'No Specification Found',
                            'message' => 'No technical specification has been added for this product.',
                            'btnText' => 'Add Tecnical Specification',
                            'btnUrl' => route('product.addtecnicalspecs'),
                        ])
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
