@once
    @push('link')
        <style>
            .student-ui {
                --su-surface: var(--bs-body-bg);
                --su-soft: var(--bs-tertiary-bg);
                --su-border: var(--bs-border-color);
                --su-text: var(--bs-body-color);
                --su-muted: var(--bs-secondary-color);
                --su-primary-soft: rgba(var(--bs-primary-rgb), .09);
                --su-shadow: 0 12px 34px rgba(15, 23, 42, .07);
                direction: rtl;
                text-align: right;
                color: var(--su-text);
                min-width: 0;
            }
            [data-bs-theme="dark"] .student-ui, html.dark .student-ui {
                --su-surface: #17191d;
                --su-soft: #202329;
                --su-border: rgba(255, 255, 255, .11);
                --su-text: #f1f5f9;
                --su-muted: #a8b0bd;
                --su-primary-soft: rgba(var(--bs-primary-rgb), .15);
                --su-shadow: 0 16px 42px rgba(0, 0, 0, .24);
            }
            .student-ui *, .student-ui *::before, .student-ui *::after { box-sizing: border-box; }
            .student-ui .container-fluid, .student-ui.container-fluid { max-width: 100%; padding-inline: 0; }
            .student-ui .app-page-head { margin-bottom: 14px; }
            .student-ui .breadcrumb { margin: 0; padding: 10px 14px; border: 1px solid var(--su-border); border-radius: 11px; background: var(--su-surface); color: var(--su-muted); }
            .student-ui .breadcrumb a { color: var(--bs-primary); text-decoration: none; }
            .student-ui .page-title-box { gap: 14px; margin-bottom: 18px; padding: 16px 18px; border: 1px solid var(--su-border); border-radius: 14px; background: linear-gradient(135deg, var(--su-primary-soft), var(--su-surface)); box-shadow: var(--su-shadow); }
            .student-ui .page-title-box h4 { color: var(--su-text); }

            .student-ui .student-page-hero {
                display: flex; align-items: center; justify-content: space-between; gap: 16px;
                padding: 19px 20px; margin-bottom: 18px; border: 1px solid var(--su-border); border-radius: 14px;
                background: linear-gradient(135deg, var(--su-primary-soft), var(--su-surface)); box-shadow: var(--su-shadow);
            }
            .student-ui .student-page-hero__main { display: flex; align-items: center; gap: 13px; min-width: 0; }
            .student-ui .student-page-hero__icon { width: 48px; height: 48px; flex: 0 0 48px; display: grid; place-items: center; border-radius: 12px; color: #fff; background: linear-gradient(135deg, #0d6efd, #6f42c1); font-size: 1.15rem; }
            .student-ui .student-page-hero h1, .student-ui .student-page-hero h2, .student-ui .student-page-hero h3, .student-ui .student-page-hero h4 { margin: 0 0 4px; color: var(--su-text); }
            .student-ui .student-page-hero p { margin: 0; color: var(--su-muted); font-size: .8rem; line-height: 1.8; }

            .student-ui .card, .student-ui .statbox.widget, .student-ui .modern-card {
                color: var(--su-text); background-color: var(--su-surface); border: 1px solid var(--su-border) !important;
                border-radius: 14px; box-shadow: var(--su-shadow) !important; overflow: hidden;
            }
            .student-ui .card-header, .student-ui .widget-header, .student-ui .modern-card-header { padding: 15px 17px; color: var(--su-text); background: var(--su-soft); border-color: var(--su-border); }
            .student-ui .card-body, .student-ui .widget-content.widget-content-area { padding: 17px; color: var(--su-text); background-color: var(--su-surface); }
            .student-ui .card-footer, .student-ui .modal-header, .student-ui .modal-footer { color: var(--su-text); background: var(--su-soft); border-color: var(--su-border); }
            .student-ui .modal-body, .student-ui .modal-content { color: var(--su-text); background: var(--su-surface); }
            .student-ui .bg-body, .student-ui .bg-white { background-color: var(--su-surface) !important; }
            .student-ui .bg-body-tertiary, .student-ui .bg-light { color: var(--su-text) !important; background-color: var(--su-soft) !important; }
            .student-ui .text-dark { color: var(--su-text) !important; }
            .student-ui .text-muted, .student-ui .text-body-secondary { color: var(--su-muted) !important; }
            .student-ui .border, .student-ui .border-bottom, .student-ui .border-top, .student-ui hr { border-color: var(--su-border) !important; }

            .student-ui .form-control, .student-ui .form-select {
                min-height: 42px; color: var(--su-text); background-color: var(--su-surface); border-color: var(--su-border); border-radius: 9px;
            }
            .student-ui .form-control-sm, .student-ui .form-select-sm { min-height: 36px; }
            .student-ui .form-control::placeholder { color: color-mix(in srgb, var(--su-muted) 75%, transparent); }
            .student-ui .form-control:focus, .student-ui .form-select:focus { border-color: color-mix(in srgb, var(--bs-primary) 65%, var(--su-border)); box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .12); }
            .student-ui .form-label { color: var(--su-text); font-size: .8rem; font-weight: 700; }
            .student-ui .form-check-input { background-color: var(--su-surface); border-color: var(--su-border); }
            .student-ui .btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; min-height: 38px; border-radius: 9px; }
            .student-ui .btn-sm { min-height: 32px; }

            .student-ui .table-responsive, .student-ui .dt-container { max-width: 100%; overflow-x: auto; scrollbar-width: thin; scrollbar-color: var(--su-border) transparent; }
            .student-ui .table-responsive { border: 1px solid var(--su-border); border-radius: 11px; }
            .student-ui .table { --bs-table-bg: transparent; --bs-table-color: var(--su-text); --bs-table-border-color: var(--su-border); margin: 0; min-width: 760px; }
            .student-ui .table > :not(caption) > * > * { padding: 11px; vertical-align: middle; border-color: var(--su-border); }
            .student-ui .table thead th { color: var(--su-text); background: var(--su-soft) !important; font-size: .76rem; white-space: nowrap; }
            .student-ui .table-hover > tbody > tr:hover > * { --bs-table-bg-state: var(--su-primary-soft); }
            .student-ui .list-group-item { color: var(--su-text); background: transparent; border-color: var(--su-border); }
            .student-ui .badge { line-height: 1.45; border-radius: 999px; padding: .35rem .55rem; }
            .student-ui .progress { background: var(--su-soft); border-radius: 999px; }

            .student-ui .alert { color: var(--su-text); border: 1px solid var(--su-border) !important; border-radius: 11px; background: var(--su-soft); }
            .student-ui .alert-success { background: color-mix(in srgb, #198754 12%, var(--su-surface)); }
            .student-ui .alert-danger { background: color-mix(in srgb, #dc3545 12%, var(--su-surface)); }
            .student-ui .alert-warning { background: color-mix(in srgb, #ffc107 12%, var(--su-surface)); }
            .student-ui .alert-info { background: color-mix(in srgb, #0dcaf0 10%, var(--su-surface)); }

            .student-ui .modal { padding: 10px; backdrop-filter: blur(3px); }
            .student-ui .modal-dialog { margin: 0 auto; min-height: calc(100dvh - 20px); display: flex; align-items: center; }
            .student-ui .modal-content { max-height: calc(100dvh - 20px); border: 1px solid var(--su-border); border-radius: 14px; box-shadow: 0 24px 60px rgba(0, 0, 0, .3); overflow: hidden; }
            .student-ui .modal-body { overflow-y: auto; }
            [data-bs-theme="dark"] .student-ui .btn-close, html.dark .student-ui .btn-close { filter: invert(1) grayscale(100%) brightness(180%); }

            .student-ui .pagination { flex-wrap: wrap; gap: 4px; }
            .student-ui .page-link { color: var(--su-text); background: var(--su-surface); border-color: var(--su-border); border-radius: 7px !important; }

            .student-ui .su-data-drawer { margin-bottom: 18px; border: 1px solid var(--su-border); border-radius: 14px; background: var(--su-surface); box-shadow: var(--su-shadow); overflow: hidden; }
            .student-ui .su-data-drawer > summary { display: flex; align-items: center; gap: 10px; min-height: 52px; padding: 13px 17px; cursor: pointer; list-style: none; color: var(--su-text); background: var(--su-soft); font-weight: 800; user-select: none; }
            .student-ui .su-data-drawer > summary::-webkit-details-marker { display: none; }
            .student-ui .su-data-drawer > summary::after { content: "v"; font-family: inherit; font-size: .9rem; margin-inline-start: auto; width: 30px; height: 30px; display: grid; place-items: center; border: 1px solid var(--su-border); border-radius: 8px; color: var(--bs-primary); background: var(--su-surface); transition: transform .2s ease; }
            .student-ui .su-data-drawer[open] > summary::after { transform: rotate(180deg); }
            .student-ui .su-data-drawer > .su-data-drawer__body { padding: 15px; }
            .student-ui .su-data-drawer > .su-data-drawer__body > .row { margin-bottom: 0 !important; }
            .student-ui .su-data-drawer .card { box-shadow: none !important; }
            .student-ui .su-data-drawer__hint { margin-inline-start: 7px; color: var(--su-muted); font-size: .72rem; font-weight: 500; }
            .student-ui .su-collapsible-card > .card-header, .student-ui .su-collapsible-card > .widget-header, .student-ui .su-collapsible-card > .modern-card-header { display: flex; align-items: center; gap: 10px; }
            .student-ui .su-card-toggle { width: 32px; height: 32px; min-height: 32px; flex: 0 0 32px; margin-inline-start: auto; padding: 0; display: grid; place-items: center; border: 1px solid var(--su-border); border-radius: 8px; color: var(--bs-primary); background: var(--su-surface); }
            .student-ui .su-card-toggle span { display: block; font-size: 1.15rem; line-height: 1; transition: transform .2s ease; }
            .student-ui .su-collapsible-card.su-collapsed > :not(.card-header):not(.widget-header):not(.modern-card-header) { display: none !important; }
            .student-ui .su-collapsible-card.su-collapsed .su-card-toggle span { transform: rotate(180deg); }

            @media (max-width: 767.98px) {
                .student-ui { margin-inline: -4px; }
                .student-ui .breadcrumb { padding: 9px 11px; font-size: .72rem; overflow-x: auto; flex-wrap: nowrap; white-space: nowrap; }
                .student-ui .student-page-hero { align-items: stretch; flex-direction: column; padding: 15px; }
                .student-ui .student-page-hero__main { align-items: flex-start; }
                .student-ui .student-page-hero__icon { width: 42px; height: 42px; flex-basis: 42px; }
                .student-ui .card-header, .student-ui .card-body, .student-ui .widget-header, .student-ui .modern-card-header, .student-ui .widget-content.widget-content-area { padding: 13px; }
                .student-ui .card-header.d-flex, .student-ui .alert .d-flex { align-items: stretch !important; flex-direction: column; }
                .student-ui .table { min-width: 700px; }
                .student-ui .table > :not(caption) > * > * { padding: 9px; }
                .student-ui .su-data-drawer > summary { padding: 12px 13px; }
                .student-ui .su-data-drawer > .su-data-drawer__body { padding: 11px; }
                .student-ui .modal { padding: 6px; }
                .student-ui .modal-dialog { min-height: calc(100dvh - 12px); }
                .student-ui .modal-content { max-height: calc(100dvh - 12px); }
            }
        </style>
    @endpush

    @push('script')
        <script>
            (() => {
                const enhanceStudentCards = () => {
                    document.querySelectorAll('.student-ui-auto-collapse').forEach((root) => {
                        const containers = '.card, .statbox.widget, .modern-card';
                        root.querySelectorAll(containers).forEach((card) => {
                            if (card.closest('.modal') || card.parentElement?.closest(containers)) return;
                            const header = Array.from(card.children).find((child) =>
                                child.classList?.contains('card-header') ||
                                child.classList?.contains('widget-header') ||
                                child.classList?.contains('modern-card-header')
                            );
                            if (!header || card.classList.contains('su-collapsible-card')) return;

                            card.classList.add('su-collapsible-card');
                            const button = document.createElement('button');
                            button.type = 'button';
                            button.className = 'su-card-toggle';
                            button.setAttribute('aria-label', 'باز یا بسته کردن بخش');
                            button.setAttribute('aria-expanded', 'true');
                            const arrow = document.createElement('span');
                            arrow.textContent = '^';
                            button.appendChild(arrow);
                            button.addEventListener('click', (event) => {
                                event.preventDefault();
                                event.stopPropagation();
                                const collapsed = card.classList.toggle('su-collapsed');
                                button.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                            });
                            header.appendChild(button);
                        });
                    });
                };

                document.addEventListener('DOMContentLoaded', enhanceStudentCards);
                document.addEventListener('livewire:navigated', enhanceStudentCards);
                document.addEventListener('livewire:init', () => {
                    if (window.Livewire?.hook) {
                        Livewire.hook('morph.updated', () => queueMicrotask(enhanceStudentCards));
                    }
                });
                enhanceStudentCards();
            })();
        </script>
    @endpush
@endonce
