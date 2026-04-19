{{-- notification message --}}
@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif
@if (session('bulk-success'))
    <div class="alert alert-success">
        {{-- {{ session('action') }} <br> --}}
        @php
            $ids = session('ids');
        @endphp
        IDs:
        {{ is_array($ids) ? implode(', ', array_slice($ids, 0, 10)) : $ids }}
        @if (is_array($ids) && count($ids) > 10)
            ... +{{ count($ids) - 10 }} more
        @endif
        Action: {{ session('bulk-success') }} <br>
    </div>
@endif

@if (session('denied'))
    <div class="alert alert-warning" role="alert">
        {{ session('denied') }}
    </div>
@endif
@if (session('update'))
    <div class="alert btn-outline-info" role="alert">
        {{ session('update') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif
@isset($errors)
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endisset

@isset($error)
    @error('category_name')
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
    @enderror
@endisset

@isset($error)
    @error('category_slug')
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
    @enderror
@endisset

{{-- Notifiction using javacript --}}
<div id="notify-area">
    @if (session('success_event'))
        <div class="alert alert-success" role="alert">
            {{ session('success_event') }}
        </div>
    @endif

    @if (session('success_event'))
        <div class="alert alert-danger" role="alert">
            {{ session('success_event') }}
        </div>
    @endif
</div>

<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => el.remove());
    }, 4000);
</script>
