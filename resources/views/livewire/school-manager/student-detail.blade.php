<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">{{ $student->user?->name }} <span class="text-sm text-slate-400">({{ $student->grade }}، {{ $student->field }})</span></h1>
        <a href="{{ route('school-manager.students') }}" class="text-sm text-slate-500 hover:text-blue-500">بازگشت</a>
    </div>

    <div class="flex gap-2 mb-4 border-b border-slate-200 dark:border-slate-700">
        <button wire:click="switchTab('grades')"
                class="px-4 py-2 text-sm {{ $tab === 'grades' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-slate-500' }}">
            نمرات ({{ $grades->count() }})
        </button>
        <button wire:click="switchTab('reports')"
                class="px-4 py-2 text-sm {{ $tab === 'reports' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-slate-500' }}">
            گزارش‌ها ({{ $reports->count() }})
        </button>
        <button wire:click="switchTab('contacts')"
                class="px-4 py-2 text-sm {{ $tab === 'contacts' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-slate-500' }}">
            تماس‌ها ({{ $contacts->count() }})
        </button>
    </div>

    @if($tab === 'grades')
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 dark:bg-slate-700 text-xs">
                <tr>
                    <th class="px-3 py-2 text-right">درس</th>
                    <th class="px-3 py-2 text-right">فصل</th>
                    <th class="px-3 py-2 text-right">نمره</th>
                    <th class="px-3 py-2 text-right">تاریخ</th>
                    <th class="px-3 py-2 text-right">یادداشت</th>
                    <th class="px-3 py-2 text-right">ثبت‌کننده</th>
                </tr>
                </thead>
                <tbody>
                @forelse($grades as $g)
                    <tr class="border-t border-slate-200 dark:border-slate-700">
                        <td class="px-3 py-2">{{ $g->subject?->name ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $g->chapter?->name ?? '—' }}</td>
                        <td class="px-3 py-2"><strong>{{ $g->score }}</strong>/{{ $g->scale }}</td>
                        <td class="px-3 py-2">{{ jalali($g->recorded_at)->format('Y/m/d') }}</td>
                        <td class="px-3 py-2">{{ $g->note ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $g->recordedBy?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-6 text-slate-400">نمره‌ای ثبت نشده.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    @elseif($tab === 'reports')
        @forelse($reports as $r)
            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 mb-3 shadow">
                <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                    <div>
                        <strong>{{ jalali($r->report_date)->format('Y/m/d') }}</strong>
                        @php
                            $cls = match($r->status){
                                'approved' => 'bg-emerald-500',
                                'rejected' => 'bg-rose-500',
                                default => 'bg-amber-500',
                            };
                        @endphp
                        <span class="text-xs px-2 py-0.5 rounded text-white {{ $cls }}">{{ $r->status_label }}</span>
                    </div>
                    <div class="text-xs text-slate-500">
                        مطالعه: {{ $r->total_study_minutes }}د | موبایل: {{ $r->total_mobile_minutes }}د
                    </div>
                </div>
                <table class="w-full text-xs">
                    <thead class="text-slate-400"><tr><th class="text-right">درس</th><th class="text-right">فصل</th><th>مطالعه</th><th>موبایل</th></tr></thead>
                    <tbody>
                    @foreach($r->parts as $p)
                        <tr><td>{{ $p->subject?->name }}</td><td>{{ $p->chapter?->name }}</td><td class="text-center">{{ $p->study_minutes }}</td><td class="text-center">{{ $p->mobile_minutes }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
                @if($r->advisor_comment)
                    <div class="mt-2 text-xs text-slate-500">نظر پشتیبان: {{ $r->advisor_comment }}</div>
                @endif
            </div>
        @empty
            <div class="text-center py-6 text-slate-400">گزارشی ثبت نشده.</div>
        @endforelse
    @else
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 dark:bg-slate-700 text-xs">
                <tr>
                    <th class="px-3 py-2 text-right">تاریخ</th>
                    <th class="px-3 py-2 text-right">تماس با</th>
                    <th class="px-3 py-2 text-right">یادداشت</th>
                    <th class="px-3 py-2 text-right">ثبت‌کننده</th>
                </tr>
                </thead>
                <tbody>
                @forelse($contacts as $c)
                    <tr class="border-t border-slate-200 dark:border-slate-700">
                        <td class="px-3 py-2">{{ jalali($c->contacted_at)->format('Y/m/d H:i') }}</td>
                        <td class="px-3 py-2">{{ $c->contacted_with === 'father' ? 'پدر' : 'مادر' }}</td>
                        <td class="px-3 py-2">{{ $c->notes ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $c->admin?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-6 text-slate-400">تماسی ثبت نشده.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
