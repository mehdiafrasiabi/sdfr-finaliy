@php
    // تنظیمات بر اساس mode
    $config = match($mode) {
        'trial' => [
            'timerShow'   => false,
            'btnEnabled'  => true,
            'btnText'     => $submitted ? 'مشاهده و ویرایش' : 'شروع طبقه‌بندی آزمایشی',
            'btnVariant'  => $submitted ? 'success-soft' : 'primary',
            'btnIcon'     => $submitted ? 'eye' : 'chevron-left',
        ],
        'active' => [
            'timerShow'   => true,
            'timerLabel'  => 'زمان باقی‌مانده تا پایان',
            'timerTarget' => $project->end_at->toIso8601String(),
            'timerColor'  => 'success',
            'btnEnabled'  => true,
            'btnText'     => $submitted ? 'مشاهده و ویرایش' : 'شروع طبقه‌بندی',
            'btnVariant'  => $submitted ? 'success-soft' : 'success',
            'btnIcon'     => $submitted ? 'eye' : 'chevron-left',
        ],
        'upcoming' => [
            'timerShow'   => true,
            'timerLabel'  => 'زمان باقی‌مانده تا شروع',
            'timerTarget' => $project->start_at->toIso8601String(),
            'timerColor'  => 'warning',
            'btnEnabled'  => false,
            'btnText'     => 'هنوز شروع نشده',
            'btnVariant'  => 'warning-soft',
            'btnIcon'     => 'clock',
        ],
        'ended' => [
            'timerShow'   => false,
            'btnEnabled'  => false,
            'btnText'     => 'این پروژه تمام شده',
            'btnVariant'  => 'secondary',
            'btnIcon'     => 'x',
        ],
    };

    $isOpaque = $mode === 'ended';
@endphp

<div @if($config['timerShow']) x-data="projectTimer('{{ $config['timerTarget'] }}', '{{ $mode }}')" @endif
class="glass border border-border rounded-2xl overflow-hidden flex flex-col {{ $isOpaque ? 'opacity-70' : '' }}">

    {{-- ═══ موبایل ═══ --}}
    <div class="md:hidden">
        <x-ui.thumbnail class="w-full h-36">
            <img src="/client/icons/classification.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
        </x-ui.thumbnail>

        <div class="p-4 space-y-3">
            <div class="flex items-start justify-between gap-2">
                <h3 class="font-bold text-foreground text-base leading-snug flex-1">{{ $project->name }}</h3>
                @include('livewire.client.profile.classification._status-badge', ['mode' => $mode, 'submitted' => $submitted])
            </div>

            @if($project->description)
                <p class="text-xs text-muted leading-relaxed line-clamp-2">{{ $project->description }}</p>
            @endif

            <div class="flex flex-wrap gap-1.5">
                <span class="inline-flex items-center gap-1 rounded-lg bg-background border border-border px-2 py-1 text-[11px] font-semibold text-foreground">
                    <x-ui.icon name="calendar" class="w-3 h-3 text-success"/>
                    {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}
                </span>
                <span class="inline-flex items-center gap-1 rounded-lg bg-background border border-border px-2 py-1 text-[11px] font-semibold text-foreground">
                    <x-ui.icon name="clock" class="w-3 h-3 text-error"/>
                    {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}
                </span>
            </div>

            @if($config['timerShow'])
                @include('livewire.client.profile.classification._timer-strip', ['config' => $config])
            @endif
        </div>

        <div class="px-4 pb-4">
            <x-ui.button type="button" wire:click="{{ $wireAction }}" variant="{{ $config['btnVariant'] }}"
                         icon="{{ $config['btnIcon'] }}" :disabled="!$config['btnEnabled']" block>
                {{ $config['btnText'] }}
            </x-ui.button>
        </div>
    </div>

    {{-- ═══ دسکتاپ ═══ --}}
    {{-- توجه: این گرادیانِ آبی عمداً x-ui.thumbnail نشده — دارک‌مودش
         (#1e3a5f/#1e40af) با نسخه‌ی موبایل (blue-950/900) که همان کامپوننت
         تولید می‌کند کمی فرق دارد، و طبق درخواستِ صریحِ قبلی («این گرادیانِ
         آبی رو تغییر نده») دست‌کاری‌اش نکردم؛ اگر خواستی این دو رنگِ
         دارک‌مودِ موبایل/دسکتاپ هم یکی بشوند بگو تا از طریق thumbnail یکسان‌سازی کنم. --}}
    <div class="hidden md:flex flex-row min-h-[130px]">
        <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
            <img src="/client/icons/classification.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
        </div>

        <div class="flex-1 p-4 flex items-center justify-between gap-4">
            <div class="space-y-2 flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-bold text-foreground text-base leading-snug">{{ $project->name }}</h3>
                    @include('livewire.client.profile.classification._status-badge', ['mode' => $mode, 'submitted' => $submitted])
                </div>

                @if($project->description)
                    <p class="text-xs text-muted leading-relaxed line-clamp-1">{{ $project->description }}</p>
                @endif

                <div class="flex flex-wrap gap-1.5 items-center">
                    <span class="inline-flex items-center gap-1 rounded-lg bg-background border border-border px-2 py-0.5 text-[11px] font-semibold text-foreground">
                        <x-ui.icon name="calendar" class="w-3 h-3 text-success"/>
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-lg bg-background border border-border px-2 py-0.5 text-[11px] font-semibold text-foreground">
                        <x-ui.icon name="clock" class="w-3 h-3 text-error"/>
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}
                    </span>

                    @if($config['timerShow'])
                        @include('livewire.client.profile.classification._timer-inline', ['config' => $config])
                    @endif
                </div>
            </div>

            <div class="flex-shrink-0" dir="ltr">
                <x-ui.button type="button" wire:click="{{ $wireAction }}" variant="{{ $config['btnVariant'] }}"
                             icon="{{ $config['btnIcon'] }}" :disabled="!$config['btnEnabled']">
                    {{ $config['btnText'] }}
                </x-ui.button>
            </div>
        </div>
    </div>
</div>
