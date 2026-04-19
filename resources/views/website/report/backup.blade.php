{{-- resources/views/reviews/create.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Write a Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* Star rating */
        .star-btn {
            background: none;
            border: none;
            font-size: 1.7rem;
            color: #dee2e6;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: color .12s, transform .12s;
        }

        .star-btn.active,
        .star-btn.hovered {
            color: #ffc107;
        }

        .star-btn:hover {
            transform: scale(1.1);
        }

        /* Field block animation */
        .field-block {
            animation: slideDown .22s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .field-block.is-removing {
            opacity: 0;
            transform: translateY(-6px);
            transition: all .2s ease;
        }

        /* Add section dashed button */
        .btn-add-section {
            width: 100%;
            border: 2px dashed #ffc107;
            background: transparent;
            border-radius: 8px;
            padding: .8rem;
            font-size: .92rem;
            font-weight: 700;
            color: #b78800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            transition: background .2s;
        }

        .btn-add-section:hover {
            background: rgba(255, 193, 7, .08);
        }

        .btn-add-section.shake {
            border-color: #dc3545;
            color: #dc3545;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-4" style="max-width:680px;">

        <h4 class="fw-bold mb-1">Write a Review</h4>
        <p class="text-muted small mb-4">Share your experience about the product.</p>

        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" id="reviewForm"
            novalidate>
            @csrf
            {{-- ── Card 1: Rating + Title ── --}}
            <div class="card border mb-3">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted fw-bold small mb-3">Overall Rating</h6>

                    <div class="d-flex gap-1 mb-1" id="starGroup" role="group" aria-label="Star rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn" data-value="{{ $i }}"
                                aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}">&#9733;</button>
                        @endfor
                    </div>
                    <div class="text-muted small mb-3" id="starLabel">Click to rate</div>
                    <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 0) }}">

                    <label class="form-label fw-semibold small">Review Title <span
                            class="text-muted fw-normal">(optional)</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        placeholder="Summarise your experience…" value="{{ old('title') }}" maxlength="255">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ── Card 2: Dynamic Sections ── --}}
            <div class="card border mb-3">
                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-uppercase text-muted fw-bold small mb-0">Review Sections</h6>
                        <span class="badge bg-warning text-dark" id="sectionCountPill">0</span>
                    </div>
                    <p class="text-muted small mb-3">Add sections — choose a field type, enter a value, and describe it.
                    </p>

                    {{-- Field blocks --}}
                    <div id="fieldsContainer">
                        <div class="text-center text-muted py-4" id="emptyState">
                            <i class="bi bi-layout-text-sidebar-reverse fs-2 opacity-25 d-block mb-2"></i>
                            <p class="small mb-0">No sections yet. Click <strong>Add Section</strong> to begin.</p>
                        </div>
                    </div>

                    <button type="button" class="btn-add-section mt-2 mb-4" id="addSectionBtn">
                        <i class="bi bi-plus-circle-fill"></i> Add Section
                    </button>

                    <hr class="border-top border-dashed">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2">
                        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-dark btn-sm px-4" id="submitBtn">
                            <i class="bi bi-send-fill"></i> Submit Review
                        </button>
                    </div>

                </div>
            </div>

        </form>
    </div>


    {{-- Field block template --}}
    <template id="fieldBlockTemplate">
        <div class="field-block card bg-light border mb-3 p-3 position-relative" data-block-index="[[IDX]]">

            <button type="button" class="btn btn-outline-danger btn-sm js-remove position-absolute top-0 end-0 m-2"
                style="font-size:.72rem;">
                <i class="bi bi-x-lg"></i> Remove
            </button>

            <div class="d-flex align-items-center gap-2 mb-3 pe-5">
                <span class="badge bg-warning text-dark js-num">[[NUM]]</span>
                <span class="fw-bold small js-title">Section [[NUM]]</span>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-5">
                    <label class="form-label fw-semibold small">Field Type <span class="text-danger">*</span></label>
                    <select name="fields[[[IDX]]][field_type]" class="form-select form-select-sm js-type" required>
                        <option value="" disabled selected>— Select type —</option>
                        <option value="text">📝 Text</option>
                        <option value="number">🔢 Number</option>
                        <option value="file">📎 File / Image</option>
                        <option value="email">✉️ Email</option>
                        <option value="url">🔗 URL / Link</option>
                        <option value="date">📅 Date</option>
                        <option value="textarea">📄 Long Text</option>
                    </select>
                    <div class="invalid-feedback">Please select a field type.</div>
                </div>
                <div class="col-sm-7">
                    <label class="form-label fw-semibold small">Value <span class="text-danger">*</span></label>
                    <div class="js-value-wrap">
                        <input type="text" class="form-control form-control-sm" placeholder="Select a type first…"
                            disabled style="background:#f9f6f0">
                    </div>
                    <div class="invalid-feedback d-none js-value-err">Please enter a value.</div>
                </div>
            </div>

            <div>
                <label class="form-label fw-semibold small">Description <span class="text-danger">*</span></label>
                <textarea name="fields[[[IDX]]][description]" class="form-control form-control-sm js-desc" rows="2"
                    placeholder="Provide context or detail…" required maxlength="1000"></textarea>
                <div class="invalid-feedback">Description is required.</div>
                <div class="text-end mt-1">
                    <span class="text-muted small js-chars">0 / 1000</span>
                </div>
            </div>

        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            'use strict';

            let globalIdx = 0;

            const form = document.getElementById('reviewForm');
            const container = document.getElementById('fieldsContainer');
            const emptyState = document.getElementById('emptyState');
            const addBtn = document.getElementById('addSectionBtn');
            const countPill = document.getElementById('sectionCountPill');
            const template = document.getElementById('fieldBlockTemplate');

            /* ── Star rating ── */
            const starBtns = document.querySelectorAll('.star-btn');
            const starLabel = document.getElementById('starLabel');
            const ratingInput = document.getElementById('ratingInput');
            const LABELS = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent!'];
            let currentRating = parseInt(ratingInput.value) || 0;

            if (currentRating > 0) highlightStars(currentRating, false);

            starBtns.forEach(btn => {
                btn.addEventListener('mouseenter', () => highlightStars(+btn.dataset.value, true));
                btn.addEventListener('mouseleave', () => highlightStars(currentRating, false));
                btn.addEventListener('click', () => {
                    currentRating = +btn.dataset.value;
                    ratingInput.value = currentRating;
                    starLabel.textContent = LABELS[currentRating];
                    highlightStars(currentRating, false);
                });
            });

            function highlightStars(upTo, hover) {
                starBtns.forEach(b => {
                    const v = +b.dataset.value;
                    b.classList.toggle('active', !hover && v <= upTo);
                    b.classList.toggle('hovered', hover && v <= upTo);
                });
            }

            /* ── Build value input ── */
            function buildValueInput(type, idx) {
                const name = `fields[${idx}][field_value]`;
                if (type === 'file')
                return `<input type="file" name="fields[${idx}][file]" class="form-control form-control-sm js-value-input" required>`;
                if (type === 'textarea')
                return `<textarea name="${name}" class="form-control form-control-sm js-value-input" rows="2" placeholder="Write in detail…" required></textarea>`;
                const map = {
                    text: {
                        t: 'text',
                        ph: 'Enter text…'
                    },
                    number: {
                        t: 'number',
                        ph: 'Enter a number…'
                    },
                    email: {
                        t: 'email',
                        ph: 'you@example.com'
                    },
                    url: {
                        t: 'url',
                        ph: 'https://example.com'
                    },
                    date: {
                        t: 'date',
                        ph: ''
                    },
                };
                const c = map[type] || {
                    t: 'text',
                    ph: 'Enter value…'
                };
                return `<input type="${c.t}" name="${name}" class="form-control form-control-sm js-value-input" placeholder="${c.ph}" required>`;
            }

            /* ── Add section ── */
            function addSection() {
                const num = document.querySelectorAll('.field-block').length + 1;
                const idx = globalIdx++;
                const html = template.innerHTML.replaceAll('[[IDX]]', idx).replaceAll('[[NUM]]', num);
                const wrap = document.createElement('div');
                wrap.innerHTML = html;
                const block = wrap.firstElementChild;

                const typeSelect = block.querySelector('.js-type');
                const valueWrap = block.querySelector('.js-value-wrap');
                const valueErr = block.querySelector('.js-value-err');

                typeSelect.addEventListener('change', function() {
                    valueWrap.innerHTML = buildValueInput(this.value, idx);
                    valueErr.classList.add('d-none');
                    this.classList.remove('is-invalid');
                });

                const descTA = block.querySelector('.js-desc');
                const chars = block.querySelector('.js-chars');
                descTA.addEventListener('input', () => {
                    chars.textContent = `${descTA.value.length} / 1000`;
                });

                block.querySelector('.js-remove').addEventListener('click', () => {
                    block.classList.add('is-removing');
                    setTimeout(() => {
                        block.remove();
                        refreshState();
                    }, 220);
                });

                container.appendChild(block);
                refreshState();
                block.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }

            /* ── Refresh ── */
            function refreshState() {
                const blocks = document.querySelectorAll('.field-block');
                countPill.textContent = blocks.length;
                emptyState.style.display = blocks.length === 0 ? 'block' : 'none';
                blocks.forEach((b, i) => {
                    b.querySelector('.js-num').textContent = i + 1;
                    b.querySelector('.js-title').textContent = `Section ${i + 1}`;
                });
            }

            /* ── Validation ── */
            form.addEventListener('submit', function(e) {
                const blocks = document.querySelectorAll('.field-block');
                if (blocks.length === 0) {
                    e.preventDefault();
                    addBtn.classList.add('shake');
                    addBtn.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    setTimeout(() => addBtn.classList.remove('shake'), 1800);
                    return;
                }

                let valid = true;
                blocks.forEach(block => {
                    const typeSelect = block.querySelector('.js-type');
                    const valueWrap = block.querySelector('.js-value-wrap');
                    const valueInput = valueWrap.querySelector('.js-value-input');
                    const descTA = block.querySelector('.js-desc');
                    const valueErr = block.querySelector('.js-value-err');

                    if (!typeSelect.value) {
                        typeSelect.classList.add('is-invalid');
                        valid = false;
                    } else {
                        typeSelect.classList.remove('is-invalid');
                    }

                    if (valueInput && valueInput.type !== 'file') {
                        if (!valueInput.value.trim()) {
                            valueInput.classList.add('is-invalid');
                            valueErr.classList.remove('d-none');
                            valid = false;
                        } else {
                            valueInput.classList.remove('is-invalid');
                            valueErr.classList.add('d-none');
                        }
                    }

                    if (!descTA.value.trim()) {
                        descTA.classList.add('is-invalid');
                        valid = false;
                    } else {
                        descTA.classList.remove('is-invalid');
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    document.querySelector('.is-invalid')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });

            addBtn.addEventListener('click', addSection);
            refreshState();
        })();
    </script>

</body>

</html>
