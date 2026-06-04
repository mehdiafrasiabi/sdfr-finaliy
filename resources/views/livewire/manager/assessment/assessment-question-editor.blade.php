<div class="p-6" dir="rtl">

    <div class="mb-4">
        <a href="{{ route('manager.assessments.index') }}" class="text-sm text-base-content/70 hover:text-primary">
            ← بازگشت به فهرست آزمون‌ها
        </a>
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">سوالات آزمون: {{ $assessment->name_fa }}</h1>
        <button wire:click="openCreate" class="btn btn-primary btn-sm">+ سوال جدید</button>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-error mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-base-100 rounded-xl border border-base-300 overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>ترتیب</th>
                    <th>متن سوال</th>
                    <th>نوع</th>
                    <th>تعداد گزینه</th>
                    <th>فعال</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($questions as $q)
                    <tr>
                        <td>{{ $q->order }}</td>
                        <td class="max-w-md">{{ \Illuminate\Support\Str::limit($q->question_text_fa, 80) }}</td>
                        <td>{{ $q->type }}</td>
                        <td>{{ $q->options->count() }}</td>
                        <td>
                            <span class="badge {{ $q->is_active ? 'badge-success' : 'badge-ghost' }}">
                                {{ $q->is_active ? 'فعال' : 'غیرفعال' }}
                            </span>
                        </td>
                        <td class="flex gap-2">
                            <button wire:click="openEdit({{ $q->id }})" class="btn btn-xs btn-outline">ویرایش</button>
                            <button wire:click="deleteQuestion({{ $q->id }})"
                                    wire:confirm="آیا مطمئنید؟"
                                    class="btn btn-xs btn-error">حذف</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-8 text-base-content/60">سوالی ثبت نشده است.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if ($showForm)
        <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center p-4" wire:click.self="closeForm">
            <div class="bg-base-100 rounded-2xl p-6 max-w-3xl w-full max-h-[92vh] overflow-y-auto">
                <h2 class="text-xl font-bold mb-4">{{ $editingId ? 'ویرایش سوال' : 'سوال جدید' }}</h2>

                <div class="space-y-4">
                    <div>
                        <label class="label"><span class="label-text">متن سوال</span></label>
                        <textarea wire:model="f_text" rows="3" class="textarea textarea-bordered w-full"></textarea>
                        @error('f_text') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="label"><span class="label-text">نوع سوال</span></label>
                            <select wire:model.live="f_type" class="select select-bordered w-full">
                                <option value="likert5">لیکرت ۱-۵</option>
                                <option value="yes_no">بله/خیر</option>
                                <option value="mbti_binary">MBTI دوگزینه‌ای</option>
                                <option value="vark_multi">VARK چندگزینه‌ای</option>
                            </select>
                        </div>
                        <div>
                            <label class="label"><span class="label-text">ترتیب</span></label>
                            <input type="number" min="1" wire:model="f_order" class="input input-bordered w-full" />
                        </div>
                        <div class="flex items-end">
                            <label class="cursor-pointer flex items-center gap-2">
                                <input type="checkbox" wire:model="f_is_active" class="toggle toggle-primary toggle-sm" />
                                <span>فعال</span>
                            </label>
                        </div>
                    </div>

                    @if ($f_type === \App\Models\AssessmentQuestion::TYPE_MBTI_BINARY)
                        <div class="grid grid-cols-3 gap-4 bg-base-200 rounded-xl p-3">
                            <div>
                                <label class="label"><span class="label-text text-xs">محور MBTI</span></label>
                                <select wire:model="f_mbti_axis" class="select select-bordered w-full select-sm">
                                    <option value="EI">EI (برون‌گرا/درون‌گرا)</option>
                                    <option value="SN">SN (حسی/شهودی)</option>
                                    <option value="TF">TF (منطقی/احساسی)</option>
                                    <option value="JP">JP (قضاوتی/ادراکی)</option>
                                </select>
                            </div>
                            <div>
                                <label class="label"><span class="label-text text-xs">قطب گزینه A</span></label>
                                <input type="text" wire:model="f_mbti_a_pole" maxlength="1" class="input input-bordered w-full input-sm" />
                            </div>
                            <div>
                                <label class="label"><span class="label-text text-xs">قطب گزینه B</span></label>
                                <input type="text" wire:model="f_mbti_b_pole" maxlength="1" class="input input-bordered w-full input-sm" />
                            </div>
                        </div>
                    @endif

                    @if (in_array($f_type, [\App\Models\AssessmentQuestion::TYPE_LIKERT5, \App\Models\AssessmentQuestion::TYPE_YES_NO]))
                        <div class="grid grid-cols-2 gap-4 bg-base-200 rounded-xl p-3">
                            <div>
                                <label class="label"><span class="label-text text-xs">facet (دسته‌بندی داخلی)</span></label>
                                <input type="text" wire:model="f_facet" class="input input-bordered w-full input-sm" />
                            </div>
                            <div class="flex items-end">
                                <label class="cursor-pointer flex items-center gap-2">
                                    <input type="checkbox" wire:model="f_reverse" class="toggle toggle-warning toggle-sm" />
                                    <span class="text-xs">امتیاز معکوس</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="label-text">گزینه‌ها</span>
                            <button type="button" wire:click="addOption" class="btn btn-xs btn-ghost">+ گزینه</button>
                        </div>
                        @error('options') <p class="text-error text-xs mb-2">{{ $message }}</p> @enderror

                        <div class="space-y-2">
                            @foreach ($options as $i => $opt)
                                <div wire:key="opt-{{ $i }}" class="grid grid-cols-12 gap-2 items-center bg-base-200 rounded-lg p-2">
                                    <input type="text" wire:model="options.{{ $i }}.label" placeholder="متن گزینه" class="input input-xs input-bordered col-span-5" />
                                    <input type="text" wire:model="options.{{ $i }}.value" placeholder="value" class="input input-xs input-bordered col-span-2" />
                                    @if ($f_type === \App\Models\AssessmentQuestion::TYPE_VARK_MULTI)
                                        <input type="number" wire:model="options.{{ $i }}.weight_v" placeholder="V" class="input input-xs input-bordered col-span-1" />
                                        <input type="number" wire:model="options.{{ $i }}.weight_a" placeholder="A" class="input input-xs input-bordered col-span-1" />
                                        <input type="number" wire:model="options.{{ $i }}.weight_r" placeholder="R" class="input input-xs input-bordered col-span-1" />
                                        <input type="number" wire:model="options.{{ $i }}.weight_k" placeholder="K" class="input input-xs input-bordered col-span-1" />
                                    @else
                                        <div class="col-span-4"></div>
                                    @endif
                                    <button type="button" wire:click="removeOption({{ $i }})" class="btn btn-xs btn-error col-span-1">×</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button wire:click="closeForm" class="btn btn-ghost btn-sm">لغو</button>
                    <button wire:click="save" class="btn btn-primary btn-sm">ذخیره</button>
                </div>
            </div>
        </div>
    @endif
</div>
