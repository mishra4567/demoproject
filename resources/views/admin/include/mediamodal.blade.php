{{--
    Select Media Modal
--}}
<div class="modal fade" id="mediaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Select Product Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row">

                    @foreach ($media as $item)
                        <div class="col-md-3 mb-4 text-center">

                            <div class="media-card border p-2 rounded" style="cursor:pointer;">
                                <input type="radio" name="selected_media" value="{{ $item->id }}"
                                    data-file="{{ $item->file_name }}" class="form-check-input mb-2">
                                <img src="{{ asset('storage/media/' . $item->file_name) }}" class="img-fluid"
                                    style="height:120px; object-fit:cover;">

                                <div class="mt-2 small">
                                    {{ $item->file_name }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>
                <div class="text-center">
                    <button type="button" class="btn btn-primary" id="addMediaButton" onclick="addMediaButton()">
                        Add Button
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
