<div class="p-6" dir="rtl">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">مدیریت آزمون‌های روان‌شناختی</h1>
        <button wire:click="openCreate" class="btn btn-primary btn-sm">+ آزمون جدید</button>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-error mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-base-100 rounded-xl border border-base-300 p-4 mb-4">
        <input type="text" wire:model.live.debounce.400ms="filterName"
               placeholder="جستجو بر اساس نام یا slug..."
               class="input input-bordered w-full max-w-md" />
    </div>

    <div class="bg-base-100 rounded-xl border border-base-300 overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>ترتیب</th>
                    <th>نام</th>
                    <th>Slug</th>
                    <th>نوع</th>
                    <th>مخاطب</th>
                    <th>سوالات</th>
                    <th>فعال</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assessments as $a)
                    <tr>
                        <td>{{ $a->display_order }}</td>
                        <td class="font-medium">{{ $a->name_fa }}</td>
                        <td><code class="text-xs">{{ $a->slug }}</code></td>
                        <td>{{ $a->kind_label }}</td>
                        <td>{{ $a->audience === 'student' ? 'دانش‌آموز' : 'والد' }}</td>
                        <td>{{ $a->questions_count }} / {{ $a->expected_question_count ?? '—' }}</td>
                        <td>
                            <button wire:click="toggleActive({{ $a->id }})"
                                    class="badge {{ $a->is_active ? 'badge-success' : 'badge-ghost' }} cursor-pointer">
                                {{ $a->is_active ? 'فعال' : 'غیرفعال' }}
                            </button>
                        </td>
                        <td class="flex gap-2">
                            <a href="{{ route('manager.assessments.questions', $a->id) }}"
                               class="btn btn-xs btn-info">سوالات</a>
                            <button wire:click="openEdit({{ $a->id }})" class="btn btn-xs btn-outline">ویرایش</button>
                            <button wire:click="delete({{ $a->id }})"
                                    wire:confirm="آیا مطمئنید؟"
                                    class="btn btn-xs btn-error">حذف</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-8 text-base-content/60">آزمونی ثبت نشده است.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $assessments->links() }}</div>

    {{-- Modal form --}}
    @if ($showForm)
        <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center p-4" wire:click.self="closeForm">
            <div class="bg-base-100 rounded-2xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-bold mb-4">{{ $editingId ? 'ویرایش آزمون' : 'آزمون جدید' }}</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label"><span class="label-text">Slug (انگلیسی)</span></label>
                        <input type="text" wire:model="f_slug" class="input input-bordered w-full" />
                        @error('f_slug') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label"><span class="label-text">نام فارسی</span></label>
                        <input type="text" wire:model="f_name_fa" class="input input-bordered w-full" />
                        @error('f_name_fa') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="label"><span class="label-text">توضیحات</span></label>
                        <textarea wire:model="f_description_fa" rows="3" class="textarea textarea-bordered w-full"></textarea>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">نوع آزمون</span></label>
                        <select wire:model="f_kind" class="select select-bordered w-full">
                            <option value="mbti">MBTI</option>
                            <option value="vark">VARK</option>
                            <option value="custom">اختصاصی</option>
                        </select>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">نوع سوال غالب</span></label>
                        <select wire:model="f_question_type" class="select select-bordered w-full">
                            <option value="mbti_binary">MBTI دوگزینه‌ای</option>
                            <option value="vark_multi">VARK چندگزینه‌ای</option>
                            <option value="likert5">لیکرت ۱-۵</option>
                            <option value="yes_no">بله/خیر</option>
                            <option value="mixed">ترکیبی</option>
                        </select>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">مخاطب</span></label>
                        <select wire:model="f_audience" class="select select-bordered w-full">
                            <option value="student">دانش‌آموز</option>
                            <option value="parent">والد (فاز ۲)</option>
                        </select>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">ترتیب نمایش</span></label>
                        <input type="number" min="0" wire:model="f_display_order" class="input input-bordered w-full" />
                    </div>
                    <div>
                        <label class="label"><span class="label-text">تعداد سوال موردانتظار</span></label>
                        <input type="number" min="0" wire:model="f_expected_count" class="input input-bordered w-full" />
                    </div>
                    <div class="flex items-center gap-4 sm:col-span-2">
                        <label class="cursor-pointer flex items-center gap-2">
                            <input type="checkbox" wire:model="f_is_active" class="toggle toggle-primary toggle-sm" />
                            <span>فعال</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                            <input type="checkbox" wire:model="f_is_required" class="toggle toggle-primary toggle-sm" />
                            <span>الزامی</span>
                        </label>
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
