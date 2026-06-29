@extends('admin.layout.layout')
@section('page_title', 'Size Manager')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Size</h3>
            <a href="{{ route('size') }}">
                <button type="button" class="btn btn-success " disabled="">Back to Size</button>
            </a>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('size.manage_size_process') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label>Type</label>
                                    <select name="type"id="size_type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="Clothing"
                                            {{ old('type', $type ?? '') == 'Clothing' ? 'selected' : '' }}>
                                            Clothing
                                        </option>
                                        <option value="Shoes" {{ old('type', $type ?? '') == 'Shoes' ? 'selected' : '' }}>
                                            Shoes
                                        </option>
                                        <option value="Other" {{ old('type', $type ?? '') == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-3" id="other_type_wrapper" style="display:none;">
                                    <label>Add Type</label>
                                    <input type="text" name="custom_type" id="custom_type" class="form-control"
                                        value="{{ old('custom_type', $custom_type ?? '') }}"
                                        placeholder="Example: Watch, Helmet, Bag">
                                </div>
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label for="size" class="control-label mb-1">Size
                                            @include('admin.partials.field_info', [
                                                'info' => $info['size'] ?? '',
                                            ])
                                        </label>
                                        <input class="form-control" id="size" name="size"
                                            value="{{ old('size', $size) }}" type="text" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Details</label>
                                    <input type="text" name="details" class="form-control"
                                        value="{{ old('details', $details ?? '') }}"
                                        placeholder="Example: Chest 46-48 inch">
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
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const typeSelect = document.getElementById('size_type');
            const otherWrapper = document.getElementById('other_type_wrapper');

            function toggleOtherType() {
                if (typeSelect.value === 'Other') {
                    otherWrapper.style.display = 'block';
                } else {
                    otherWrapper.style.display = 'none';
                }
            }

            toggleOtherType(); // edit mode support

            typeSelect.addEventListener('change', toggleOtherType);
        });
    </script>
@endsection
