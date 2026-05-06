@extends('admin.layout.layout')
@section('page_title', 'Techical Specs Manager')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Technical Specs</h3>
            <a href="{{ route('product.tecnicalspacs') }}">
                <button type="button" class="btn btn-success " disabled="">Back Technical Specs</button>
            </a>
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('product.processTechnicalSpecs') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="product_id" class="control-label mb-1">
                                            Product ID & name
                                            @include('admin.partials.field_info', [
                                                'info' => $info['productIdName'] ?? '',
                                            ])
                                        </label>
                                        <select name="product_id" class="form-control" required>
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $result->product_id == $product->id ? 'selected' : '' }}>
                                                    ID:{{ $product->id }} Name:{{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="title" class="control-label mb-1">Title
                                            @include('admin.partials.field_info', [
                                                'info' => $info['title'] ?? '',
                                            ])
                                        </label>
                                        <input id="title" name="title" value="{{ $result->title }}" type="text"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="fw-bold control-label mb-1">Lead Time From
                                        @include('admin.partials.field_info', [
                                            'info' => $info['lead_time_from'] ?? '',
                                        ])
                                    </label>
                                    <input type="datetime-local" name="lead_time_from" class="form-control"
                                        value="{{ isset($result->lead_time_from) ? \Carbon\Carbon::parse($result->lead_time_from)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="fw-bold control-label mb-1">Lead Time To
                                        @include('admin.partials.field_info', [
                                            'info' => $info['lead_time_to'] ?? '',
                                        ])
                                    </label>
                                    <input type="datetime-local" name="lead_time_to" class="form-control"
                                        value="{{ isset($result->lead_time_to) ? \Carbon\Carbon::parse($result->lead_time_to)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="fw-bold control-label mb-1">Tax (%)
                                        @include('admin.partials.field_info', [
                                            'info' => $info['tax'] ?? '',
                                        ])
                                    </label>
                                    <input type="number" name="tax" class="form-control" step="0.01" min="0"
                                        value="{{ $result->tax ?? 0 }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="fw-bold control-label mb-1">Select Tax Type
                                        @include('admin.partials.field_info', [
                                            'info' => $info['tax_type'] ?? '',
                                        ])
                                    </label>
                                    <select name="tax_type" class="form-control">
                                        <option value="none" {{ ($result->tax_type ?? '') == 'none' ? 'selected' : '' }}>
                                            Select Type</option>
                                        <option value="inclusive"
                                            {{ ($result->tax_type ?? '') == 'inclusive' ? 'selected' : '' }}>Inclusive
                                        </option>
                                        <option value="exclusive"
                                            {{ ($result->tax_type ?? '') == 'exclusive' ? 'selected' : '' }}>Exclusive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            {{-- Flags --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_promo" class="form-check-input" value="1"
                                            {{ $result->is_promo ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label">Promo
                                            @include('admin.partials.field_info', [
                                                'info' => $info['is_promo'] ?? '',
                                            ])
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_featured" class="form-check-input" value="1"
                                            {{ $result->is_featured ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label">Featured
                                            @include('admin.partials.field_info', [
                                                'info' => $info['is_featured'] ?? '',
                                            ])
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_discounted" class="form-check-input" value="1"
                                            {{ $result->is_discounted ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label">Discounted
                                            @include('admin.partials.field_info', [
                                                'info' => $info['is_discounted'] ?? '',
                                            ])
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_trending" class="form-check-input" value="1"
                                            {{ $result->is_trending ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label">Trending
                                            @include('admin.partials.field_info', [
                                                'info' => $info['is_trending'] ?? '',
                                            ])
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <input type="hidden" name="id" value="{{ $result->id != 0 ? $result->id : '' }}">
                                <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
