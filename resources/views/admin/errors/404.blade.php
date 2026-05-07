@php

    $presets = [
        '404' => [
            'icon' => 'fa-exclamation-triangle',
            'title' => 'Page Not Found',
            'message' => 'The page you are looking for does not exist.',
        ],

        'empty' => [
            'icon' => 'fa-folder-open',
            'title' => 'No Data Found',
            'message' => 'There is nothing to display here.',
        ],

        'access' => [
            'icon' => 'fa-lock',
            'title' => 'Access Denied',
            'message' => 'You do not have permission to access this page.',
        ],
    ];

    $preset = $presets[$type ?? 'empty'] ?? $presets['empty'];

    $icon = $icon ?? $preset['icon'];
    $title = $title ?? $preset['title'];
    $message = $message ?? $preset['message'];

    $btnText = $btnText ?? null;
    $btnUrl = $btnUrl ?? null;

    $is404 = ($type ?? '') === '404';

    $backUrl = url()->previous() !== url()->current() ? url()->previous() : url('admin/dashboard');

@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center align-items-center" style="height:100vh;">

            <div class="col-md-6 text-center">
                <div class="d-flex flex-column align-items-center justify-content-center text-center py-5"
                    style="min-height: {{ $is404 ? '65vh' : '280px' }};">
                    {{-- Icon --}}
                    <div class="mb-3" style="color: #cbd5e1;">
                        <i class="fa {{ $icon }}" style="font-size: {{ $is404 ? '72px' : '52px' }};"></i>
                    </div>

                    {{-- 404 number --}}
                    @if ($is404)
                        <h1 class="fw-bold mb-1" style="font-size: 96px; color: #e2e8f0; line-height: 1;">
                            404
                        </h1>
                    @endif

                    {{-- Title --}}
                    <h4 class="fw-semibold text-dark mb-2">{{ $title }}</h4>

                    {{-- Message --}}
                    <p class="text-muted mb-4" style="max-width: 420px;">{{ $message }}</p>

                    {{-- Buttons --}}
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @if ($btnText && $btnUrl)
                            <a href="{{ $btnUrl }}" class="btn btn-primary px-4">
                                <i class="fa fa-home me-1"></i> {{ $btnText }}
                            </a>
                        @endif
                        @if ($is404)
                            {{-- <a href="{{ url('admin/dashboard') }}" class="btn btn-primary px-4">
                                <i class="fa fa-home me-1"></i> Dashboard
                            </a> --}}
                            <a href="{{ $backUrl }}" class="btn btn-outline-secondary px-4">
                                <i class="fa fa-arrow-left me-1"></i> Go Back
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>

</html>
