{{--
    محتوای داخلیِ مودالِ جزئیاتِ درس — قبلاً عیناً (کلمه‌به‌کلمه) هم توی نسخه‌ی
    دسکتاپ و هم موبایلِ subject-detail-modal.blade.php کپی شده بود (حتی با یک
    کامنتِ یادآوری برای جدا کردنش که هیچ‌وقت انجام نشده بود)؛ الان یک‌بار اینجا
    نوشته شده و مودالِ بیرونی (که خودش هم به شکلِ استانداردِ یکسانِ همه‌ی
    مودال‌های پروژه یکپارچه شده) فقط همین یک نسخه را @include می‌کند.
--}}
<button type="button" @click="$wire.close()" data-elevated="false"
        class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors">
    <x-ui.icon name="x" class="w-4 h-4"/>
</button>

<div class="p-6">
    {{-- عنوان --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-black text-foreground">{{ $subjectName }}</h2>
            <p class="text-xs text-muted">پیشرفت فصل‌ها در این ماه</p>
        </div>
    </div>

    @if(empty($subjectData))
        <div class="py-10 text-center text-muted">
            <svg class="mx-auto mb-3 w-16 h-16 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375"/>
            </svg>
            <p>داده‌ای برای این درس ثبت نشده است.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($subjectData as $chapter)
                @php
                    $badgeClass = $chapter['percent'] >= 70 ? 'bg-success/15 text-success'
                        : ($chapter['percent'] >= 40 ? 'bg-warning/15 text-warning' : 'bg-error/15 text-error');
                    $fillClass = $chapter['percent'] >= 70 ? 'bg-success'
                        : ($chapter['percent'] >= 40 ? 'bg-warning' : 'bg-error');
                @endphp
                <div class="bg-secondary border border-border rounded-xl p-4 transition-all hover:border-primary/40">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-foreground">{{ $chapter['chapter_name'] }}</span>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $badgeClass }}">
                            {{ $chapter['percent'] }}%
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex-1 h-2 bg-background border border-border rounded-full overflow-hidden">
                            <div class="h-full rounded-full progress-fill {{ $fillClass }}"
                                 style="width: {{ min(100, $chapter['percent']) }}%"></div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-[11px] text-muted">
                        <span>📚 {{ $chapter['parts_studied'] }}/{{ $chapter['parts_total'] }} پارت مطالعه‌شده</span>
                        <span class="opacity-50">|</span>
                        <span>⏱️ {{ $this->formatMinutes($chapter['studied_minutes']) }} از {{ $this->formatMinutes($chapter['planned_minutes']) }}</span>
                    </div>
                    <div class="flex items-center gap-1 mt-2 flex-wrap text-[10px]">
                        @if($chapter['quality']['عالی'])
                            <span class="px-2 py-0.5 rounded-full bg-success/15 text-success font-semibold">عالی: {{ $chapter['quality']['عالی'] }}</span>
                        @endif
                        @if($chapter['quality']['با کیفیت'])
                            <span class="px-2 py-0.5 rounded-full bg-info/15 text-info font-semibold">با کیفیت: {{ $chapter['quality']['با کیفیت'] }}</span>
                        @endif
                        @if($chapter['quality']['بی‌کیفیت'])
                            <span class="px-2 py-0.5 rounded-full bg-error/15 text-error font-semibold">بی‌کیفیت: {{ $chapter['quality']['بی‌کیفیت'] }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
