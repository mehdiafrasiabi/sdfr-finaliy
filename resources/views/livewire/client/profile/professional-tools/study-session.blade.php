<div>
    @push('link')
        <style>
            @font-face {
                font-family: 'Digital';
                src: url('/client/assets/fonts/digital-7.ttf') format('truetype');
            }

            [x-cloak] { display: none !important; }

            #confetti-canvas {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 9999;
            }

            .digital-clock {
                font-family: 'Digital', 'Courier New', monospace;
                font-size: 76px;
                letter-spacing: 3px;
                color: #ff9c00;
                text-shadow: 0 0 8px rgba(255,150,0,0.7);
            }
        </style>
    @endpush


    <canvas id="confetti-canvas"></canvas>

    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8" x-data="{
                    setupModal: @entangle('showSetupModal'),
                    finishModal: @entangle('showConfirmFinishModal'),
                    successModal: @entangle('showSuccessModal')
                }">
                <div class="space-y-10">
                    <div class="space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">ابزار های حرفه ای</div>

                            <a wire:navigate href="{{route('client.profile.professionalTools.index')}}"
                               class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-background border rounded-full text-muted transition-colors hover:text-foreground px-6 ms-auto">
                                <span class="font-semibold text-xs">بازگشت</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/>
                                </svg>
                            </a>
                        </div>


                        <div class="space-y-5">
                            <div
                                class="flex items-start gap-3 relative bg-zinc-50 dark:bg-zinc-900 border border-border rounded-xl p-5">
                                <span class="text-yellow-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         class="w-5 h-5">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                <div class="flex flex-col items-start">
                                    <div class="font-bold text-sm text-yellow-500 mb-2">توجه</div>
                                    <div class="font-semibold text-xs text-zinc-400 space-y-1">
                                        <p>قبل از شروع مطالعه لطفاً نکات زیر را بخوانید:</p>
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>مبحث خود را مشخص کنید و سپس مطالعه را آغاز نمایید.</li>
                                            <li>این ابزار مخصوص دانش‌آموزان SDFR است.</li>
                                            <li>امکان ویرایش جلسات ثبت شده وجود ندارد (فقط حذف).</li>
                                            <li>با بستن صفحه مطالعه متوقف می‌شود و ثبت نخواهد شد.</li>
                                            <li class="text-primary">برای شروع روی «شروع» بزنید</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="rounded-3xl overflow-hidden shadow-lg bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">
                                <div class="p-8" wire:poll.visible.1000ms="tick">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="font-black tracking-widest digital-clock" style="">
                                            {{ $this->formatClock($targetSeconds > 0 ? $remainingSeconds : $liveSeconds) }}
                                        </div>
                                    </div>
                                    <div class="mt-8 flex flex-wrap items-center justify-center gap-3 mb-10">
                                        @if(!$isRunning and !$startedAt)
                                            <button type="button"
                                                    wire:click="openSetupModal"
                                                    @click="setupModal = true"
                                                    class="px-6 h-11 rounded-full bg-primary hover:bg-rose-500 transition font-semibold">
                                                <span class="font-semibold text-sm">شروع</span>
                                            </button>
                                        @endif
                                        @if($isRunning)
                                            <button type="button"
                                                    class="px-6 h-11 rounded-full  hover:bg-rose-500 transition font-semibold"
                                                    style="background-color: #ec7904"
                                                    wire:click="pauseTimer">
                                                توقف مطالعه
                                            </button>
                                        @elseif($startedAt)
                                            <button type="button"
                                                    class="px-6 h-11 rounded-full bg-primary hover:bg-emerald-500 transition font-semibold"
                                                    wire:click="resumeTimer">
                                                ادامه مطالعه
                                            </button>
                                        @endif

                                        @if($startedAt && !$alarmTriggered)
                                            <button type="button"
                                                    class="px-6 h-11 rounded-full bg-green-500 hover:bg-cyan-500 transition font-semibold"
                                                    wire:click="requestFinish">
                                                ثبت مطالعه
                                            </button>
                                        @endif
                                    </div>

                                    @if($note)
                                        <div class="mt-6 bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                                            <div class="text-xs text-white"></div>
                                            <div class="text-lg font-semibold mt-1 text-white">  مبحث مورد نظر:
                                                <div class="text-primary">{{ $note }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="relative  @if($sessions->isNotEmpty()) overflow-x-auto @endif">
                                <table class="w-full text-sm text-right">
                                    @if($sessions->isNotEmpty())
                                        <thead
                                            class="text-xs text-white uppercase bg-background border-b border-border">
                                        <tr>
                                            <th class="whitespace-nowrap p-5">شروع/پایان</th>
                                            <th class="whitespace-nowrap p-5">مدت ثبت‌شده</th>
                                            <th class="whitespace-nowrap p-5">مدت هدف</th>
                                            <th class="whitespace-nowrap p-5">مبحث</th>
                                            <th class="whitespace-nowrap p-5"></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($sessions as $session)
                                            <tr class="odd:bg-secondary even:bg-background">
                                                <td class="p-5 ">
                                                    <div class="font-black text-muted whitespace-nowrap">
                                                        {{ jalali($session->started_at)->format('H:i') }}
                                                        - {{ jalali($session->ended_at)->format('H:i') }}
                                                    </div>
                                                </td>
                                                <td class="p-5">
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="font-bold text-xs text-muted whitespace-nowrap"> {{ $this->formatDuration($session->duration_seconds) }}</span>
                                                    </div>
                                                </td>
                                                <td class="p-5">
                                                    <div class="text-xs text-muted whitespace-nowrap">
                                                        {{ $session->planned_seconds ? $this->formatDuration($session->planned_seconds) : '---' }}
                                                    </div>
                                                </td>
                                                <td class="p-5">
                                                    <div class="font-bold text-xs text-muted whitespace-nowrap">
                                                        {{ $session->note }}
                                                    </div>
                                                </td>
                                                <td class="p-5">
                                                    <div class="text-xs text-muted whitespace-nowrap">
                                                        <button class="text-rose-500 text-xs font-bold"
                                                                wire:click="deleteSession({{ $session->id }})"
                                                                onclick="return confirm('آیا مطمئن هستید؟')">
                                                            حذف
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                    @else
                                        <div class="flex flex-col items-center justify-center space-y-12">
                                            <img src="/client/assets/images/theme/empty.svg"
                                                 class="w-full max-w-xs opacity-35" alt="..."/>
                                            <div class="text-center space-y-3">
                                                <h2 class="font-bold text-xl text-foreground">
                                                    جلسه مشاوره برای شما وجود ندارد.
                                                </h2>
                                            </div>
                                        </div>
                                    @endif
                                </table>
                            </div>
                            <div class="p-5 text-xs text-muted whitespace-nowrap text-white">

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Setup Modal -->
                <div x-cloak x-show="setupModal"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
                     x-transition>
                    <div class="w-full max-w-xl mx-4 bg-background border border-border rounded-2xl shadow-2xl"
                         @click.away="setupModal = false">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                            <div>
                                <h3 class="text-base font-bold text-foreground">تنظیم ساعت مطالعه</h3>
                            </div>
                            <button type="button" @click="setupModal = false"
                                    class="text-muted hover:text-foreground transition-all">
                                <i class="material-symbols-outlined !text-[22px] text-red-500">✕</i>
                            </button>
                        </div>

                        <div class="px-6 py-5 space-y-4">
                            <div class="bg-secondary rounded-xl p-4">
                                <div class="font-medium text-sm text-primary">
                                    <p>با توجه به برنامه مطالعاتی، مبحث مورد نظر، ساعت و دقیقه موظفی خود را وارد نمایید.</p>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label for="note" class="font-medium text-xs text-muted">مبحث مورد نظر :</label>
                                <sup class="text-red-500">*</sup>
                                <input type="text" id="note" dir="rtl" name="note"
                                       minlength="3"
                                       wire:model.defer="note"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                @error('note')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div class="space-y-1">
                                    <label for="studyHours" class="font-medium text-xs text-muted">ساعت موظفی :</label>
                                    <sup class="text-red-500">*</sup>
                                    <select type="text" id="studyHours" name="studyHours"
                                            wire:model.defer="studyHours"
                                            class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                        @for($i = 0; $i <= 24; $i++)
                                            <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                        @endfor
                                    </select>
                                    @error('studyHours')
                                    <div class="font-medium text-xs text-muted text-red-500">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="space-y-1">
                                    <label for="studyMinutes" class="font-medium text-xs text-muted">دقیقه موظفی:</label>
                                    <sup class="text-red-500">*</sup>
                                    <select type="text" id="studyMinutes" name="studyMinutes"
                                            wire:model.defer="studyMinutes"
                                            class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                        @for($i = 0; $i <= 60; $i++)
                                            <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                        @endfor
                                    </select>
                                    @error('studyMinutes')
                                    <div class="font-medium text-xs text-muted text-red-500">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border bg-secondary rounded-b-2xl">
                            <button type="button"
                                    class="px-6 h-11 rounded-full bg-primary text-white font-semibold disabled:opacity-50 hover:bg-primary/90 transition"
                                    wire:click="startTimer"
                                    wire:loading.attr="disabled">
                                شروع
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Finish Confirm Modal -->
                <div x-cloak x-show="finishModal"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                     x-transition>
                    <div class="w-full max-w-md mx-4 bg-background border border-border rounded-2xl shadow-2xl"
                         @click.away="finishModal = false">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xl">
                                    !
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-foreground">مطالعه هنوز فعال است</h3>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-5 space-y-4">
                            <p class="text-sm text-muted leading-7">
                                تایم مطالعه شما هنوز به پایان نرسیده است. در صورت انتخاب «بله» مطالعه متوقف شده و جلسه ثبت می‌گردد.
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border bg-secondary rounded-b-2xl">
                            <button type="button"
                                    class="px-4 h-10 rounded-full border border-border text-muted hover:bg-background transition"
                                    wire:click="cancelFinishRequest">
                                خیر، ادامه بده
                            </button>
                            <button type="button"
                                    class="px-5 h-10 rounded-full bg-rose-500 text-white font-semibold hover:bg-rose-600 transition"
                                    wire:click="finishAndSave">
                                بله، پایان بده
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Success Modal با انیمیشن -->
                <div x-cloak x-show="successModal"
                     class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                    <div class="w-full max-w-md mx-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-2 border-green-500 rounded-3xl shadow-2xl overflow-hidden"
                         x-transition:enter-end="opacity-100 scale-100">

                        <div class="relative px-6 py-8 text-center space-y-4 bg-background border border-border">
                            <br>
                            <!-- آیکون تیک موفقیت -->
                            <div class="flex justify-center">
                                <div class="w-14 h-14 rounded-full bg-green-500 flex items-center justify-center animate-bounce">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-5 h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                            </div>
<br>
                            <!-- متن -->
                            <div class="space-y-2">
                                <h3 class="text-2xl font-black text-green-500 dark:text-green-400">
                                    🎉 تبریک! 🎉
                                </h3>
                                <p class="text-lg font-bold text-green-500 dark:text-green-300">
                                    تایم مطالعه با موفقیت ثبت شد
                                </p>
                                <br>

                            </div>
                            <button type="button"
                                    @click="successModal = false"
                                    class="mt-4 px-8 h-11 rounded-full mb-5 bg-primary hover:bg-green-600 font-semibold transition transform hover:scale-105">

                                <span class="text-white ">بریم پارت بعدی!</span>
                            </button>
                            <!-- دکمه بستن -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            // Confetti Animation
            function startConfetti() {
                const canvas = document.getElementById('confetti-canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;

                const confettiPieces = [];
                const confettiCount = 150;
                const gravity = 0.5;
                const terminalVelocity = 5;
                const drag = 0.075;
                const colors = [
                    { front: '#7b5cff', back: '#6245e0' },
                    { front: '#b3c7ff', back: '#8fa5e5' },
                    { front: '#5c86ff', back: '#345dd1' },
                    { front: '#00d4ff', back: '#00a7cc' },
                    { front: '#00ff88', back: '#00cc6e' },
                    { front: '#ffcc00', back: '#cc9900' },
                    { front: '#ff6666', back: '#cc4444' },
                    { front: '#ff66cc', back: '#cc44aa' }
                ];

                function randomRange(min, max) {
                    return Math.random() * (max - min) + min;
                }

                function initConfetti() {
                    for (let i = 0; i < confettiCount; i++) {
                        confettiPieces.push({
                            color: colors[Math.floor(randomRange(0, colors.length))],
                            dimensions: {
                                x: randomRange(10, 20),
                                y: randomRange(10, 30),
                            },
                            position: {
                                x: randomRange(0, canvas.width),
                                y: canvas.height - 1,
                            },
                            rotation: randomRange(0, 2 * Math.PI),
                            scale: {
                                x: 1,
                                y: 1,
                            },
                            velocity: {
                                x: randomRange(-25, 25),
                                y: randomRange(0, -50),
                            },
                        });
                    }
                }

                function updateConfetti() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    confettiPieces.forEach((confetto, index) => {
                        confetto.velocity.x -= confetto.velocity.x * drag;
                        confetto.velocity.y = Math.min(confetto.velocity.y + gravity, terminalVelocity);
                        confetto.velocity.x += Math.random() > 0.5 ? Math.random() : -Math.random();

                        confetto.position.x += confetto.velocity.x;
                        confetto.position.y += confetto.velocity.y;

                        if (confetto.position.y >= canvas.height) confettiPieces.splice(index, 1);

                        if (confetto.position.x > canvas.width) confetto.position.x = 0;
                        if (confetto.position.x < 0) confetto.position.x = canvas.width;

                        confetto.scale.y = Math.cos(confetto.position.y * 0.1);
                        ctx.fillStyle = confetto.scale.y > 0 ? confetto.color.front : confetto.color.back;

                        ctx.beginPath();
                        ctx.setTransform(confetto.scale.x, 0, 0, confetto.scale.y, confetto.position.x, confetto.position.y);
                        ctx.rotate(confetto.rotation);
                        ctx.fillRect(-confetto.dimensions.x / 2, -confetto.dimensions.y / 2, confetto.dimensions.x, confetto.dimensions.y);
                        ctx.setTransform(1, 0, 0, 1, 0, 0);
                    });

                    if (confettiPieces.length > 0) {
                        window.requestAnimationFrame(updateConfetti);
                    } else {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                    }
                }

                initConfetti();
                updateConfetti();
            }

            // Study finished event
            window.addEventListener('study-finished', () => {
                startConfetti();
                try {
                    const audio = new Audio('/client/sounds/Alarmclock.ogg');
                    audio.volume = 1;
                    audio.play().catch(e => console.warn("Sound play blocked:", e));
                } catch (error) {
                    console.warn('alarm error', error);
                }
            });

            // Success saved event
            window.addEventListener('study-saved-success', () => {
                startConfetti();
            });

            // Page unload
            window.addEventListener('beforeunload', () => {
                Livewire.dispatch('timerAborted');
            });

            // Audio unlock
            document.addEventListener('click', () => {
                const silent = new Audio('/client/sounds/Alarmclock.ogg');
                silent.volume = 0;
                silent.play().then(() => silent.pause());
            }, { once: true });
        </script>
    @endpush
</div>
