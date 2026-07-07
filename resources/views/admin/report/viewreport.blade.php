@extends('admin.layout.layout')
@section('page_title', 'Reports')
@section('report_select', 'active')
@section('container')

    <div class="section__content section__content--p30">
        <div class="container-fluid">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Reports</h3>
                    <p class="text-muted small mb-0">
                        Total: {{ $report->total() }} report(s)
                    </p>
                </div>
                <a href="{{ route('report.create') }}" class="btn btn-success">
                    <i class="fa fa-plus me-1"></i> New Report
                </a>
            </div>

            {{-- Filters --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fa fa-filter me-2 text-muted"></i>Filters
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET">
                        <div class="row g-3">

                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm"
                                    value="{{ request('search') }}" placeholder="Title, User ID, Email, Name">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">User Type</label>
                                <select name="user_type" class="form-select form-select-sm">
                                    <option value="">All Types</option>
                                    <option value="admin" @selected(request('user_type') == 'admin')>Admin</option>
                                    <option value="vendor" @selected(request('user_type') == 'vendor')>Vendor</option>
                                    <option value="customer" @selected(request('user_type') == 'customer')>Customer</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Rating</label>
                                <select name="rating" class="form-select form-select-sm">
                                    <option value="">All Ratings</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @selected(request('rating') == $i)>
                                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="1" @selected(request('status') === '1')>Active</option>
                                    <option value="0" @selected(request('status') === '0')>Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Date</label>
                                <input type="date" name="date" value="{{ request('date') }}"
                                    class="form-control form-control-sm">
                            </div>

                        </div>

                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-primary">
                                <i class="fa fa-search me-1"></i> Apply
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-rotate-left me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Empty --}}
            @if ($report->isEmpty())
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body text-center py-5">
                        <i class="fa fa-inbox fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">No reports found.</p>
                        <a href="{{ route('report.create') }}" class="btn btn-sm btn-outline-success mt-3">
                            Add First Report
                        </a>
                    </div>
                </div>
            @else
                {{-- Table --}}
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">

                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th class="px-4 py-3">#</th>
                                        <th class="px-4 py-3">User</th>
                                        <th class="px-4 py-3">Name / Email</th>
                                        <th class="px-4 py-3">Title</th>
                                        <th class="px-4 py-3">Rating</th>
                                        <th class="px-4 py-3">Sections</th>
                                        <th class="px-4 py-3">Submitted</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($report as $item)
                                        @php
                                            $data = is_array($item->report_data)
                                                ? $item->report_data
                                                : json_decode($item->report_data, true) ?? [];
                                            $sections = isset($data['sections']) ? array_values($data['sections']) : [];
                                            if (!is_array($sections)) {
                                                $sections = [];
                                            }
                                        @endphp

                                        {{-- Main row --}}
                                        <tr>
                                            <td class="px-4">
                                                <span class="badge bg-light text-dark border">
                                                    #{{ $item->id }}
                                                </span>
                                            </td>

                                            <td class="px-4">
                                                <span
                                                    class="badge rounded-pill
                                        @if ($item->user_type === 'admin') bg-danger
                                        @elseif($item->user_type === 'vendor') bg-warning text-dark
                                        @else bg-primary @endif">
                                                    {{ ucfirst($item->user_type) }}
                                                </span>
                                                <small class="text-muted ms-1">#{{ $item->user_id }}</small>
                                            </td>

                                            <td class="px-4">
                                                <div class="fw-semibold small">
                                                    {{ $item->user_name ?? '—' }}
                                                </div>
                                                <div class="text-muted" style="font-size:.75rem;">
                                                    {{ $item->auth_email ?? '—' }}
                                                </div>
                                            </td>

                                            <td class="px-4">
                                                {{ $item->title ?? '—' }}
                                            </td>

                                            <td class="px-4">
                                                <div class="d-flex align-items-center gap-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fa fa-star {{ $i <= $item->rating ? 'text-warning' : 'text-muted' }}"
                                                            style="font-size:12px;"></i>
                                                    @endfor
                                                    <small class="text-muted ms-1">{{ $item->rating }}/5</small>
                                                </div>
                                            </td>

                                            <td class="px-4">
                                                <span class="badge bg-secondary">
                                                    {{ count($sections) }} section{{ count($sections) !== 1 ? 's' : '' }}
                                                </span>
                                            </td>

                                            <td class="px-4">
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                                    <br>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('h:i A') }}
                                                </small>
                                            </td>

                                            <td class="px-4">
                                                <span class="badge {{ $item->status ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $item->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>

                                            <td class="px-4">
                                                <button class="btn btn-sm btn-outline-primary" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#report{{ $item->id }}"
                                                    aria-expanded="false">
                                                    <i class="fa fa-eye me-1"></i> View
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Detail collapse row --}}
                                        <tr class="bg-light">
                                            <td colspan="9" class="p-0 border-0">
                                                <div class="collapse" id="report{{ $item->id }}">
                                                    <div class="p-4 border-top">

                                                        {{-- User Details --}}
                                                        <h6 class="fw-bold mb-3">
                                                            <i class="fa fa-user me-2 text-muted"></i>User Details
                                                        </h6>
                                                        <div class="row g-3 mb-4">
                                                            <div class="col-md-3">
                                                                <div class="small text-muted fw-semibold mb-1">Name</div>
                                                                <div>{{ $item->user_name ?? '—' }}</div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="small text-muted fw-semibold mb-1">Email</div>
                                                                <div>{{ $item->auth_email ?? '—' }}</div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="small text-muted fw-semibold mb-1">User ID
                                                                </div>
                                                                <div>#{{ $item->user_id }}</div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="small text-muted fw-semibold mb-1">IP Address
                                                                </div>
                                                                <div>{{ $data['ip'] ?? '—' }}</div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="small text-muted fw-semibold mb-1">Submitted At
                                                                </div>
                                                                <div class="small">
                                                                    {{ isset($data['submitted_at'])
                                                                        ? \Carbon\Carbon::parse($data['submitted_at'])->format('d M Y, h:i A')
                                                                        : \Carbon\Carbon::parse($item->created_at)->format('d M Y, h:i A') }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- User Agent --}}
                                                        @if (!empty($data['user_agent']))
                                                            <h6 class="fw-bold mb-2">
                                                                <i class="fa fa-desktop me-2 text-muted"></i>User Agent
                                                            </h6>
                                                            <div class="alert alert-light border small mb-4">
                                                                {{ $data['user_agent'] }}
                                                            </div>
                                                        @endif

                                                        {{-- Sections --}}
                                                        <h6 class="fw-bold mb-3">
                                                            <i class="fa fa-list me-2 text-muted"></i>
                                                            Sections ({{ count($sections) }})
                                                        </h6>

                                                        @forelse($sections as $index => $section)
                                                            <div class="card mb-3 border">
                                                                <div
                                                                    class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                                                                    <strong class="small">
                                                                        Section {{ $index + 1 }}
                                                                    </strong>
                                                                    <span class="badge bg-dark">
                                                                        {{ $section['field_type'] ?? 'text' }}
                                                                    </span>
                                                                </div>
                                                                <div class="card-body">

                                                                    <div class="mb-3">
                                                                        <div class="small text-muted fw-semibold mb-1">
                                                                            Description</div>
                                                                        <div class="border rounded p-2 bg-light small">
                                                                            {{ $section['description'] ?? '—' }}
                                                                        </div>
                                                                    </div>

                                                                    @if (!empty($section['field_value']))
                                                                        <div class="mb-3">
                                                                            <div class="small text-muted fw-semibold mb-1">
                                                                                Value</div>
                                                                            <div class="border rounded p-2 bg-light small">
                                                                                {{ $section['field_value'] }}
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if (!empty($section['file_path']))
                                                                        @php
                                                                            $fileUrl = asset(
                                                                                'storage/' . $section['file_path'],
                                                                            );
                                                                            $ext = strtolower(
                                                                                pathinfo(
                                                                                    $section['file_path'],
                                                                                    PATHINFO_EXTENSION,
                                                                                ),
                                                                            );
                                                                            $isImage = in_array($ext, [
                                                                                'jpg',
                                                                                'jpeg',
                                                                                'png',
                                                                                'gif',
                                                                                'webp',
                                                                                'bmp',
                                                                            ]);
                                                                        @endphp
                                                                        <div>
                                                                            <div class="small text-muted fw-semibold mb-2">
                                                                                Attachment</div>
                                                                            @if ($isImage)
                                                                                <a href="{{ $fileUrl }}"
                                                                                    target="_blank">
                                                                                    <img src="{{ $fileUrl }}"
                                                                                        alt="Attachment"
                                                                                        class="img-thumbnail"
                                                                                        style="max-height:200px;cursor:pointer;">
                                                                                </a>
                                                                                <div class="mt-2">
                                                                                    <a href="{{ $fileUrl }}"
                                                                                        target="_blank"
                                                                                        class="btn btn-sm btn-outline-primary">
                                                                                        <i class="fa fa-eye me-1"></i> Full
                                                                                        Image
                                                                                    </a>
                                                                                </div>
                                                                            @else
                                                                                <a href="{{ $fileUrl }}"
                                                                                    target="_blank"
                                                                                    class="btn btn-sm btn-outline-primary">
                                                                                    <i class="fa fa-download me-1"></i>
                                                                                    Download
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="alert alert-warning small">
                                                                <i class="fa fa-exclamation-triangle me-1"></i>
                                                                No sections found in this report.
                                                            </div>
                                                        @endforelse

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if ($report->hasPages())
                        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Showing {{ $report->firstItem() }}–{{ $report->lastItem() }}
                                of {{ $report->total() }} reports
                            </small>
                            {{ $report->links() }}
                        </div>
                    @endif
                </div>

            @endif

        </div>
    </div>

@endsection
