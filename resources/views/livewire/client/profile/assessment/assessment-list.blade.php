<div class="max-w-5xl mx-auto px-4 py-6 sm:py-10" dir="rtl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-base-content mb-2">آزمون‌های روان‌شناختی</h1>
        <p class="text-sm text-base-content/70 leading-7">
            برای شروع فرایند تخصیص پشتیبان و طراحی برنامه‌ی اختصاصی، لطفاً تمام آزمون‌های زیر را تکمیل کنید.
            هر آزمون را می‌توانید جداگانه شروع کنید و در صورت نیمه‌کاره ماندن، از همان سوال بعدی ادامه دهید.
        </p>
    </div>

    @if (session()->has('info'))
        <div class="alert alert-info mb-4">{{ session('info') }}</div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-base-200 rounded-2xl p-4 sm:p-6 mb-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium">پیشرفت کلی</span>
            <span class="text-sm font-bold text-primary">{{ $completedCount }} / {{ $totalCount }}</span>
        </div>
        <div class="w-full bg-base-300 rounded-full h-3 overflow-hidden">
            <div class="bg-primary h-3 transition-all duration-500"
                 style="width: {{ $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0 }}%"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach ($items as $item)
            @php
                $badgeClass = match ($item->status) {
                    'completed'   => 'badge-success',
                    'in_progress' => 'badge-warning',
                    default       => 'badge-ghost',
                };
                $badgeLabel = match ($item->status) {
                    'completed'   => 'تکمیل‌شده',
                    'in_progress' => 'در حال انجام',
                    default       => 'شروع نشده',
                };
            @endphp
            <div class="bg-base-100 border border-base-300 rounded-2xl p-5 flex flex-col">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-bold text-base-content text-lg">{{ $item->assessment->name_fa }}</h3>
                        <p class="text-xs text-base-content/60 mt-1">{{ $item->assessment->kind_label }}</p>
                    </div>
                    <span class="badge {{ $badgeClass }} text-xs">{{ $badgeLabel }}</span>
                </div>

                @if ($item->assessment->description_fa)
                    <p class="text-sm text-base-content/70 leading-6 mb-4">{{ $item->assessment->description_fa }}</p>
                @endif

                <div class="mt-auto">
                    <div class="flex items-center justify-between text-xs text-base-content/60 mb-2">
                        <span>پیشرفت: {{ $item->answered }} از {{ $item->total }}</span>
                        @if ($item->total > 0)
                            <span>{{ round(($item->answered / $item->total) * 100) }}%</span>
                        @endif
                    </div>
                    @if ($item->total > 0)
                        <div class="w-full bg-base-300 rounded-full h-1.5 mb-3 overflow-hidden">
                            <div class="bg-primary h-1.5 transition-all"
                                 style="width: {{ round(($item->answered / $item->total) * 100) }}%"></div>
                        </div>
                    @endif

                    @if ($item->status === 'completed')
                        <button class="btn btn-success btn-sm w-full" disabled>تکمیل شده</button>
                    @elseif ($item->status === 'in_progress')
                        <button wire:click="start('{{ $item->assessment->slug }}')" class="btn btn-warning btn-sm w-full">
                            ادامه‌ی آزمون
                        </button>
                    @else
                        <button wire:click="start('{{ $item->assessment->slug }}')" class="btn btn-primary btn-sm w-full">
                            شروع آزمون
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if ($items->isEmpty())
        <div class="bg-base-200 rounded-2xl p-8 text-center text-base-content/70">
            هیچ آزمونی برای نمایش وجود ندارد.
        </div>
    @endif
</div>
