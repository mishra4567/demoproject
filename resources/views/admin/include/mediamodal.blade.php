{{-- Select Media Modal --}}
<div class="modal fade" id="mediaModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            {{-- mediaModal.blade.php --}}
            <div class="modal-header">
                <h5 id="mediaModalTitle">Select Media</h5>
                <button type="button" class="btn btn-sm btn-primary" onclick="toggleModalUpload()">
                    <i class="fa fa-upload me-1"></i> Upload
                </button>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            @include('admin.partials.media_modal_upload_section') 
            <div class="modal-body">

                {{-- Search Bar --}}
                <div class="mb-3">
                    <input type="text" id="mediaSearch" class="form-control" placeholder="Search by tag..."
                        autocomplete="off">
                </div>

                {{-- Filter Tabs --}}
                <div class="mb-3 d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-dark active filter-btn"
                        onclick="filterMedia('all', this)">All</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-btn"
                        onclick="filterMedia('image', this)">
                        <i class="fa fa-image me-1"></i> Images
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-btn"
                        onclick="filterMedia('video', this)">
                        <i class="fa fa-play-circle me-1"></i> Videos
                    </button>
                </div>

                {{-- Media Grid --}}
                <div class="row g-2" id="mediaGrid">
                    @forelse ($media as $item)
                        @php
                            $isVideo = in_array(strtolower($item->media_type), ['mp4', 'mov', 'avi', 'webm']);
                            $streamUrl = route('media.stream', $item->file_name);
                        @endphp

                        <div class="col-6 col-md-3 media-grid-item" data-kind="{{ $isVideo ? 'video' : 'image' }}">
                            <div class="media-card border rounded p-1 text-center"
                                style="cursor:pointer; transition: border-color .2s;" onclick="selectMediaCard(this)">

                                <input type="radio" name="selected_media" value="{{ $item->id }}"
                                    data-file="{{ $item->file_name }}" data-type="{{ $item->media_type }}"
                                    data-stream="{{ $streamUrl }}" data-kind="{{ $isVideo ? 'video' : 'image' }}"
                                    class="d-none selected-radio">

                                <div class="position-relative">
                                    @if ($isVideo)
                                        <video src="{{ $streamUrl }}"
                                            style="height:100px; width:100%; object-fit:cover; border-radius:4px; pointer-events:none;"
                                            muted preload="metadata">
                                        </video>
                                        <div class="position-absolute top-50 start-50 translate-middle"
                                            style="pointer-events:none;">
                                            <span class="badge bg-dark bg-opacity-75"
                                                style="font-size:18px; padding:4px 8px; border-radius:50%;">▶</span>
                                        </div>
                                        <span class="position-absolute top-0 end-0 badge bg-secondary m-1"
                                            style="font-size:9px;">
                                            {{ strtoupper($item->media_type) }}
                                        </span>
                                    @else
                                        <img src="{{ $streamUrl }}"
                                            style="height:100px; width:100%; object-fit:cover; border-radius:4px;"
                                            loading="lazy" alt="{{ $item->tags }}">
                                    @endif
                                </div>

                                <div class="mt-1 small text-truncate px-1" title="{{ $item->tags }}">
                                    {{ $item->tags ?? '—' }}
                                </div>

                                @if ($isVideo)
                                    <button type="button" class="btn btn-outline-dark btn-sm w-100 mt-1"
                                        style="font-size:11px;"
                                        onclick="event.stopPropagation();
                                                openInlinePlayer('{{ $streamUrl }}', '{{ $item->file_name }}')">
                                        <i class="fa fa-play me-1"></i> Preview
                                    </button>
                                @endif

                            </div>
                        </div>

                    @empty
                        <div class="col-12 text-center text-muted py-4">
                            <small>No media uploaded yet.</small>
                        </div>
                    @endforelse
                </div>

                {{-- No search results --}}
                <div id="mediaNoResult" class="text-center text-muted py-3 d-none">
                    <small>No media found for your search.</small>
                </div>

                {{-- Inline video player --}}
                <div id="inlinePlayerWrap" class="d-none mt-3">
                    <div class="bg-dark rounded p-2">
                        <div class="d-flex justify-content-between align-items-center mb-1 px-1">
                            <small id="inlinePlayerTitle" class="text-white"
                                style="font-size:11px; word-break:break-all;"></small>
                            <button type="button" class="btn-close btn-close-white btn-sm"
                                onclick="closeInlinePlayer()"></button>
                        </div>
                        <video id="inlinePlayer" controls autoplay preload="metadata"
                            style="width:100%; max-height:300px; border-radius:4px; background:#000;">
                            Your browser does not support video playback.
                        </video>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer justify-content-between">
                <small id="selectedFileInfo" class="text-muted">No file selected</small>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="stopModalVideo()">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="addMediaButton()">
                        <i class="fa fa-check me-1"></i> Add Selected
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ✅ NO @push('scripts') here — all JS lives in mediaModal.js --}}
