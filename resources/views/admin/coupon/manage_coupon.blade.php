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
                                <label class="form-label">Coupon Title</label>
                                <input type="text" name="title" value="{{ $title }}" class="form-control"
                                    placeholder="Enter coupon title" required>
                            </div>
                            <!-- Code -->
                            <div class="col-md-6">
                                <label class="form-label">Coupon Code</label>
                                <input type="text" name="code" value="{{ $code }}" class="form-control"
                                    placeholder="Enter coupon code" required>
                            </div>
                            <!-- Value -->
                            <div class="col-md-6">
                                <label class="form-label">Coupon Value</label>
                                <input type="number" name="value" value="{{ $value }}" class="form-control"
                                    placeholder="Enter value" required>
                            </div>
                            <!-- Value -->
                            <div class="col-md-6">
                                <label class="form-label">Min order Amt</label>
                                <input type="number" name="min_order_amt" value="{{ $min_order_amt }}" class="form-control"
                                    placeholder="Enter value" required>
                            </div>
                            <!-- Type -->
                            <div class="col-md-6">
                                <label class="form-label">Coupon Type</label>
                                <select name="type" class="form-control">
                                    <option value="value" {{ ($type ?? '') == 'value' ? 'selected' : '' }}>Value</option>
                                    <option value="per" {{ ($type ?? '') == 'per' ? 'selected' : '' }}>Percent
                                    </option>
                                </select>
                            </div>
                            <!-- Promo Switch -->
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-3">
                                    <input type="checkbox" name="is_promo" class="form-check-input" id="is_promo"
                                        value="1" {{ ($is_one_time ?? 0)== 1 ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="is_promo">One Time Usable</label>
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
