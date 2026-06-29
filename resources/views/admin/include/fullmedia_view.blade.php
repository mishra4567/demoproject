{{-- ./views/include/fullmedia_view.blade.php --}}
<div class="modal fade" id="mediaPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Media Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" class="img-fluid d-none" style="max-height:80vh;">
                <video id="previewVideo" class="w-100 d-none" controls style="max-height:80vh;">
                    <source id="previewVideoSource">
                </video>
            </div>
        </div>
    </div>
</div>

{{-- line 32 start custom controll --}}
{{-- <div class="modal fade" id="mediaPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Media Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" class="img-fluid d-none" style="max-height:80vh;">
                <div id="videoWrapper" class="d-none">
                    <video id="previewVideo" class="w-100" style="max-height:80vh; background:#000;">
                        <source id="previewVideoSource">
                    </video>
                    <div class="card mt-2 border-0">
                        <div class="card-body p-2">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <button type="button" class="btn btn-primary btn-sm" onclick="togglePlay()">
                                    <i class="fa fa-play"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="stopVideo()">
                                    <i class="fa fa-stop"></i>
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="toggleMute()">
                                    <i class="fa fa-volume-up"></i>
                                </button>
                                <button type="button" class="btn btn-info btn-sm" onclick="skipBackward()">
                                    -10s
                                </button>
                                <button type="button" class="btn btn-info btn-sm" onclick="skipForward()">
                                    +10s
                                </button>
                                <input type="range" id="seekBar" min="0" max="100" value="0"
                                    class="form-range flex-grow-1">
                                <span id="videoTime" class="small fw-bold">
                                    0:00 / 0:00
                                </span>
                                <button type="button" class="btn btn-success btn-sm" onclick="fullscreenVideo()">
                                    <i class="fa fa-expand"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function openMediaPreview(url, type) {
        const image = document.getElementById('previewImage');
        const video = document.getElementById('previewVideo');
        const source = document.getElementById('previewVideoSource');
        const wrapper = document.getElementById('videoWrapper');
        image.classList.add('d-none');
        wrapper.classList.add('d-none');
        if (type === 'image') {
            image.src = url;
            image.classList.remove('d-none');
        } else {
            source.src = url;
            video.load();
            wrapper.classList.remove('d-none');
        }
        bootstrap.Modal
            .getOrCreateInstance(
                document.getElementById('mediaPreviewModal')
            )
            .show();
    }
    const video = document.getElementById('previewVideo');
    const seekBar = document.getElementById('seekBar');
    const videoTime = document.getElementById('videoTime');

    function togglePlay() {
        if (video.paused) {
            video.play();
        } else {
            video.pause();
        }
    }

    function stopVideo() {
        video.pause();
        video.currentTime = 0;
    }

    function toggleMute() {
        video.muted = !video.muted;
    }

    function skipForward() {
        video.currentTime += 10;
    }

    function skipBackward() {
        video.currentTime -= 10;
    }

    function fullscreenVideo() {
        if (video.requestFullscreen) {
            video.requestFullscreen();
        }
    }
    video.addEventListener('timeupdate', function() {
        if (video.duration) {
            seekBar.value =
                (video.currentTime / video.duration) * 100;
            videoTime.innerHTML =
                formatTime(video.currentTime) +
                ' / ' +
                formatTime(video.duration);
        }
    });
    seekBar.addEventListener('input', function() {
        if (video.duration) {
            video.currentTime =
                (seekBar.value / 100) * video.duration;
        }
    });

    function formatTime(seconds) {
        let minutes = Math.floor(seconds / 60);
        let secs = Math.floor(seconds % 60);
        return minutes + ':' +
            String(secs).padStart(2, '0');
    }
    document.getElementById('mediaPreviewModal')
        .addEventListener('hidden.bs.modal', function() {
            video.pause();
            video.currentTime = 0;
        });
</script> --}}
