@php
    $presets = [
        'product' => [
            'icon' => 'fa-box-open',
            'title' => 'No Products Found',
            'message' => 'You haven\'t added any products yet.',
        ],
        'category' => [
            'icon' => 'fa-tags',
            'title' => 'No Categories Found',
            'message' => 'Start by creating your first category.',
        ],
        'media' => ['icon' => 'fa-images', 'title' => 'No Media Files', 'message' => 'Your media library is empty.'],
        'linkproduct' => [
            'icon' => 'fa-link',
            'title' => 'No Variants Found',
            'message' => 'No product attributes linked yet.',
        ],
        'tecnicalspecs' => [
            'icon' => 'fa-cogs',
            'title' => 'No Technical Specs Found',
            'message' => 'No technical specifications added yet.',
        ],
        'size' => ['icon' => 'fa-ruler', 'title' => 'No Sizes Found', 'message' => 'No sizes have been added yet.'],
        'color' => [
            'icon' => 'fa-palette',
            'title' => 'No Colors Found',
            'message' => 'No colors have been added yet.',
        ],
        'coupon' => [
            'icon' => 'fa-ticket-alt',
            'title' => 'No Coupons Found',
            'message' => 'No discount coupons created yet.',
        ],
        'order' => [
            'icon' => 'fa-shopping-bag',
            'title' => 'No Orders Found',
            'message' => 'No orders have been placed yet.',
        ],
        'customer' => [
            'icon' => 'fa-users',
            'title' => 'No Customers Found',
            'message' => 'No customers have registered yet.',
        ],
        'calendar' => [
            'icon' => 'fa-calendar-times',
            'title' => 'No Events Found',
            'message' => 'No events scheduled yet.',
        ],
        '404' => [
            'icon' => 'fa-exclamation-triangle',
            'title' => 'Not Found',
            'message' => 'The record you\'re looking for doesn\'t exist.',
        ],
        'empty' => ['icon' => 'fa-inbox', 'title' => 'Nothing Here', 'message' => 'No data available to display.'],
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
                <i class="fa fa-plus me-1"></i> {{ $btnText }}
            </a>
        @endif
        @if ($is404)
            <a href="{{ url('admin/dashboard') }}" class="btn btn-primary px-4">
                <i class="fa fa-home me-1"></i> Dashboard
            </a>
            <a href="{{ $backUrl }}" class="btn btn-outline-secondary px-4">
                <i class="fa fa-arrow-left me-1"></i> Go Back
            </a>
        @endif
    </div>
</div>
