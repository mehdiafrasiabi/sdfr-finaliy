<div class="max-w-7xl space-y-14 px-4 mx-auto">
    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

            <!-- end user:info -->

            <!-- user:menus -->

            <livewire:client.profile.sidebar />

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
                        <div class="font-black text-foreground">پومودرو</div>
                    </div>
                    <!-- end section:title -->

                    <!-- section:learning-courses:slider -->
                    <div class="max-w-md mx-auto p-4 bg-gray-800 text-white rounded-lg shadow-lg">

                        <h2 class="text-2xl mb-4 font-bold">
                            حالت:
                            @if ($mode === 'focus')
                                <p class="text-white"> تمرکز ۲۵ دقیقه‌ای</p>
                            @elseif ($mode === 'shortBreak')
                                <p class="text-yellow-500">                                استراحت کوتاه ۵ دقیقه‌ای
                                </p>
                            @elseif ($mode === 'longBreak')
                                <p class="text-green-500"> استراحت طولانی ۲۰ دقیقه‌ای</p>
                            @endif
                        </h2>
                        <br>
                        <div class="text-2xl font-bold font-mono mb-6 text-center text-primary">
                            {{ floor($timeLeft / 60) }}:{{ str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <br>
                        <div class="flex justify-center gap-4 mb-6">
                            <button wire:click="start" class="px-5 py-2 bg-green-500 rounded-full hover:bg-green-700">شروع</button>
                            <button wire:click="stop" class="px-5 py-2 bg-secondary/80 rounded-full hover:bg-yellow-600">توقف</button>
                            <button wire:click="resetTimer" class="px-5 py-2 bg-red-500 rounded-full hover:bg-red-700">ریست</button>
                        </div>

                        @if ($isRunning)
                            <div wire:poll.1000ms="tick"></div>
                        @endif

                        <div class="text-center text-lg font-semibold">
                            دورهای مطالعه کامل شده: <span class="text-green-500">{{ $rounds }}</span><br>
                            چرخه‌های کامل شده (۴ دور + استراحت طولانی): <span class="text-blue-500">{{ $cycles }}</span>
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
    @push('script')
        <script>
            window.addEventListener('pomodoro-alarm', () => {
                const alarmSound = new Audio('/client/sounds/alarm.wav');
                alarmSound.play().catch(() => { /* جلوگیری از خطا در مرورگرهای مختلف */ });
                // می‌توانید اینجا حالت ضدحواس‌پرتی یا نوتیفیکیشن هم اضافه کنید
            });
        </script>
    @endpush
</div>
