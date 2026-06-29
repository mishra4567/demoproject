@extends('admin.layout.layout')
@section('page_title', 'Color Manager')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Color</h3>
            <a href="{{ route('color') }}">
                <button type="button" class="btn btn-success" disabled="">Back to Color</button>
            </a>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('color.manage_color_process') }}" method="post">
                                @csrf
                                {{-- Color Name --}}
                                <div class="mb-3">
                                    <label for="color_name" class="control-label mb-1">
                                        Color Name
                                        @include('admin.partials.field_info', [
                                            'info' => $info['color_name'] ?? '',
                                        ])
                                    </label>
                                    <input id="color_name" name="color_name"
                                        value="{{ old('color_name', $color_name ?? '') }}" type="text"
                                        class="form-control @error('color_name') is-invalid @enderror"
                                        placeholder="e.g. Sky Blue, Dark Red" required>
                                    @error('color_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Hex Code + Picker --}}
                                <div class="mb-3">
                                    <label for="hex_id" class="control-label mb-1">
                                        Hex Code
                                        @include('admin.partials.field_info', [
                                            'info' => $info['hex_id'] ?? '',
                                        ])
                                    </label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input id="hex_id" name="hex_id" value="{{ old('hex_id', $hex_id ?? '') }}"
                                            type="text" class="form-control @error('hex_id') is-invalid @enderror"
                                            placeholder="#FF0000" oninput="updateFromText(this.value)" required>
                                        <input type="color" id="color_picker"
                                            value="{{ old('hex_id', $hex_id ?? '#000000') }}"
                                            style="width:45px; height:38px; padding:2px;
                                                border:1px solid #ddd; border-radius:6px;
                                                cursor:pointer;"
                                            oninput="updateFromPicker(this.value)" title="Pick a color">
                                    </div>
                                    @error('hex_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Live Preview --}}
                                <div class="mb-3">
                                    <label class="control-label mb-1">Preview</label>
                                    <div class="d-flex align-items-center gap-3 p-3
                                                border rounded"
                                        style="background:#f9f9f9;">
                                        <div id="color_circle"
                                            style="
                                                width:45px; height:45px;
                                                background:{{ old('hex_id', $hex_id ?: '#000000') }};
                                                border-radius:50%;
                                                border:2px solid #ddd;
                                                transition:background 0.2s;">
                                        </div>
                                        <div id="color_square"
                                            style="
                                                width:45px; height:45px;
                                                background:{{ old('hex_id', $hex_id ?: '#000000') }};
                                                border-radius:6px;
                                                border:2px solid #ddd;
                                                transition:background 0.2s;">
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Name</span>
                                            <strong id="preview_name">
                                                {{ old('color_name', $color_name ?? '—') }}
                                            </strong>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Hex</span>
                                            <strong id="color_hex_label">
                                                {{ old('hex_id', $hex_id ?: '#000000') }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid mt-4">
                                    <input type="hidden" name="id" value="{{ $id ?? '' }}">
                                    <button type="submit" class="btn btn-lg btn-info">
                                        {{ isset($id) && $id > 0 ? 'Update Color' : 'Add Color' }}
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
        // Update from hex text input
        function updateFromText(value) {
            document.getElementById('color_hex_label').textContent = value;
            document.getElementById('color_circle').style.background = value;
            document.getElementById('color_square').style.background = value;
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                document.getElementById('color_picker').value = value;
            }
        }

        // Update from color picker
        function updateFromPicker(value) {
            document.getElementById('color').value = value;
            document.getElementById('color_hex_label').textContent = value;
            document.getElementById('color_circle').style.background = value;
            document.getElementById('color_square').style.background = value;
        }

        // Select swatch — fills both name and hex
        function selectSwatch(hex, name) {
            document.getElementById('color').value = hex;
            document.getElementById('color_name').value = name;
            document.getElementById('color_picker').value = hex;
            document.getElementById('color_hex_label').textContent = hex;
            document.getElementById('preview_name').textContent = name;
            document.getElementById('color_circle').style.background = hex;
            document.getElementById('color_square').style.background = hex;
        }

        // Live preview name update
        document.getElementById('color_name').addEventListener('input', function() {
            document.getElementById('preview_name').textContent = this.value || '—';
        });
    </script>

@endsection
