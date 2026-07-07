@extends('website.report.layout')
@section('reportcontainer')
    <!-- ══ MAIN ════════════════════════════════════════ -->
    <main class="page-wrapper">

        <div class="page-heading d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1>Write a Review</h1>
                <p>Share your honest experience — your feedback helps other shoppers.</p>
            </div>

            <a href="{{ route('report.show') }}" class="btn btn-dark">
                <i class="bi bi-file-earmark-text me-1"></i>
                View Reports
            </a>
        </div>
        @include('admin.include.notify')
        <form action="
                {{ route('report.store') }}
                "method="POST"
            enctype="multipart/form-data" id="reviewForm" novalidate>
            @csrf
            <input type="hidden" name="product_id" value="">
            {{-- ── Card 0: Your Details ── --}}
            <div class="rev-card">
                <div class="rev-card-title">Your Details</div>
                <p class="text-muted mb-3" style="font-size:.8rem">
                    Tell us who you are — this helps us attach your report to the right account.
                </p>

                <div class="row g-3">

                    {{-- Account Type --}}
                    <div class="col-sm-4">
                        <label class="form-label-custom" for="userType">
                            Account Type <span class="req">*</span>
                        </label>
                        <select name="user_type" id="userType" class="form-select @error('user_type') is-invalid @enderror"
                            required>
                            <option value="" disabled selected>— Select —</option>
                            <option value="admin" {{ old('user_type') === 'admin' ? 'selected' : '' }}>Admin
                            </option>
                            <option value="vendor" {{ old('user_type') === 'vendor' ? 'selected' : '' }}>Vendor
                            </option>
                            <option value="customer" {{ old('user_type') === 'customer' ? 'selected' : '' }}>Customer
                            </option>
                        </select>
                        @error('user_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- User ID --}}
                    <div class="col-sm-4">
                        <label class="form-label-custom" for="userId">
                            User ID <span class="req">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"
                                style="border:1.5px solid var(--border);border-right:none;background:#fff;">
                                <i class="bi bi-person-badge" style="color:var(--muted);"></i>
                            </span>
                            <input type="text" name="user_id" id="userId"
                                class="form-control @error('user_id') is-invalid @enderror" style="border-left:none;"
                                placeholder="e.g. 42" value="{{ old('user_id') }}" required min="1">
                        </div>
                        @error('user_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-sm-4">
                        <label class="form-label-custom" for="authEmail">
                            Email <span class="req">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"
                                style="border:1.5px solid var(--border);border-right:none;background:#fff;">
                                <i class="bi bi-envelope" style="color:var(--muted);"></i>
                            </span>
                            <input type="email" name="auth_email" id="authEmail"
                                class="form-control @error('auth_email') is-invalid @enderror" style="border-left:none;"
                                placeholder="you@example.com" value="{{ old('auth_email') }}" required autocomplete="email">
                        </div>
                        @error('auth_email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="col-sm-6">
                        <label class="form-label-custom" for="authPassword">
                            Password <span class="req">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"
                                style="border:1.5px solid var(--border);border-right:none;background:#fff;">
                                <i class="bi bi-lock" style="color:var(--muted);"></i>
                            </span>
                            <input type="password" name="auth_password" id="authPassword"
                                class="form-control @error('auth_password') is-invalid @enderror"
                                style="border-left:none;border-right:none;" placeholder="••••••••" required
                                autocomplete="current-password">
                            <button type="button" class="input-group-text"
                                style="border:1.5px solid var(--border);border-left:none;
                           background:#fff;cursor:pointer;"
                                onclick="togglePassword()" aria-label="Toggle password visibility">
                                <i class="bi bi-eye" id="pwdEyeIcon" style="color:var(--muted);"></i>
                            </button>
                        </div>
                        @error('auth_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Name (optional) --}}
                    <div class="col-sm-6">
                        <label class="form-label-custom" for="userName">
                            Full Name <span class="opt">(optional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"
                                style="border:1.5px solid var(--border);border-right:none;background:#fff;">
                                <i class="bi bi-person" style="color:var(--muted);"></i>
                            </span>
                            <input type="text" name="user_name" id="userName" class="form-control"
                                style="border-left:none;" placeholder="John Doe" value="{{ old('user_name') }}"
                                autocomplete="name">
                        </div>
                    </div>

                </div>
            </div>
            {{-- ── Card 1: Rating + Title ── --}}
            <div class="rev-card">
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
                <input type="text" id="reviewTitle" name="title"
                    class="form-control @error('title') is-invalid @enderror"
                    placeholder="Summarise your experience in a few words…" value="{{ old('title') }}" maxlength="255">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- ── Card 2: Dynamic Sections ── --}}
            <div class="rev-card">

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
                    <a href="javascript:history.back()" class="btn-cancel-form">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn-submit-form" id="submitBtn">
                        <i class="bi bi-send-fill"></i> Submit Review
                    </button>
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
    </main>



    {{-- ══════════════════════════════════════════════
     BOOTSTRAP 5 JS (CDN)
══════════════════════════════════════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword() {
            const input = document.getElementById('authPassword')
            const icon = document.getElementById('pwdEyeIcon')
            if (input.type === 'password') {
                input.type = 'text'
                icon.className = 'bi bi-eye-slash'
            } else {
                input.type = 'password'
                icon.className = 'bi bi-eye'
            }
        }
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

        // Progress bar — updates as sections are added
        function updateProgress() {
            const blocks = document.querySelectorAll('.field-block').length
            const rating = parseInt(document.getElementById('ratingInput').value) || 0
            const hasRating = rating > 0 ? 1 : 0
            const score = Math.min(100, (hasRating * 40) + Math.min(blocks, 3) * 20)
            document.getElementById('progressBar').style.width = score + '%'
        }

        // Hook into existing addSection / remove / star click
        document.getElementById('addSectionBtn').addEventListener('click', updateProgress)
        document.querySelectorAll('.star-btn').forEach(b =>
            b.addEventListener('click', updateProgress)
        )
        document.addEventListener('click', e => {
            if (e.target.closest('.js-remove')) {
                setTimeout(updateProgress, 250)
            }
        })
    </script>
@endsection
