<div class="max-w-4xl mx-auto px-4 py-6 sm:py-10" dir="rtl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">پروفایل روان‌شناختی شما</h1>
        <p class="text-sm text-base-content/70 leading-7">
            بر اساس پاسخ‌هایتان به آزمون‌های مرحله‌ی قبل، این پروفایل برای شما ساخته شد.
            لطفاً آن را مرور کنید. این پروفایل پایه‌ی برنامه‌ی هفتگی شما خواهد بود.
        </p>
    </div>

    @if (session()->has('info'))
        <div class="alert alert-info mb-4">{{ session('info') }}</div>
    @endif

    {{-- MBTI Card --}}
    @if (! empty($summary['mbti']['type']))
        <div class="bg-base-100 border border-base-300 rounded-2xl p-5 sm:p-6 mb-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div>
                    <p class="text-xs text-base-content/60 mb-1">تیپ شخصیتی شما</p>
                    <h2 class="text-2xl font-bold text-primary">
                        {{ $summary['mbti']['type'] }}
                        <span class="text-base font-medium text-base-content/80">— {{ $summary['mbti']['title'] }}</span>
                    </h2>
                </div>
            </div>
            <p class="text-sm leading-7 text-base-content/80 mb-3">{{ $summary['mbti']['description'] }}</p>
            <div class="bg-primary/5 border border-primary/20 rounded-xl p-3 mt-3">
                <p class="text-xs font-bold text-primary mb-1">توصیه برای یادگیری</p>
                <p class="text-sm leading-7">{{ $summary['mbti']['study_tip'] }}</p>
            </div>
        </div>
    @endif

    {{-- VARK Card --}}
    @if (! empty($summary['vark']['profile']))
        <div class="bg-base-100 border border-base-300 rounded-2xl p-5 sm:p-6 mb-5">
            <div class="mb-4">
                <p class="text-xs text-base-content/60 mb-1">سبک یادگیری</p>
                <h2 class="text-xl font-bold">
                    {{ $summary['vark']['profile'] }}
                    @if ($summary['vark']['is_multimodal'])
                        <span class="text-xs text-base-content/60 font-normal">(چندوجهی)</span>
                    @endif
                </h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach ($summary['vark']['modalities'] as $m)
                    <div class="rounded-xl border p-3 text-center
                                {{ $m['dominant'] ? 'border-primary bg-primary/5' : 'border-base-300' }}">
                        <p class="text-2xl font-bold {{ $m['dominant'] ? 'text-primary' : 'text-base-content/60' }}">
                            {{ $m['letter'] }}
                        </p>
                        <p class="text-xs leading-5 mt-1">{{ $m['title'] }}</p>
                        <p class="text-xs text-base-content/60 mt-1">{{ $m['percent'] }}%</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Mindset Facets --}}
    @if (! empty($summary['mindset_facets']))
        <div class="bg-base-100 border border-base-300 rounded-2xl p-5 sm:p-6 mb-5">
            <h2 class="text-lg font-bold mb-4">ذهنیت تحصیلی</h2>
            <div class="space-y-3">
                @foreach ($summary['mindset_facets'] as $facet)
                    @php
                        $level = $facet['level'] ?? 'medium';
                        $barColor = match ($level) {
                            'low'    => 'bg-success',
                            'high'   => 'bg-error',
                            default  => 'bg-warning',
                        };
                        $levelLabel = match ($level) {
                            'low'    => 'پایین',
                            'high'   => 'بالا',
                            default  => 'متوسط',
                        };
                        $badgeClass = match ($level) {
                            'low'    => 'badge-success',
                            'high'   => 'badge-error',
                            default  => 'badge-warning',
                        };
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-medium">{{ $facet['label'] }}</span>
                            <span class="badge {{ $badgeClass }} text-[10px]">{{ $levelLabel }} · {{ $facet['percent'] }}%</span>
                        </div>
                        <div class="w-full bg-base-300 rounded-full h-1.5 overflow-hidden">
                            <div class="{{ $barColor }} h-1.5 transition-all"
                                 style="width: {{ $facet['percent'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Acknowledge Button --}}
    <div class="bg-primary/5 border border-primary/30 rounded-2xl p-5 sm:p-6 mt-6">
        <p class="text-sm leading-7 mb-4 text-center text-base-content/80">
            با تأیید این پروفایل، آماده‌ی ساخت برنامه‌ی هفتگی اختصاصی توسط پشتیبان خواهید شد.
        </p>
        <button type="button"
                wire:click="acknowledge"
                wire:loading.attr="disabled"
                class="btn btn-primary w-full">
            <span wire:loading.remove wire:target="acknowledge">
                پروفایل من را تأیید می‌کنم و آماده‌ی شروع برنامه هستم
            </span>
            <span wire:loading wire:target="acknowledge">در حال ثبت...</span>
        </button>
    </div>
</div>
