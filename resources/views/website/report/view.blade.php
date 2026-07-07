{{-- resources/views/website/report/view.blade.php --}}
@extends('website.report.layout')

@section('page_title', 'Reports')

@section('reportcontainer')

    <div class="section__content section__content--p30">
        <div class="container-fluid">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Reports</h3>
                    <p class="text-muted small mb-0">
                        Total: {{ $report->count() }} report(s)
                    </p>
                </div>

                {{-- <a href="{{ route('report.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> New Report
                </a> --}}
            </div>

            {{-- reusable filter --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold">
                        <i class="fa fa-filter me-2"></i>Filters
                    </h6>
                </div>

                <div class="card-body">
                    <form method="GET">

                        <div class="row g-3">

                            {{-- Search --}}
                            <label class="form-label">Add your User ID, Email, Tittle</label>
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                    placeholder="Title, User ID, Email">
                            </div>

                            {{-- User Type --}}
                            {{-- <div class="col-md-2">
                                <label class="form-label">User Type</label>
                                <select name="user_type" class="form-select">
                                    <option value="">All</option>
                                    <option value="admin" @selected(request('user_type') == 'admin')>Admin</option>
                                    <option value="vendor" @selected(request('user_type') == 'vendor')>Vendor</option>
                                    <option value="customer" @selected(request('user_type') == 'customer')>Customer</option>
                                </select>
                            </div> --}}

                            {{-- Rating --}}
                            {{-- <div class="col-md-2">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-select">
                                    <option value="">All</option>

                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @selected(request('rating') == $i)>
                                            {{ $i }} Star
                                        </option>
                                    @endfor
                                </select>
                            </div> --}}

                            {{-- Status --}}
                            {{-- <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All</option>
                                    <option value="1" @selected(request('status') === '1')>Active</option>
                                    <option value="0" @selected(request('status') === '0')>Inactive</option>
                                </select>
                            </div> --}}

                            {{-- Date --}}
                            {{-- <div class="col-md-3">
                                <label class="form-label">Submitted Date</label>
                                <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                            </div> --}}
                            <div class="col-md-3">
                                <button class="btn btn-primary">
                                    <i class="fa fa-search me-1"></i>
                                    Apply Filter
                                </button>

                                <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                                    Reset
                                </a>
                                <a href="{{ route('report.create') }}" class="btn btn-outline-success">
                                    Add New Report
                                </a>
                            </div>

                        </div>
                        {{-- <div class="mt-3 d-flex gap-2">

                            <button class="btn btn-primary">
                                <i class="fa fa-search me-1"></i>
                                Apply Filter
                            </button>

                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                                Reset
                            </a>
                            <a href="{{ route('report.create') }}" class="btn btn-outline-success">
                                Add New Report
                            </a>

                        </div> --}}


                    </form>
                </div>
            </div>

            {{-- Empty --}}
            @if ($report->isEmpty())
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body text-center py-5">
                        <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No reports found.</p>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">

                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th class="px-4 py-3">#</th>
                                        <th class="px-4 py-3">User</th>
                                        <th class="px-4 py-3">Title</th>
                                        <th class="px-4 py-3">Rating</th>
                                        <th class="px-4 py-3">Sections</th>
                                        <th class="px-4 py-3">Submitted</th>
                                        <th class="px-4 py-3">Status</th>
                                        {{-- <th class="px-4 py-3">Action</th> --}}
                                        <th width="120">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($report as $item)
                                        @php
                                            $data = $item->report_data ?? [];
                                            $sections = $data['sections'] ?? [];
                                            $submittedAt = $data['submitted_at'] ?? null;
                                        @endphp

                                        {{-- Main Row --}}
                                        <tr>
                                            {{-- ID --}}
                                            <td class="px-4">
                                                <span class="badge bg-light text-muted">
                                                    #{{ $item->id }}
                                                </span>
                                            </td>
                                            {{-- User --}}
                                            <td class="px-4">
                                                <span
                                                    class="badge rounded-pill
                                        @if ($item->user_type === 'ADMIN') bg-danger
                                        @elseif($item->user_type === 'VENDOR') bg-warning text-dark
                                        @else bg-primary @endif">
                                                    {{ $item->user_type }}
                                                </span>
                                                <small class="text-muted ms-1">
                                                    #{{ $item->user_id }}
                                                </small>
                                            </td>
                                            {{-- Title --}}
                                            <td class="px-4">
                                                {{ $item->title ?? '—' }}
                                            </td>
                                            {{-- Rating --}}
                                            <td class="px-4">
                                                <div class="d-flex align-items-center gap-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fa fa-star @if ($i <= $item->rating) text-warning @else text-muted @endif"
                                                            style="font-size:13px;"></i>
                                                    @endfor
                                                    <small class="text-muted ms-1">({{ $item->rating }}/5)</small>
                                                </div>
                                            </td>
                                            {{-- Sections count --}}
                                            <td class="px-4">
                                                <span class="badge bg-secondary">
                                                    {{ count($sections) }} section(s)
                                                </span>
                                            </td>
                                            {{-- Submitted at --}}
                                            <td class="px-4">
                                                <small class="text-muted">
                                                    {{ $submittedAt
                                                        ? \Carbon\Carbon::parse($submittedAt)->format('d M Y, h:i A')
                                                        : \Carbon\Carbon::parse($item->created_at)->format('d M Y, h:i A') }}
                                                </small>
                                            </td>
                                            {{-- Status --}}
                                            <td class="px-4">
                                                <span
                                                    class="badge @if ($item->status) bg-success @else bg-secondary @endif">
                                                    {{ $item->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            {{-- Action --}}
                                            <td class="px-4">
                                                <button class="btn btn-sm btn-outline-primary" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#report{{ $item->id }}">
                                                    <i class="fa fa-eye"></i>
                                                    View
                                                </button>
                                            </td>
                                            {{-- <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="collapse"
                                                    data-bs-target="#report{{ $item->id }}">
                                                    <i class="fa fa-eye"></i>
                                                    View
                                                </button>
                                            </td> --}}

                                        </tr>

                                        {{-- Detail Row --}}
                                        <tr>

                                            <td colspan="8" class="p-0 border-0">

                                                <div class="collapse" id="report{{ $item->id }}">

                                                    <div class="bg-light p-4 border-top">

                                                        <h5>User Details</h5>

                                                        <div class="row mb-4">

                                                            <div class="col-md-4">
                                                                <strong>Name</strong>
                                                                <div>{{ $item->user_name }}</div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <strong>Email</strong>
                                                                <div>{{ $item->auth_email }}</div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <strong>User ID</strong>
                                                                <div>{{ $item->user_id }}</div>
                                                            </div>

                                                            <div class="col-md-6 mt-3">
                                                                <strong>IP Address</strong>
                                                                <div>{{ $data['ip'] ?? '-' }}</div>
                                                            </div>

                                                            <div class="col-md-6 mt-3">
                                                                <strong>Submitted At</strong>
                                                                <div>{{ $data['submitted_at'] ?? '-' }}</div>
                                                            </div>

                                                        </div>

                                                        <h5>User Agent</h5>

                                                        <div class="alert alert-light border">
                                                            {{ $data['user_agent'] ?? '-' }}
                                                        </div>

                                                        <h5>Sections</h5>

                                                        @forelse($sections as $index => $section)
                                                            <div class="card mb-3">

                                                                <div class="card-header d-flex justify-content-between">
                                                                    <strong>
                                                                        Section {{ $index + 1 }}
                                                                    </strong>

                                                                    <span class="badge bg-secondary">
                                                                        {{ $section['field_type'] ?? 'text' }}
                                                                    </span>
                                                                </div>

                                                                <div class="card-body">

                                                                    <div class="mb-3">
                                                                        <strong>Description</strong>

                                                                        <div class="border rounded p-2 bg-light">
                                                                            {{ $section['description'] ?? '-' }}
                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <strong>Value</strong>

                                                                        <div class="border rounded p-2 bg-light">
                                                                            {{ $section['field_value'] ?? '-' }}
                                                                        </div>
                                                                    </div>

                                                                    @if (!empty($section['file_path']))
                                                                        @php
                                                                            $fileUrl = asset(
                                                                                'storage/' . $section['file_path'],
                                                                            );

                                                                            $extension = strtolower(
                                                                                pathinfo(
                                                                                    $section['file_path'],
                                                                                    PATHINFO_EXTENSION,
                                                                                ),
                                                                            );

                                                                            $imageExtensions = [
                                                                                'jpg',
                                                                                'jpeg',
                                                                                'png',
                                                                                'gif',
                                                                                'webp',
                                                                                'bmp',
                                                                            ];
                                                                        @endphp

                                                                        <div class="mb-3">
                                                                            <strong>Attachment</strong>

                                                                            <div class="mt-2">

                                                                                @if (in_array($extension, $imageExtensions))
                                                                                    {{-- Image Preview --}}
                                                                                    <a href="{{ $fileUrl }}"
                                                                                        target="_blank">
                                                                                        <img src="{{ $fileUrl }}"
                                                                                            alt="Attachment"
                                                                                            class="img-thumbnail"
                                                                                            style="max-height:250px; cursor:pointer;">
                                                                                    </a>

                                                                                    <div class="mt-2">
                                                                                        <a href="{{ $fileUrl }}"
                                                                                            target="_blank"
                                                                                            class="btn btn-sm btn-outline-primary">
                                                                                            <i class="fa fa-eye"></i>
                                                                                            View Full Image
                                                                                        </a>
                                                                                    </div>
                                                                                @else
                                                                                    {{-- Other Files --}}
                                                                                    <a href="{{ $fileUrl }}"
                                                                                        target="_blank"
                                                                                        class="btn btn-sm btn-outline-primary">
                                                                                        <i class="fa fa-download"></i>
                                                                                        View Attachment
                                                                                    </a>
                                                                                @endif

                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                </div>

                                                            </div>

                                                        @empty

                                                            <div class="alert alert-warning">
                                                                No sections found.
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
                </div>

            @endif

        </div>
    </div>

@endsection
