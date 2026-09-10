<div>
    @assets
    <style>
        .remove-image-btn {
            position: absolute; top: -0.5rem; right: -0.5rem;
            background: #ef4444; color: white;
            border-radius: 9999px;
            width: 2rem; height: 2rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .remove-image-btn:hover { background: #dc2626; transform: scale(1.1); }

        .spinner-circle {
            width: 1.125rem; height: 1.125rem;
            border: 2.25px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: jdp-spin 0.7s linear infinite;
            display: inline-block;
        }
        .spinner-sm { width: 1rem; height: 1rem; border-width: 2px; }
        @keyframes jdp-spin { to { transform: rotate(360deg); } }

        /* ═══ OTP segmented input ═══ */
        .otp-slot {
            width: 2.75rem;
            height: 3.25rem;
            text-align: center;
            caret-color: hsl(var(--primary));
        }
        @media (min-width: 400px) {
            .otp-slot { width: 3rem; height: 3.5rem; }
        }
        .otp-slot.is-filled {
            background: hsl(var(--secondary));
            border-color: hsl(var(--primary) / 0.5);
        }
        .otp-slot.is-error {
            border-color: hsl(0 84% 60%) !important;
            color: hsl(0 84% 60%);
            box-shadow: 0 0 0 3px hsl(0 84% 60% / 0.15) !important;
        }
        .otp-slot.is-success {
            border-color: hsl(152 69% 40%) !important;
            color: hsl(152 69% 40%);
            box-shadow: 0 0 0 3px hsl(152 69% 40% / 0.15) !important;
        }
        @keyframes otp-pop {
            0% { transform: scale(1); }
            40% { transform: scale(1.12); }
            100% { transform: scale(1); }
        }
        .otp-slot.is-pop { animation: otp-pop 0.18s ease-out; }

        @keyframes otp-shake-edit {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .shake { animation: otp-shake-edit 0.4s ease; }

        @media (prefers-reduced-motion: reduce) {
            .otp-slot, .shake { animation: none !important; transition: none !important; }
        }
    </style>
    @endassets

    <div class="max-w-7xl space-y-10 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-6"
                     x-data="{
                         activeTab: 'account',
                         photoError: false,
                     }">

                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">ویرایش پروفایل</div>
                    </div>

                    {{-- ═══ Notification-style pill tabs ═══ --}}
                    <x-ui.segmented-tabs
                        :items="[
                            'account'  => 'اطلاعات حساب',
                            'password' => 'رمز عبور',
                        ]"
                        :icons="[
                            'account'  => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z',
                            'password' => 'M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z',
                        ]"
                        active="account"

                        @segmented-change="activeTab = $event.detail"
                    />

                    {{-- ═══════════════ TAB: Account ═══════════════ --}}
                    <div x-show="activeTab === 'account'">
                        <div class="space-y-5">

                            @php
                                $avList = $this->avatarOptions();
                                $currentSrc = $photo
                                    ? ((str_starts_with($photo, '/') || str_starts_with($photo, 'http')) ? $photo : asset('user/img/' . auth()->id() . '/' . $photo))
                                    : null;
                            @endphp

                            {{-- ═══ Avatar card (glass) ═══ --}}
                            <div class="glass border border-border rounded-2xl p-6 space-y-6" wire:key="avatar-card">
                                <div class="flex flex-col sm:flex-row items-center gap-6">
                                    <div class="flex-shrink-0">
                                        <div class="w-28 h-28 rounded-full overflow-hidden ring-4 ring-white/40 dark:ring-white/10 shadow-xl bg-gradient-to-br from-blue-100 to-sky-100 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center">
                                            @if($currentSrc)
                                                <img src="{{ $currentSrc }}" class="w-full h-full object-cover object-top" alt="آواتار پروفایل">
                                            @else
                                                <svg class="w-12 h-12 {{ $gender === 'female' ? 'text-pink-400 dark:text-pink-500/70' : 'text-blue-400 dark:text-blue-500/70' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex-1 text-center sm:text-right">
                                        <h3 class="font-bold text-lg text-foreground mb-1 flex items-center justify-center sm:justify-start gap-2">
                                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                            </svg>
                                            آواتار پروفایل
                                        </h3>
                                        <p class="text-sm text-muted">یکی از آواتارهای زیر را انتخاب کن؛ تغییر بلافاصله ذخیره می‌شود.</p>
                                    </div>
                                </div>

                                {{-- گرید آواتارها بر اساس جنسیت --}}
                                @if(count($avList))
                                    <div class="grid grid-cols-3 gap-4 max-w-sm mx-auto sm:mx-0">
                                        @foreach($avList as $a)
                                            <button type="button" wire:click="selectAvatar('{{ $a }}')" wire:loading.attr="disabled" wire:target="selectAvatar"
                                                    class="relative aspect-square rounded-2xl overflow-hidden border-2 bg-secondary transition-all {{ $photo === $a ? 'border-primary ring-2 ring-primary/40' : 'border-border hover:border-primary/50' }}">
                                                <img src="{{ $a }}" class="w-full h-full object-cover object-top" alt="آواتار" loading="lazy">
                                                @if($photo === $a)
                                                    <span class="absolute top-1.5 left-1.5 w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center shadow">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                                    </span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="max-w-sm rounded-2xl border border-dashed border-border px-4 py-5 text-sm text-muted">
                                        هنوز آواتاری برای این جنسیت تعریف نشده است.
                                    </div>
                                @endif

                                {{-- هشدار: برای تغییر اطلاعات، تیکت ثبت کنید --}}
                                <div class="flex items-start gap-3 rounded-2xl bg-amber-500/10 border border-amber-500/30 px-4 py-4">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500/15 shrink-0">
                                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    <div class="flex-1">
                                        <div class="font-bold text-sm text-amber-600 dark:text-amber-300 mb-1">تغییر سایر اطلاعات</div>
                                        <p class="text-xs text-amber-600/90 dark:text-amber-400 leading-6">برای تغییر نام، کد ملی، تاریخ تولد یا هر یک از اطلاعات حساب، لطفاً یک تیکت پشتیبانی ثبت کنید.</p>
                                        <a href="{{ route('client.profile.ticket') }}" wire:navigate
                                           class="inline-flex items-center gap-1.5 mt-2.5 h-9 px-4 rounded-full bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-colors">
                                            ثبت تیکت پشتیبانی
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- ═══ Fields card (glass) ═══ --}}
                            <div class="glass border border-border rounded-2xl p-6 space-y-5">
                                <div class="flex items-center gap-2 pb-3 border-b border-border">
                                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                    </svg>
                                    <h3 class="font-bold text-foreground">اطلاعات حساب</h3>
                                </div>

                                @php
                                    $roBox      = 'w-full h-12 px-4 flex items-center bg-secondary border border-border rounded-xl text-sm text-foreground cursor-not-allowed';
                                    $pi_grade   = ['10' => 'دهم', '11' => 'یازدهم', '12' => 'دوازدهم'];
                                    $pi_field   = ['math' => 'ریاضی', 'experimental' => 'تجربی', 'human' => 'انسانی'];
                                    $genderText = $gender === 'female' ? 'زن' : ($gender === 'male' ? 'مرد' : 'ثبت نشده');
                                    $gradeText  = $is_graduate ? 'فارغ‌التحصیل' : ($pi_grade[$grade] ?? 'ثبت نشده');
                                    $fieldText  = $pi_field[$field] ?? 'ثبت نشده';
                                    $schoolText = $is_graduate ? '—' : ($attends_school ? 'بله، مدرسه می‌رود' : 'خیر، نمی‌رود');
                                @endphp

                                <div class="grid sm:grid-cols-2 gap-5">

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                            نام
                                        </label>
                                        <input type="text" wire:model="name" readonly tabindex="-1"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border rounded-xl text-sm text-foreground px-4 cursor-not-allowed">
                                        @error('name')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                            نام و نام خانوادگی
                                        </label>
                                        <input type="text" wire:model="full_name" readonly tabindex="-1"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border rounded-xl text-sm text-foreground px-4 cursor-not-allowed">
                                        @error('full_name')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5V6a1.5 1.5 0 0 1 1.5-1.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75a3 3 0 0 1 6 0M15 9h3.75M15 12h3.75M9.75 10.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/></svg>
                                            کد ملی
                                        </label>
                                        <div class="{{ $roBox }} font-mono justify-start" dir="ltr">{{ $code_mell ?: 'ثبت نشده' }}</div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                            ایمیل
                                        </label>
                                        <input type="email" dir="ltr" wire:model="email" readonly tabindex="-1"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border rounded-xl text-sm text-foreground px-4 cursor-not-allowed">
                                        @error('email')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-muted">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                                            شماره موبایل
                                            <span class="text-[10px] bg-muted/20 text-muted rounded px-1.5 py-0.5">غیرقابل ویرایش</span>
                                        </label>
                                        <input type="text" dir="ltr" wire:model="mobile" readonly
                                               class="w-full h-12 !ring-0 bg-secondary border border-border rounded-xl text-sm text-foreground px-4 cursor-not-allowed">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                            شماره پدر
                                        </label>
                                        <div class="{{ $roBox }} font-mono justify-start" dir="ltr">{{ $father_mobile ?: 'ثبت نشده' }}</div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                            شماره مادر
                                        </label>
                                        <div class="{{ $roBox }} font-mono justify-start" dir="ltr">{{ $mother_mobile ?: 'ثبت نشده' }}</div>
                                    </div>

                                    {{-- Gender --}}
                                    <div class="space-y-2 sm:col-span-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                                            جنسیت
                                            <span class="text-[10px] bg-muted/20 text-muted rounded px-1.5 py-0.5">غیرقابل ویرایش</span>
                                        </label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label class="relative cursor-not-allowed">
                                                <input type="radio" wire:model="gender" value="male" class="peer sr-only" disabled tabindex="-1">
                                                <div class="flex items-center justify-center gap-2 h-12 bg-secondary border-2 border-border peer-checked:border-blue-500 peer-checked:bg-blue-500/10 rounded-xl opacity-80">
                                                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                                    <span class="font-semibold text-sm text-foreground">مرد</span>
                                                </div>
                                            </label>
                                            <label class="relative cursor-not-allowed">
                                                <input type="radio" wire:model="gender" value="female" class="peer sr-only" disabled tabindex="-1">
                                                <div class="flex items-center justify-center gap-2 h-12 bg-secondary border-2 border-border peer-checked:border-pink-500 peer-checked:bg-pink-500/10 rounded-xl opacity-80">
                                                    <svg class="w-5 h-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                                    <span class="font-semibold text-sm text-foreground">زن</span>
                                                </div>
                                            </label>
                                        </div>
                                        @error('gender')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- پایه --}}
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                                            پایه
                                        </label>
                                        <div class="{{ $roBox }}">{{ $gradeText }}</div>
                                    </div>

                                    {{-- رشته --}}
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                                            رشته
                                        </label>
                                        <div class="{{ $roBox }}">{{ $fieldText }}</div>
                                    </div>

                                    {{-- وضعیت مدرسه --}}
                                    <div class="space-y-2 sm:col-span-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                                            وضعیت مدرسه
                                        </label>
                                        <div class="{{ $roBox }}">{{ $schoolText }}</div>
                                    </div>

                                    {{-- State with search --}}
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                            </svg>
                                            استان
                                            <span class="text-[10px] bg-muted/20 text-muted rounded px-1.5 py-0.5">غیرقابل ویرایش</span>
                                        </label>
                                        <div class="w-full h-12 px-4 flex items-center rounded-xl border border-border bg-secondary text-foreground text-sm cursor-not-allowed">
                                            {{ optional($states->firstWhere('id', $state_id))->name ?? 'ثبت نشده' }}
                                        </div>
                                    </div>

                                    {{-- City with search --}}
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/></svg>
                                            شهر
                                            <span class="text-[10px] bg-muted/20 text-muted rounded px-1.5 py-0.5">غیرقابل ویرایش</span>
                                        </label>
                                        <div class="w-full h-12 px-4 flex items-center rounded-xl border border-border bg-secondary text-foreground text-sm cursor-not-allowed">
                                            {{ optional($cities->firstWhere('id', $city_id))->name ?? 'ثبت نشده' }}
                                        </div>
                                    </div>

                                    {{-- تاریخ تولد --}}
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                            تاریخ تولد
                                        </label>
                                        <div class="{{ $roBox }} justify-center font-mono tracking-wide" dir="ltr">{{ $birth_date ?: 'ثبت نشده' }}</div>
                                    </div>

                                    {{-- محل تولد --}}
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                            محل تولد
                                        </label>
                                        <div class="{{ $roBox }}">{{ $place_of_birth ?: 'ثبت نشده' }}</div>
                                    </div>

                                    {{-- آدرس --}}
                                    <div class="space-y-2 sm:col-span-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                            آدرس
                                        </label>
                                        <div class="w-full min-h-[3rem] px-4 py-3 flex items-start bg-secondary border border-border rounded-xl text-sm text-foreground cursor-not-allowed leading-7">{{ $address ?: 'ثبت نشده' }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-3 border-t border-border">
                                    <svg class="w-4 h-4 text-muted shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                                    <p class="text-[11px] text-muted">این اطلاعات فقط قابل مشاهده است. برای تغییر آن‌ها از طریق تیکت پشتیبانی اقدام کنید.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ═══════════════ TAB: Password ═══════════════ --}}
                    <div x-show="activeTab === 'password'" x-cloak class="space-y-5">

                        <div class="glass border border-amber-500/30 rounded-2xl p-5" x-data="{ open: true }" x-show="open">
                            <div class="flex items-start gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500/15 shrink-0">
                                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <div class="flex-1">
                                    <div class="font-bold text-sm text-amber-600 dark:text-amber-300 mb-2">الزامات رمز عبور</div>
                                    <ul class="space-y-1 text-xs text-amber-600/90 dark:text-amber-400">
                                        <li>• حداقل ۸ کاراکتر</li>
                                        <li>• حداقل یک حرف کوچک</li>
                                        <li>• حداقل یک حرف بزرگ</li>
                                        <li>• حداقل یک عدد</li>
                                    </ul>
                                    <button type="button" @click="open = false" class="mt-3 text-xs text-amber-600 dark:text-amber-400 hover:underline font-semibold">فهمیدم</button>
                                </div>
                            </div>
                        </div>

                        {{-- Change with current password --}}
                        <div x-show="!$wire.showForgotPassword" class="glass border border-border rounded-2xl p-6">
                            <form wire:submit.prevent="changePassword" class="space-y-5">
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-foreground">رمز فعلی</label>
                                        <input type="password" dir="ltr" wire:model="current_password" placeholder="********"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('current_password')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-foreground">رمز جدید</label>
                                        <input type="password" dir="ltr" wire:model="new_password" placeholder="********"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('new_password')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="space-y-2 sm:col-span-2">
                                        <label class="font-semibold text-xs text-foreground">تکرار رمز جدید</label>
                                        <input type="password" dir="ltr" wire:model="new_password_confirmation" placeholder="********"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('new_password_confirmation')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3 border-t border-border">
                                    <button type="button" wire:click="toggleForgotPassword"
                                            class="text-primary text-sm font-medium hover:underline order-2 sm:order-1">
                                        رمز عبور را فراموش کرده‌ام
                                    </button>
                                    <button type="submit" wire:loading.attr="disabled" wire:target="changePassword"
                                            class="w-full sm:w-auto h-11 inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 rounded-full text-white px-8 transition-all order-1 sm:order-2 disabled:opacity-60 min-w-[130px]">
                                        <span wire:loading.remove wire:target="changePassword" class="font-semibold text-sm">تغییر رمز</span>
                                        <span wire:loading wire:target="changePassword" class="spinner-circle text-white"></span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- OTP forgot flow --}}
                        <div x-show="$wire.showForgotPassword" x-cloak x-data="profileOtpForm()" x-init="init()" class="glass border border-border rounded-2xl p-6 space-y-5">
                            <div class="flex items-center gap-3 p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-blue-600 dark:text-blue-300">کد تایید به شماره موبایل شما ارسال خواهد شد</span>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-3 flex-wrap">
                                    <label class="font-semibold text-xs text-foreground">کد تایید</label>
                                    <button type="button" wire:click="sendOtp" wire:loading.attr="disabled" wire:target="sendOtp" x-show="!$wire.otp_verified && countdown === 0"
                                            class="h-10 px-5 bg-primary hover:bg-primary/90 text-white rounded-xl text-sm font-semibold transition-all whitespace-nowrap disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[110px]">
                                        <span wire:loading.remove wire:target="sendOtp">ارسال کد</span>
                                        <span wire:loading wire:target="sendOtp" class="spinner-circle text-white"></span>
                                    </button>
                                </div>

                                {{-- ═══ Segmented OTP input ═══ --}}
                                <div class="relative"
                                     x-data="otpInput({ length: 6, hasServerError: @js($errors->has('otp_code')), autoSubmit: false })"
                                     x-init="init()"
                                     @otp-cleared.window="reset()"
                                     @otp-error.window="triggerError()"
                                     @otp-success.window="triggerSuccess()">

                                    <input type="hidden" wire:model.live="otp_code" x-ref="hidden">

                                    <div class="flex items-center justify-center gap-2 sm:gap-2.5" dir="ltr"
                                         :class="{ 'shake': status === 'error' }">
                                        <template x-for="(digit, index) in digits" :key="index">
                                            <input
                                                type="text"
                                                inputmode="numeric"
                                                autocomplete="one-time-code"
                                                maxlength="1"
                                                data-otp-slot
                                                :value="digits[index]"
                                                :disabled="$wire.otp_verified"
                                                :aria-label="'رقم ' + (index + 1) + ' از ' + length"
                                                wire:loading.attr="disabled"
                                                wire:target="otp_code, verifyOtp"
                                                @input="handleInput($event, index)"
                                                @keydown="handleKeydown($event, index)"
                                                @paste="handlePaste($event)"
                                                @focus="$event.target.select()"
                                                class="otp-slot glass-input rounded-xl text-xl sm:text-2xl font-bold font-mono disabled:opacity-50"
                                                :class="{
                                                    'is-filled': digit !== '' && status === 'idle',
                                                    'is-error': status === 'error',
                                                    'is-success': status === 'success',
                                                    'is-pop': poppedIndex === index
                                                }"
                                            >
                                        </template>
                                    </div>
                                </div>

                                <div x-show="!$wire.otp_verified && countdown > 0" x-cloak class="text-xs text-muted text-center">
                                    ارسال مجدد تا <span class="font-mono text-primary" x-text="countdown"></span> ثانیه
                                </div>

                                {{-- کد به محض کامل شدن ۶ رقم خودکار بررسی می‌شود --}}
                                <div wire:loading wire:target="otp_code, verifyOtp" x-show="!$wire.otp_verified" class="inline-flex items-center gap-2 text-xs text-muted mt-1 justify-center w-full">
                                    <span class="spinner-circle spinner-sm text-primary"></span>
                                    <span>در حال بررسی کد...</span>
                                </div>

                                @error('otp_code')<div class="font-medium text-xs text-red-500 text-center">{{ $message }}</div>@enderror

                                <div x-show="$wire.otp_verified" class="inline-flex items-center gap-2 text-xs text-emerald-500 mt-1 justify-center w-full">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    کد تایید شد
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <template x-if="$wire.otp_verified">
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-foreground">رمز جدید</label>
                                        <input type="password" dir="ltr" wire:model="forgot_new_password" placeholder="********"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('forgot_new_password')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                </template>

                                <template x-if="$wire.otp_verified">
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-foreground">تکرار رمز جدید</label>
                                        <input type="password" dir="ltr" wire:model="forgot_new_password_confirmation" placeholder="********"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('forgot_new_password_confirmation')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                </template>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3 border-t border-border">
                                <button type="button" wire:click="toggleForgotPassword"
                                        class="text-muted text-sm font-medium hover:underline hover:text-foreground transition-colors order-2 sm:order-1">
                                    بازگشت
                                </button>
                                <button type="button" wire:click="changePasswordWithOtp" wire:loading.attr="disabled" wire:target="changePasswordWithOtp" x-show="$wire.otp_verified"
                                        class="w-full sm:w-auto h-11 inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 rounded-full text-white px-8 transition-all order-1 sm:order-2 disabled:opacity-60 min-w-[130px]">
                                    <span wire:loading.remove wire:target="changePasswordWithOtp" class="font-semibold text-sm">تغییر رمز</span>
                                    <span wire:loading wire:target="changePasswordWithOtp" class="spinner-circle text-white"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@push('script')
    <script>
        function otpInput(config) {
            return {
                length: config.length || 6,
                digits: Array(config.length || 6).fill(''),
                status: 'idle', // idle | error | success
                hasServerError: config.hasServerError || false,
                autoSubmit: config.autoSubmit !== false,
                poppedIndex: -1,

                get code() {
                    return this.digits.join('');
                },

                init() {
                    if (this.hasServerError) {
                        this.triggerError();
                    }
                    this.$nextTick(() => this.focusSlot(0));
                },

                toEnglishDigits(str) {
                    return str
                        .replace(/[۰-۹]/g, (d) => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
                        .replace(/[٠-٩]/g, (d) => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));
                },

                sync() {
                    this.$refs.hidden.value = this.code;
                    this.$refs.hidden.dispatchEvent(new Event('input'));
                },

                pop(index) {
                    this.poppedIndex = index;
                    setTimeout(() => {
                        if (this.poppedIndex === index) this.poppedIndex = -1;
                    }, 180);
                },

                maybeSubmit() {
                    if (!this.autoSubmit) return;
                    if (this.status !== 'idle') return;
                    if (this.digits.every((d) => d !== '')) {
                        this.$nextTick(() => this.$wire.call('verifyOtp'));
                    }
                },

                applySequence(seq, startIndex) {
                    const chars = seq.split('');
                    for (let i = 0; i < chars.length && (startIndex + i) < this.length; i++) {
                        this.digits[startIndex + i] = chars[i];
                    }
                    this.sync();
                    this.pop(Math.min(startIndex + chars.length - 1, this.length - 1));
                    const nextIndex = Math.min(startIndex + chars.length, this.length - 1);
                    this.focusSlot(nextIndex);
                    this.maybeSubmit();
                },

                handleInput(e, index) {
                    if (this.status === 'error') this.status = 'idle';

                    let val = this.toEnglishDigits(e.target.value).replace(/[^0-9]/g, '');

                    if (val.length > 1) {
                        e.target.value = this.digits[index] || '';
                        this.applySequence(val, index);
                        return;
                    }

                    this.digits[index] = val;
                    e.target.value = val;
                    this.sync();

                    if (val) {
                        this.pop(index);
                        if (index < this.length - 1) this.focusSlot(index + 1);
                    }

                    this.maybeSubmit();
                },

                handleKeydown(e, index) {
                    if (e.key === 'Backspace') {
                        e.preventDefault();
                        if (this.status === 'error') this.status = 'idle';
                        if (this.digits[index]) {
                            this.digits[index] = '';
                            this.sync();
                        } else if (index > 0) {
                            this.digits[index - 1] = '';
                            this.sync();
                            this.focusSlot(index - 1);
                        }
                    } else if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        if (index > 0) this.focusSlot(index - 1);
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        if (index < this.length - 1) this.focusSlot(index + 1);
                    } else if (e.key === 'Home') {
                        e.preventDefault();
                        this.focusSlot(0);
                    } else if (e.key === 'End') {
                        e.preventDefault();
                        this.focusSlot(this.length - 1);
                    }
                },

                handlePaste(e) {
                    e.preventDefault();
                    const pasted = (e.clipboardData || window.clipboardData).getData('text');
                    const cleaned = this.toEnglishDigits(pasted).replace(/[^0-9]/g, '').slice(0, this.length);
                    if (!cleaned) return;
                    if (this.status === 'error') this.status = 'idle';
                    this.digits = Array(this.length).fill('');
                    this.applySequence(cleaned, 0);
                },

                focusSlot(i) {
                    this.$nextTick(() => {
                        const el = this.$root.querySelectorAll('[data-otp-slot]')[i];
                        if (el) el.focus();
                    });
                },

                reset() {
                    this.digits = Array(this.length).fill('');
                    this.status = 'idle';
                    this.sync();
                    this.focusSlot(0);
                },

                triggerError() {
                    this.status = 'error';
                    setTimeout(() => {
                        this.digits = Array(this.length).fill('');
                        this.status = 'idle';
                        this.sync();
                        this.focusSlot(0);
                    }, 550);
                },

                triggerSuccess() {
                    this.status = 'success';
                }
            };
        }

        function profileOtpForm() {
            return {
                countdown: 0,
                timer: null,

                init() {
                    this.countdown = this.$wire.countdown || 0;

                    if (this.countdown > 0) {
                        this.startCountdown(this.countdown);
                    }

                    Livewire.on('start-countdown', () => {
                        this.startCountdown(this.$wire.countdown || 0);
                    });
                },

                startCountdown(seconds) {
                    if (this.timer) clearInterval(this.timer);
                    this.countdown = seconds;
                    this.$wire.set('countdown', this.countdown, false);

                    if (this.countdown <= 0) {
                        return;
                    }

                    this.timer = setInterval(() => {
                        if (this.countdown > 0) {
                            this.countdown--;
                            this.$wire.set('countdown', this.countdown, false);
                        } else {
                            clearInterval(this.timer);
                            this.$wire.countdownFinished();
                        }
                    }, 1000);
                }
            }
        }
    </script>
@endpush
