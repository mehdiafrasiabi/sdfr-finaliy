<div class="max-w-3xl mx-auto px-4 py-6 sm:py-10" dir="rtl">

    @if ($expired)
        <div class="bg-base-100 border border-base-300 rounded-2xl p-6 text-center">
            <div class="text-warning text-4xl mb-3">⚠</div>
            <h2 class="text-lg font-bold mb-2">لینک منقضی شده است</h2>
            <p class="text-sm text-base-content/70">برای دریافت لینک تازه با همکاران ما تماس بگیرید.</p>
        </div>
    @else
        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl font-bold mb-2">تست‌های والدینی</h1>
            <p class="text-sm text-base-content/70 leading-7">
                {{ $invitation->parent_role_label }} گرامی، لطفاً تمام تست‌های زیر را تکمیل کنید.
            </p>
        </div>

        @if (session()->has('info'))
            <div class="alert alert-info mb-4">{{ session('info') }}</div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-base-200 rounded-2xl p-4 mb-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium">پیشرفت کلی</span>
                <span class="text-sm font-bold text-primary">{{ $completedCount }} / {{ $totalCount }}</span>
            </div>
            <div class="w-full bg-base-300 rounded-full h-3 overflow-hidden">
                <div class="bg-primary h-3 transition-all"
                     style="width: {{ $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0 }}%"></div>
            </div>
        </div>

        @if ($invitation->isCompleted())
            <div class="bg-success/10 border border-success/30 rounded-2xl p-6 text-center">
                <div class="text-success text-4xl mb-3">✓</div>
                <h3 class="font-bold text-success mb-2">تمام تست‌ها تکمیل شد</h3>
                <p class="text-sm text-base-content/70">از همکاری شما سپاسگزاریم. می‌توانید این صفحه را ببندید.</p>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
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
                        <h3 class="font-bold text-base-content text-base">{{ $item->assessment->name_fa }}</h3>
                        <span class="badge {{ $badgeClass }} text-xs">{{ $badgeLabel }}</span>
                    </div>
                    @if ($item->assessment->description_fa)
                        <p class="text-sm text-base-content/70 leading-6 mb-4">{{ $item->assessment->description_fa }}</p>
                    @endif
                    <div class="mt-auto">
                        <div class="flex items-center justify-between text-xs text-base-content/60 mb-2">
                            <span>پیشرفت: {{ $item->answered }} از {{ $item->total }}</span>
                        </div>
                        @if ($item->total > 0)
                            <div class="w-full bg-base-300 rounded-full h-1.5 mb-3 overflow-hidden">
                                <div class="bg-primary h-1.5 transition-all"
                                     style="width: {{ round(($item->answered / $item->total) * 100) }}%"></div>
                            </div>
                        @endif
                        @if ($item->status === 'completed')
                            <button class="btn btn-success btn-sm w-full" disabled>تکمیل‌شده</button>
                        @elseif ($item->status === 'in_progress')
                            <button wire:click="start('{{ $item->assessment->slug }}')" class="btn btn-warning btn-sm w-full">
                                ادامه
                            </button>
                        @else
                            <button wire:click="start('{{ $item->assessment->slug }}')" class="btn btn-primary btn-sm w-full">
                                شروع
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
