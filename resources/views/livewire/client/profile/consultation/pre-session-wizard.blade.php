<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-6 sm:py-10" dir="rtl">
    <div class="container mx-auto px-3 sm:px-4 max-w-4xl">

        @push('link')
            <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
            <style>
                [x-cloak] {
                    display: none !important;
                }

                .wizard-header-gradient {
                    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 40%, #0f172a 100%);
                }

                .wizard-card-shadow {
                    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18),
                    0 10px 20px rgba(15, 23, 42, 0.06);
                }

                .wizard-step-connector {
                    transition: background-color 0.2s ease, opacity 0.2s ease;
                }
            </style>
        @endpush

        {{-- HEADER --}}
        <div
            class="wizard-card-shadow overflow-hidden rounded-2xl border border-slate-100/80 bg-white/95 dark:border-slate-800 dark:bg-slate-900/95">
            <div class="wizard-header-gradient px-5 py-6 sm:px-7 sm:py-7">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">
                            پیش‌جلسه مشاوره
                        </h1>
                        <p class="text-sm text-blue-100">
                            {{ $session->title }}
                        </p>

                        <p class="mt-3 text-xs sm:text-sm text-blue-100/90">
                            تاریخ جلسه:
                            <span class="font-semibold">
                                {{ jalali($session->activation_date)->format('%d %B %Y') }}
                            </span>

                            @if($session->session_time)
                                <span class="mx-1 text-blue-200/80">•</span>
                                <span>
                                    ساعت
                                    {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}
                                </span>
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-col items-stretch gap-2 sm:items-end">
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-black/10 px-3 py-1 text-[11px] text-blue-100/90 ring-1 ring-blue-200/40">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                            <span>
                                مرحله فعلی:
                                <strong class="mr-1">{{ $stepTitles[$currentStep] ?? '' }}</strong>
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- WIZARD STEPS --}}
            <div
                class="border-b border-slate-100/80 bg-slate-50/70 px-3 py-3 sm:px-5 sm:py-4 dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-1 sm:gap-2">
                    @foreach($stepTitles as $step => $title)
                        <div class="flex items-center {{ $step < $totalSteps ? 'flex-1' : '' }}">
                            {{-- Circle --}}
                            <button
                                wire:click="goToStep({{ $step }})"
                                class="relative flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full text-xs font-bold transition-all
                                @if($currentStep === $step)
                                    bg-blue-600 text-white shadow-sm ring-2 ring-blue-300/80 dark:bg-blue-500 dark:ring-blue-300
                                @elseif($currentStep > $step)
                                    bg-emerald-500 text-white shadow-sm ring-1 ring-emerald-300/70 dark:bg-emerald-500
                                @else
                                    bg-slate-200 text-slate-600 ring-1 ring-slate-300/80 dark:bg-slate-800 dark:text-slate-200 dark:ring-slate-700
                                @endif"
                            >
                                @if($currentStep > $step)
                                    <i class="material-symbols-outlined">مشاهده</i>
                                @else
                                    {{ $step }}
                                @endif
                            </button>

                            {{-- Title (hidden on small screens) --}}
                            <span class="mr-2 hidden text-[11px] sm:inline-block sm:text-xs
                                @if($currentStep === $step)
                                    font-medium text-blue-700 dark:text-blue-300
                                @elseif($currentStep > $step)
                                    text-emerald-600 dark:text-emerald-300
                                @else
                                    text-slate-500 dark:text-slate-400
                                @endif">
                                {{ $title }}
                            </span>

                            {{-- Connector --}}
                            @if($step < $totalSteps)
                                <div class="wizard-step-connector ml-2 flex-1">
                                    <div class="h-1 rounded-full bg-slate-200 dark:bg-slate-700">
                                        <div
                                            class="h-1 rounded-full
                                            @if($currentStep > $step)
                                                bg-emerald-500 dark:bg-emerald-400
                                            @elseif($currentStep === $step)
                                                bg-blue-500 dark:bg-blue-400
                                            @else
                                                bg-transparent
                                            @endif"
                                        ></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- عنوان مرحله روی موبایل --}}
                <div class="mt-3 text-xs text-slate-600 sm:hidden dark:text-slate-300">
                    <span class="font-medium text-slate-800 dark:text-slate-100">
                        {{ $stepTitles[$currentStep] ?? '' }}
                    </span>
                </div>
            </div>

            {{-- STEP CONTENT --}}
            <div
                class="bg-white/95 p-4 sm:p-6 border-t border-slate-100/80 rounded-b-2xl dark:bg-slate-900/95 dark:border-slate-800">

                {{-- هشدار عدم امکان ویرایش --}}
                @if(!$canEdit)
                    <div
                        class="mb-6 flex items-start gap-2 rounded-xl border border-amber-200/80 bg-amber-50 px-3 py-3 text-xs text-amber-800 dark:border-amber-700 dark:bg-amber-900/30 dark:text-amber-200">
                        <span class="mt-0.5">
                            <i class="material-symbols-outlined text-base">warning</i>
                        </span>
                        <p>
                            زمان ویرایش پیش‌جلسه به پایان رسیده است.
                            <span class="font-medium">فقط می‌توانید اطلاعات ثبت شده را مشاهده کنید.</span>
                        </p>
                    </div>
                @endif

                {{-- مرحله ۱: امتحانات --}}
                @if($currentStep === 1)
                    <h3 class="mb-2 text-base sm:text-lg font-bold text-slate-900 dark:text-slate-50">
                        امتحانات
                    </h3>
                    <p class="mb-5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        تمام امتحاناتی که در هفته پیش رو را  دارید ، ثبت کنید.
                    </p>

                    @if($canEdit)
                        <div class="mb-6 rounded-xl bg-slate-50 px-3 py-4 sm:px-4 sm:py-5 dark:bg-slate-800/80">
                            <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-2 mb-4">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        نام درس
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="examForm.subject"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                        placeholder="مثال: ریاضی"
                                    >
                                    @error('examForm.subject')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        تاریخ امتحان
                                    </label>
                                    <input
                                        type="text"
                                        data-jdp
                                        wire:model="examForm.exam_date"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                        placeholder="انتخاب تاریخ"
                                    >
                                    @error('examForm.exam_date')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        تعداد پارت (پیشنهادی جهت مطالعه امتحان فوق)
                                    </label>
                                    <input
                                        type="number"
                                        min="1"
                                        wire:model="examForm.part_count"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                    >
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        زمان هر پارت (دقیقه)
                                    </label>
                                    <input
                                        type="number"
                                        min="1"
                                        wire:model="examForm.time_per_part"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                    >
                                </div>
                            </div>

                            <button
                                wire:click="addExam"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition
                                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1 focus:ring-offset-slate-50
                                       dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-slate-900"
                            >
                                افزودن امتحان
                            </button>
                        </div>
                    @endif

                    {{-- لیست امتحانات --}}
                    @if(count($exams) > 0)
                        <div class="space-y-2">
                            @foreach($exams as $exam)
                                <div
                                    class="flex items-center justify-between rounded-xl bg-blue-50 px-3 py-2.5 text-xs sm:text-sm text-slate-800 dark:bg-slate-800/80 dark:text-slate-50">
                                    <div class="space-x-1 space-x-reverse">
                                        <span class="text-muted">امتحان ({{ jalali($exam['exam_date'])->format('Y/m/d') }}) :</span>
                                        <span class="font-medium">{{ $exam['subject'] }}

                                            <span class="text-slate-500 dark:text-slate-300">
 ({{ $exam['part_count'] }} پارت)
                                        </span>
                                        </span>


                                    </div>

                                    @if($canEdit)
                                        <button
                                            wire:click="deleteExam({{ $exam['id'] }})"
                                            class="text-red-500 transition hover:text-red-600"
                                        >
                                            <i class="material-symbols-outlined text-base">حذف</i>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-4 text-center text-xs text-slate-400 dark:text-slate-500">
                            هیچ امتحانی ثبت نشده است.
                        </p>
                    @endif
                @endif

                {{-- مرحله ۲: پرسش و پاسخ --}}
                @if($currentStep === 2)
                    <h3 class="mb-2 text-base sm:text-lg font-bold text-slate-900 dark:text-slate-50">
                        پرسش و پاسخ کلاسی
                    </h3>
                    <p class="mb-5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        پرسش و پاسخ‌های کلاسی هفته پیش رو را ثبت کنید.
                    </p>

                    @if($canEdit)
                        <div class="mb-6 rounded-xl bg-slate-50 px-3 py-4 sm:px-4 sm:py-5 dark:bg-slate-800/80">
                            <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-2 mb-4">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        نام درس
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="qaForm.subject"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                        placeholder="مثال: فیزیک"
                                    >
                                    @error('qaForm.subject')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        تاریخ
                                    </label>
                                    <input
                                        type="text"
                                        data-jdp
                                        wire:model="qaForm.qa_date"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                        placeholder="انتخاب تاریخ"
                                    >
                                    @error('qaForm.qa_date')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        تعداد پارت (پیشنهادی جهت آمادگی در فعالیت فوق)
                                    </label>
                                    <input
                                        type="number"
                                        min="1"
                                        wire:model="qaForm.part_count"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                    >
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        زمان هر پارت (دقیقه)
                                    </label>
                                    <input
                                        type="number"
                                        min="1"
                                        wire:model="qaForm.time_per_part"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                    >
                                </div>
                            </div>

                            <button
                                wire:click="addQa"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition
                                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1 focus:ring-offset-slate-50
                                       dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-slate-900"
                            >
                                افزودن پرسش و پاسخ
                            </button>
                        </div>
                    @endif

                    @if(count($qas) > 0)
                        <div class="space-y-2">
                            @foreach($qas as $qa)
                                <div
                                    class="flex items-center justify-between rounded-xl bg-emerald-50 px-3 py-2.5 text-xs sm:text-sm text-slate-800 dark:bg-slate-800/80 dark:text-slate-50">
                                    <div class="space-x-1 space-x-reverse">
                                        <span class="text-muted">پرسش و پاسخ کلاسی ({{ jalali($qa['qa_date'])->format('Y/m/d') }}) :</span>
                                        <span class="font-medium">{{ $qa['subject'] }}
                                         <span class="text-slate-500 dark:text-slate-300">
                                          (پارت{{ $qa['part_count'] }})
                                        </span>
                                        </span>


                                    </div>

                                    @if($canEdit)
                                        <button
                                            wire:click="deleteQa({{ $qa['id'] }})"
                                            class="text-red-500 transition hover:text-red-600"
                                        >
                                             <i class="material-symbols-outlined text-base">حذف</i>

                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-4 text-center text-xs text-slate-400 dark:text-slate-500">
                            هیچ پرسش و پاسخی ثبت نشده است.
                        </p>
                    @endif
                @endif

                {{-- مرحله ۳: تکالیف --}}
                @if($currentStep === 3)
                    <h3 class="mb-2 text-base sm:text-lg font-bold text-slate-900 dark:text-slate-50">
                        تکالیف
                    </h3>
                    <p class="mb-5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        تکالیف هفته پیش رو را ثبت کنید.
                    </p>

                    @if($canEdit)
                        <div class="mb-6 rounded-xl bg-slate-50 px-3 py-4 sm:px-4 sm:py-5 dark:bg-slate-800/80">
                            <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-2 mb-4">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        نام درس
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="assignmentForm.subject"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                        placeholder="مثال: شیمی"
                                    >
                                    @error('assignmentForm.subject')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        تاریخ
                                    </label>
                                    <input
                                        type="text"
                                        data-jdp
                                        wire:model="assignmentForm.due_date"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                        placeholder="انتخاب تاریخ"
                                    >
                                    @error('assignmentForm.due_date')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        تعداد پارت (پیشنهادی جهت مطالعه انجام تکالیف فوق)
                                    </label>
                                    <input
                                        type="number"
                                        min="1"
                                        wire:model="assignmentForm.part_count"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                    >
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-200">
                                        زمان هر پارت (دقیقه)
                                    </label>
                                    <input
                                        type="number"
                                        min="1"
                                        wire:model="assignmentForm.time_per_part"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition
                                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                    >
                                </div>
                            </div>

                            <button
                                wire:click="addAssignment"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition
                                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1 focus:ring-offset-slate-50
                                       dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-slate-900"
                            >
                                افزودن تکلیف
                            </button>
                        </div>
                    @endif

                    @if(count($assignments) > 0)
                        <div class="space-y-2">
                            @foreach($assignments as $assignment)
                                <div
                                    class="flex items-center justify-between rounded-xl bg-violet-50 px-3 py-2.5 text-xs sm:text-sm text-slate-800 dark:bg-slate-800/80 dark:text-slate-50">
                                    <div class="space-x-1 space-x-reverse">
                                        <span class="text-muted">پرسش و پاسخ کلاسی ({{ jalali($assignment['due_date'])->format('Y/m/d') }}) :</span>
                                        <span class="font-medium">{{ $assignment['subject'] }}
                                         <span class="text-slate-500 dark:text-slate-300">
                                          (پارت{{ $assignment['part_count'] }})
                                        </span>
                                        </span>

                                    </div>


                                    @if($canEdit)
                                        <button
                                            wire:click="deleteAssignment({{ $assignment['id'] }})"
                                            class="text-red-500 transition hover:text-red-600"
                                        >
                                             <i class="material-symbols-outlined text-base">حذف</i>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-4 text-center text-xs text-slate-400 dark:text-slate-500">
                            هیچ تکلیفی ثبت نشده است.
                        </p>
                    @endif
                @endif

                {{-- مرحله ۴: متفرقه --}}
                @if($currentStep === 4)
                    <h3 class="mb-2 text-base sm:text-lg font-bold text-slate-900 dark:text-slate-50">
                        متفرقه
                    </h3>
                    <p class="mb-5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        هر توضیح یا نکته دیگری که می‌خواهید به مشاور بگویید را اینجا بنویسید.
                    </p>

                    <div class="mb-4">
                        <textarea
                            wire:model="miscDescription"
                            rows="6"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-900 shadow-sm outline-none transition
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                   dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40
                                   {{ !$canEdit ? 'bg-slate-100 dark:bg-slate-800 cursor-not-allowed' : '' }}"
                            placeholder="توضیحات خود را اینجا بنویسید..."
                            {{ !$canEdit ? 'disabled' : '' }}
                        ></textarea>
                    </div>

                    @if($canEdit)
                        <button
                            wire:click="saveMiscellaneous"
                            class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1 focus:ring-offset-slate-50
                                   dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-slate-900"
                        >
                            ذخیره توضیحات
                        </button>
                    @endif
                @endif

                {{-- مرحله ۵: خلاصه --}}
                @if($currentStep === 5)
                    <h3 class="mb-2 text-base sm:text-lg font-bold text-slate-900 dark:text-slate-50">
                        خلاصه پیش‌جلسه
                    </h3>
                    <p class="mb-5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        تمام اطلاعاتی که ثبت کرده‌اید در بخش‌های زیر نمایش داده شده است.
                    </p>

                    <div class="space-y-4 sm:space-y-6">
                        {{-- امتحانات --}}
                        <div
                            class="rounded-xl border border-slate-200 bg-slate-50/80 p-3 sm:p-4 dark:border-slate-700 dark:bg-slate-900/80">
                            <h4 class="mb-3 text-xs sm:text-sm font-bold text-blue-700 dark:text-blue-300">
                                امتحانات ({{ count($exams) }} مورد)
                            </h4>
                            @forelse($exams as $exam)
                                <div
                                    class="border-b border-dashed border-slate-200 py-1 text-xs sm:text-sm last:border-b-0 dark:border-slate-700 text-white">
                                    {{ $exam['subject'] }}
                                    –
                                    {{ $exam['part_count'] }} پارت
                                    –
                                    {{ jalali($exam['exam_date'])->format('Y/m/d') }}
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 dark:text-slate-500">
                                    ثبت نشده
                                </p>
                            @endforelse
                        </div>

                        {{-- پرسش و پاسخ --}}
                        <div
                            class="rounded-xl border border-slate-200 bg-slate-50/80 p-3 sm:p-4 dark:border-slate-700 dark:bg-slate-900/80">
                            <h4 class="mb-3 text-xs sm:text-sm font-bold text-emerald-700 dark:text-emerald-300">
                                پرسش و پاسخ ({{ count($qas) }} مورد)
                            </h4>
                            @forelse($qas as $qa)
                                <div
                                    class="border-b border-dashed border-slate-200 py-1 text-xs sm:text-sm last:border-b-0 dark:border-slate-70 text-white">
                                    {{ $qa['subject'] }}
                                    –
                                    {{ $qa['part_count'] }} پارت
                                    –
                                    {{ jalali($qa['qa_date'])->format('Y/m/d') }}
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 dark:text-slate-500">
                                    ثبت نشده
                                </p>
                            @endforelse
                        </div>

                        {{-- تکالیف --}}
                        <div
                            class="rounded-xl border border-slate-200 bg-slate-50/80 p-3 sm:p-4 dark:border-slate-700 dark:bg-slate-900/80 text-white">
                            <h4 class="mb-3 text-xs sm:text-sm font-bold text-violet-700 dark:text-violet-300">
                                تکالیف ({{ count($assignments) }} مورد)
                            </h4>
                            @forelse($assignments as $assignment)
                                <div
                                    class="border-b border-dashed border-slate-200 py-1 text-xs sm:text-sm last:border-b-0 dark:border-slate-700">
                                    {{ $assignment['subject'] }}
                                    –
                                    {{ $assignment['part_count'] }} پارت
                                    –
                                    {{ jalali($assignment['due_date'])->format('Y/m/d') }}
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 dark:text-slate-500">
                                    ثبت نشده
                                </p>
                            @endforelse
                        </div>

                        {{-- متفرقه --}}
                        <div
                            class="rounded-xl border border-slate-200 bg-slate-50/80 p-3 sm:p-4 dark:border-slate-700 dark:bg-slate-900/80">
                            <h4 class="mb-3 text-xs sm:text-sm font-bold text-amber-700 dark:text-amber-300">
                                متفرقه
                            </h4>
                            @if($miscDescription)
                                <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-200">
                                    {{ $miscDescription }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400 dark:text-slate-500">
                                    ثبت نشده
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- دکمه‌های ناوبری --}}
                <div
                    class="mt-8 border-t border-slate-100 pt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
                    <div>
                        @if($currentStep > 1)
                            <button
                                wire:click="prevStep"
                                class="inline-flex items-center justify-center rounded-lg bg-slate-500 px-5 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition
                                       hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-1 focus:ring-offset-white
                                       dark:bg-slate-600 dark:hover:bg-slate-500 dark:focus:ring-slate-500 dark:focus:ring-offset-slate-900"
                            >
                                مرحله قبل
                            </button>
                        @endif
                    </div>

                    <div class="flex justify-end">
                        @if($currentStep < $totalSteps)
                            <button
                                wire:click="nextStep"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition
                                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1 focus:ring-offset-white
                                       dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-slate-900"
                            >
                                مرحله بعد
                            </button>
                        @else
                            @if($canEdit)
                                <button
                                    wire:click="finalSubmit"
                                    class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition
                                           hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-1 focus:ring-offset-white
                                           dark:bg-emerald-500 dark:hover:bg-emerald-600 dark:focus:ring-emerald-400 dark:focus:ring-offset-slate-900"
                                >
                                    ثبت نهایی پیش‌جلسه
                                </button>
                            @else
                                <a
                                    href="{{ route('client.profile.consultation.sessions') }}"
                                    class="inline-flex items-center justify-center rounded-lg bg-slate-600 px-5 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition
                                           hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-1 focus:ring-offset-white
                                           dark:bg-slate-700 dark:hover:bg-slate-600 dark:focus:ring-slate-500 dark:focus:ring-offset-slate-900"
                                >
                                    بازگشت به لیست جلسات
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    @push('script')
        <script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
        <script>
            (function () {
                function startJalaliDatepicker() {
                    if (typeof jalaliDatepicker !== 'undefined') {
                        jalaliDatepicker.startWatch();
                    }
                }

                document.addEventListener('DOMContentLoaded', startJalaliDatepicker);
                document.addEventListener('livewire:navigated', startJalaliDatepicker);
                document.addEventListener('livewire:initialized', startJalaliDatepicker);
            })();
        </script>
    @endpush
</div>
