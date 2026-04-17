@extends('admin.layout.layout')
@section('page_title', 'Customer Manager')
@section('container')
    <style>
        .address-slider {
            display: flex;
            overflow-x: auto;
            gap: 15px;
            padding-bottom: 10px;
            scroll-snap-type: x mandatory;
        }

        .address-slider::-webkit-scrollbar {
            height: 6px;
        }

        .address-slider::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .address-card {
            min-width: 250px;
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            scroll-snap-align: start;
            cursor: pointer;
            transition: 0.3s;
        }

        .address-card:hover {
            transform: scale(1.03);
        }
    </style>
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Technical Specs</h3>
            <a href="{{ route('product.tecnicalspacs') }}">
                <button type="button" class="btn btn-success " disabled="">Back Technical Specs</button>
            </a>
            {{-- <div class="row">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('product.processTechnicalSpecs') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="name" class="control-label mb-1">Title
                                        </label>
                                        <input id="name" name="name" value="{{ $result->name }}" type="text"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="email" class="control-label mb-1">Email
                                        </label>
                                        <input id="email" name="email" value="{{ $result->email }}" type="text"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="password" class="control-label mb-1">Password
                                        </label>
                                        <input id="password" name="password" value="{{ $result->password }}" type="text"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="fw-bold control-label mb-1">Lead Time From</label>
                                    <input type="datetime-local" name="lead_time_from" class="form-control"
                                        value="{{ isset($result->lead_time_from) ? \Carbon\Carbon::parse($result->lead_time_from)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="fw-bold control-label mb-1">Lead Time To</label>
                                    <input type="datetime-local" name="lead_time_to" class="form-control"
                                        value="{{ isset($result->lead_time_to) ? \Carbon\Carbon::parse($result->lead_time_to)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="fw-bold control-label mb-1">Tax (%)</label>
                                    <input type="number" name="tax" class="form-control" step="0.01" min="0"
                                        value="{{ $result->tax ?? 0 }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="fw-bold control-label mb-1">Select Tax Type</label>
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
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_promo" class="form-check-input" id="is_promo"
                                            value="1" {{ $result->is_promo ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_promo">Promo</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured"
                                            value="1" {{ $result->is_featured ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Featured</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_discounted" class="form-check-input"
                                            id="is_discounted" value="1"
                                            {{ $result->is_discounted ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_discounted">Discounted</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_trending" class="form-check-input" id="is_trending"
                                            value="1" {{ $result->is_trending ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_trending">Trending</label>
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
            </div> --}}
            <form action="{{ route('customer.manage_customer_process') }}" method="post">
                @csrf
                <div class="row">
                    <div class="table-responsive  m-b-30">
                        <table class="table table-borderless table-striped table-earning" id="editTable">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td ondblclick="enableEdit(this)">
                                        <span class="text">{{ $result->name }}</span>
                                        <input type="text" name="name" value="{{ $result->name }}"
                                            class="form-control d-none">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td ondblclick="enableEdit(this)">
                                        <span class="text">{{ $result->email }}</span>
                                        <input type="text" name="email" value="{{ $result->email }}"
                                            class="form-control d-none">
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <td>Password</td>
                                    <td ondblclick="enableEdit(this)">
                                        <span class="text">{{ $result->password }}</span>
                                        <input type="text" name="password" value="{{ $result->password }}"
                                            class="form-control d-none">
                                    </td>
                                </tr> --}}
                                <tr>
                                    <td>phone</td>
                                    <td ondblclick="enableEdit(this)">
                                        <span class="text">{{ $result->phone }}</span>
                                        <input type="text" name="phone" value="{{ $result->phone }}"
                                            class="form-control d-none">
                                    </td>
                                </tr>

                                <tr>
                                    <td>Status</td>
                                    <td onclick="enableEdit(this)">
                                        <span class="text">
                                            {{ $result->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>

                                        <select name="status" class="form-control d-none">
                                            <option value="1" {{ $result->status == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ $result->status == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="mt-4">
                        <div class="d-flex justify-content-between">
                            <h5>Addresses</h5>

                            <a class="btn btn-success btn-sm" onclick="openAddModal()">
                                + Add Address
                            </a>
                        </div>
                        <div class="address-slider">
                            @if (!empty($custo_add))
                                @foreach ($custo_add as $addr)
                                    <div class="address-card border p-3 rounded "
                                        style="min-width:220px; cursor:pointer; {{ !empty($addr->is_default) ? 'background-color: #bccc56;' : '' }}"
                                        data-id="{{ $addr->id }}" data-address="{{ $addr->address }}"
                                        data-landmark="{{ $addr->landmark }}" data-city="{{ $addr->city }}"
                                        data-state="{{ $addr->state }}" data-zipcode="{{ $addr->zipcode }}"
                                        data-country="{{ $addr->country }}"
                                        data-label="{{ $addr->label ?? 'bg-primary' }}"
                                        data-is_default="{{ $addr->is_default ?? 0 }}" onclick="handleCardClick(this)">

                                        @if (!empty($addr->is_default))
                                            <span class="badge bg-warning text-dark mb-1">Default</span>
                                        @endif
                                        <span class="badge {{ $addr->label ?? 'bg-primary' }} mb-2">
                                            @switch($addr->label ?? 'bg-primary')
                                                @case('bg-primary')
                                                    Home
                                                @break

                                                @case('bg-success')
                                                    Work
                                                @break

                                                @case('bg-info')
                                                    Temporary
                                                @break

                                                @default
                                                    Home
                                            @endswitch
                                        </span>
                                        <p>{{ $addr->address }}</p>
                                        @if (!empty($addr->landmark))
                                            <small class="text-muted">Near: {{ $addr->landmark }}</small><br>
                                        @endif
                                        <small>{{ $addr->city }}, {{ $addr->state }}</small><br>
                                        <small>{{ $addr->zipcode }}</small>

                                    </div>
                                @endforeach
                            @else
                                <div class="address-card">
                                    <p>No address found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- <div id="hiddenInput"></div> --}}
                <input type="hidden"name="id" class="id" value="{{ $result->id }}">
                <button id="submitBtn" class="btn btn-info mt-3" type="submit">update</button>
            </form>

        </div>

        @include('admin.include.custmodal');
    @endsection
    <script>
        function enableEdit(td) {

            const span = td.querySelector('.text');
            const input = td.querySelector('input, select');
            const btn = document.getElementById('submitBtn');

            if (!span || !input) return;

            // Show input
            span.style.display = 'none';
            input.classList.remove('d-none');
            input.focus();

            input.onblur = function() {

                let value = input.value;

                if (input.tagName === 'SELECT') {
                    span.innerText = (value == 1) ? 'Active' : 'Inactive';
                } else {
                    span.innerText = value;
                }

                input.classList.add('d-none');
                span.style.display = 'inline';

                btn.classList.add('btn-warning');
            };
        }
        // Address slider functionality
        const slider = document.querySelector('.address-slider');

        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => isDown = false);
        slider.addEventListener('mouseup', () => isDown = false);

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });
    </script>
    <script>
        function openAddModal() {
            resetAddForm()
            document.getElementById('modalTitle').innerText = "Add Address";
            document.getElementById('addressModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addressModal').style.display = 'none';
        }

        function resetAddForm() {
            document.getElementById('addr_id').value = '';
            document.getElementById('addr_address').value = '';
            document.getElementById('addr_landmark').value = '';
            document.getElementById('addr_city').value = '';
            document.getElementById('addr_state').value = '';
            document.getElementById('addr_zipcode').value = '';
            document.getElementById('addr_country').value = '';
            document.getElementById('addr_label').value = 'bg-primary';
            document.getElementById('is_default').checked = false;
        }

        function handleCardClick(el) {
            document.getElementById('modalTitle').innerText = "Edit Address";

            document.getElementById('addr_id').value = el.dataset.id;
            document.getElementById('addr_address').value = el.dataset.address;
            document.getElementById('addr_landmark').value = el.dataset.landmark;
            document.getElementById('addr_city').value = el.dataset.city;
            document.getElementById('addr_state').value = el.dataset.state;
            document.getElementById('addr_zipcode').value = el.dataset.zipcode;
            document.getElementById('addr_country').value = el.dataset.country;
            document.getElementById('addr_label').value = el.dataset.label;
            document.getElementById('is_default').checked = el.dataset.is_default == '1';

            document.getElementById('addressModal').style.display = 'block';
        }

        function deleteAdd() {
            let customerId = document.querySelector('input[name="customer_id"]').value;
            let addressId = document.getElementById('addr_id').value;
            if (!addressId) {
                alert('No address selected');
                return;
            }
            if (!confirm('Are you sure you want to delete this address?')) return;
            window.location.href = `/admin/customer/deleteaddress/${customerId}/${addressId}`;
        }
    </script>
