<div class="max-w-7xl space-y-14 px-4 mx-auto">
    @assets
        <style>
            @font-face {
                font-family: 'Digital';
                src: url('/client/assets/fonts/digital-7.ttf') format('truetype');
            }

            [x-cloak] {
                display: none !important;
            }

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
                text-shadow: 0 0 8px rgba(255, 150, 0, 0.7);
            }
        </style>
    @endassets
    <canvas id="confetti-canvas"></canvas>
    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

            <!-- end user:info -->

            <!-- user:menus -->

            <livewire:client.profile.sidebar/>

            <!-- end user:menus -->
        </div>

        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-10">
                <!-- section:learning-courses -->
                <div class="space-y-5">
                    <!-- section:title -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">پومودرو حرفه‌ای</div>
                    </div>
                    <!-- end section:title -->

                    <!-- section:learning-courses:slider -->
                    <div class="bg-slate-900 text-white rounded-2xl shadow-2xl p-6 border border-white/10">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm text-slate-300">حالت فعلی</p>
                                <p class="text-xl font-bold">
                                    @if ($mode === 'focus')
                                        <span class="text-amber-400">دوره تمرکز ۲۵ دقیقه‌ای</span>
                                    @elseif ($mode === 'shortBreak')
                                        <span class="text-emerald-400">استراحت کوتاه ۵ دقیقه‌ای</span>
                                    @else
                                        <span class="text-sky-300">استراحت طولانی ۲۰ دقیقه‌ای</span>
                                    @endif
                                </p>
                            </div>

                            <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full">
                                <div
                                    class="w-3 h-3 rounded-full animate-pulse {{ $isRunning ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                <span class="text-xs font-semibold">{{ $isRunning ? 'در حال اجرا' : 'متوقف' }}</span>
                            </div>
                        </div>

                        @php
                            $duration = $mode === 'focus' ? $focusLength * 60 : ($mode === 'shortBreak' ? $shortBreakLength * 60 : $longBreakLength * 60);
                            $elapsed = max($duration - $timeLeft, 0);
                            $progress = $duration > 0 ? min(100, ($elapsed / $duration) * 100) : 0;
                        @endphp

                        <div class="mt-6 bg-black/30 rounded-2xl p-6 shadow-inner">
                            <div class="flex justify-center digital-clock leading-none" dir="ltr">
                                {{ str_pad(floor($timeLeft / 60), 2, '0', STR_PAD_LEFT) }}
                                :{{ str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="mt-4 h-3 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-amber-400 via-lime-400 to-emerald-400"
                                     style="width: {{ $progress }}%"></div>
                            </div>
                            <p class="text-center text-xs mt-2 text-slate-300">پیشرفت این مرحله بر اساس قوانین
                                پومودرو</p>
                        </div>

                        <div class="mt-6 grid md:grid-cols-2 gap-3">
                            <button wire:click="{{ $isRunning ? 'stop' : 'start' }}"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-bold transition bg-gradient-to-r {{ $isRunning ? 'text-white bg-red-400 hover:text-rose-600 hover:to-orange-600' : 'bg-emerald-500 text-white  ' }}">
                                <span>{{ $isRunning ? 'توقف موقت' : 'شروع / ادامه' }}</span>
                            </button>
                            <button wire:click="resetTimer"
                                    class="w-full px-4 py-3 rounded-xl font-bold transition bg-yellow-500 hover:bg-white/20">
                                شروع تازه چرخه
                            </button>
                            <button wire:click="skipPhase"
                                    class="w-full px-4 py-3 rounded-xl font-bold transition bg-lime-950 hover:bg-sky-500">
                                پرش به مرحله بعد (بدون ثبت)
                            </button>
                            <label
                                class="w-full px-4 py-3 rounded-xl font-bold transition bg-white/5 hover:bg-white/10 flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" wire:model.live="autoStartNext" class="accent-emerald-400"/>
                                <span class="text-sm">شروع خودکار مرحله بعد پس از پایان زمان</span>
                            </label>
                        </div>

                        @if ($isRunning)
                            <div wire:poll.1000ms="tick"></div>
                        @endif

                        <div class="mb-5 grid md:grid-cols-2 gap-4 text-sm">
                            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                                <p class="text-slate-300">دورهای تمرکز کامل‌شده</p>
                                <p class="text-2xl font-black text-emerald-400">{{ $rounds }}</p>
                                <p class="text-xs text-slate-400">هر ۴ دور → یک استراحت طولانی</p>
                            </div>
                            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                                <p class="text-slate-300">چرخه‌های کامل پومودرو</p>
                                <p class="text-2xl font-black text-sky-300">{{ $cycles }}</p>
                                <p class="text-xs text-slate-400">پس از استراحت طولانی جشن می‌گیریم! 🎉</p>
                            </div>
                        </div>
<br>
                        <div class="mb-5 grid sm:grid-cols-3 gap-3 text-xs">
                            <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                                <p class="text-slate-300">تمرکز</p>
                                <p class="font-bold text-amber-300">{{ $focusLength }} دقیقه</p>
                                <p class="text-[11px] text-slate-400">۴ تکرار سپس استراحت طولانی</p>
                            </div>
                            <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                                <p class="text-slate-300">استراحت کوتاه</p>
                                <p class="font-bold text-emerald-300">{{ $shortBreakLength }} دقیقه</p>
                                <p class="text-[11px] text-slate-400">بین هر دو دوره تمرکز</p>
                            </div>
                            <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                                <p class="text-slate-300">استراحت طولانی</p>
                                <p class="font-bold text-sky-200">{{ $longBreakLength }} دقیقه</p>
                                <p class="text-[11px] text-slate-400">پس از تکمیل ۴ دور تمرکز</p>
                            </div>
                        </div>
<br>
                        <div
                            class="mb-5 p-4 rounded-xl bg-gradient-to-r from-white/5 via-white/10 to-white/5 border border-white/5 text-xs leading-6 text-slate-200">
                            <p class="font-semibold text-white">قوانین طلایی تکنیک پومودرو</p>
                            <ul class="list-disc list-inside space-y-1 mt-2">
                                <li>۲۵ دقیقه تمرکز عمیق بدون حواس‌پرتی.</li>
                                <li>۵ دقیقه استراحت کوتاه برای ریکاوری بعد از هر دور.</li>
                                <li>بعد از ۴ دور تمرکز، حتماً ۲۰ دقیقه استراحت طولانی داشته باشید.</li>
                                <li>امکان توقف، پرش مرحله و شروع خودکار برای پیگیری دقیق چرخه‌ها فراهم شده است.</li>
                            </ul>
                        </div>

                    </div>

                    <!-- end section:learning-courses:slider -->
                </div>
                <!-- end section:learning-courses -->
            </div>
            <div class="p-5 text-xs text-muted whitespace-nowrap text-white">
            </div>
        </div>
    </div>


    @script
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
                    {front: '#7b5cff', back: '#6245e0'},
                    {front: '#b3c7ff', back: '#8fa5e5'},
                    {front: '#5c86ff', back: '#345dd1'},
                    {front: '#00d4ff', back: '#00a7cc'},
                    {front: '#00ff88', back: '#00cc6e'},
                    {front: '#ffcc00', back: '#cc9900'},
                    {front: '#ff6666', back: '#cc4444'},
                    {front: '#ff66cc', back: '#cc44aa'}
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
            }, {once: true});

            window.addEventListener('pomodoro-alarm', () => {
                const alarmSound = new Audio('/client/sounds/alarm.wav');
                alarmSound.play().catch(() => { /* جلوگیری از خطا در مرورگرهای مختلف */
                });
            });
        </script>
    @endscript
</div>
