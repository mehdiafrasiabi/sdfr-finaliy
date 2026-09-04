@once
    <style>
        .pa-page {
            --pa-surface: var(--bs-body-bg);
            --pa-surface-soft: rgba(var(--bs-secondary-rgb), .06);
            --pa-border: var(--bs-border-color);
            --pa-text: var(--bs-heading-color);
            --pa-muted: var(--bs-secondary-color);
            --pa-shadow: 0 14px 34px rgba(15, 23, 42, .08);
            color: var(--pa-text);
        }

        [data-bs-theme="dark"] .pa-page,
        html.dark .pa-page {
            --pa-surface-soft: rgba(255, 255, 255, .045);
            --pa-shadow: 0 14px 34px rgba(0, 0, 0, .24);
        }

        .pa-page .app-page-head {
            margin-bottom: 14px;
        }

        .pa-page .breadcrumb {
            row-gap: 6px;
            margin-bottom: 0;
            color: var(--pa-muted);
        }

        .pa-page .breadcrumb a {
            color: var(--bs-primary);
            text-decoration: none;
        }

        .pa-page .statbox,
        .pa-page .card {
            background: var(--pa-surface);
            border: 1px solid var(--pa-border) !important;
            border-radius: 8px;
            box-shadow: var(--pa-shadow) !important;
            color: var(--pa-text);
        }

        .pa-page .widget-header,
        .pa-page .widget-content-area,
        .pa-page .card-body {
            background: transparent;
        }

        .pa-page .card-body {
            display: flex;
            flex-direction: column;
        }

        .pa-page .widget-header {
            padding: 18px;
            border-bottom: 1px solid var(--pa-border);
        }

        .pa-page .widget-content-area {
            padding: 18px;
        }

        .pa-page .text-muted,
        .pa-page small {
            color: var(--pa-muted) !important;
        }

        .pa-page .form-control,
        .pa-page .form-select {
            min-height: 42px;
            border-color: var(--pa-border);
            background-color: var(--pa-surface-soft);
            color: var(--pa-text);
        }

        .pa-page .form-control::placeholder {
            color: var(--pa-muted);
        }

        .pa-page .badge.bg-light,
        .pa-page .btn-light {
            background-color: var(--pa-surface-soft) !important;
            color: var(--pa-text) !important;
            border-color: var(--pa-border) !important;
        }

        .pa-page .badge {
            white-space: normal;
            line-height: 1.65;
        }

        .pa-page .card h3,
        .pa-page .card h4,
        .pa-page .card h5,
        .pa-page .card h6 {
            color: var(--pa-text);
        }

        .pa-page a.card {
            transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
        }

        .pa-page a.card:hover {
            transform: translateY(-2px);
            border-color: rgba(var(--bs-primary-rgb), .45) !important;
        }

        .pa-page .card[style*="border-right-width"] {
            overflow: hidden;
        }

        .pa-page .form-check.rounded-3,
        .pa-page .form-check.border {
            background: var(--pa-surface-soft);
            border-color: var(--pa-border) !important;
        }

        .pa-page .card-body > .btn.w-100:last-child,
        .pa-page .card-body > .btn.disabled.w-100:last-child {
            margin-top: auto;
        }

        .pa-page .modal {
            padding: 12px;
        }

        .pa-page .modal-dialog {
            margin: 0 auto;
            max-width: min(720px, calc(100vw - 24px));
        }

        .pa-page .modal-content {
            background: var(--pa-surface);
            color: var(--pa-text);
            border: 1px solid var(--pa-border);
            border-radius: 8px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, .24);
            overflow: hidden;
        }

        .pa-page .modal-header,
        .pa-page .modal-footer {
            border-color: var(--pa-border);
            gap: 8px;
        }

        .pa-page .modal-body {
            max-height: calc(100dvh - 170px);
            overflow-y: auto;
        }

        .pa-page .pa-toolbar {
            row-gap: 12px;
        }

        .pa-page .pa-lead-actions {
            margin-top: auto;
        }

        .pa-followup-page .widget-header {
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), .09), transparent 65%);
        }

        .pa-followup-page .pa-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 7px;
            color: var(--bs-primary);
            font-size: .78rem;
            font-weight: 800;
        }

        .pa-followup-page .pa-search-wrap {
            position: relative;
        }

        .pa-followup-page .pa-search-wrap > i {
            position: absolute;
            z-index: 2;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: var(--pa-muted);
        }

        .pa-followup-page .pa-search-wrap .form-control {
            padding-right: 42px;
            border-radius: 8px;
        }

        .pa-followup-page .pa-summary-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
        }

        .pa-followup-page .pa-summary-item {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 175px;
            padding: 10px 13px;
            background: var(--pa-surface);
            border: 1px solid var(--pa-border);
            border-radius: 8px;
        }

        .pa-followup-page .pa-summary-item > span:last-child {
            display: flex;
            flex-direction: column;
        }

        .pa-followup-page .pa-summary-item strong {
            color: var(--pa-text);
            font-size: 1.05rem;
            line-height: 1.25;
        }

        .pa-followup-page .pa-summary-item small {
            font-size: .72rem;
        }

        .pa-followup-page .pa-summary-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            flex: 0 0 36px;
        }

        .pa-followup-page .pa-due-switch {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-right: auto;
            padding: 10px 13px !important;
            background: var(--pa-surface);
            border: 1px solid var(--pa-border);
            border-radius: 8px;
        }

        .pa-followup-page .pa-due-switch .form-check-input {
            float: none;
            flex: 0 0 auto;
            margin: 0 !important;
        }

        .pa-followup-page .pa-color-legend {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 7px;
            margin-top: 10px;
            color: var(--pa-muted);
            font-size: .76rem;
        }

        .pa-followup-page .pa-section-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 11px;
            padding-top: 11px;
            border-top: 1px solid var(--pa-border);
        }

        .pa-followup-page .pa-section-tabs a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 11px;
            color: var(--pa-muted);
            background: var(--pa-surface);
            border: 1px solid var(--pa-border);
            border-radius: 8px;
            text-decoration: none;
            font-size: .76rem;
            font-weight: 700;
        }

        .pa-followup-page .pa-section-tabs a.active {
            color: #fff;
            background: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .pa-followup-page .pa-followup-card {
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .pa-followup-page .pa-followup-card:hover {
            transform: translateY(-3px);
        }

        .pa-followup-page .pa-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 13px;
            margin-bottom: 13px;
            border-bottom: 1px solid var(--pa-border);
        }

        .pa-followup-page .pa-card-kicker {
            display: block;
            margin-bottom: 3px;
            color: var(--pa-muted);
            font-size: .7rem;
            font-weight: 700;
        }

        .pa-followup-page .pa-mobile {
            color: var(--pa-text);
            font-size: 1.12rem;
            font-weight: 800;
            letter-spacing: .025em;
        }

        .pa-followup-page .pa-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border-radius: 8px;
            font-size: .72rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .pa-followup-page .pa-status-pill.is-success {
            color: var(--bs-success);
            background: rgba(var(--bs-success-rgb), .13);
        }

        .pa-followup-page .pa-status-pill.is-warning {
            color: #8a5a00;
            background: rgba(var(--bs-warning-rgb), .2);
        }

        .pa-followup-page .pa-info-grid {
            display: grid;
            gap: 0;
            margin-bottom: 13px;
            border: 1px solid var(--pa-border);
            border-radius: 8px;
            overflow: hidden;
        }

        .pa-followup-page .pa-info-row {
            display: grid;
            grid-template-columns: minmax(90px, .8fr) minmax(110px, 1.2fr);
            align-items: center;
            gap: 10px;
            padding: 9px 11px;
            background: var(--pa-surface-soft);
            font-size: .78rem;
        }

        .pa-followup-page .pa-info-row + .pa-info-row {
            border-top: 1px solid var(--pa-border);
        }

        .pa-followup-page .pa-info-row > span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--pa-muted);
        }

        .pa-followup-page .pa-info-row strong {
            color: var(--pa-text);
            font-weight: 700;
            text-align: left;
        }

        .pa-followup-page .pa-reminder-box,
        .pa-followup-page .pa-last-result {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 13px;
            padding: 10px 11px;
            background: rgba(var(--bs-primary-rgb), .08);
            border: 1px solid rgba(var(--bs-primary-rgb), .22);
            border-radius: 8px;
            color: var(--pa-text);
            font-size: .76rem;
        }

        .pa-followup-page .pa-reminder-box.is-due {
            background: rgba(var(--bs-danger-rgb), .09);
            border-color: rgba(var(--bs-danger-rgb), .28);
        }

        .pa-followup-page .pa-reminder-box > span,
        .pa-followup-page .pa-last-result > span {
            color: var(--pa-muted);
        }

        .pa-followup-page .pa-link-section {
            margin-bottom: 14px;
            padding: 11px;
            background: var(--pa-surface-soft);
            border: 1px solid var(--pa-border);
            border-radius: 8px;
        }

        .pa-followup-page .pa-link-meta {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 7px;
            color: var(--pa-muted);
            font-size: .74rem;
        }

        .pa-followup-page .pa-link-meta strong {
            color: var(--pa-text);
        }

        .pa-followup-page .pa-registration-link {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 9px;
            padding: 8px 10px;
            color: var(--bs-primary);
            background: var(--pa-surface);
            border: 1px dashed rgba(var(--bs-primary-rgb), .45);
            border-radius: 8px;
            text-decoration: none;
            font-size: .72rem;
        }

        .pa-followup-page .pa-reason-box {
            margin-bottom: 13px;
            padding: 10px 11px;
            color: var(--pa-text);
            background: rgba(var(--bs-secondary-rgb), .09);
            border: 1px solid rgba(var(--bs-secondary-rgb), .25);
            border-radius: 8px;
        }

        .pa-followup-page .pa-reason-box.is-temporary {
            background: rgba(var(--bs-warning-rgb), .1);
            border-color: rgba(var(--bs-warning-rgb), .3);
        }

        .pa-followup-page .pa-reason-box > span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 5px;
            color: var(--pa-muted);
            font-size: .72rem;
            font-weight: 700;
        }

        .pa-followup-page .pa-reason-box p {
            margin: 0;
            color: var(--pa-text);
            font-size: .8rem;
            line-height: 1.85;
        }

        .pa-followup-page .pa-call-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 5px 10px;
            margin-bottom: 13px;
            color: var(--pa-muted);
            font-size: .72rem;
        }

        .pa-followup-page .pa-call-meta strong {
            color: var(--pa-text);
        }

        .pa-followup-page .pa-call-meta > span:last-child:not(:first-child) {
            width: 100%;
        }

        .pa-followup-page .pa-lock-notice {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 10px;
            color: var(--pa-muted);
            background: var(--pa-surface-soft);
            border: 1px solid var(--pa-border);
            border-radius: 8px;
            font-size: .74rem;
            font-weight: 700;
        }

        .pa-dashboard-page .pa-dashboard-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 22px;
            color: var(--pa-text);
            background:
                radial-gradient(circle at 8% 20%, rgba(var(--bs-primary-rgb), .18), transparent 34%),
                linear-gradient(135deg, var(--pa-surface), var(--pa-surface-soft));
            border: 1px solid var(--pa-border);
            border-radius: 8px;
            box-shadow: var(--pa-shadow);
        }

        .pa-dashboard-page .pa-dashboard-hero h3 {
            margin: 0 0 7px;
            color: var(--pa-text);
            font-size: clamp(1.2rem, 2vw, 1.65rem);
        }

        .pa-dashboard-page .pa-dashboard-hero p {
            max-width: 680px;
            margin: 0;
            color: var(--pa-muted);
            font-size: .84rem;
        }

        .pa-dashboard-page .pa-dashboard-hero-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 9px;
            flex: 0 0 auto;
        }

        .pa-dashboard-page .pa-dashboard-date {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 12px;
            color: var(--pa-text);
            background: var(--pa-surface-soft);
            border: 1px solid var(--pa-border);
            border-radius: 8px;
            font-size: .76rem;
            white-space: nowrap;
        }

        .pa-dashboard-page .pa-dashboard-section-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 11px;
        }

        .pa-dashboard-page .pa-dashboard-section-head span {
            display: block;
            margin-bottom: 2px;
            color: var(--bs-primary);
            font-size: .7rem;
            font-weight: 800;
        }

        .pa-dashboard-page .pa-dashboard-section-head h4 {
            margin: 0;
            color: var(--pa-text);
            font-size: 1.04rem;
        }

        .pa-dashboard-page .pa-dashboard-section-head > a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--bs-primary);
            text-decoration: none;
            font-size: .76rem;
            font-weight: 700;
        }

        .pa-dashboard-page .pa-metric-card,
        .pa-dashboard-page .pa-queue-card,
        .pa-dashboard-page .pa-insight-card,
        .pa-dashboard-page .pa-goal-card {
            border-radius: 8px;
        }

        .pa-dashboard-page .pa-metric-card .card-body {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 12px;
            padding: 15px;
        }

        .pa-dashboard-page .pa-metric-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 8px;
            flex: 0 0 42px;
            font-size: 1.05rem;
        }

        .pa-dashboard-page .pa-metric-content {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .pa-dashboard-page .pa-metric-content > span {
            color: var(--pa-muted);
            font-size: .72rem;
            font-weight: 700;
        }

        .pa-dashboard-page .pa-metric-content > strong {
            margin: 2px 0;
            color: var(--pa-text);
            font-size: 1.3rem;
            line-height: 1.25;
        }

        .pa-dashboard-page .pa-metric-content > small {
            color: var(--pa-muted) !important;
            font-size: .67rem;
            line-height: 1.55;
        }

        .pa-dashboard-page .pa-queue-card {
            color: var(--pa-text);
            transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
        }

        .pa-dashboard-page .pa-queue-card:hover {
            color: var(--pa-text);
            transform: translateY(-3px);
            border-color: rgba(var(--bs-primary-rgb), .45) !important;
        }

        .pa-dashboard-page .pa-queue-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 13px;
        }

        .pa-dashboard-page .pa-queue-card-head > strong {
            color: var(--pa-text);
            font-size: 1.7rem;
        }

        .pa-dashboard-page .pa-queue-card h5 {
            margin-bottom: 5px;
            color: var(--pa-text);
            font-size: .92rem;
        }

        .pa-dashboard-page .pa-queue-card p {
            min-height: 38px;
            margin-bottom: 13px;
            color: var(--pa-muted);
            font-size: .72rem;
            line-height: 1.65;
        }

        .pa-dashboard-page .pa-queue-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 10px;
            color: var(--bs-primary);
            border-top: 1px solid var(--pa-border);
            font-size: .72rem;
            font-weight: 800;
        }

        .pa-dashboard-page .pa-insight-head,
        .pa-dashboard-page .pa-goal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 12px;
            margin-bottom: 13px;
            border-bottom: 1px solid var(--pa-border);
        }

        .pa-dashboard-page .pa-insight-head > div > span,
        .pa-dashboard-page .pa-goal-head > div > span {
            display: block;
            margin-bottom: 3px;
            color: var(--pa-muted);
            font-size: .69rem;
        }

        .pa-dashboard-page .pa-insight-head h5,
        .pa-dashboard-page .pa-goal-head h5 {
            margin: 0;
            color: var(--pa-text);
            font-size: .94rem;
        }

        .pa-dashboard-page .pa-color-breakdown {
            display: grid;
            gap: 10px;
        }

        .pa-dashboard-page .pa-color-row {
            display: grid;
            grid-template-columns: 72px minmax(90px, 1fr) 35px;
            align-items: center;
            gap: 10px;
            color: var(--pa-muted);
            font-size: .72rem;
        }

        .pa-dashboard-page .pa-color-row > span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .pa-dashboard-page .pa-color-row > span i {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .pa-dashboard-page .pa-color-row .progress {
            height: 6px;
            background: var(--pa-surface-soft);
        }

        .pa-dashboard-page .pa-color-row > strong {
            color: var(--pa-text);
            text-align: left;
        }

        .pa-dashboard-page .pa-insight-note {
            margin: 14px 0 0;
            color: var(--pa-muted);
            font-size: .68rem;
            line-height: 1.65;
        }

        .pa-dashboard-page .pa-best-hour {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px;
            margin-bottom: 13px;
            background: rgba(var(--bs-success-rgb), .09);
            border: 1px solid rgba(var(--bs-success-rgb), .22);
            border-radius: 8px;
        }

        .pa-dashboard-page .pa-best-hour > span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            color: var(--bs-success);
            background: rgba(var(--bs-success-rgb), .12);
            border-radius: 8px;
            flex: 0 0 42px;
        }

        .pa-dashboard-page .pa-best-hour > div {
            display: flex;
            flex-direction: column;
        }

        .pa-dashboard-page .pa-best-hour strong {
            color: var(--pa-text);
            font-size: 1.05rem;
        }

        .pa-dashboard-page .pa-best-hour small {
            color: var(--pa-muted) !important;
        }

        .pa-dashboard-page .pa-hour-candidates {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .pa-dashboard-page .pa-hour-candidates > span {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 9px 10px;
            color: var(--pa-muted);
            background: var(--pa-surface-soft);
            border: 1px solid var(--pa-border);
            border-radius: 8px;
            font-size: .69rem;
        }

        .pa-dashboard-page .pa-hour-candidates strong {
            color: var(--pa-text);
        }

        .pa-dashboard-page .pa-dashboard-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 125px;
            color: var(--pa-muted);
            background: var(--pa-surface-soft);
            border: 1px dashed var(--pa-border);
            border-radius: 8px;
            font-size: .78rem;
        }

        .pa-dashboard-page .pa-goal-card p {
            margin-bottom: 13px;
            color: var(--pa-muted);
            font-size: .74rem;
        }

        .pa-dashboard-page .pa-goal-progress-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 7px;
            color: var(--pa-muted);
            font-size: .72rem;
        }

        .pa-dashboard-page .pa-goal-progress-label strong {
            color: var(--pa-text);
        }

        .pa-dashboard-page .pa-goal-card .progress {
            height: 9px;
            background: var(--pa-surface-soft);
            border-radius: 999px;
        }

        [data-bs-theme="dark"] .pa-followup-page,
        html.dark .pa-followup-page {
            --pa-text: #f8fafc;
            --pa-muted: #fff;
            --pa-surface: #171d2d;
            --pa-surface-soft: rgba(255, 255, 255, .055);
            --pa-border: rgba(255, 255, 255, .14);
        }

        [data-bs-theme="dark"] .pa-followup-page .badge,
        html.dark .pa-followup-page .badge {
            color: #fff !important;
        }

        [data-bs-theme="dark"] .pa-followup-page .badge.bg-warning,
        html.dark .pa-followup-page .badge.bg-warning {
            background-color: #854d0e !important;
        }

        [data-bs-theme="dark"] .pa-followup-page .badge.bg-info,
        html.dark .pa-followup-page .badge.bg-info {
            background-color: #075985 !important;
        }

        [data-bs-theme="dark"] .pa-followup-page .pa-status-pill.is-warning,
        html.dark .pa-followup-page .pa-status-pill.is-warning {
            color: #fde68a;
        }

        [data-bs-theme="dark"] .pa-followup-page .text-dark,
        html.dark .pa-followup-page .text-dark {
            color: #fff !important;
        }

        @media (max-width: 767.98px) {
            .pa-page {
                margin-right: -5px;
                margin-left: -5px;
            }

            .pa-page .app-page-head {
                padding: 0 4px;
            }

            .pa-page .widget-header,
            .pa-page .widget-content-area,
            .pa-page .card-body {
                padding: 14px;
            }

            .pa-page .row.g-3 {
                --bs-gutter-x: .75rem;
                --bs-gutter-y: .75rem;
            }

            .pa-page h3 {
                font-size: 1.6rem;
            }

            .pa-page h4,
            .pa-page h5 {
                font-size: 1.05rem;
            }

            .pa-page .d-flex.justify-content-between.align-items-start {
                align-items: flex-start !important;
            }

            .pa-page .btn {
                min-height: 42px;
            }

            .pa-page .modal {
                padding: 8px;
            }

            .pa-page .modal-dialog {
                max-width: calc(100vw - 16px);
            }

            .pa-page .modal-body {
                max-height: calc(100dvh - 150px);
            }

            .pa-page .modal-footer {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .pa-page .modal-footer .btn {
                width: 100%;
                margin: 0;
            }

            .pa-page .display-3 {
                font-size: 2.4rem;
            }

            .pa-page .display-4 {
                font-size: 2rem;
            }

            .pa-page .pa-call-icon {
                width: 76px;
                height: 76px;
                font-size: 30px;
            }

            .pa-followup-page .pa-summary-item {
                min-width: calc(50% - 5px);
                flex: 1 1 calc(50% - 5px);
            }

            .pa-followup-page .pa-due-switch {
                width: 100%;
                margin-right: 0;
            }

            .pa-dashboard-page .pa-dashboard-hero {
                align-items: stretch;
                flex-direction: column;
                padding: 16px;
            }

            .pa-dashboard-page .pa-dashboard-hero-actions {
                justify-content: stretch;
            }

            .pa-dashboard-page .pa-dashboard-date,
            .pa-dashboard-page .pa-dashboard-hero-actions .btn {
                justify-content: center;
                width: 100%;
            }

            .pa-dashboard-page .pa-metric-card .card-body {
                flex-direction: column;
                gap: 9px;
                padding: 12px;
            }

            .pa-dashboard-page .pa-metric-icon {
                width: 36px;
                height: 36px;
                flex-basis: 36px;
            }

            .pa-dashboard-page .pa-metric-content > strong {
                font-size: 1.12rem;
            }

            .pa-dashboard-page .pa-hour-candidates {
                grid-template-columns: 1fr;
            }

            .pa-dashboard-page .pa-dashboard-section-head {
                align-items: flex-start;
            }
        }
    </style>
@endonce
