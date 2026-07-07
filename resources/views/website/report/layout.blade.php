{{-- resources/views/reviews/create.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Write a Review – ShopStar</title>

    {{-- ── CDN: Bootstrap 5.3 ── --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- ── CDN: Bootstrap Icons ── --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- ── Google Fonts ── --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

        /* ══════════════════════════════════════════════
           HEADER
        ══════════════════════════════════════════════ */
        .site-header {
            background: #fff;
            border-bottom: 1.5px solid var(--border);
            padding: .9rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
        }

        .header-inner {
            max-width: 760px;
            margin: 0 auto;
            padding: 0 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: .55rem;
            text-decoration: none;
        }

        .header-brand-icon {
            width: 36px;
            height: 36px;
            background: var(--gold);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .header-brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
        }

        .header-brand-tag {
            font-size: .65rem;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: .04em;
            line-height: 1;
            margin-top: 2px;
        }

        .header-meta {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .header-product-pill {
            background: var(--gold-bg);
            border: 1.5px solid var(--border);
            border-radius: 30px;
            padding: .3rem .85rem;
            font-size: .72rem;
            font-weight: 700;
            color: var(--gold);
            display: flex;
            align-items: center;
            gap: .3rem;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-step {
            font-size: .72rem;
            font-weight: 700;
            color: var(--muted);
            white-space: nowrap;
        }

        /* ══════════════════════════════════════════════
           PROGRESS BAR
        ══════════════════════════════════════════════ */
        .progress-bar-wrap {
            height: 3px;
            background: var(--border);
        }

        .progress-bar-fill {
            height: 100%;
            background: var(--gold);
            width: 0%;
            transition: width .3s ease;
        }

        /* ══════════════════════════════════════════════
           MAIN WRAPPER
        ══════════════════════════════════════════════ */
        .page-wrapper {
            max-width: 760px;
            margin: 2rem auto;
            padding: 0 1.25rem 6rem;
        }

        .page-heading {
            margin-bottom: 1.5rem;
        }

        .page-heading h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: .3rem;
        }

        .page-heading p {
            font-size: .85rem;
            color: var(--muted);
            margin: 0;
        }

        /* ══════════════════════════════════════════════
           FOOTER
        ══════════════════════════════════════════════ */
        .site-footer {
            background: var(--ink);
            color: #9ca3af;
            padding: 2rem 0 1.5rem;
            margin-top: 3rem;
        }

        .footer-inner {
            max-width: 760px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        .footer-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            margin-bottom: 1.2rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .footer-brand-icon {
            width: 32px;
            height: 32px;
            background: var(--gold);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .footer-brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
        }

        .footer-tagline {
            font-size: .75rem;
            color: #6b7280;
            margin-top: .2rem;
        }

        .footer-links {
            display: flex;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            font-size: .78rem;
            font-weight: 600;
            color: #9ca3af;
            text-decoration: none;
            transition: color .15s;
        }

        .footer-links a:hover {
            color: var(--gold-lt);
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .footer-copy {
            font-size: .72rem;
            color: #6b7280;
        }

        .footer-secure {
            display: flex;
            align-items: center;
            gap: .3rem;
            font-size: .72rem;
            color: #6b7280;
        }

        .footer-secure .bi {
            color: var(--gold);
        }
    </style>
</head>

<body>
    <!-- ══ HEADER ══════════════════════════════════════ -->
    <header class="site-header">
        <div class="header-inner">

            <!-- Brand -->
            <a href="/" class="header-brand">
                <div class="header-brand-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div>
                    <div class="header-brand-name">ShopStar</div>
                    <div class="header-brand-tag">Verified Reviews</div>
                </div>
            </a>

            <!-- Meta -->
            <div class="header-meta">
                <div class="header-product-pill">
                    <i class="bi bi-box-seam"></i>
                    <span id="headerProductName">Product Review</span>
                </div>
                <span class="header-step">Step 1 of 1</span>
            </div>

        </div>

        <!-- Progress bar -->
        <div class="progress-bar-wrap">
            <div class="progress-bar-fill" id="progressBar"></div>
        </div>
    </header>


    @section('reportcontainer')
    @show

    <!-- ══ FOOTER ══════════════════════════════════════ -->
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-top">

                <!-- Brand -->
                <div>
                    <div class="footer-brand">
                        <div class="footer-brand-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div class="footer-brand-name">ShopStar</div>
                    </div>
                    <div class="footer-tagline">Honest reviews from real customers.</div>
                </div>

                <!-- Links -->
                <nav class="footer-links" aria-label="Footer navigation">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Use</a>
                    <a href="#">Help Centre</a>
                    <a href="#">Contact Us</a>
                </nav>

            </div>

            <div class="footer-bottom">
                <span class="footer-copy">
                    &copy; {{ date('Y') }} ShopStar. All rights reserved.
                </span>
                <span class="footer-secure">
                    <i class="bi bi-shield-lock-fill"></i>
                    Secured &amp; encrypted
                </span>
            </div>
        </div>
    </footer>
</body>

</html>
