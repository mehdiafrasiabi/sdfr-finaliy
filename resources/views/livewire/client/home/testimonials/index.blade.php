<div>
    @php
        $row1 = [
            ['name' => 'علی', 'stars' => 5, 'text' => 'واقعاً از این اپ راضیم. برنامه‌ریزی‌ام خیلی منظم‌تر شده و دیگه هیچ روزی رو بدون هدف شروع نمی‌کنم.'],
            ['name' => 'سارا', 'stars' => 5, 'text' => 'بهترین اپ برای کنکور! هر روز باهاش برنامه دارم و پشتیبانی‌شون عالیه. واقعاً توصیه می‌کنم.'],
            ['name' => 'مهدی', 'stars' => 5, 'text' => 'با SDFR تونستم مدیریت وقتم رو کاملاً تغییر بدم. الان می‌دونم دقیقاً چی باید بخونم و چه موقع.'],
            ['name' => 'نیلوفر', 'stars' => 5, 'text' => 'هوش مصنوعیش خیلی هوشمندانه برنامه می‌چینه. احساس می‌کنم یه مربی شخصی دارم که همیشه کنارمه.'],
            ['name' => 'امیر', 'stars' => 5, 'text' => 'اول باورم نمی‌شد این‌قدر مفید باشه، ولی بعد از یه ماه کاملاً عاشقش شدم. راندمان درسیم دو برابر شد.'],
            ['name' => 'زهرا', 'stars' => 5, 'text' => 'رابط کاربری خوب، پشتیبانی سریع و تحلیل‌های دقیق. هر چیزی که یه دانش‌آموز کنکوری نیاز داره اینجاست.'],
            ['name' => 'رضا', 'stars' => 5, 'text' => 'ممنون از تیم SDFR. به خاطر این اپ تونستم از یه دانش‌آموز معمولی به رتبه سه رقمی برسم.'],
        ];

        $row2 = [
            ['name' => 'فاطمه', 'stars' => 5, 'text' => 'اپ خیلی کاربردیه. ثبت تکالیف، پیگیری پیشرفت و گزارش روزانه همه توش هست. دیگه به چیز دیگه‌ای نیاز ندارم.'],
            ['name' => 'حسین', 'stars' => 5, 'text' => 'از وقتی SDFR استفاده می‌کنم، استرسم خیلی کم شده. می‌دونم کجام و باید کجا برسم. این حس آرامش خیلی ارزشمنده.'],
            ['name' => 'مریم', 'stars' => 5, 'text' => 'تیم پشتیبانی همیشه جواب می‌ده و مشاوره‌هاشون واقعاً تخصصیه. احساس نمی‌کنی تنها هستی.'],
            ['name' => 'کیان', 'stars' => 5, 'text' => 'برنامه‌ریزی خودکارش باورنکردنیه. با توجه به ضعف‌هام بهترین برنامه رو می‌چینه و پیشرفتم کاملاً محسوسه.'],
            ['name' => 'آرین', 'stars' => 5, 'text' => 'قبل از این اپ حس می‌کردم وقتم هدر می‌ره. الان هر ساعت درسم معنا داره و نتیجه‌اش رو می‌بینم.'],
            ['name' => 'دینا', 'stars' => 5, 'text' => 'گزارش‌های هفتگی و تحلیل نقاط ضعف واقعاً کمک‌کننده‌ست. انگار یه معلم خصوصی بی‌نهایت صبور دارم.'],
            ['name' => 'پارسا', 'stars' => 5, 'text' => 'سادگی و کارایی با هم! رابط کاربری شیک، سرعت بالا و محتوا دقیقاً مناسب کنکور. یه اپ کامل.'],
        ];
    @endphp

    <div class="overflow-hidden">
        <div class="max-w-7xl px-4 mx-auto mb-12 text-center">
            <h2 class="text-2xl font-black text-foreground">نظرات دانش‌آموزان</h2>
        </div>
        {{-- Row 1: حرکت به چپ (rtl scroll) --}}
        <div class="overflow-hidden w-full mb-4" dir="ltr">
            <div class="flex w-max" id="track-1">
                @foreach(array_merge($row1, $row1, $row1) as $item)
                    <div class="flex-shrink-0 w-72 mx-2 rounded-2xl p-6 flex flex-col gap-3 bg-secondary border border-border relative overflow-hidden" dir="rtl">
                        <div class="flex gap-1">
                            @for($i = 0; $i < $item['stars']; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.6824 6.7863L13.2658 6.2938C13.0724 6.26964 12.9041 6.1488 12.8149 5.97047L11.2916 2.92047C11.2891 2.91714 11.2874 2.91381 11.2858 2.90964C11.1458 2.65047 10.9283 2.43297 10.6658 2.2938C9.95992 1.93464 9.08409 2.21131 8.71159 2.92047L7.18662 5.96964C7.09662 6.14797 6.92662 6.26964 6.72579 6.29464L3.31662 6.7863C2.98662 6.83547 2.69746 6.98214 2.48246 7.20879C2.21746 7.48295 2.07579 7.84462 2.08329 8.22629C2.09079 8.60795 2.24662 8.96212 2.51996 9.22379L4.99246 11.6038C5.12996 11.7321 5.19412 11.928 5.16079 12.1188L4.57662 15.4671C4.52662 15.7796 4.57996 16.1021 4.72496 16.3688C4.90662 16.7105 5.20912 16.9596 5.57746 17.073C5.71662 17.1155 5.85912 17.1363 5.99996 17.1363C6.23162 17.1363 6.46079 17.0796 6.66829 16.9688L9.71826 15.3921C9.89492 15.298 10.1074 15.298 10.2883 15.3938L13.3233 16.9638C13.5958 17.1138 13.9066 17.168 14.2341 17.118C15.0166 16.9896 15.5524 16.248 15.4249 15.4605L14.8416 12.1188C14.8074 11.9221 14.8708 11.7296 15.0149 11.5913L17.4799 9.22379C17.7108 9.00129 17.8624 8.70379 17.9041 8.38629C18.0066 7.60962 17.4574 6.8913 16.6824 6.7863Z" fill="url(#paint0_linear_27037_99)"/>
                                    <defs>
                                        <linearGradient id="paint0_linear_27037_99" x1="9.9997" y1="2.13672" x2="9.9997" y2="17.1363" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#B99C49"/>
                                            <stop offset="1" stop-color="#A5862B"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-sm leading-7 text-foreground flex-1">{{ $item['text'] }}</p>
                        <span class="text-xs font-bold text-foreground/60">{{ $item['name'] }}</span>
                        <div class="absolute -bottom-2 left-3 opacity-[0.07]">
                            <svg width="80" height="60" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Row 2: حرکت به راست (ltr scroll) --}}
        <div class="overflow-hidden w-full" dir="ltr">
            <div class="flex w-max" id="track-2">
                @foreach(array_merge($row2, $row2, $row2) as $item)
                    <div class="flex-shrink-0 w-72 mx-2 rounded-2xl p-6 flex flex-col gap-1 bg-secondary border border-border relative overflow-hidden" dir="rtl">
                        <div class="flex gap-1">
                            @for($i = 0; $i < $item['stars']; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.6824 6.7863L13.2658 6.2938C13.0724 6.26964 12.9041 6.1488 12.8149 5.97047L11.2916 2.92047C11.2891 2.91714 11.2874 2.91381 11.2858 2.90964C11.1458 2.65047 10.9283 2.43297 10.6658 2.2938C9.95992 1.93464 9.08409 2.21131 8.71159 2.92047L7.18662 5.96964C7.09662 6.14797 6.92662 6.26964 6.72579 6.29464L3.31662 6.7863C2.98662 6.83547 2.69746 6.98214 2.48246 7.20879C2.21746 7.48295 2.07579 7.84462 2.08329 8.22629C2.09079 8.60795 2.24662 8.96212 2.51996 9.22379L4.99246 11.6038C5.12996 11.7321 5.19412 11.928 5.16079 12.1188L4.57662 15.4671C4.52662 15.7796 4.57996 16.1021 4.72496 16.3688C4.90662 16.7105 5.20912 16.9596 5.57746 17.073C5.71662 17.1155 5.85912 17.1363 5.99996 17.1363C6.23162 17.1363 6.46079 17.0796 6.66829 16.9688L9.71826 15.3921C9.89492 15.298 10.1074 15.298 10.2883 15.3938L13.3233 16.9638C13.5958 17.1138 13.9066 17.168 14.2341 17.118C15.0166 16.9896 15.5524 16.248 15.4249 15.4605L14.8416 12.1188C14.8074 11.9221 14.8708 11.7296 15.0149 11.5913L17.4799 9.22379C17.7108 9.00129 17.8624 8.70379 17.9041 8.38629C18.0066 7.60962 17.4574 6.8913 16.6824 6.7863Z" fill="url(#paint0_linear_27037_99)"/>
                                    <defs>
                                        <linearGradient id="paint0_linear_27037_99" x1="9.9997" y1="2.13672" x2="9.9997" y2="17.1363" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#B99C49"/>
                                            <stop offset="1" stop-color="#A5862B"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-sm leading-7 text-foreground flex-1">{{ $item['text'] }}</p>
                        <span class="text-xs font-bold text-foreground/60">{{ $item['name'] }}</span>
                        <div class="absolute -bottom-2 left-3 opacity-[0.07]">
                            <svg width="80" height="60" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        (function () {
            function setupMarquee(trackId, direction) {
                const track = document.getElementById(trackId);
                if (!track) return;

                const totalWidth = track.scrollWidth;
                const oneSet = totalWidth / 3;

                // شروع از وسط (ست دوم) تا loop بی‌درز باشه
                let pos = direction === 'ltr' ? -oneSet : 0;
                const speed = 1.5;

                function animate() {
                    if (direction === 'ltr') {
                        // حرکت به سمت راست
                        pos += speed;
                        if (pos >= 0) pos = -oneSet;
                    } else {
                        // حرکت به سمت چپ
                        pos -= speed;
                        if (pos <= -oneSet) pos = 0;
                    }
                    track.style.transform = `translateX(${pos}px)`;
                    requestAnimationFrame(animate);
                }

                animate();
            }

            setupMarquee('track-1', 'rtl'); // ردیف اول به چپ
            setupMarquee('track-2', 'ltr'); // ردیف دوم به راست
        })();
    </script>
</div>
