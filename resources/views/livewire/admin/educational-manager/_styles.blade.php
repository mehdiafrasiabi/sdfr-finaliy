@once
    @push('link')
        <style>
            .em-page {
                --em-surface: var(--bs-body-bg);
                --em-soft: var(--bs-tertiary-bg);
                --em-border: var(--bs-border-color);
                --em-text: var(--bs-body-color);
                --em-muted: var(--bs-secondary-color);
                --em-shadow: 0 12px 34px rgba(15, 23, 42, .07);
                direction: rtl;
                text-align: right;
                color: var(--em-text);
                min-width: 0;
            }

            [data-bs-theme="dark"] .em-page,
            html.dark .em-page {
                --em-surface: #17191d;
                --em-soft: #202329;
                --em-border: rgba(255, 255, 255, .11);
                --em-text: #f1f5f9;
                --em-muted: #a8b0bd;
                --em-shadow: 0 16px 42px rgba(0, 0, 0, .24);
            }

            .em-page *, .em-page *::before, .em-page *::after { box-sizing: border-box; }
            .em-page .app-page-head { margin-bottom: 14px; }
            .em-page .breadcrumb { margin: 0; padding: 10px 14px; border: 1px solid var(--em-border); border-radius: 11px; background: var(--em-surface); color: var(--em-muted); }
            .em-page .breadcrumb a { color: var(--bs-primary); text-decoration: none; }

            .em-page .em-hero {
                display: flex; align-items: center; justify-content: space-between; gap: 16px;
                margin-bottom: 18px; padding: 19px 20px; border: 1px solid var(--em-border);
                border-radius: 14px; background: linear-gradient(135deg, color-mix(in srgb, var(--bs-primary) 9%, var(--em-surface)), var(--em-surface));
                box-shadow: var(--em-shadow); overflow: hidden;
            }
            .em-page .em-hero-main { display: flex; align-items: center; gap: 13px; min-width: 0; }
            .em-page .em-hero-icon { width: 48px; height: 48px; flex: 0 0 48px; display: grid; place-items: center; border-radius: 12px; color: #fff; background: linear-gradient(135deg, #0d6efd, #6f42c1); font-size: 1.15rem; }
            .em-page .em-hero h3, .em-page .em-hero h4 { margin: 0 0 4px; color: var(--em-text); }
            .em-page .em-hero p { margin: 0; color: var(--em-muted); font-size: .8rem; line-height: 1.8; }

            .em-page .statbox.widget,
            .em-page .card {
                color: var(--em-text); background: var(--em-surface); border: 1px solid var(--em-border) !important;
                border-radius: 14px; box-shadow: var(--em-shadow) !important; overflow: hidden;
            }
            .em-page .card[style*="border-right-width"] { border-right-width: 5px !important; }
            .em-page .card.border-primary { border-color: rgba(var(--bs-primary-rgb), 1) !important; }
            .em-page .card.border-success { border-color: rgba(var(--bs-success-rgb), 1) !important; }
            .em-page .card.border-warning { border-color: rgba(var(--bs-warning-rgb), 1) !important; }
            .em-page .card.border-danger { border-color: rgba(var(--bs-danger-rgb), 1) !important; }
            .em-page .card.border-secondary { border-color: rgba(var(--bs-secondary-rgb), 1) !important; }
            .em-page .widget-header { padding: 16px 18px; background: var(--em-soft); border-bottom: 1px solid var(--em-border); }
            .em-page .widget-header h4, .em-page .widget-header h5, .em-page .card h4, .em-page .card h5, .em-page .card h6 { color: var(--em-text); }
            .em-page .widget-content.widget-content-area { padding: 18px; background: var(--em-surface); }
            .em-page .card-header, .em-page .modal-header, .em-page .modal-footer { color: var(--em-text); background: var(--em-soft); border-color: var(--em-border); }
            .em-page .card-body, .em-page .modal-body, .em-page .modal-content { color: var(--em-text); background: var(--em-surface); }
            .em-page .border, .em-page .border-bottom, .em-page hr { border-color: var(--em-border) !important; }
            .em-page .text-muted, .em-page .text-body-secondary { color: var(--em-muted) !important; }

            .em-page .form-control, .em-page .form-select {
                min-height: 42px; color: var(--em-text); background-color: var(--em-surface); border-color: var(--em-border); border-radius: 9px;
            }
            .em-page .form-control-sm, .em-page .form-select-sm { min-height: 36px; }
            .em-page .form-control::placeholder { color: color-mix(in srgb, var(--em-muted) 75%, transparent); }
            .em-page .form-control:focus, .em-page .form-select:focus { border-color: color-mix(in srgb, var(--bs-primary) 65%, var(--em-border)); box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .12); }
            .em-page .form-label { color: var(--em-text); font-size: .8rem; font-weight: 700; }
            .em-page .form-check-input { background-color: var(--em-surface); border-color: var(--em-border); }
            .em-page .btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; border-radius: 9px; min-height: 38px; }

            .em-page .table-responsive { border: 1px solid var(--em-border); border-radius: 11px; scrollbar-width: thin; scrollbar-color: var(--em-border) transparent; }
            .em-page .table { --bs-table-bg: transparent; --bs-table-color: var(--em-text); --bs-table-border-color: var(--em-border); margin: 0; min-width: 760px; }
            .em-page .table > :not(caption) > * > * { padding: 12px; vertical-align: middle; border-color: var(--em-border); }
            .em-page .table thead th { position: sticky; top: 0; z-index: 2; color: var(--em-text); background: var(--em-soft); font-size: .76rem; white-space: nowrap; }
            .em-page .table-hover > tbody > tr:hover > * { --bs-table-bg-state: color-mix(in srgb, var(--bs-primary) 5%, transparent); }
            .em-page .table-warning > * { --bs-table-bg: color-mix(in srgb, #ffc107 10%, var(--em-surface)); color: var(--em-text); }
            .em-page .badge { line-height: 1.45; border-radius: 999px; padding: .35rem .55rem; }
            [data-bs-theme="dark"] .em-page .bg-light, html.dark .em-page .bg-light { color: var(--em-text) !important; background: var(--em-soft) !important; }

            .em-page .nav-pills { flex-wrap: nowrap; overflow-x: auto; padding-bottom: 5px; }
            .em-page .nav-pills .nav-link { white-space: nowrap; border: 1px solid var(--em-border); border-radius: 9px; color: var(--em-text); background: var(--em-surface); }
            .em-page .nav-pills .nav-link.active { color: #fff; background: var(--bs-primary); border-color: var(--bs-primary); }
            .em-page .progress { background: var(--em-soft); border-radius: 999px; }

            .em-page .alert { color: var(--em-text); border-color: var(--em-border); border-radius: 11px; background: var(--em-soft); }
            .em-page .alert-success { background: color-mix(in srgb, #198754 12%, var(--em-surface)); }
            .em-page .alert-danger { background: color-mix(in srgb, #dc3545 12%, var(--em-surface)); }
            .em-page .alert-warning { background: color-mix(in srgb, #ffc107 12%, var(--em-surface)); }
            .em-page .alert-info { background: color-mix(in srgb, #0dcaf0 10%, var(--em-surface)); }

            .em-page .modal { padding: 12px; backdrop-filter: blur(3px); }
            .em-page .modal-dialog { margin: 0 auto; min-height: calc(100dvh - 24px); display: flex; align-items: center; }
            .em-page .modal-content { max-height: calc(100dvh - 24px); border: 1px solid var(--em-border); border-radius: 14px; box-shadow: 0 24px 60px rgba(0, 0, 0, .28); overflow: hidden; }
            .em-page .modal-body { overflow-y: auto; }
            [data-bs-theme="dark"] .em-page .btn-close, html.dark .em-page .btn-close { filter: invert(1) grayscale(100%) brightness(180%); }

            .em-page .pagination { flex-wrap: wrap; gap: 4px; }
            .em-page .page-link { color: var(--em-text); background: var(--em-surface); border-color: var(--em-border); border-radius: 7px !important; }

            @media (max-width: 767.98px) {
                .em-page { margin-inline: -4px; }
                .em-page .breadcrumb { padding: 9px 11px; font-size: .72rem; overflow-x: auto; flex-wrap: nowrap; white-space: nowrap; }
                .em-page .em-hero { align-items: stretch; flex-direction: column; padding: 15px; }
                .em-page .em-hero-main { align-items: flex-start; }
                .em-page .em-hero-icon { width: 42px; height: 42px; flex-basis: 42px; }
                .em-page .widget-header, .em-page .widget-content.widget-content-area, .em-page .card-body { padding: 14px; }
                .em-page .widget-header .row, .em-page .card-body > .row { row-gap: 10px; }
                .em-page .table { min-width: 700px; }
                .em-page .table > :not(caption) > * > * { padding: 10px; }
                .em-page .modal { padding: 7px; }
                .em-page .modal-dialog { min-height: calc(100dvh - 14px); }
                .em-page .modal-content { max-height: calc(100dvh - 14px); }
            }
        </style>
    @endpush
@endonce
