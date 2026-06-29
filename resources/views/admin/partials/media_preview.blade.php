{{-- ./views/include/media_preview.blade.php --}}
@php
    $videoTypes = ['mp4', 'mov', 'avi', 'webm'];
    $isVideo = in_array(strtolower($media->media_type ?? ''), $videoTypes);
    $streamUrl = route('media.stream', $media->file_name); // videos only
    $serveUrl = route('media.serve', $media->file_name); // images + fallback
    $size = $size ?? 'sm';
    $autoplay = $autoplay ?? false;

    $dimensions = match ($size) {
        'sm' => 'width:80px; height:80px;',
        'md' => 'width:200px; height:140px;',
        'lg' => 'width:100%; height:360px;',
        default => 'width:80px; height:80px;',
    };
@endphp

@if ($isVideo)
    <video onclick="openMediaPreview('{{ $streamUrl }}', 'video')"
        style="{{ $dimensions }} object-fit:cover; border-radius:6px; background:#000; cursor:pointer;"
        preload="metadata">
        <source src="{{ $streamUrl }}">
    </video>
@else
    <img src="{{ $serveUrl }}" onclick="openMediaPreview('{{ $serveUrl }}', 'image')"
        style="{{ $dimensions }} object-fit:cover; border-radius:6px; cursor:pointer;" loading="lazy"
        alt="{{ $media->tags ?? 'media' }}">
@endif



