@extends('admin.layout.layout')
@section('page_title', 'Color')
@section('color_select', 'active')
@section('container')

    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <h3 class="title-5 m-b-35">Color</h3>

            <div class="d-flex gap-2 mb-3">
                <a href="{{ route('color.add_color') }}">
                    <button type="button" class="btn btn-success">Add Color</button>
                </a>

                {{-- Trash Toggle --}}
                <button type="button" class="btn btn-secondary" onclick="toggleTrash()" id="trash_toggle_btn">
                    <i class="fa fa-trash me-1"></i>
                    Show Deleted
                    @if (count(${'delete-data'}) > 0)
                        <span class="badge bg-danger ms-1">{{ count(${'delete-data'}) }}</span>
                    @endif
                </button>
            </div>

            @include('admin.include.notify')

            <div class="row">
                <form action="{{ route('color.bulkAction') }}" method="POST">
                    @csrf

                    {{-- Bulk Action --}}
                    <div class="mb-3 d-flex gap-2">
                        <select name="action" class="form-control w-auto" required>
                            <option value="">Bulk Action</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>

                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all"></th>
                                    <th>Color ID</th>
                                    <th>Color</th>
                                    <th>Preview</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- ✅ Active Rows --}}
                                @forelse ($data as $list)
                                    <tr class="active-row">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>{{ $list->color }}</td>
                                        <td>
                                            <div
                                                style="
                                            width:28px; height:28px;
                                            background:{{ $list->color }};
                                            border-radius:50%;
                                            border:1px solid #ddd;">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('color.status', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            <a href="{{ route('color.edit_color', $list->id) }}">
                                                <button type="button" class="btn btn-outline-success btn-sm">
                                                    Edit
                                                </button>
                                            </a>
                                            <a href="{{ route('color.delete', $list->id) }}"
                                                onclick="return confirm('Move {{ $list->color }} to trash?')">
                                                <button type="button" class="btn btn-outline-danger btn-sm">
                                                    Delete
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="active-row">
                                        <td colspan="5">
                                            @include('admin.partials.not_found', [
                                                'type' => 'color',
                                                'btnText' => 'Add Color',
                                                'btnUrl' => route('color.add_color'),
                                            ])
                                        </td>
                                    </tr>
                                @endforelse

                                {{-- ✅ Deleted Rows — hidden by default --}}
                                @foreach (${'delete-data'} as $list)
                                    <tr class="trash-row d-none" style="background:#fff3f3;">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>
                                            <span class="text-muted text-decoration-line-through">
                                                {{ $list->color }}
                                            </span>
                                        </td>
                                        <td>
                                            <div
                                                style="
                                            width:28px; height:28px;
                                            background:{{ $list->color }};
                                            border-radius:50%;
                                            border:1px solid #ddd;
                                            filter:grayscale(100%);">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('color.restore', $list->id) }}"
                                                onclick="return confirm('Restore {{ $list->color }}?')">
                                                <button type="button" class="btn btn-success btn-sm">
                                                    <i class="fa fa-undo me-1"></i> Restore
                                                </button>
                                            </a>
                                            <a href="{{ route('color.permanent_delete', $list->id) }}"
                                                onclick="return confirm('Permanently delete? Cannot be undone.')">
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-times me-1"></i> Remove
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let trashVisible = false;

        function toggleTrash() {
            trashVisible = !trashVisible;
            const btn = document.getElementById('trash_toggle_btn');
            const trashRows = document.querySelectorAll('.trash-row');
            const activeRows = document.querySelectorAll('.active-row');

            if (trashVisible) {
                trashRows.forEach(r => r.classList.remove('d-none'));
                activeRows.forEach(r => r.classList.add('d-none'));
                btn.innerHTML = '<i class="fa fa-list me-1"></i> Show Active';
                btn.classList.replace('btn-secondary', 'btn-success');
            } else {
                trashRows.forEach(r => r.classList.add('d-none'));
                activeRows.forEach(r => r.classList.remove('d-none'));
                btn.innerHTML =
                    '<i class="fa fa-trash me-1"></i> Show Deleted <span class="badge bg-danger ms-1">{{ count(${'delete-data'}) }}</span>';
                btn.classList.replace('btn-success', 'btn-secondary');
            }
        }

        // Select all checkboxes
        document.getElementById('select_all').addEventListener('change', function() {
            document.querySelectorAll('.checkbox_ids').forEach(function(checkbox) {
                checkbox.checked = this.checked;
            }, this);
        });
    </script>

@endsection
