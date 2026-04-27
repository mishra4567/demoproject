@extends('admin.layout.layout')
@section('page_title', 'Settings')
@section('settings_select', 'active')
@section('container')

    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <h3 class="title-5 m-b-35">Admin Users</h3>

            @include('admin.include.notify')

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="fw-bold text-dark">{{ $counts['total'] }}</h3>
                            <p class="text-muted mb-0 small">Total</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="fw-bold text-warning">{{ $counts['pending'] }}</h3>
                            <p class="text-muted mb-0 small">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="fw-bold text-success">{{ $counts['approved'] }}</h3>
                            <p class="text-muted mb-0 small">Approved</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="fw-bold text-danger">{{ $counts['suspended'] }}</h3>
                            <p class="text-muted mb-0 small">Suspended</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Email Verified</th>
                                <th>Account Status</th>
                                <th>Available Actions</th>
                                <th>Registered</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($profile->isEmpty())
                                <tr>
                                    <td colspan="9">
                                        @include('admin.partials.not_found', [
                                            'type' => 'empty',
                                            'icon' => 'fa-users',
                                            'title' => 'No Admin Users Found',
                                            'message' => 'No admin users have registered yet.',
                                        ])
                                    </td>
                                </tr>
                            @else
                                @foreach ($profile as $index => $list)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $list->name }}</td>
                                        <td>{{ $list->email }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ ucfirst($list->admin_role) }}
                                            </span>
                                        </td>

                                        {{-- Email Verified --}}
                                        <td>
                                            @if ($list->email_verified_at)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Not Verified</span>
                                            @endif
                                        </td>

                                        {{-- Account Status --}}
                                        <td>
                                            @if ($list->status == 2)
                                                <span class="badge bg-secondary">Suspended</span>
                                            @elseif ($list->admin_appr == 2)
                                                <span class="badge bg-danger">Rejected</span>
                                            @elseif ($list->admin_appr == 1 && $list->status == 1)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>

                                        {{-- ✅ Available Actions as badges (view only) --}}
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @if ($list->admin_appr != 1 || $list->status != 1)
                                                    <span class="badge bg-success">✅ Approve</span>
                                                @endif
                                                @if ($list->admin_appr != 2)
                                                    <span class="badge bg-danger">❌ Reject</span>
                                                @endif
                                                @if ($list->status != 2 && $list->id != session('ADMIN_ID'))
                                                    <span class="badge bg-warning text-dark">🚫 Suspend</span>
                                                @endif
                                                @if ($list->status == 2 || $list->admin_appr == 2)
                                                    <span class="badge bg-primary">♻️ Reactivate</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($list->created_at)->format('d M Y') }}
                                        </td>

                                        {{-- ✅ Action — dropdown + apply --}}
                                        <td>
                                            <div class="d-flex gap-1 align-items-center">

                                                {{-- View --}}
                                                <a href="{{ route('admin.users.view', $list->id) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                {{-- Dropdown trigger --}}
                                                <button type="button" class="btn btn-dark btn-sm"
                                                    onclick="toggleActionForm({{ $list->id }})">
                                                    <i class="fa fa-cog"></i>
                                                </button>

                                            </div>

                                            {{-- ✅ Hidden action form — shown on cog click --}}
                                            <div id="action_form_{{ $list->id }}" class="mt-2 d-none">
                                                <form action="{{ route('admin.users.update', $list->id) }}" method="POST"
                                                    onsubmit="return handleSubmit(this)">
                                                    @csrf
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <select name="action" class="form-control form-control-sm"
                                                            style="min-width:130px;" onchange="toggleReason(this)">
                                                            <option value="">-- Select --</option>
                                                            @if ($list->admin_appr != 1 || $list->status != 1)
                                                                <option value="approve">✅ Approve</option>
                                                            @endif
                                                            @if ($list->admin_appr != 2)
                                                                <option value="reject">❌ Reject</option>
                                                            @endif
                                                            @if ($list->status != 2 && $list->id != session('ADMIN_ID'))
                                                                <option value="suspend">🚫 Suspend</option>
                                                            @endif
                                                            @if ($list->status == 2 || $list->admin_appr == 2)
                                                                <option value="reactivate">♻️ Reactivate</option>
                                                            @endif
                                                        </select>
                                                        <button type="submit" class="btn btn-dark btn-sm">
                                                            Apply
                                                        </button>
                                                    </div>
                                                    <div class="reason-box mt-2 d-none">
                                                        <input type="text" name="reason"
                                                            class="form-control form-control-sm"
                                                            placeholder="Enter reason...">
                                                    </div>
                                                </form>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Toggle action form visibility
        function toggleActionForm(id) {
            const form = document.getElementById('action_form_' + id);
            form.classList.toggle('d-none');
        }

        // Show reason input for reject/suspend
        function toggleReason(select) {
            const form = select.closest('form');
            const reasonBox = form.querySelector('.reason-box');
            const reasonInput = form.querySelector('input[name="reason"]');

            if (select.value === 'reject' || select.value === 'suspend') {
                reasonBox.classList.remove('d-none');
                reasonInput.required = true;
            } else {
                reasonBox.classList.add('d-none');
                reasonInput.required = false;
                reasonInput.value = '';
            }
        }

        // Confirm on submit
        function handleSubmit(form) {
            const action = form.querySelector('select[name="action"]').value;

            if (!action) {
                alert('Please select an action.');
                return false;
            }

            const messages = {
                'approve': 'Approve this admin user?',
                'reject': 'Reject this admin user?',
                'suspend': 'Suspend this admin user?',
                'reactivate': 'Reactivate this admin user?',
            };

            return confirm(messages[action] ?? 'Are you sure?');
        }
    </script>

@endsection
