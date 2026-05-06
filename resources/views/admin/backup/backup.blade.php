{{-- Category --}}
<tr>
    {{-- <td>2025-01-15 14:32</td> --}}
    <td>
        <input type="checkbox" name="ids[]" value="{{ $list->id }}" class="checkbox_ids">
    </td>
    <td>{{ $list->id }}</td>
    <td>{{ $list->category_name }}</td>
    <td>{{ $list->category_slug }}</td>
    <td>
        <a href="{{ route('category.status', $list->id) }}"
            class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm ">
            {{ $list->status == 1 ? 'Active' : 'Deactive' }}
        </a>
        <a href="{{ route('category.edit_category', $list->id) }}">
            <button type="button" class="btn btn-outline-success btn-sm">Edit </button>
        </a>
        <a href="{{ route('category.delete', $list->id) }}">
            <button type="button" class="btn btn-outline-danger btn-sm">Delete
            </button>
        </a>
    </td>
</tr>
