@extends('admin.layout.layout')
@section('page_title', 'Cupon Manager')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Coupon</h3>
            <a href="{{ route('coupons') }}">
                <button type="button" class="btn btn-success " disabled="">Back to Coupon</button>
            </a>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('coupons.manage_coupons_process') }}" method="post">
                        @csrf
                        <div class="row g-3">
                            <!-- Title -->
                            <div class="col-md-6">
                                <label class="form-label">Coupon Title
                                    @include('admin.partials.field_info', [
                                        'info' => $info['title'] ?? '',
                                    ])
                                </label>
                                <input type="text" name="title" value="{{ old('title', $title ?? '') }}"
                                    class="form-control" placeholder="Enter coupon title" required>
                            </div>
                            <!-- Code -->
                            <div class="col-md-6">
                                <label class="form-label">Coupon Code
                                    @include('admin.partials.field_info', [
                                        'info' => $info['code'] ?? '',
                                    ])
                                </label>
                                <input type="text" name="code" value="{{ old('code', $code ?? '') }}"
                                    class="form-control" placeholder="Enter coupon code" required>
                            </div>
                            <!-- Value -->
                            <div class="col-md-6">
                                <label class="form-label">Coupon Value
                                    @include('admin.partials.field_info', [
                                        'info' => $info['discount'] ?? '',
                                    ])
                                </label>
                                <input type="number" name="value" value="{{ old('value', $value ?? '') }}"
                                    class="form-control" placeholder="Enter value" required>
                            </div>
                            <!-- Value -->
                            <div class="col-md-6">
                                <label class="form-label">Min order Amt
                                    @include('admin.partials.field_info', [
                                        'info' => $info['min_order'] ?? '',
                                    ])
                                </label>
                                <input type="number" name="min_order_amt"
                                    value="{{ old('min_order_amt', $min_order_amt ?? '') }}" class="form-control"
                                    placeholder="Enter value">
                            </div>
                            <!-- Type -->
                            <div class="col-md-6">
                                <label class="form-label">Coupon Type
                                    @include('admin.partials.field_info', [
                                        'info' => $info['type'] ?? '',
                                    ])
                                </label>
                                <select name="type" class="form-control">
                                    <option value="value" {{ old('type', $type ?? '') == 'value' ? 'selected' : '' }}>
                                        Fixed Amount
                                    </option>
                                    <option value="percent" {{ old('type', $type ?? '') == 'percent' ? 'selected' : '' }}>
                                        Percentage
                                    </option>
                                </select>
                            </div>
                            {{-- Expiry --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Expiry Date & Time
                                </label>

                                <input type="datetime-local" name="expiry"
                                    value="{{ !empty($expiry) ? \Carbon\Carbon::parse($expiry)->format('Y-m-d\TH:i') : '' }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-3">
                                    <input type="checkbox" name="is_one_time" class="form-check-input" id="is_one_time"
                                        value="1" {{ ($is_one_time ?? 0) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="is_one_time">One Time Usable
                                        @include('admin.partials.field_info', [
                                            'info' => $info['one_time'] ?? '',
                                        ])
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="mt-4">
                            <input type="hidden" name="id" value="{{ $id ?? '' }}">
                            <button type="submit" class="btn btn-info w-100">
                                Submit
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
