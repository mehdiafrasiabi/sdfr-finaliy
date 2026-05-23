<div class="min-h-screen bg-background py-6 sm:py-10 relative" dir="rtl"
     x-data="preSessionWizard()"
     x-init="init()"
     @keydown.escape.window="closeModal()">

    @push('link')
        <style>
            [x-cloak] { display: none !important; }

            /* ─── Decorative grid pattern background ─── */
            .bg-grid {
                background-image:
                    linear-gradient(to right, hsl(var(--border) / 0.3) 1px, transparent 1px),
                    linear-gradient(to bottom, hsl(var(--border) / 0.3) 1px, transparent 1px);
                background-size: 28px 28px;
                -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 30%, transparent 80%);
                mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 30%, transparent 80%);
            }

            /* ─── Card animations ─── */
            @keyframes card-in {
                from { opacity: 0; transform: translateY(20px) scale(0.96); }
                to   { opacity: 1; transform: translateY(0) scale(1); }
            }
            .pre-card {
                animation: card-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
                transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                border-color 0.2s ease,
                box-shadow 0.25s ease;
                position: relative;
                overflow: hidden;
            }
            .pre-card:nth-child(1) { animation-delay: 0.05s; }
            .pre-card:nth-child(2) { animation-delay: 0.1s; }
            .pre-card:nth-child(3) { animation-delay: 0.15s; }
            .pre-card:nth-child(4) { animation-delay: 0.2s; }
            .pre-card:nth-child(5) { animation-delay: 0.25s; }
            .pre-card:nth-child(6) { animation-delay: 0.3s; }
            .pre-card:hover:not(.is-disabled) {
                transform: translateY(-4px);
            }

            /* Card subtle shimmer on hover */
            .pre-card::before {
                content: '';
                position: absolute;
                top: 0; left: -100%;
                width: 100%; height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
                transition: left 0.7s ease;
                pointer-events: none;
            }
            .pre-card:hover::before { left: 100%; }

            .pre-card.is-completed::after {
                content: '';
                position: absolute;
                top: 12px; left: 12px;
                width: 8px; height: 8px;
                background: rgb(16 185 129);
                border-radius: 50%;
                box-shadow: 0 0 0 4px rgb(16 185 129 / 0.2),
                0 0 12px rgb(16 185 129 / 0.5);
                animation: pulse-dot 2s ease infinite;
            }
            @keyframes pulse-dot {
                0%, 100% { box-shadow: 0 0 0 4px rgb(16 185 129 / 0.2), 0 0 12px rgb(16 185 129 / 0.5); }
                50%      { box-shadow: 0 0 0 6px rgb(16 185 129 / 0.1), 0 0 16px rgb(16 185 129 / 0.7); }
            }

            /* ─── Icon container animation ─── */
            .icon-box {
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            .pre-card:hover .icon-box {
                transform: scale(1.08) rotate(-4deg);
            }

            /* ─── Badge counter pop ─── */
            @keyframes badge-pop {
                0%   { transform: scale(0); opacity: 0; }
                70%  { transform: scale(1.15); }
                100% { transform: scale(1); opacity: 1; }
            }
            .badge-pop { animation: badge-pop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); }

            /* ─── Buttons ─── */
            .btn-primary-fancy {
                position: relative;
                overflow: hidden;
                background: linear-gradient(135deg, rgb(37 99 235), rgb(59 130 246));
                color: white;
                transition: transform 0.15s ease, box-shadow 0.15s ease;
                box-shadow: 0 4px 14px rgb(59 130 246 / 0.35);
            }
            .btn-primary-fancy:hover:not(:disabled) {
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgb(59 130 246 / 0.5);
            }
            .btn-primary-fancy:active:not(:disabled) {
                transform: translateY(1px);
                box-shadow: 0 2px 8px rgb(59 130 246 / 0.4);
            }
            .btn-primary-fancy::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
                opacity: 0;
                transition: opacity 0.2s ease;
            }
            .btn-primary-fancy:hover:not(:disabled)::after { opacity: 1; }

            /* ─── Chip selector ─── */
            .chip {
                cursor: pointer;
                padding: 0.5rem 0.5rem;
                border-radius: 0.875rem;
                border: 1.5px solid hsl(var(--border));
                background: hsl(var(--background));
                color: hsl(var(--foreground));
                font-size: 0.8125rem;
                font-weight: 700;
                transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
                text-align: center;
                user-select: none;
                position: relative;
                overflow: hidden;
            }
            .chip:hover {
                border-color: rgb(59 130 246 / 0.5);
                transform: translateY(-1px);
            }
            .chip.active {
                background: linear-gradient(135deg, rgb(37 99 235), rgb(59 130 246));
                border-color: rgb(37 99 235);
                color: white;
                box-shadow: 0 6px 16px rgb(59 130 246 / 0.4);
                transform: translateY(-2px);
            }
            .chip.active::before {
                content: '';
                position: absolute;
                inset: 0;
                background: radial-gradient(circle at center, rgba(255,255,255,0.3), transparent 60%);
                opacity: 0;
                animation: chip-flash 0.5s ease;
            }
            @keyframes chip-flash {
                0%   { opacity: 0; transform: scale(0.5); }
                50%  { opacity: 1; }
                100% { opacity: 0; transform: scale(1.5); }
            }

            /* ─── Counter (+/-) buttons - حرفه‌ای ─── */
            .counter-wrap {
                display: flex;
                align-items: center;
                gap: 0;
                background: hsl(var(--secondary) / 0.5);
                border: 1.5px solid hsl(var(--border));
                border-radius: 1rem;
                padding: 4px;
                transition: border-color 0.2s ease;
            }
            .counter-wrap:focus-within {
                border-color: rgb(59 130 246);
                box-shadow: 0 0 0 4px rgb(59 130 246 / 0.1);
            }
            .counter-btn {
                width: 44px;
                height: 44px;
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                background: hsl(var(--background));
                color: hsl(var(--foreground));
                transition: all 0.15s ease;
                font-weight: bold;
                cursor: pointer;
                user-select: none;
            }
            .counter-btn:hover { background: hsl(var(--primary) / 0.1); color: hsl(var(--primary)); }
            .counter-btn:active { transform: scale(0.92); }
            .counter-input {
                flex: 1;
                background: transparent;
                border: none;
                outline: none;
                text-align: center;
                font-weight: 800;
                font-size: 1.125rem;
                color: hsl(var(--foreground));
                padding: 0 0.5rem;
            }
            .counter-input::-webkit-outer-spin-button,
            .counter-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
            .counter-input[type=number] { -moz-appearance: textfield; }

            /* ─── Bottom-sheet / Centered modal ─── */
            .sheet-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.65);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                z-index: 90;
            }
            .sheet {
                position: fixed;
                left: 0; right: 0; bottom: 0;
                background: hsl(var(--background));
                border-top: 1px solid hsl(var(--border));
                border-radius: 28px 28px 0 0;
                max-height: 92dvh;
                display: flex;
                flex-direction: column;
                z-index: 100;
                padding-bottom: env(safe-area-inset-bottom, 0);
                box-shadow: 0 -20px 60px rgba(0, 0, 0, 0.3);
            }
            @media (min-width: 768px) {
                .sheet {
                    left: 50%;
                    top: 50%;
                    bottom: auto;
                    right: auto;
                    transform: translate(-50%, -50%);
                    width: 90%;
                    max-width: 680px;
                    border-radius: 24px;
                    border: 1px solid hsl(var(--border));
                    max-height: 88dvh;
                }
            }
            .sheet-handle {
                width: 44px;
                height: 5px;
                background: hsl(var(--muted-foreground) / 0.35);
                border-radius: 999px;
                margin: 10px auto 4px;
            }
            @media (min-width: 768px) {
                .sheet-handle { display: none; }
            }

            /* Decorative gradient header inside modal */
            .sheet-header-pattern {
                position: relative;
                overflow: hidden;
            }
            .sheet-header-pattern::before {
                content: '';
                position: absolute;
                top: -50%; right: -10%;
                width: 200px; height: 200px;
                background: radial-gradient(circle, var(--accent-color, hsl(var(--primary) / 0.15)), transparent 70%);
                pointer-events: none;
            }
            .sheet-header-pattern::after {
                content: '';
                position: absolute;
                bottom: 0; left: 0; right: 0;
                height: 1px;
                background: linear-gradient(to left, transparent, var(--accent-color, hsl(var(--primary) / 0.3)), transparent);
            }

            /* ─── Item list animations ─── */
            @keyframes item-in {
                from { opacity: 0; transform: translateX(20px); }
                to   { opacity: 1; transform: translateX(0); }
            }
            .item-row {
                animation: item-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) backwards;
                transition: all 0.2s ease;
            }
            .item-row:hover {
                transform: translateX(-4px);
            }

            /* ─── Form field reveal ─── */
            @keyframes field-reveal {
                from { opacity: 0; transform: translateY(8px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .field-reveal { animation: field-reveal 0.35s cubic-bezier(0.16, 1, 0.3, 1); }

            /* ─── Success checkmark ─── */
            @keyframes check-pop {
                0%   { transform: scale(0); opacity: 0; }
                50%  { transform: scale(1.3); opacity: 1; }
                100% { transform: scale(1); opacity: 1; }
            }
            .success-check {
                animation: check-pop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            /* ─── Status badge animations ─── */
            .live-dot {
                position: relative;
            }
            .live-dot::after {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 50%;
                background: currentColor;
                animation: ping 2s ease infinite;
                opacity: 0.4;
            }
            @keyframes ping {
                0%   { transform: scale(1); opacity: 0.4; }
                100% { transform: scale(2.5); opacity: 0; }
            }

            /* ─── Modal entrance ─── */
            @keyframes overlay-in { from { opacity: 0; } to { opacity: 1; } }
            @keyframes sheet-slide-up {
                from { transform: translateY(100%); }
                to   { transform: translateY(0); }
            }
            @keyframes sheet-desktop-in {
                from { opacity: 0; transform: translate(-50%, -45%) scale(0.95); }
                to   { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            }
            .sheet-overlay { animation: overlay-in 0.3s ease forwards; }
            .sheet { animation: sheet-slide-up 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
            @media (min-width: 768px) {
                .sheet { animation: sheet-desktop-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
            }

            /* Date picker styling */
            .date-grid-btn {
                transition: all 0.15s ease;
            }
            .date-grid-btn:hover:not(.active) {
                transform: scale(1.04);
                border-color: rgb(59 130 246 / 0.5);
            }
            .date-grid-btn.active {
                background: linear-gradient(135deg, rgb(37 99 235), rgb(59 130 246));
                color: white;
                border-color: rgb(37 99 235);
                box-shadow: 0 4px 14px rgb(59 130 246 / 0.4);
                transform: scale(1.05);
            }

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after { animation: none !important; transition: none !important; }
            }
        </style>
    @endpush

    @php
        $cards = [
            'exams' => [
                'title' => 'امتحانات',
                'desc'  => 'امتحانات هفته پیش رو را ثبت کنید',
                'count' => count($exams),
                'color' => 'blue',
                'hex' => '59 130 246',
                'icon'  => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
            ],
            'qas' => [
                'title' => 'پرسش و پاسخ کلاسی',
                'desc'  => 'پرسش‌و‌پاسخ‌های کلاسی هفته',
                'count' => count($qas),
                'color' => 'emerald',
                'hex' => '16 185 129',
                'icon'  => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>',
            ],
            'assignments' => [
                'title' => 'تکالیف',
                'desc'  => 'تکالیف هفته پیش رو',
                'count' => count($assignments),
                'color' => 'violet',
                'hex' => '139 92 246',
                'icon'  => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
            ],
            'requested' => [
                'title' => 'پارت درخواستی',
                'desc'  => 'درس‌های مدنظر شما',
                'count' => count($requestedParts),
                'color' => 'orange',
                'hex' => '249 115 22',
                'icon'  => '<path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>',
            ],
            'misc' => [
                'title' => 'متفرقه',
                'desc'  => 'توضیحات تکمیلی به مشاور',
                'count' => $miscDescription ? 1 : 0,
                'color' => 'amber',
                'hex' => '245 158 11',
                'icon'  => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
            ],
            'summary' => [
                'title' => 'خلاصه و ثبت نهایی',
                'desc'  => 'مرور و ثبت نهایی پیش‌جلسه',
                'count' => count($exams) + count($qas) + count($assignments) + count($requestedParts),
                'color' => 'pink',
                'hex' => '236 72 153',
                'icon'  => '<polyline points="20 6 9 17 4 12"/>',
            ],
        ];

        $timePresets = [15, 30, 45, 60, 90, 120];
    @endphp

    {{-- Grid pattern background --}}
    <div class="absolute inset-0 bg-grid pointer-events-none"></div>

    <div class="container mx-auto px-3 sm:px-4 max-w-5xl relative">

        {{-- ─── HEADER ─── --}}
        <div class="relative overflow-hidden rounded-3xl border border-border shadow-sm mb-6">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-400"></div>
            {{-- Decorative blob --}}
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-10 w-72 h-72 bg-blue-300/20 rounded-full blur-3xl"></div>

            <div class="relative px-5 py-6 sm:px-7 sm:py-7">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">
                            پیش‌جلسه مشاوره ({{ $session->title }})
                        </h1>
                        <p class="mt-2 text-xs sm:text-sm text-blue-100/90">
                            تاریخ جلسه:
                            <span class="font-semibold">{{ jalali($session->activation_date)->format('%d %B %Y') }}</span>
                            @if($session->session_time)
                                <span class="mx-1 text-blue-200/80">•</span>
                                <span>ساعت {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="flex flex-col items-stretch gap-2 sm:items-end">
                        <a wire:navigate href="{{ route('client.profile.consultation.sessions') }}"
                           class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-white/15 hover:bg-white/25 backdrop-blur px-4 py-2 text-xs sm:text-sm font-medium text-white transition-all hover:scale-105">
                            <span>بازگشت به لیست</span>
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/>
                            </svg>
                        </a>
                        @if(!$canEdit)
                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-500/20 backdrop-blur px-3 py-1 text-[11px] text-amber-100 ring-1 ring-amber-300/40">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/><circle cx="12" cy="12" r="10"/></svg>
                                فقط مشاهده
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/20 backdrop-blur px-3 py-1 text-[11px] text-emerald-100 ring-1 ring-emerald-300/40">
                                <span class="live-dot w-2 h-2 rounded-full bg-emerald-300 text-emerald-300"></span>
                                قابل ویرایش
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(!$canEdit)
            <div class="mb-6 flex items-start gap-2 rounded-2xl border border-amber-200/80 bg-amber-50 dark:bg-amber-500/10 px-4 py-3 text-xs text-amber-700 dark:border-amber-500/40 dark:text-amber-300">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A1 1 0 003.75 18h16.5a1 1 0 00.86-1.32l-7.4-12.82a1 1 0 00-1.72 0z"/>
                </svg>
                <p class="leading-6">زمان ویرایش پیش‌جلسه به پایان رسیده است. فقط می‌توانید اطلاعات ثبت‌شده را مشاهده کنید.</p>
            </div>
        @endif

        {{-- ─── 6 CARDS GRID ─── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @foreach($cards as $key => $card)
                <div class="pre-card group rounded-2xl border-2 border-border bg-card p-5 {{ $card['count'] > 0 ? 'is-completed' : '' }}"
                     style="--accent: rgb({{ $card['hex'] }});">

                    {{-- Icon + Badge --}}
                    <div class="flex items-start justify-between mb-4">
                        <div class="icon-box flex items-center justify-center w-12 h-12 rounded-xl border"
                             style="background: rgb({{ $card['hex'] }} / 0.1); color: rgb({{ $card['hex'] }}); border-color: rgb({{ $card['hex'] }} / 0.3);">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                {!! $card['icon'] !!}
                            </svg>
                        </div>

                        @if($card['count'] > 0)
                            <span class="badge-pop inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-[11px] font-black"
                                  style="background: rgb({{ $card['hex'] }} / 0.1); color: rgb({{ $card['hex'] }}); border-color: rgb({{ $card['hex'] }} / 0.3);">
                                @if($key === 'misc')
                                    ثبت شده ✓
                                @else
                                    {{ $card['count'] }} مورد
                                @endif
                            </span>
                        @endif
                    </div>

                    <h3 class="font-black text-base text-foreground mb-1">{{ $card['title'] }}</h3>
                    <p class="text-xs text-muted-foreground leading-6 mb-5">{{ $card['desc'] }}</p>

                    {{-- Action buttons --}}
                    @if($key === 'summary')
                        <button @click="openModal('summary')"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-white transition-all hover:scale-[1.02] active:scale-95"
                                style="background: linear-gradient(135deg, rgb({{ $card['hex'] }}), rgb({{ $card['hex'] }} / 0.85)); box-shadow: 0 4px 14px rgb({{ $card['hex'] }} / 0.4);">
                            مرور و ثبت نهایی
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                        </button>
                    @elseif($canEdit)
                        <button @click="openModal('{{ $key }}')"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-white transition-all hover:scale-[1.02] active:scale-95"
                                style="background: linear-gradient(135deg, rgb({{ $card['hex'] }}), rgb({{ $card['hex'] }} / 0.85)); box-shadow: 0 4px 14px rgb({{ $card['hex'] }} / 0.4);">
                            @if($card['count'] > 0)
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4z"/>
                                </svg>
                                مدیریت
                            @else
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                افزودن
                            @endif
                        </button>
                    @else
                        @if($card['count'] > 0)
                            <button @click="openModal('{{ $key }}')"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-secondary hover:bg-secondary/70 px-4 py-2.5 text-sm font-bold text-foreground border border-border transition-all hover:scale-[1.02]">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                مشاهده
                            </button>
                        @else
                            <div class="text-center py-2.5 text-xs text-muted-foreground italic">چیزی ثبت نشده</div>
                        @endif
                    @endif

                </div>
            @endforeach

        </div>


        {{-- ════════════════════════════════════════════════════════════════
             PERSISTENT MODALS (همیشه در DOM، فقط hidden)
             این روش از بسته شدن مودال موقع تغییر فیلد جلوگیری می‌کنه
             ════════════════════════════════════════════════════════════════ --}}

        {{-- ━━━━━━━━━━━━━━━━━━━━━━━ EXAMS MODAL ━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        <div x-show="openCard === 'exams'" x-cloak style="display: none;">
            <div class="sheet-overlay" @click="closeModal()"></div>
            <div class="sheet" @click.stop>
                <div class="sheet-handle"></div>

                <div class="sheet-header-pattern shrink-0 px-5 py-4 border-b border-border flex items-center justify-between"
                     style="--accent-color: rgb(59 130 246 / 0.25);">
                    <div class="flex items-center gap-3 relative">
                        <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-600 border border-blue-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-base">امتحانات هفته پیش رو</h3>
                            <p class="text-[11px] text-muted-foreground mt-0.5">امتحاناتی که در پیش داری رو ثبت کن</p>
                        </div>
                    </div>
                    <button @click="closeModal()" class="w-9 h-9 rounded-xl hover:bg-muted text-muted-foreground hover:text-foreground transition flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-5">

                    @if($canEdit && count($exams) > 0)
                        <div>
                            <h4 class="text-xs font-bold text-muted-foreground mb-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                ثبت شده ({{ count($exams) }} مورد)
                            </h4>
                            <div class="space-y-2">
                                @foreach($exams as $i => $exam)
                                    <div class="item-row flex items-center justify-between rounded-xl bg-blue-500/10 border border-blue-500/20 px-3 py-2.5 text-xs sm:text-sm"
                                         style="animation-delay: {{ $i * 0.05 }}s;">
                                        <div>
                                            <span class="text-muted-foreground">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($exam['exam_date'], 'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }} :</span>
                                            <span class="font-bold mr-1">{{ $exam['subject'] }}</span>
                                            <span class="text-muted-foreground">({{ $exam['part_count'] }} پارت × {{ $exam['time_per_part'] }} دقیقه)</span>
                                        </div>
                                        <button wire:click="deleteExam({{ $exam['id'] }})"
                                                class="text-red-500 hover:text-red-600 hover:bg-red-500/10 rounded-lg w-8 h-8 flex items-center justify-center transition-all hover:scale-110 mr-2 shrink-0">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M6 6l1 16h10l1-16"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($canEdit)
                        <div class="space-y-4 {{ count($exams) > 0 ? 'pt-4 border-t border-border' : '' }}">
                            <h4 class="text-xs font-bold text-muted-foreground flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                افزودن امتحان جدید
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold mb-1.5">درس را انتخاب کنید</label>
                                    @if(count($availableSubjects) > 0)
                                        <x-ui.select
                                            wire:model.live="examForm.cc_subject_id"
                                            :options="array_map(fn($s) => ['id' => $s['id'], 'name' => $s['name'] . ' (' . ($s['type'] === 'general' ? 'عمومی' : 'تخصصی') . ')'], $availableSubjects)"
                                            value-key="id" label-key="name"
                                            placeholder="انتخاب درس..."
                                        />
                                    @else
                                        <input type="text" wire:model="examForm.subject" placeholder="مثال: ریاضی"
                                               class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                                    @endif
                                    @error('examForm.subject')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold mb-1.5">فصل</label>
                                    <x-ui.select wire:model="examForm.cc_chapter_id" :options="$availableChapters"
                                                 value-key="id" label-key="name"
                                                 placeholder="ابتدا درس را انتخاب کنید"
                                                 :disabled="count($availableChapters) === 0"/>
                                </div>
                            </div>

                            {{-- Date picker (inline, not collapsible to avoid extra clicks) --}}
                            <div>
                                <label class="block text-xs font-semibold mb-2">تاریخ امتحان</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                    @foreach($availableDates as $dateItem)
                                        <button type="button"
                                                wire:click="$set('examForm.exam_date', '{{ $dateItem['value'] }}')"
                                                class="date-grid-btn flex flex-col items-center justify-center px-2 py-2.5 rounded-xl border-2 text-xs
                                                {{ $examForm['exam_date'] === $dateItem['value'] ? 'active' : 'border-border bg-background hover:border-blue-400' }}">
                                            <span class="font-bold text-[11px]">{{ $dateItem['day_name'] }}</span>
                                            <span class="text-[10px] opacity-80 mt-0.5">{{ $dateItem['day'] }} {{ $dateItem['month_name'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                                @error('examForm.exam_date')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                            </div>

                            {{-- Time per part FIRST --}}
                            <div>
                                <label class="block text-xs font-semibold mb-1">
                                    برای مطالعه‌ی این امتحان به نظرت چقدر زمان نیاز داری؟
                                </label>
                                <p class="text-[11px] text-muted-foreground mb-2.5">یکی از گزینه‌های پیشنهادی یا مقدار دلخواه را وارد کن</p>

                                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 mb-3">
                                    @foreach($timePresets as $t)
                                        <div class="chip {{ (int)$examForm['time_per_part'] === $t ? 'active' : '' }}"
                                             wire:click="$set('examForm.time_per_part', {{ $t }})">
                                            {{ $t }} د
                                        </div>
                                    @endforeach
                                </div>

                                <div class="counter-wrap" x-data="{ val: $wire.entangle('examForm.time_per_part') }">
                                    <button type="button" @click="val = Math.max(5, (parseInt(val)||0) - 5)" class="counter-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                    </button>
                                    <input type="number" min="5" x-model.number="val" class="counter-input" placeholder="دقیقه">
                                    <button type="button" @click="val = (parseInt(val)||0) + 5" class="counter-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Parts count SECOND - only after time is set --}}
                            @if($examForm['time_per_part'] > 0)
                                <div class="field-reveal">
                                    <label class="block text-xs font-semibold mb-1">
                                        برای بازدهی بیشتر، این زمان به چند قسمت تقسیم بشه؟
                                    </label>
                                    <p class="text-[11px] text-muted-foreground mb-2.5">
                                        هر قسمت تقریباً <span class="font-bold text-blue-600">{{ $examForm['time_per_part'] }} دقیقه</span>
                                    </p>

                                    <div class="counter-wrap" x-data="{ val: $wire.entangle('examForm.part_count') }">
                                        <button type="button" @click="val = Math.max(1, (parseInt(val)||0) - 1)" class="counter-btn">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                        </button>
                                        <input type="number" min="1" x-model.number="val" class="counter-input">
                                        <button type="button" @click="val = (parseInt(val)||0) + 1" class="counter-btn">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @if($canEdit)
                    <div class="shrink-0 p-4 border-t border-border bg-background/95 backdrop-blur">
                        <button wire:click="addExam" wire:loading.attr="disabled" wire:target="addExam"
                                class="btn-primary-fancy w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black">
                            <span wire:loading.remove wire:target="addExam" class="flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                افزودن امتحان
                            </span>
                            <span wire:loading wire:target="addExam" class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                        </button>
                    </div>
                @endif
            </div>
        </div>


        {{-- ━━━━━━━━━━━━━━━━━━━━━━━ QAS MODAL ━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        <div x-show="openCard === 'qas'" x-cloak style="display: none;">
            <div class="sheet-overlay" @click="closeModal()"></div>
            <div class="sheet" @click.stop>
                <div class="sheet-handle"></div>

                <div class="sheet-header-pattern shrink-0 px-5 py-4 border-b border-border flex items-center justify-between"
                     style="--accent-color: rgb(16 185 129 / 0.25);">
                    <div class="flex items-center gap-3 relative">
                        <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-600 border border-emerald-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-base">پرسش و پاسخ کلاسی</h3>
                            <p class="text-[11px] text-muted-foreground mt-0.5">پرسش‌و‌پاسخ‌های هفته پیش رو</p>
                        </div>
                    </div>
                    <button @click="closeModal()" class="w-9 h-9 rounded-xl hover:bg-muted transition flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-5">
                    @if($canEdit && count($qas) > 0)
                        <div>
                            <h4 class="text-xs font-bold text-muted-foreground mb-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                ثبت شده ({{ count($qas) }} مورد)
                            </h4>
                            <div class="space-y-2">
                                @foreach($qas as $i => $qa)
                                    <div class="item-row flex items-center justify-between rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-3 py-2.5 text-xs sm:text-sm"
                                         style="animation-delay: {{ $i * 0.05 }}s;">
                                        <div>
                                            <span class="text-muted-foreground">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($qa['qa_date'], 'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }} :</span>
                                            <span class="font-bold mr-1">{{ $qa['subject'] }}</span>
                                            <span class="text-muted-foreground">({{ $qa['part_count'] }} پارت × {{ $qa['time_per_part'] }} دقیقه)</span>
                                        </div>
                                        <button wire:click="deleteQa({{ $qa['id'] }})"
                                                class="text-red-500 hover:bg-red-500/10 rounded-lg w-8 h-8 flex items-center justify-center transition-all hover:scale-110 mr-2 shrink-0">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M6 6l1 16h10l1-16"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($canEdit)
                        <div class="space-y-4 {{ count($qas) > 0 ? 'pt-4 border-t border-border' : '' }}">
                            <h4 class="text-xs font-bold text-muted-foreground">افزودن پرسش‌و‌پاسخ جدید</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold mb-1.5">درس را انتخاب کنید</label>
                                    @if(count($availableSubjects) > 0)
                                        <x-ui.select
                                            wire:model.live="qaForm.cc_subject_id"
                                            :options="array_map(fn($s) => ['id' => $s['id'], 'name' => $s['name'] . ' (' . ($s['type'] === 'general' ? 'عمومی' : 'تخصصی') . ')'], $availableSubjects)"
                                            value-key="id" label-key="name"
                                            placeholder="انتخاب درس..."
                                        />
                                    @else
                                        <input type="text" wire:model="qaForm.subject" placeholder="مثال: فیزیک"
                                               class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm">
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold mb-1.5">فصل</label>
                                    <x-ui.select wire:model="qaForm.cc_chapter_id" :options="$availableChapters"
                                                 value-key="id" label-key="name"
                                                 placeholder="ابتدا درس را انتخاب کنید"
                                                 :disabled="count($availableChapters) === 0"/>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold mb-2">تاریخ پرسش و پاسخ</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                    @foreach($availableDates as $dateItem)
                                        <button type="button" wire:click="$set('qaForm.qa_date', '{{ $dateItem['value'] }}')"
                                                class="date-grid-btn flex flex-col items-center justify-center px-2 py-2.5 rounded-xl border-2 text-xs
                                                {{ $qaForm['qa_date'] === $dateItem['value'] ? 'active' : 'border-border bg-background hover:border-blue-400' }}">
                                            <span class="font-bold text-[11px]">{{ $dateItem['day_name'] }}</span>
                                            <span class="text-[10px] opacity-80 mt-0.5">{{ $dateItem['day'] }} {{ $dateItem['month_name'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                                @error('qaForm.qa_date')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold mb-1">
                                    برای آمادگی این پرسش‌و‌پاسخ چقدر زمان نیاز داری؟
                                </label>
                                <p class="text-[11px] text-muted-foreground mb-2.5">یکی از گزینه‌ها یا دلخواه</p>

                                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 mb-3">
                                    @foreach($timePresets as $t)
                                        <div class="chip {{ (int)$qaForm['time_per_part'] === $t ? 'active' : '' }}"
                                             wire:click="$set('qaForm.time_per_part', {{ $t }})">{{ $t }} د</div>
                                    @endforeach
                                </div>

                                <div class="counter-wrap" x-data="{ val: $wire.entangle('qaForm.time_per_part') }">
                                    <button type="button" @click="val = Math.max(5, (parseInt(val)||0) - 5)" class="counter-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                    </button>
                                    <input type="number" min="5" x-model.number="val" class="counter-input" placeholder="دقیقه">
                                    <button type="button" @click="val = (parseInt(val)||0) + 5" class="counter-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>

                            @if($qaForm['time_per_part'] > 0)
                                <div class="field-reveal">
                                    <label class="block text-xs font-semibold mb-1">این زمان به چند قسمت تقسیم بشه؟</label>
                                    <p class="text-[11px] text-muted-foreground mb-2.5">
                                        هر قسمت تقریباً <span class="font-bold text-emerald-600">{{ $qaForm['time_per_part'] }} دقیقه</span>
                                    </p>

                                    <div class="counter-wrap" x-data="{ val: $wire.entangle('qaForm.part_count') }">
                                        <button type="button" @click="val = Math.max(1, (parseInt(val)||0) - 1)" class="counter-btn">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                        </button>
                                        <input type="number" min="1" x-model.number="val" class="counter-input">
                                        <button type="button" @click="val = (parseInt(val)||0) + 1" class="counter-btn">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @if($canEdit)
                    <div class="shrink-0 p-4 border-t border-border bg-background/95 backdrop-blur">
                        <button wire:click="addQa" wire:loading.attr="disabled" wire:target="addQa"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black text-white transition-all hover:scale-[1.01] active:scale-95"
                                style="background: linear-gradient(135deg, rgb(5 150 105), rgb(16 185 129)); box-shadow: 0 4px 14px rgb(16 185 129 / 0.4);">
                            <span wire:loading.remove wire:target="addQa" class="flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                افزودن پرسش‌و‌پاسخ
                            </span>
                            <span wire:loading wire:target="addQa" class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                        </button>
                    </div>
                @endif
            </div>
        </div>


        {{-- ━━━━━━━━━━━━━━━━━━━━━ ASSIGNMENTS MODAL ━━━━━━━━━━━━━━━━━━━━ --}}
        <div x-show="openCard === 'assignments'" x-cloak style="display: none;">
            <div class="sheet-overlay" @click="closeModal()"></div>
            <div class="sheet" @click.stop>
                <div class="sheet-handle"></div>

                <div class="sheet-header-pattern shrink-0 px-5 py-4 border-b border-border flex items-center justify-between"
                     style="--accent-color: rgb(139 92 246 / 0.25);">
                    <div class="flex items-center gap-3 relative">
                        <div class="w-11 h-11 rounded-xl bg-violet-500/10 text-violet-600 border border-violet-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-base">تکالیف هفته</h3>
                            <p class="text-[11px] text-muted-foreground mt-0.5">تکالیف هفته‌ی پیش رو</p>
                        </div>
                    </div>
                    <button @click="closeModal()" class="w-9 h-9 rounded-xl hover:bg-muted transition flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-5">
                    @if($canEdit && count($assignments) > 0)
                        <div>
                            <h4 class="text-xs font-bold text-muted-foreground mb-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                ثبت شده ({{ count($assignments) }} مورد)
                            </h4>
                            <div class="space-y-2">
                                @foreach($assignments as $i => $assignment)
                                    <div class="item-row flex items-center justify-between rounded-xl bg-violet-500/10 border border-violet-500/20 px-3 py-2.5 text-xs sm:text-sm"
                                         style="animation-delay: {{ $i * 0.05 }}s;">
                                        <div>
                                            <span class="text-muted-foreground">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($assignment['due_date'], 'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }} :</span>
                                            <span class="font-bold mr-1">{{ $assignment['subject'] }}</span>
                                            <span class="text-muted-foreground">({{ $assignment['part_count'] }} پارت × {{ $assignment['time_per_part'] }} دقیقه)</span>
                                        </div>
                                        <button wire:click="deleteAssignment({{ $assignment['id'] }})"
                                                class="text-red-500 hover:bg-red-500/10 rounded-lg w-8 h-8 flex items-center justify-center transition-all hover:scale-110 mr-2 shrink-0">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M6 6l1 16h10l1-16"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($canEdit)
                        <div class="space-y-4 {{ count($assignments) > 0 ? 'pt-4 border-t border-border' : '' }}">
                            <h4 class="text-xs font-bold text-muted-foreground">افزودن تکلیف جدید</h4>

                            <div>
                                <label class="block text-xs font-semibold mb-1.5">درس را انتخاب کنید</label>
                                @if(count($availableSubjects) > 0)
                                    <x-ui.select
                                        wire:model.live="assignmentForm.cc_subject_id"
                                        :options="array_map(fn($s) => ['id' => $s['id'], 'name' => $s['name'] . ' (' . ($s['type'] === 'general' ? 'عمومی' : 'تخصصی') . ')'], $availableSubjects)"
                                        value-key="id" label-key="name"
                                        placeholder="انتخاب درس..."
                                    />
                                @else
                                    <input type="text" wire:model="assignmentForm.subject" placeholder="مثال: شیمی"
                                           class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm">
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs font-semibold mb-2">تاریخ تحویل تکلیف</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                    @foreach($availableDates as $dateItem)
                                        <button type="button" wire:click="$set('assignmentForm.due_date', '{{ $dateItem['value'] }}')"
                                                class="date-grid-btn flex flex-col items-center justify-center px-2 py-2.5 rounded-xl border-2 text-xs
                                                {{ $assignmentForm['due_date'] === $dateItem['value'] ? 'active' : 'border-border bg-background hover:border-blue-400' }}">
                                            <span class="font-bold text-[11px]">{{ $dateItem['day_name'] }}</span>
                                            <span class="text-[10px] opacity-80 mt-0.5">{{ $dateItem['day'] }} {{ $dateItem['month_name'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                                @error('assignmentForm.due_date')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold mb-1">برای انجام این تکلیف چقدر زمان نیاز داری؟</label>
                                <p class="text-[11px] text-muted-foreground mb-2.5">یکی از گزینه‌ها یا دلخواه</p>

                                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 mb-3">
                                    @foreach($timePresets as $t)
                                        <div class="chip {{ (int)$assignmentForm['time_per_part'] === $t ? 'active' : '' }}"
                                             wire:click="$set('assignmentForm.time_per_part', {{ $t }})">{{ $t }} د</div>
                                    @endforeach
                                </div>

                                <div class="counter-wrap" x-data="{ val: $wire.entangle('assignmentForm.time_per_part') }">
                                    <button type="button" @click="val = Math.max(5, (parseInt(val)||0) - 5)" class="counter-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                    </button>
                                    <input type="number" min="5" x-model.number="val" class="counter-input" placeholder="دقیقه">
                                    <button type="button" @click="val = (parseInt(val)||0) + 5" class="counter-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>

                            @if($assignmentForm['time_per_part'] > 0)
                                <div class="field-reveal">
                                    <label class="block text-xs font-semibold mb-1">این زمان به چند قسمت تقسیم بشه؟</label>
                                    <p class="text-[11px] text-muted-foreground mb-2.5">
                                        هر قسمت تقریباً <span class="font-bold text-violet-600">{{ $assignmentForm['time_per_part'] }} دقیقه</span>
                                    </p>

                                    <div class="counter-wrap" x-data="{ val: $wire.entangle('assignmentForm.part_count') }">
                                        <button type="button" @click="val = Math.max(1, (parseInt(val)||0) - 1)" class="counter-btn">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                        </button>
                                        <input type="number" min="1" x-model.number="val" class="counter-input">
                                        <button type="button" @click="val = (parseInt(val)||0) + 1" class="counter-btn">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @if($canEdit)
                    <div class="shrink-0 p-4 border-t border-border bg-background/95 backdrop-blur">
                        <button wire:click="addAssignment" wire:loading.attr="disabled" wire:target="addAssignment"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black text-white transition-all hover:scale-[1.01] active:scale-95"
                                style="background: linear-gradient(135deg, rgb(124 58 237), rgb(139 92 246)); box-shadow: 0 4px 14px rgb(139 92 246 / 0.4);">
                            <span wire:loading.remove wire:target="addAssignment" class="flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                افزودن تکلیف
                            </span>
                            <span wire:loading wire:target="addAssignment" class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                        </button>
                    </div>
                @endif
            </div>
        </div>


        {{-- ━━━━━━━━━━━━━━━━━━━━━ REQUESTED PARTS MODAL ━━━━━━━━━━━━━━━━━ --}}
        <div x-show="openCard === 'requested'" x-cloak style="display: none;">
            <div class="sheet-overlay" @click="closeModal()"></div>
            <div class="sheet" @click.stop>
                <div class="sheet-handle"></div>

                <div class="sheet-header-pattern shrink-0 px-5 py-4 border-b border-border flex items-center justify-between"
                     style="--accent-color: rgb(249 115 22 / 0.25);">
                    <div class="flex items-center gap-3 relative">
                        <div class="w-11 h-11 rounded-xl bg-orange-500/10 text-orange-600 border border-orange-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-base">پارت درخواستی</h3>
                            <p class="text-[11px] text-muted-foreground mt-0.5">درس‌هایی که می‌خوای در برنامه باشن</p>
                        </div>
                    </div>
                    <button @click="closeModal()" class="w-9 h-9 rounded-xl hover:bg-muted transition flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-5">
                    @if($canEdit && count($requestedParts) > 0)
                        <div>
                            <h4 class="text-xs font-bold text-muted-foreground mb-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                ثبت شده ({{ count($requestedParts) }} مورد)
                            </h4>
                            <div class="space-y-2">
                                @foreach($requestedParts as $i => $rp)
                                    <div class="item-row flex items-start justify-between rounded-xl bg-orange-500/10 border border-orange-500/20 px-3 py-2.5 text-xs sm:text-sm"
                                         style="animation-delay: {{ $i * 0.05 }}s;">
                                        <div class="flex-1">
                                            <span class="font-bold">{{ $rp['subject'] }}</span>
                                            <span class="text-muted-foreground mr-1">({{ $rp['part_count'] }} پارت × {{ $rp['time_per_part'] }} دقیقه)</span>
                                            @if(!empty($rp['description']))
                                                <p class="mt-1 text-muted-foreground text-[11px] leading-5">{{ $rp['description'] }}</p>
                                            @endif
                                        </div>
                                        <button wire:click="deleteRequestedPart({{ $rp['id'] }})"
                                                class="text-red-500 hover:bg-red-500/10 rounded-lg w-8 h-8 flex items-center justify-center transition-all hover:scale-110 mr-2 shrink-0">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M6 6l1 16h10l1-16"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($canEdit)
                        <div class="space-y-4 {{ count($requestedParts) > 0 ? 'pt-4 border-t border-border' : '' }}">
                            <h4 class="text-xs font-bold text-muted-foreground">افزودن پارت درخواستی</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold mb-1.5">درس را انتخاب کنید</label>
                                    @if(count($availableGradeSubjects) > 0)
                                        <x-ui.select-grouped
                                            wire:model.live="requestedPartForm.cc_subject_id"
                                            :groups="$availableGradeSubjects"
                                            group-label-key="grade_label"
                                            group-items-key="subjects"
                                            value-key="id" label-key="name"
                                            placeholder="انتخاب درس..."
                                            :searchable="true"
                                            search-placeholder="جستجوی درس..."
                                        />
                                    @else
                                        <input type="text" wire:model="requestedPartForm.subject" placeholder="مثال: ریاضی"
                                               class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm">
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold mb-1.5">فصل <span class="text-muted-foreground font-normal">(اختیاری)</span></label>
                                    <x-ui.select wire:model="requestedPartForm.cc_chapter_id" :options="$requestedPartChapters"
                                                 value-key="id" label-key="name"
                                                 placeholder="ابتدا درس را انتخاب کنید"
                                                 :disabled="count($requestedPartChapters) === 0"/>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold mb-1.5">توضیحات <span class="text-muted-foreground font-normal">(اختیاری)</span></label>
                                <textarea wire:model="requestedPartForm.description" rows="3"
                                          placeholder="مثال: از ابتدای فصل تا مبحث مشتق"
                                          class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold mb-1">چقدر زمان برای این درس می‌خوای؟</label>
                                <p class="text-[11px] text-muted-foreground mb-2.5">یکی از گزینه‌ها یا دلخواه</p>

                                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 mb-3">
                                    @foreach($timePresets as $t)
                                        <div class="chip {{ (int)$requestedPartForm['time_per_part'] === $t ? 'active' : '' }}"
                                             wire:click="$set('requestedPartForm.time_per_part', {{ $t }})">{{ $t }} د</div>
                                    @endforeach
                                </div>

                                <div class="counter-wrap" x-data="{ val: $wire.entangle('requestedPartForm.time_per_part') }">
                                    <button type="button" @click="val = Math.max(5, (parseInt(val)||0) - 5)" class="counter-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                    </button>
                                    <input type="number" min="5" x-model.number="val" class="counter-input" placeholder="دقیقه">
                                    <button type="button" @click="val = (parseInt(val)||0) + 5" class="counter-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>

                            @if($requestedPartForm['time_per_part'] > 0)
                                <div class="field-reveal">
                                    <label class="block text-xs font-semibold mb-1">این زمان به چند قسمت تقسیم بشه؟</label>
                                    <p class="text-[11px] text-muted-foreground mb-2.5">
                                        هر قسمت تقریباً <span class="font-bold text-orange-600">{{ $requestedPartForm['time_per_part'] }} دقیقه</span>
                                    </p>

                                    <div class="counter-wrap" x-data="{ val: $wire.entangle('requestedPartForm.part_count') }">
                                        <button type="button" @click="val = Math.max(1, (parseInt(val)||0) - 1)" class="counter-btn">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                        </button>
                                        <input type="number" min="1" x-model.number="val" class="counter-input">
                                        <button type="button" @click="val = (parseInt(val)||0) + 1" class="counter-btn">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @if($canEdit)
                    <div class="shrink-0 p-4 border-t border-border bg-background/95 backdrop-blur">
                        <button wire:click="addRequestedPart" wire:loading.attr="disabled" wire:target="addRequestedPart"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black text-white transition-all hover:scale-[1.01] active:scale-95"
                                style="background: linear-gradient(135deg, rgb(234 88 12), rgb(249 115 22)); box-shadow: 0 4px 14px rgb(249 115 22 / 0.4);">
                            <span wire:loading.remove wire:target="addRequestedPart" class="flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                افزودن پارت درخواستی
                            </span>
                            <span wire:loading wire:target="addRequestedPart" class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                        </button>
                    </div>
                @endif
            </div>
        </div>


        {{-- ━━━━━━━━━━━━━━━━━━━━━━━ MISC MODAL ━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        <div x-show="openCard === 'misc'" x-cloak style="display: none;">
            <div class="sheet-overlay" @click="closeModal()"></div>
            <div class="sheet" @click.stop>
                <div class="sheet-handle"></div>

                <div class="sheet-header-pattern shrink-0 px-5 py-4 border-b border-border flex items-center justify-between"
                     style="--accent-color: rgb(245 158 11 / 0.25);">
                    <div class="flex items-center gap-3 relative">
                        <div class="w-11 h-11 rounded-xl bg-amber-500/10 text-amber-600 border border-amber-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-base">متفرقه</h3>
                            <p class="text-[11px] text-muted-foreground mt-0.5">هر نکته‌ی دیگه‌ای برای مشاور</p>
                        </div>
                    </div>
                    <button @click="closeModal()" class="w-9 h-9 rounded-xl hover:bg-muted transition flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5">
                    <label class="block text-xs font-semibold mb-1.5">توضیحات تکمیلی</label>
                    <p class="text-[11px] text-muted-foreground mb-3 leading-5">
                        هر چیزی که فکر می‌کنی مشاورت باید بدونه — مشکلات، اهداف، نگرانی‌ها، نکات خاص و …
                    </p>
                    <textarea wire:model="miscDescription" rows="8"
                              class="w-full rounded-xl border border-border bg-background px-3 py-3 text-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition {{ !$canEdit ? 'opacity-60 cursor-not-allowed' : '' }}"
                              placeholder="توضیحات خود را اینجا بنویسید..."
                              {{ !$canEdit ? 'disabled' : '' }}></textarea>
                </div>

                @if($canEdit)
                    <div class="shrink-0 p-4 border-t border-border bg-background/95 backdrop-blur">
                        <button wire:click="saveMiscellaneous" @click="setTimeout(() => closeModal(), 400)"
                                wire:loading.attr="disabled" wire:target="saveMiscellaneous"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black text-white transition-all hover:scale-[1.01] active:scale-95"
                                style="background: linear-gradient(135deg, rgb(217 119 6), rgb(245 158 11)); box-shadow: 0 4px 14px rgb(245 158 11 / 0.4);">
                            <span wire:loading.remove wire:target="saveMiscellaneous" class="flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                ذخیره توضیحات
                            </span>
                            <span wire:loading wire:target="saveMiscellaneous" class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                        </button>
                    </div>
                @endif
            </div>
        </div>


        {{-- ━━━━━━━━━━━━━━━━━━━━━━━ SUMMARY MODAL ━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        <div x-show="openCard === 'summary'" x-cloak style="display: none;">
            <div class="sheet-overlay" @click="closeModal()"></div>
            <div class="sheet" @click.stop>
                <div class="sheet-handle"></div>

                <div class="sheet-header-pattern shrink-0 px-5 py-4 border-b border-border flex items-center justify-between"
                     style="--accent-color: rgb(236 72 153 / 0.25);">
                    <div class="flex items-center gap-3 relative">
                        <div class="w-11 h-11 rounded-xl bg-pink-500/10 text-pink-600 border border-pink-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-base">خلاصه پیش‌جلسه</h3>
                            <p class="text-[11px] text-muted-foreground mt-0.5">قبل از ثبت نهایی مرور کنید</p>
                        </div>
                    </div>
                    <button @click="closeModal()" class="w-9 h-9 rounded-xl hover:bg-muted transition flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-4">

                    <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                        <h4 class="mb-3 text-xs font-bold text-blue-600 flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                            امتحانات ({{ count($exams) }} مورد)
                        </h4>
                        @forelse($exams as $exam)
                            <div class="border-b border-dashed border-border py-1.5 text-xs sm:text-sm last:border-b-0">
                                {{ $exam['subject'] }} – {{ $exam['part_count'] }}×{{ $exam['time_per_part'] }}د – {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($exam['exam_date'], 'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }}
                            </div>
                        @empty
                            <p class="text-xs text-muted-foreground">ثبت نشده</p>
                        @endforelse
                    </div>

                    <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                        <h4 class="mb-3 text-xs font-bold text-emerald-600 flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/></svg>
                            پرسش‌و‌پاسخ ({{ count($qas) }} مورد)
                        </h4>
                        @forelse($qas as $qa)
                            <div class="border-b border-dashed border-border py-1.5 text-xs sm:text-sm last:border-b-0">
                                {{ $qa['subject'] }} – {{ $qa['part_count'] }}×{{ $qa['time_per_part'] }}د – {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($qa['qa_date'], 'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }}
                            </div>
                        @empty
                            <p class="text-xs text-muted-foreground">ثبت نشده</p>
                        @endforelse
                    </div>

                    <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                        <h4 class="mb-3 text-xs font-bold text-violet-600 flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            تکالیف ({{ count($assignments) }} مورد)
                        </h4>
                        @forelse($assignments as $assignment)
                            <div class="border-b border-dashed border-border py-1.5 text-xs sm:text-sm last:border-b-0">
                                {{ $assignment['subject'] }} – {{ $assignment['part_count'] }}×{{ $assignment['time_per_part'] }}د – {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($assignment['due_date'], 'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }}
                            </div>
                        @empty
                            <p class="text-xs text-muted-foreground">ثبت نشده</p>
                        @endforelse
                    </div>

                    <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                        <h4 class="mb-3 text-xs font-bold text-orange-600 flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/></svg>
                            پارت درخواستی ({{ count($requestedParts) }} مورد)
                        </h4>
                        @forelse($requestedParts as $rp)
                            <div class="border-b border-dashed border-border py-1.5 text-xs sm:text-sm last:border-b-0">
                                <span class="font-bold">{{ $rp['subject'] }}</span>
                                <span class="text-muted-foreground mr-1">– {{ $rp['part_count'] }}×{{ $rp['time_per_part'] }}د</span>
                                @if(!empty($rp['description']))<span class="text-muted-foreground">– {{ $rp['description'] }}</span>@endif
                            </div>
                        @empty
                            <p class="text-xs text-muted-foreground">ثبت نشده</p>
                        @endforelse
                    </div>

                    <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                        <h4 class="mb-3 text-xs font-bold text-amber-600 flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/></svg>
                            متفرقه
                        </h4>
                        @if($miscDescription)
                            <p class="text-xs sm:text-sm leading-7">{{ $miscDescription }}</p>
                        @else
                            <p class="text-xs text-muted-foreground">ثبت نشده</p>
                        @endif
                    </div>

                </div>

                @if($canEdit)
                    <div class="shrink-0 p-4 border-t border-border bg-background/95 backdrop-blur">
                        <button wire:click="finalSubmit" wire:loading.attr="disabled" wire:target="finalSubmit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black text-white transition-all hover:scale-[1.01] active:scale-95"
                                style="background: linear-gradient(135deg, rgb(219 39 119), rgb(236 72 153)); box-shadow: 0 4px 14px rgb(236 72 153 / 0.4);">
                            <span wire:loading.remove wire:target="finalSubmit" class="flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                ثبت نهایی پیش‌جلسه
                            </span>
                            <span wire:loading wire:target="finalSubmit" class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

    </div>
        @push('script')
            {{-- ─── Alpine data function ─── --}}
            <script>
                function preSessionWizard() {
                    return {
                        openCard: null,
                        init() {
                            // Listen for Livewire success events to give haptic feedback
                            Livewire.on('success', () => {
                                if (navigator.vibrate) navigator.vibrate(20);
                            });
                        },
                        openModal(key) {
                            this.openCard = key;
                            document.body.style.overflow = 'hidden';
                            if (navigator.vibrate) navigator.vibrate(8);
                        },
                        closeModal() {
                            this.openCard = null;
                            document.body.style.overflow = '';
                        },
                    };
                }
            </script>
        @endpush
</div>
