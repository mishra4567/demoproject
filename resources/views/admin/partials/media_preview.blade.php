@php
    $videoTypes = ['mp4', 'mov', 'avi', 'webm'];
    $isVideo = in_array(strtolower($media->media_type ?? ''), $videoTypes);
    $streamUrl = route('media.stream', $media->file_name);
    $serveUrl = route('media.serve', $media->file_name);
    $size = $size ?? 'sm';
    $autoplay = $autoplay ?? false;

    // ✅ Use md5 only — no uniqid() which can fail in partials
    $uid = 'media_' . md5($media->file_name . $size);

    $dimensions = match ($size) {
        'sm' => 'width:80px; height:80px;',
        'md' => 'width:200px; height:140px;',
        'lg' => 'width:100%; height:360px;',
        default => 'width:80px; height:80px;',
    };
@endphp

@if ($isVideo)
    {{-- <div class="position-relative d-inline-block lazy-video-wrap"
        style="{{ $dimensions }}" id="{{ $uid }}">

        <div class="lazy-placeholder d-flex align-items-center justify-content-center"
            style="{{ $dimensions }} border-radius:6px; background:#1a1a2e;">
            <span style="color:rgba(255,255,255,0.4); font-size:20px;">▶</span>
        </div>

        <video
            data-src="{{ $streamUrl }}"
            onclick="openMediaPreview('{{ $streamUrl }}', 'video', '{{ $media->file_name }}')"
            style="{{ $dimensions }} object-fit:cover; border-radius:6px;
                   background:#000; cursor:pointer; display:none;"
            preload="none"
            muted>
        </video>

        <div class="position-absolute top-50 start-50 translate-middle video-overlay"
            style="pointer-events:none; display:none;">
            <span style="background:rgba(0,0,0,0.55); color:#fff; border-radius:50%;
                         width:26px; height:26px; display:flex; align-items:center;
                         justify-content:center; font-size:11px;">▶</span>
        </div>

    </div> --}}
    <video onclick="openMediaPreview('{{ $streamUrl }}', 'video')"
        style="{{ $dimensions }} object-fit:cover; border-radius:6px; background:#000; cursor:pointer;"
        preload="metadata">
        <source src="{{ $streamUrl }}">
    </video>
@else
    <div class="position-relative d-inline-block" style="{{ $dimensions }}">

        <div id="placeholder_{{ $uid }}"
            style="{{ $dimensions }} border-radius:6px; position:absolute; top:0; left:0;
                   background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                   background-size: 200% 100%; animation: shimmer 1.5s infinite;">
        </div>

        <img src="{{ $serveUrl }}"
            onclick="openMediaPreview('{{ $serveUrl }}', 'image', '{{ $media->file_name }}')"
            style="{{ $dimensions }} object-fit:cover; border-radius:6px;
                   cursor:pointer; opacity:0; transition: opacity .3s ease;
                   position:relative; z-index:1;"
            loading="lazy" decoding="async" alt="{{ $media->tags ?? $media->file_name }}"
            onload="
                this.style.opacity='1';
                var p=document.getElementById('placeholder_{{ $uid }}');
                if(p) p.style.display='none';
            "
            onerror="
                this.style.opacity='0.3';
                var p=document.getElementById('placeholder_{{ $uid }}');
                if(p) p.innerHTML='<span style=\'font-size:10px;color:#999;\'>No image</span>';
            ">

    </div>
@endif
