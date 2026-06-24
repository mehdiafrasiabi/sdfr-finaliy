@php
    // تنظیمات بر اساس mode
    $config = match($mode) {
        'trial' => [
            'badgeText'   => 'آزمایشی',
            'badgeColor'  => 'text-blue-500 bg-blue-500/10 border-blue-500/30',
            'badgeIcon'   => 'star',
            'timerShow'   => false,
            'btnEnabled'  => true,
            'btnText'     => $submitted ? 'مشاهده و ویرایش' : 'شروع طبقه‌بندی آزمایشی',
            'btnClass'    => $submitted
                ? 'border border-emerald-500/40 bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500/15'
                : 'bg-primary hover:bg-primary/90 text-primary-foreground',
        ],
        'active' => [
            'badgeText'   => 'فعال',
            'badgeColor'  => 'text-emerald-500 bg-emerald-500/10 border-emerald-500/30',
            'badgeIcon'   => 'pulse',
            'timerShow'   => true,
            'timerLabel'  => 'زمان باقی‌مانده تا پایان',
            'timerTarget' => $project->end_at->toIso8601String(),
            'timerColor'  => 'emerald',
            'btnEnabled'  => true,
            'btnText'     => $submitted ? 'مشاهده و ویرایش' : 'شروع طبقه‌بندی',
            'btnClass'    => $submitted
                ? 'border border-emerald-500/40 bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500/15'
                : 'bg-emerald-500 hover:bg-emerald-600 text-white',
        ],
        'upcoming' => [
            'badgeText'   => 'در انتظار',
            'badgeColor'  => 'text-amber-500 bg-amber-500/10 border-amber-500/30',
            'badgeIcon'   => 'clock',
            'timerShow'   => true,
            'timerLabel'  => 'زمان باقی‌مانده تا شروع',
            'timerTarget' => $project->start_at->toIso8601String(),
            'timerColor'  => 'amber',
            'btnEnabled'  => false,
            'btnText'     => 'هنوز شروع نشده',
            'btnClass'    => 'bg-muted text-amber-500 cursor-not-allowed',
        ],
        'ended' => [
            'badgeText'   => 'تمام شده',
            'badgeColor'  => 'text-muted bg-secondary border-border',
            'badgeIcon'   => 'close',
            'timerShow'   => false,
            'btnEnabled'  => false,
            'btnText'     => 'این پروژه تمام شده',
            'btnClass'    => 'bg-muted text-red-500 cursor-not-allowed',
        ],
    };

    $isOpaque = $mode === 'ended';
@endphp

<div @if($config['timerShow']) x-data="projectTimer('{{ $config['timerTarget'] }}', '{{ $mode }}')" @endif
class="glass border border-border rounded-2xl overflow-hidden flex flex-col {{ $isOpaque ? 'opacity-70' : '' }}">

    {{-- ═══ موبایل ═══ --}}
    <div class="md:hidden">
        <div class="w-full h-36 flex items-center justify-center bg-gradient-to-b from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900">
            <img src="/client/icons/classification.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
        </div>

        <div class="p-4 space-y-3">
            <div class="flex items-start justify-between gap-2">
                <h3 class="font-bold text-foreground text-base leading-snug flex-1">{{ $project->name }}</h3>
                @include('livewire.client.profile.classification._status-badge', ['config' => $config, 'submitted' => $submitted])
            </div>

            @if($project->description)
                <p class="text-xs text-muted leading-relaxed line-clamp-2">{{ $project->description }}</p>
            @endif

            <div class="flex flex-wrap gap-1.5">
                <span class="inline-flex items-center gap-1 rounded-lg bg-background border border-border px-2 py-1 text-[11px] font-semibold text-foreground">
                    <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}
                </span>
                <span class="inline-flex items-center gap-1 rounded-lg bg-background border border-border px-2 py-1 text-[11px] font-semibold text-foreground">
                    <svg class="w-3 h-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}
                </span>
            </div>

            @if($config['timerShow'])
                @include('livewire.client.profile.classification._timer-strip', ['config' => $config])
            @endif
        </div>

        <div class="px-4 pb-4">
            @if($config['btnEnabled'])
                <button type="button" wire:click="{{ $wireAction }}"
                        class="w-full rounded-xl px-5 py-2.5 text-sm font-bold transition-all duration-200 active:scale-[0.98] flex items-center justify-center gap-2 {{ $config['btnClass'] }}">
                    {{ $config['btnText'] }}
                </button>
            @else
                <button type="button" disabled
                        class="w-full rounded-xl px-5 py-2.5 text-sm font-bold flex items-center justify-center gap-2 {{ $config['btnClass'] }}">
                    {{ $config['btnText'] }}
                </button>
            @endif
        </div>
    </div>

    {{-- ═══ دسکتاپ ═══ --}}
    <div class="hidden md:flex flex-row min-h-[130px]">
        <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
            <img src="/client/icons/classification.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
        </div>

        <div class="flex-1 p-4 flex items-center justify-between gap-4">
            <div class="space-y-2 flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-bold text-foreground text-base leading-snug">{{ $project->name }}</h3>
                    @include('livewire.client.profile.classification._status-badge', ['config' => $config, 'submitted' => $submitted])
                </div>

                @if($project->description)
                    <p class="text-xs text-muted leading-relaxed line-clamp-1">{{ $project->description }}</p>
                @endif

                <div class="flex flex-wrap gap-1.5 items-center">
                    <span class="inline-flex items-center gap-1 rounded-lg bg-background border border-border px-2 py-0.5 text-[11px] font-semibold text-foreground">
                        <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-lg bg-background border border-border px-2 py-0.5 text-[11px] font-semibold text-foreground">
                        <svg class="w-3 h-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}
                    </span>

                    @if($config['timerShow'])
                        @include('livewire.client.profile.classification._timer-inline', ['config' => $config])
                    @endif
                </div>
            </div>

            <div class="flex-shrink-0" dir="ltr">
                @if($config['btnEnabled'])
                    <button type="button" wire:click="{{ $wireAction }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-xl text-sm font-bold transition-colors {{ $config['btnClass'] }}">
                        {{ $config['btnText'] }}
                    </button>
                @else
                    <button type="button" disabled
                            class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-xl text-sm font-bold {{ $config['btnClass'] }}">
                        {{ $config['btnText'] }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
