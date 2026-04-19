@extends('admin.layout.layout')
@section('page_title', 'Reports')
@section('report_select', 'active')
@section('container')
    <style>
        /* ══════════════════════════════════════════════
                       DESIGN TOKENS
                    ══════════════════════════════════════════════ */
        :root {
            --gold: #c9922a;
            --gold-lt: #f5c96a;
            --gold-bg: #fdf8ee;
            --ink: #1a1f2e;
            --ink-soft: #252c3f;
            --muted: #6b7280;
            --border: #e0dbd0;
            --cream: #faf7f2;
            --card-bg: #ffffff;
            --danger: #dc3545;
            --success-bg: #d1fae5;
            --success-bdr: #6ee7b7;
            --success-txt: #065f46;
            --radius: 12px;
            --radius-sm: 8px;
            --tr: .2s cubic-bezier(.4, 0, .2, 1);
        }

        /* ── Base ── */
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--cream);
            color: var(--ink);
        }


        /* ══════════════════════════════════════════════
                       SECTION CARD
                    ══════════════════════════════════════════════ */
        .rev-card {
            background: var(--card-bg);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 1.7rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .05);
        }

        .rev-card-title {
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: .45rem;
        }

        .rev-card-title::before {
            content: '';
            width: 3px;
            height: 15px;
            background: var(--gold);
            border-radius: 2px;
            display: inline-block;
            flex-shrink: 0;
        }

        /* ══════════════════════════════════════════════
                       STAR RATING
                    ══════════════════════════════════════════════ */
        .star-group {
            display: flex;
            gap: .3rem;
            margin-bottom: .4rem;
        }

        .star-btn {
            background: none;
            border: none;
            font-size: 1.7rem;
            color: var(--border);
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: color .12s, transform .12s;
        }

        .star-btn.active {
            color: var(--gold-lt);
        }

        .star-btn.hovered {
            color: var(--gold-lt);
        }

        .star-btn:hover {
            transform: scale(1.1);
        }

        .star-label {
            font-size: .78rem;
            font-weight: 600;
            color: var(--muted);
            min-height: 1.1em;
            margin-bottom: .9rem;
        }

        /* ══════════════════════════════════════════════
                       FORM OVERRIDES
                    ══════════════════════════════════════════════ */
        .form-label-custom {
            display: block;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: .35rem;
        }

        .form-label-custom .req {
            color: var(--danger);
        }

        .form-label-custom .opt {
            font-size: .68rem;
            font-weight: 500;
            text-transform: none;
            letter-spacing: 0;
            color: var(--muted);
            margin-left: .25rem;
        }

        .form-control,
        .form-select {
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: 'Nunito', sans-serif;
            font-size: .88rem;
            color: var(--ink);
            background-color: #fff;
            transition: border-color var(--tr), box-shadow var(--tr);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201, 146, 42, .15);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: var(--danger);
            box-shadow: none;
        }

        .form-control[type="file"] {
            padding: .46rem .85rem;
        }

        /* ══════════════════════════════════════════════
                       DYNAMIC FIELD BLOCKS
                    ══════════════════════════════════════════════ */
        #fieldsContainer {
            display: flex;
            flex-direction: column;
            gap: .9rem;
        }

        .field-block {
            background: var(--cream);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 1.3rem 1.25rem;
            position: relative;
            animation: slideDown .26s cubic-bezier(.4, 0, .2, 1);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
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

        .field-num {
            width: 27px;
            height: 27px;
            border-radius: 50%;
            background: var(--gold);
            color: #fff;
            font-size: .72rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .field-title {
            font-size: .9rem;
            font-weight: 700;
            color: var(--ink);
        }

        .btn-remove-field {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #fff;
            border: 1.5px solid #fecaca;
            color: #ef4444;
            border-radius: 7px;
            padding: .26rem .75rem;
            font-family: 'Nunito', sans-serif;
            font-size: .72rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: .3rem;
            transition: all var(--tr);
        }

        .btn-remove-field:hover {
            background: #fef2f2;
            border-color: #fca5a5;
        }

        .char-count-text {
            font-size: .7rem;
            color: var(--muted);
        }

        /* ══════════════════════════════════════════════
                       ADD SECTION BUTTON
                    ══════════════════════════════════════════════ */
        .btn-add-section {
            width: 100%;
            border: 2px dashed var(--gold);
            background: transparent;
            border-radius: var(--radius);
            padding: .85rem;
            font-family: 'Nunito', sans-serif;
            font-size: .92rem;
            font-weight: 700;
            color: var(--gold);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            transition: background var(--tr), transform var(--tr), border-color var(--tr), color var(--tr);
            margin-top: .5rem;
            margin-bottom: 1.4rem;
        }

        .btn-add-section:hover {
            background: rgba(201, 146, 42, .07);
            transform: translateY(-1px);
        }

        .btn-add-section.shake {
            border-color: var(--danger);
            color: var(--danger);
        }

        /* ══════════════════════════════════════════════
                       FOOTER ACTIONS
                    ══════════════════════════════════════════════ */
        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1.5px dashed var(--border);
            padding-top: 1.2rem;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .btn-cancel-form {
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: .65rem 1.5rem;
            font-family: 'Nunito', sans-serif;
            font-size: .88rem;
            font-weight: 700;
            color: var(--muted);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: all var(--tr);
        }

        .btn-cancel-form:hover {
            border-color: #bbb;
            color: var(--ink);
        }

        .btn-submit-form {
            background: var(--ink);
            border: none;
            border-radius: var(--radius-sm);
            padding: .72rem 2rem;
            font-family: 'Nunito', sans-serif;
            font-size: .92rem;
            font-weight: 800;
            color: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            box-shadow: 0 2px 10px rgba(26, 31, 46, .2);
            transition: all var(--tr);
        }

        .btn-submit-form:hover {
            background: var(--ink-soft);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(26, 31, 46, .25);
        }

        .btn-submit-form:active {
            transform: none;
        }

        /* ══════════════════════════════════════════════
                       EMPTY STATE
                    ══════════════════════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--muted);
        }

        .empty-state .bi {
            font-size: 2.2rem;
            opacity: .3;
        }

        /* ══════════════════════════════════════════════
                       COUNTER PILL
                    ══════════════════════════════════════════════ */
        .count-pill {
            background: var(--gold);
            color: #fff;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 800;
            padding: .15rem .65rem;
            min-width: 26px;
            text-align: center;
            display: inline-block;
        }

        /* ══════════════════════════════════════════════
                       CUSTOM ALERTS
                    ══════════════════════════════════════════════ */
        .alert-success-custom {
            background: var(--success-bg);
            border: 1.5px solid var(--success-bdr);
            color: var(--success-txt);
            border-radius: var(--radius-sm);
            padding: .85rem 1.2rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-weight: 600;
            font-size: .88rem;
            margin-bottom: 1.2rem;
        }

        /* ══════════════════════════════════════════════
                       HELPER
                    ══════════════════════════════════════════════ */
        .divider-dashed {
            border: none;
            border-top: 1.5px dashed var(--border);
            margin: 1.1rem 0;
        }
    </style>
    <form action="
                {{-- {{ route('reviews.store') }} --}}
                 " method="POST" enctype="multipart/form-data"
        id="reviewForm" novalidate>
        @csrf

        {{-- ── Card 1: Rating + Title ── --}}
        <div class="card border mb-3">
        <div class="card-body">
            <div class="rev-card-title">Overall Rating</div>

            {{-- Star buttons --}}
            <div class="star-group" id="starGroup" role="group" aria-label="Star rating">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" class="star-btn" data-value="{{ $i }}"
                        aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}">&#9733;</button>
                @endfor
            </div>
            <div class="star-label" id="starLabel">Click to rate</div>
            <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 0) }}">

            {{-- Review title --}}
            <label class="form-label-custom" for="reviewTitle">
                Review Title <span class="opt">(optional)</span>
            </label>
            <input type="text" id="reviewTitle" name="title" class="form-control @error('title') is-invalid @enderror"
                placeholder="Summarise your experience in a few words…" value="{{ old('title') }}" maxlength="255">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        </div>

        {{-- ── Card 2: Dynamic Sections ── --}}
        <div class="card border mb-3">
        <div class="card-body">

            {{-- Header --}}
            <div class="d-flex align-items-center justify-content-between mb-1">
                <div class="rev-card-title mb-0">Review Sections</div>
                <span class="count-pill" id="sectionCountPill">0</span>
            </div>
            <p class="text-muted mb-3" style="font-size:.8rem">
                Add unlimited sections — choose a field type, enter a value, and describe it.
            </p>

            {{-- ── Field blocks live here ── --}}
            <div id="fieldsContainer">
                <div class="empty-state" id="emptyState">
                    <i class="bi bi-layout-text-sidebar-reverse d-block mb-2"></i>
                    <p class="small mb-0">
                        No sections yet. Click <strong>Add Section</strong> to begin.
                    </p>
                </div>
            </div>

            {{-- Add section button --}}
            <button type="button" class="btn-add-section" id="addSectionBtn">
                <i class="bi bi-plus-circle-fill"></i>
                Add Section
            </button>

            <hr class="divider-dashed">

            {{-- Footer: cancel + submit --}}
            <div class="form-footer">
                <button type="submit" class="btn-submit-form" id="submitBtn">
                    <i class="bi bi-send-fill"></i> Submit Review
                </button>
            </div>
        </div>
        </div>

    </form>

    {{-- ══════════════════════════════════════════════
     FIELD BLOCK HTML TEMPLATE (hidden by browser)
══════════════════════════════════════════════ --}}
    <template id="fieldBlockTemplate">
        <div class="field-block" data-block-index="[[IDX]]">

            {{-- Remove button --}}
            <button type="button" class="btn-remove-field js-remove">
                <i class="bi bi-x-lg"></i> Remove
            </button>

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-3 pe-5">
                <span class="field-num js-num">[[NUM]]</span>
                <span class="field-title js-title">Section [[NUM]]</span>
            </div>

            {{-- Row: type selector + value input --}}
            <div class="row g-3 mb-3">

                {{-- Type selector --}}
                <div class="col-sm-5">
                    <label class="form-label-custom">
                        Field Type <span class="req">*</span>
                    </label>
                    <select name="fields[[[IDX]]][field_type]" class="form-select js-type" required>
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

                {{-- Value (swapped dynamically on type change) --}}
                <div class="col-sm-7">
                    <label class="form-label-custom">
                        Value <span class="req">*</span>
                    </label>
                    <div class="js-value-wrap">
                        <input type="text" class="form-control" placeholder="Select a type first…" disabled
                            style="background:#f9f6f0">
                    </div>
                    <div class="invalid-feedback d-none js-value-err">Please enter a value.</div>
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="form-label-custom">
                    Description <span class="req">*</span>
                </label>
                <textarea name="fields[[[IDX]]][description]" class="form-control js-desc" rows="2"
                    placeholder="Provide context or detail about this section…" required maxlength="1000"></textarea>
                <div class="invalid-feedback">Description is required.</div>
                <div class="d-flex justify-content-end mt-1">
                    <span class="char-count-text js-chars">0 / 1000</span>
                </div>
            </div>

        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function() {
            'use strict';

            /* ─────────────────────────────────────────────────
               STATE
            ───────────────────────────────────────────────── */
            let globalIdx = 0; // monotonically increasing index for field names

            /* ─────────────────────────────────────────────────
               DOM REFS
            ───────────────────────────────────────────────── */
            const form = document.getElementById('reviewForm');
            const container = document.getElementById('fieldsContainer');
            const emptyState = document.getElementById('emptyState');
            const addBtn = document.getElementById('addSectionBtn');
            const countPill = document.getElementById('sectionCountPill');
            const template = document.getElementById('fieldBlockTemplate');

            /* ─────────────────────────────────────────────────
               STAR RATING
            ───────────────────────────────────────────────── */
            const starBtns = document.querySelectorAll('.star-btn');
            const starLabel = document.getElementById('starLabel');
            const ratingInput = document.getElementById('ratingInput');
            const STAR_LABELS = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent!'];
            let currentRating = parseInt(ratingInput.value) || 0;

            if (currentRating > 0) highlightStars(currentRating, false);

            starBtns.forEach(btn => {
                btn.addEventListener('mouseenter', () => highlightStars(+btn.dataset.value, true));
                btn.addEventListener('mouseleave', () => highlightStars(currentRating, false));
                btn.addEventListener('click', () => {
                    currentRating = +btn.dataset.value;
                    ratingInput.value = currentRating;
                    starLabel.textContent = STAR_LABELS[currentRating];
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

            /* ─────────────────────────────────────────────────
               BUILD VALUE INPUT
            ───────────────────────────────────────────────── */
            function buildValueInput(type, idx) {
                const name = `fields[${idx}][field_value]`;

                if (type === 'file') {
                    return `<input type="file"
                        name="fields[${idx}][file]"
                        class="form-control js-value-input"
                        required>`;
                }

                if (type === 'textarea') {
                    return `<textarea
                        name="${name}"
                        class="form-control js-value-input"
                        rows="2"
                        placeholder="Write in detail…"
                        required></textarea>`;
                }

                const map = {
                    text: {
                        inputType: 'text',
                        ph: 'Enter text…'
                    },
                    number: {
                        inputType: 'number',
                        ph: 'Enter a number…'
                    },
                    email: {
                        inputType: 'email',
                        ph: 'you@example.com'
                    },
                    url: {
                        inputType: 'url',
                        ph: 'https://example.com'
                    },
                    date: {
                        inputType: 'date',
                        ph: ''
                    },
                };
                const cfg = map[type] || {
                    inputType: 'text',
                    ph: 'Enter value…'
                };

                return `<input
                    type="${cfg.inputType}"
                    name="${name}"
                    class="form-control js-value-input"
                    placeholder="${cfg.ph}"
                    required>`;
            }

            /* ─────────────────────────────────────────────────
               ADD SECTION
            ───────────────────────────────────────────────── */
            function addSection() {
                const num = document.querySelectorAll('.field-block').length + 1;
                const idx = globalIdx++;

                const html = template.innerHTML
                    .replaceAll('[[IDX]]', idx)
                    .replaceAll('[[NUM]]', num);

                const wrapper = document.createElement('div');
                wrapper.innerHTML = html;
                const block = wrapper.firstElementChild;

                /* Type change → swap value input */
                const typeSelect = block.querySelector('.js-type');
                const valueWrap = block.querySelector('.js-value-wrap');
                const valueErr = block.querySelector('.js-value-err');

                typeSelect.addEventListener('change', function() {
                    valueWrap.innerHTML = buildValueInput(this.value, idx);
                    valueErr.classList.add('d-none');
                    this.classList.remove('is-invalid');
                });

                /* Char counter */
                const descTA = block.querySelector('.js-desc');
                const chars = block.querySelector('.js-chars');
                descTA.addEventListener('input', () => {
                    chars.textContent = `${descTA.value.length} / 1000`;
                });

                /* Remove */
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

            /* ─────────────────────────────────────────────────
               REFRESH STATE (counter, numbering, empty)
            ───────────────────────────────────────────────── */
            function refreshState() {
                const blocks = document.querySelectorAll('.field-block');
                countPill.textContent = blocks.length;
                emptyState.style.display = blocks.length === 0 ? 'block' : 'none';

                blocks.forEach((b, i) => {
                    const n = i + 1;
                    b.querySelector('.js-num').textContent = n;
                    b.querySelector('.js-title').textContent = `Section ${n}`;
                });
            }

            /* ─────────────────────────────────────────────────
               CLIENT-SIDE VALIDATION
            ───────────────────────────────────────────────── */
            form.addEventListener('submit', function(e) {
                const blocks = document.querySelectorAll('.field-block');

                /* Must have at least one section */
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

                let formValid = true;

                blocks.forEach(block => {
                    const typeSelect = block.querySelector('.js-type');
                    const valueWrap = block.querySelector('.js-value-wrap');
                    const valueInput = valueWrap.querySelector('.js-value-input');
                    const descTA = block.querySelector('.js-desc');
                    const valueErr = block.querySelector('.js-value-err');

                    /* Field type */
                    if (!typeSelect.value) {
                        typeSelect.classList.add('is-invalid');
                        formValid = false;
                    } else {
                        typeSelect.classList.remove('is-invalid');
                    }

                    /* Value */
                    if (valueInput && valueInput.type !== 'file') {
                        if (!valueInput.value.trim()) {
                            valueInput.classList.add('is-invalid');
                            valueErr.classList.remove('d-none');
                            formValid = false;
                        } else {
                            valueInput.classList.remove('is-invalid');
                            valueErr.classList.add('d-none');
                        }
                    } else if (!valueInput && typeSelect.value && typeSelect.value !== 'file') {
                        /* Type chosen but value box not rendered */
                        typeSelect.classList.add('is-invalid');
                        formValid = false;
                    }

                    /* Description */
                    if (!descTA.value.trim()) {
                        descTA.classList.add('is-invalid');
                        formValid = false;
                    } else {
                        descTA.classList.remove('is-invalid');
                    }
                });

                if (!formValid) {
                    e.preventDefault();
                    const firstBad = document.querySelector('.is-invalid');
                    if (firstBad) firstBad.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });

            /* ─────────────────────────────────────────────────
               INIT
            ───────────────────────────────────────────────── */
            addBtn.addEventListener('click', addSection);
            refreshState();

        })();
    </script>
@endsection
