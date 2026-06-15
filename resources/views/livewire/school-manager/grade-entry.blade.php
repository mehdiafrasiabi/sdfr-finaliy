<div
    x-data="{ ok:'' }"
    x-on:success.window="ok = $event.detail[0] || $event.detail; setTimeout(() => ok='', 3000)"
>
    <h1 class="text-xl font-bold mb-4">ثبت نمرات ماهانه</h1>

    <div x-show="ok" x-cloak class="mb-4 px-4 py-2 rounded-lg bg-emerald-500 text-white text-sm" x-text="ok"></div>

    {{-- مرحله ۱: انتخاب پایه --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow mb-4">
        <div class="text-sm font-bold mb-2">۱) انتخاب پایه</div>
        @if($grades->isEmpty())
            <div class="text-sm text-slate-400">پایه‌ای برای دانش‌آموزان مدرسه ثبت نشده.</div>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($grades as $g)
                    <button wire:click="selectGrade('{{ $g }}')"
                            class="px-4 py-2 rounded-lg text-sm {{ $selectedGrade === (string)$g ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700' }}">
                        پایه {{ $g }}
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- مرحله ۲: انتخاب دانش‌آموز --}}
    @if($selectedGrade !== '')
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow mb-4">
            <div class="text-sm font-bold mb-2">۲) انتخاب دانش‌آموز پایه {{ $selectedGrade }}</div>
            @if($students->isEmpty())
                <div class="text-sm text-slate-400">دانش‌آموزی در این پایه نیست.</div>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($students as $s)
                        <button wire:click="selectStudent({{ $s->id }})"
                                class="px-3 py-2 rounded-lg text-sm {{ $selectedStudentId === $s->id ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700' }}">
                            {{ $s->user?->name ?? '—' }}
                            <span class="text-xs opacity-70">({{ $s->field ?: 'بدون رشته' }})</span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- مرحله ۳: فرم ثبت نمره --}}
    @if($selectedStudent)
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow mb-4">
            <div class="text-sm font-bold mb-3">۳) ثبت نمره برای: {{ $selectedStudent->user?->name }}</div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs text-slate-500 mb-1">درس</label>
                    <select wire:model.live="cc_subject_id" class="w-full rounded-lg border-slate-300 dark:bg-slate-700 dark:border-slate-600 text-sm">
                        <option value="">انتخاب کنید…</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                    @error('cc_subject_id') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs text-slate-500 mb-1">فصل (اختیاری)</label>
                    <select wire:model="cc_chapter_id" @disabled(!$cc_subject_id) class="w-full rounded-lg border-slate-300 dark:bg-slate-700 dark:border-slate-600 text-sm disabled:opacity-50">
                        <option value="">—</option>
                        @foreach($chapters as $ch)
                            <option value="{{ $ch->id }}">{{ $ch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 mb-1">تاریخ ثبت</label>
                    <input type="date" wire:model="recorded_at" class="w-full rounded-lg border-slate-300 dark:bg-slate-700 dark:border-slate-600 text-sm">
                    @error('recorded_at') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs text-slate-500 mb-1">نمره</label>
                    <input type="number" step="0.25" wire:model="score" class="w-full rounded-lg border-slate-300 dark:bg-slate-700 dark:border-slate-600 text-sm">
                    @error('score') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs text-slate-500 mb-1">مقیاس</label>
                    <select wire:model="scale" class="w-full rounded-lg border-slate-300 dark:bg-slate-700 dark:border-slate-600 text-sm">
                        <option value="20">از ۲۰</option>
                        <option value="100">از ۱۰۰</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 mb-1">یادداشت (اختیاری)</label>
                    <input type="text" wire:model="note" maxlength="255" class="w-full rounded-lg border-slate-300 dark:bg-slate-700 dark:border-slate-600 text-sm">
                </div>
            </div>
            <div class="mt-4">
                <button wire:click="save" wire:loading.attr="disabled"
                        class="px-5 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="save">ثبت نمره</span>
                    <span wire:loading wire:target="save">در حال ثبت…</span>
                </button>
            </div>
        </div>

        {{-- نمرات اخیر این دانش‌آموز --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow overflow-hidden">
            <div class="px-4 py-2 text-sm font-bold border-b border-slate-200 dark:border-slate-700">نمرات اخیر</div>
            <table class="w-full text-sm">
                <thead class="bg-slate-100 dark:bg-slate-700 text-xs">
                <tr>
                    <th class="px-3 py-2 text-right">درس</th>
                    <th class="px-3 py-2 text-right">فصل</th>
                    <th class="px-3 py-2 text-right">نمره</th>
                    <th class="px-3 py-2 text-right">تاریخ</th>
                    <th class="px-3 py-2 text-right">یادداشت</th>
                </tr>
                </thead>
                <tbody>
                @forelse($recentGrades as $g)
                    <tr class="border-t border-slate-200 dark:border-slate-700">
                        <td class="px-3 py-2">{{ $g->subject?->name ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $g->chapter?->name ?? '—' }}</td>
                        <td class="px-3 py-2"><strong>{{ $g->score }}</strong>/{{ $g->scale }}</td>
                        <td class="px-3 py-2">{{ jalali($g->recorded_at)->format('Y/m/d') }}</td>
                        <td class="px-3 py-2">{{ $g->note ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-6 text-slate-400">نمره‌ای ثبت نشده.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
