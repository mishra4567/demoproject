@extends('admin.layout.layout')
@section('page_title', 'Settings')
@section('settings_select', 'active')
@section('container')

    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <h3 class="title-5 m-b-35">Admin Settings Page</h3>

            <a href="{{ route('admin.profile.edite') }}">
                <button type="button" class="btn btn-success mb-3">Add Admin</button>
            </a>

            <div class="row">
                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select_all"></th>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Profile</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profile as $index => $list)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                            class="checkbox_ids">
                                    </td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $list->name }}</td>
                                    <td>
                                        {{ $list->email }}<br>
                                        @if ($list->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Not Verified</span>
                                        @endif
                                    </td>

                                    {{-- Profile Status --}}
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

                                    {{-- Action --}}
                                    <td>
                                        {{-- Cog toggle --}}
                                        <button type="button" class="btn btn-dark btn-sm"
                                            onclick="toggleActionForm({{ $list->id }})">
                                            <i class="fa fa-cog"></i>
                                        </button>

                                        {{-- Hidden form div —  unique ID on div only --}}
                                        <div id="action_div_{{ $list->id }}" class="mt-2 d-none">

                                            {{-- Unique ID on form only --}}
                                            <form id="form_{{ $list->id }}"
                                                action="{{ route('admin.profile.update', $list->id) }}" method="GET">
                                                @csrf
                                                <input type="hidden" name="action" id="action_input_{{ $list->id }}">
                                                <input type="hidden" name="reason" id="reason_input_{{ $list->id }}">
                                            </form>

                                            <div class="d-flex gap-2 align-items-center mt-1">

                                                {{-- Dropdown --}}
                                                <select id="select_{{ $list->id }}"
                                                    class="form-control form-control-sm" style="min-width:130px;"
                                                    onchange="toggleReason({{ $list->id }}, this)">
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

                                                {{-- Apply --}}
                                                <button type="button" class="btn btn-dark btn-sm"
                                                    onclick="applyAction({{ $list->id }})">
                                                    Apply
                                                </button>

                                            </div>

                                            {{-- Reason input --}}
                                            <div id="reason_box_{{ $list->id }}" class="mt-2 d-none">
                                                <input type="text" id="reason_text_{{ $list->id }}"
                                                    class="form-control form-control-sm" placeholder="Enter reason...">
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Toggle show/hide action panel
        function toggleActionForm(id) {
            const div = document.getElementById('action_div_' + id);
            div.classList.toggle('d-none');
        }

        // Show/hide reason input
        function toggleReason(id, select) {
            const reasonBox = document.getElementById('reason_box_' + id);
            if (select.value === 'reject' || select.value === 'suspend') {
                reasonBox.classList.remove('d-none');
            } else {
                reasonBox.classList.add('d-none');
                document.getElementById('reason_text_' + id).value = '';
            }
        }

        // Apply action
        function applyAction(id) {
            const select = document.getElementById('select_' + id);
            const reason = document.getElementById('reason_text_' + id);
            const action = select.value;

            // No action selected
            if (!action) {
                alert('Please select an action.');
                return;
            }

            // Reason required
            if ((action === 'reject' || action === 'suspend') && !reason.value.trim()) {
                alert('Please enter a reason.');
                reason.focus();
                return;
            }

            // Confirm
            const messages = {
                'approve': 'Approve this admin user?',
                'reject': 'Reject this admin user?',
                'suspend': 'Suspend this admin user?',
                'reactivate': 'Reactivate this admin user?',
            };

            if (!confirm(messages[action] ?? 'Are you sure?')) return;

            // Set hidden inputs
            document.getElementById('action_input_' + id).value = action;
            document.getElementById('reason_input_' + id).value = reason ? reason.value.trim() : '';

            // Submit form
            HTMLFormElement.prototype.submit.call(
                document.getElementById('form_' + id)
            );
        }

        // Select all checkboxes
        document.getElementById('select_all').addEventListener('change', function() {
            document.querySelectorAll('.checkbox_ids').forEach(function(checkbox) {
                checkbox.checked = this.checked;
            }, this);
        });
    </script>

@endsection
