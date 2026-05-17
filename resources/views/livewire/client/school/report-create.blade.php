<div class="px-4 py-6 max-w-4xl mx-auto" x-data>
    <div class="flex items-center justify-between gap-3 mb-6">
        <h1 class="text-xl font-bold text-foreground">ثبت گزارش جدید</h1>
        <a wire:navigate href="{{ route('client.profile.school.report.index') }}"
           class="text-sm text-muted hover:text-foreground">بازگشت</a>
    </div>

    <div class="bg-secondary rounded-2xl p-5 mb-4">
        <label class="block text-sm font-medium text-foreground mb-2">تاریخ گزارش</label>
        <input type="date" wire:model="reportDate"
               class="w-full px-3 py-2 rounded-lg bg-background text-foreground border border-transparent focus:border-primary outline-none">
        @error('reportDate') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div class="bg-secondary rounded-2xl p-5 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-foreground">پارت‌های گزارش</h2>
            <button type="button" wire:click="openModal(-1)"
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary text-primary-foreground text-xs font-semibold">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                افزودن پارت
            </button>
        </div>

        @forelse($parts as $i => $p)
            @php
                $subject = \App\Models\CcSubject::with('grade')->find($p['cc_subject_id']);
                $chapter = \App\Models\CcChapter::find($p['cc_chapter_id']);
                $title = trim(($subject?->grade?->name ?? '') . ' » ' . ($subject?->name ?? '') . ' » ' . ($chapter?->name ?? ''), ' »');
            @endphp
            <div class="bg-background rounded-xl p-4 mb-2 flex items-center justify-between gap-3 flex-wrap">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-foreground truncate">{{ $title }}</div>
                    <div class="text-xs text-muted mt-1">
                        مطالعه: {{ intdiv($p['study_minutes'], 60) }} ساعت و {{ $p['study_minutes'] % 60 }} دقیقه
                        <span class="mx-2">•</span>
                        موبایل (غیرمفید): {{ intdiv($p['mobile_minutes'], 60) }} ساعت و {{ $p['mobile_minutes'] % 60 }} دقیقه
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" wire:click="openModal({{ $i }})"
                            class="text-xs px-2 py-1 rounded-md bg-secondary text-foreground hover:bg-primary hover:text-primary-foreground">
                        ویرایش
                    </button>
                    <button type="button" wire:click="removePart({{ $i }})"
                            class="text-xs px-2 py-1 rounded-md bg-rose-500/20 text-rose-500 hover:bg-rose-500 hover:text-white">
                        حذف
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center text-muted text-sm py-6">هیچ پارتی اضافه نشده — روی «افزودن پارت» کلیک کنید.</div>
        @endforelse

        @error('parts') <div class="text-xs text-rose-500 mt-2">{{ $message }}</div> @enderror
    </div>

    <div class="text-end">
        <button wire:click="submit"
                class="px-6 py-2.5 rounded-full bg-emerald-500 text-white text-sm font-bold hover:bg-emerald-600">
            <span wire:loading.remove wire:target="submit">ثبت گزارش</span>
            <span wire:loading wire:target="submit">در حال ارسال...</span>
        </button>
    </div>

    {{-- Modal --}}
    @if($modalOpen)
        <div class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center" x-cloak>
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeModal"></div>
            <div class="relative z-10 w-full sm:max-w-lg bg-secondary rounded-t-3xl sm:rounded-2xl p-5 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-foreground">
                        {{ $editingPartIndex >= 0 ? 'ویرایش پارت' : 'افزودن پارت جدید' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="text-muted hover:text-foreground">×</button>
                </div>

                {{-- Search --}}
                <div class="mb-4">
                    <label class="block text-xs text-muted mb-1">جست‌وجوی سریع فصل</label>
                    <input type="text" wire:model.live.debounce.300ms="modalSearch"
                           class="w-full px-3 py-2 rounded-lg bg-background text-foreground border border-transparent focus:border-primary outline-none"
                           placeholder="نام فصل را تایپ کنید...">
                    @if($searchResults->isNotEmpty())
                        <div class="mt-2 max-h-48 overflow-y-auto rounded-lg bg-background">
                            @foreach($searchResults as $r)
                                <button type="button" wire:click="selectSearchResult('chapter', {{ $r['id'] }})"
                                        class="w-full text-right px-3 py-2 hover:bg-primary hover:text-primary-foreground text-sm text-foreground border-b border-secondary last:border-0">
                                    {{ $r['label'] }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="text-center text-xs text-muted my-3">— یا انتخاب آبشاری —</div>

                {{-- Cascading selects --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mb-4">
                    <select wire:model.live="modalGradeId"
                            class="px-3 py-2 rounded-lg bg-background text-foreground text-sm">
                        <option value="">پایه</option>
                        @foreach($grades as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="modalSubjectId"
                            class="px-3 py-2 rounded-lg bg-background text-foreground text-sm"
                            @if(!$modalGradeId) disabled @endif>
                        <option value="">درس</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="modalChapterId"
                            class="px-3 py-2 rounded-lg bg-background text-foreground text-sm"
                            @if(!$modalSubjectId) disabled @endif>
                        <option value="">فصل</option>
                        @foreach($chapters as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Time inputs --}}
                <div class="mb-4">
                    <label class="block text-xs text-muted mb-1">مدت مطالعه</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <input type="number" min="0" max="23" wire:model="modalStudyHours"
                                   class="w-full px-3 py-2 rounded-lg bg-background text-foreground text-sm text-center"
                                   placeholder="ساعت">
                            <div class="text-[10px] text-muted text-center mt-1">ساعت</div>
                        </div>
                        <div>
                            <input type="number" min="0" max="59" wire:model="modalStudyMinutes"
                                   class="w-full px-3 py-2 rounded-lg bg-background text-foreground text-sm text-center"
                                   placeholder="دقیقه">
                            <div class="text-[10px] text-muted text-center mt-1">دقیقه</div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs text-muted mb-1">استفاده‌ی غیرمفید از موبایل</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <input type="number" min="0" max="23" wire:model="modalMobileHours"
                                   class="w-full px-3 py-2 rounded-lg bg-background text-foreground text-sm text-center"
                                   placeholder="ساعت">
                            <div class="text-[10px] text-muted text-center mt-1">ساعت</div>
                        </div>
                        <div>
                            <input type="number" min="0" max="59" wire:model="modalMobileMinutes"
                                   class="w-full px-3 py-2 rounded-lg bg-background text-foreground text-sm text-center"
                                   placeholder="دقیقه">
                            <div class="text-[10px] text-muted text-center mt-1">دقیقه</div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeModal"
                            class="px-4 py-2 rounded-full bg-background text-foreground text-sm">انصراف</button>
                    <button type="button" wire:click="savePart"
                            class="px-4 py-2 rounded-full bg-primary text-primary-foreground text-sm font-bold">ذخیره پارت</button>
                </div>
            </div>
        </div>
    @endif
</div>
