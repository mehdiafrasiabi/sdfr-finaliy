<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="space-y-8">
            <!-- section:title -->
            <div class="flex items-center gap-5 bg-gradient-to-l from-secondary to-background rounded-2xl p-5">
                <span
                    class="flex items-center justify-center w-12 h-12 bg-primary text-primary-foreground rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd"
                              d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z"
                              clip-rule="evenodd"></path>
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <span class="font-black xs:text-2xl text-lg text-primary">مقالات SDFR</span>
                    <span class="font-semibold text-xs text-muted">با ما باش تا بهترین باشی !</span>
                </div>
            </div>
            <!-- end section:title -->

            <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

                <!-- Navbar - در موبایل اول، در سایزهای بزرگتر در سمت چپ -->
                <aside class="md:col-span-4 lg:col-span-3 md:order-2 order-1">
                    <div class="space-y-8 md:sticky md:top-4">
                        <!-- Categories -->
                        <div
                            class="bg-background border border-primary/20 rounded-xl shadow-xl shadow-black/5 p-6 transition-colors duration-200">
                            <h3 class="font-black xs:text-2xl text-lg text-white text-center mb-3">
                                دسته‌بندی‌ها</h3>
                            <ul class="space-y-3">
                                <li><a href="#"
                                       class="flex justify-between text-muted hover:text-primary transition-colors">
                                        <span>تکنولوژی</span>
                                        <span class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-primary text-primary-foreground font-bold text-xs">20</span>
                                    </a></li>
                                <li><a href="#"
                                       class="flex justify-between text-muted hover:text-primary transition-colors">
                                        <span>خرید اینترنتی</span>
                                        <span class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-primary text-primary-foreground font-bold text-xs">15</span>
                                    </a></li>
                                <li><a href="#"
                                       class="flex justify-between text-muted hover:text-primary transition-colors">
                                        <span>گجت‌ها</span>
                                        <span class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-primary text-primary-foreground font-bold text-xs">10</span>
                                    </a></li>
                                <li><a href="#"
                                       class="flex justify-between text-muted hover:text-primary transition-colors">
                                        <span>صوت و تصویر</span>
                                        <span class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-primary text-primary-foreground font-bold text-xs">0</span>
                                    </a></li>
                                <li><a href="#"
                                       class="flex justify-between text-muted hover:text-primary transition-colors">
                                        <span>نقد و بررسی</span>
                                        <span
                                            class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-primary text-primary-foreground font-bold text-xs">9</span>
                                    </a></li>
                            </ul>
                        </div>

                        <!--Popular articles-->
                        {{--                        <div class="bg-background rounded-xl shadow-xl shadow-black/5 p-6 transition-colors duration-200">--}}
                        {{--                            <h3 class="font-bold text-foreground text-lg mb-4 relative pb-4 before:absolute before:right-0 before:bottom-0 before:size-2 before:rounded-full before:bg-primary after:absolute after:w-40 after:h-2 after:bottom-0 after:right-4 after:bg-primary after:rounded-lg">مقالات پرطرفدار</h3>--}}
                        {{--                            <div class="space-y-4">--}}
                        {{--                                <a href="#" class="flex items-start gap-3 group">--}}
                        {{--                                    <img src="https://via.placeholder.com/64" alt="مقاله پرطرفدار" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">--}}
                        {{--                                    <div>--}}
                        {{--                                        <h4 class="text-sm font-medium text-foreground group-hover:text-primary transition-colors line-clamp-2">چگونه لپ‌تاپ مناسب برای کارهای گرافیکی انتخاب کنیم؟</h4>--}}
                        {{--                                        <span class="text-xs text-muted">25 اردیبهشت 1402</span>--}}
                        {{--                                    </div>--}}
                        {{--                                </a>--}}
                        {{--                                <a href="#" class="flex items-start gap-3 group">--}}
                        {{--                                    <img src="https://via.placeholder.com/64" alt="مقاله پرطرفدار" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">--}}
                        {{--                                    <div>--}}
                        {{--                                        <h4 class="text-sm font-medium text-foreground group-hover:text-primary transition-colors line-clamp-2">۱۰ کفش ورزشی برتر سال برای دویدن</h4>--}}
                        {{--                                        <span class="text-xs text-muted">18 اردیبهشت 1402</span>--}}
                        {{--                                    </div>--}}
                        {{--                                </a>--}}
                        {{--                                <a href="#" class="flex items-start gap-3 group">--}}
                        {{--                                    <img src="https://via.placeholder.com/64" alt="مقاله پرطرفدار" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">--}}
                        {{--                                    <div>--}}
                        {{--                                        <h4 class="text-sm font-medium text-foreground group-hover:text-primary transition-colors line-clamp-2">راهنمای خرید عینک آفتابی مناسب برای فصل تابستان</h4>--}}
                        {{--                                        <span class="text-xs text-muted">10 اردیبهشت 1402</span>--}}
                        {{--                                    </div>--}}
                        {{--                                </a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        <!-- newsletter -->
                        <div
                            class="bg-primary/5 border border-primary/20 p-6 rounded-xl transition-colors duration-200">
                            <h3 class="font-black xs:text-2xl text-lg text-white text-center mb-3">عضویت در خبرنامه</h3>
                            <p class="text-sm text-muted mb-5">با عضویت در خبرنامه از آخرین مقالات و تخفیف‌های ویژه مطلع
                                شوید.</p>
                            <form class="space-y-3">
                                <input type="email" placeholder="آدرس ایمیل شما"
                                       class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground">
                                <button type="submit"
                                        class="w-full bg-primary hover:opacity-90 text-primary-foreground py-2 px-4 rounded-lg transition duration-300 font-semibold">
                                    عضویت
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="lg:col-span-9 md:col-span-8 md:order-1 order-2">
                    <!-- sort & filter(offcanvas) -->
                    <div class="flex items-center gap-3 mb-3" x-data="{ offcanvasOpen: false }">
                        <!-- sort -->
                        <div
                            x-data="{ range: function(start, end) { return Array(end - start + 1).fill().map((_, idx) => start + idx) } }">
                            <!-- form:select container -->
                            <div class="flex items-center gap-3">
                                <!-- form:select:label -->
                                <label class="sm:flex hidden items-center gap-1 font-semibold text-xs text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         class="w-5 h-5">
                                        <path
                                            d="M10 3.75a2 2 0 1 0-4 0 2 2 0 0 0 4 0ZM17.25 4.5a.75.75 0 0 0 0-1.5h-5.5a.75.75 0 0 0 0 1.5h5.5ZM5 3.75a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 .75.75ZM4.25 17a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5h1.5ZM17.25 17a.75.75 0 0 0 0-1.5h-5.5a.75.75 0 0 0 0 1.5h5.5ZM9 10a.75.75 0 0 1-.75.75h-5.5a.75.75 0 0 1 0-1.5h5.5A.75.75 0 0 1 9 10ZM17.25 10.75a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5h1.5ZM14 10a2 2 0 1 0-4 0 2 2 0 0 0 4 0ZM10 16.25a2 2 0 1 0-4 0 2 2 0 0 0 4 0Z"/>
                                    </svg>
                                    مرتب سازی:
                                </label><!-- end form:select:label -->
                                <!-- form:select -->
                                <div class="w-52 relative"
                                     x-data="{ open: false, selectedOption: 'انتخاب کنید', selectedValue: '', options: ['جدید‌ترین', 'در حال برگزاری', 'تکمیل ضبط‌', 'دوره‌های خریداری شده', 'در حال مشاهده', 'قدیمی‌ترین'] }">
                                    <select wire:model.live.debounce.350ms="categoryId" id="category"
                                            class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                        <option value="">انتخاب پایه و رشته</option>
                                    </select>
                                </div><!-- end form:select -->
                            </div><!-- end form:select container -->
                        </div>
                        <!-- end sort -->

                    </div>

                    <!-- articles:wrapper -->
                    <div class="grid lg:grid-cols-3 sm:grid-cols-2 gap-x-5 gap-y-10">
                        @forelse($blogs as $blog)
                            @php
                                $slug = optional($blog->seo)->slug ?? \Illuminate\Support\Str::slug($blog->title);
                                $image = $blog->images->first();
                            @endphp

                            <div class="relative bg-background rounded-xl shadow-xl shadow-black/5 p-4">
                                <div class="relative mb-3 z-20">
                                    <a href="{{ route('client.blog.show', [$blog->blog_code, $slug]) }}" class="block">
                                        <img
                                            src="{{ $image ? asset('blogs/'.$blog->id.'/photo/'.$image->path) : '/client/assets/images/theme/default.jpg' }}"
                                            class="max-w-full rounded-xl" alt="{{ $blog->seo->meta_title }}"/>
                                    </a>
                                </div>
                                <div class="relative space-y-3 z-10">
                                    <h2 class="font-bold text-sm">
                                        <a href="{{ route('client.blog.show', [$blog->blog_code, $slug]) }}"
                                           class="line-clamp-1 text-foreground transition-colors hover:text-primary">
                                            {{$blog->title}}
                                        </a>
                                    </h2>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1">
                                            <div class="flex items-center gap-1 text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                <span class="font-semibold text-xs text-muted">زمان مطالعه:</span>
                                                <span class="font-semibold text-xs text-foreground">{{$blog->study_time}} دقیقه</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('client.blog.show', [$blog->blog_code, $slug]) }}"
                                           class="bg-primary/10 rounded-full text-primary transition-all hover:opacity-80 py-1 px-4">
                                            <span class="font-bold text-xxs">{{$blog->category->name}}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center text-center col-span-full">
                                <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35"
                                     alt="..."/>
                                <div class="text-center space-y-3">
                                    <h2 class="font-bold text-xl text-foreground">مقاله ای وجود ندارد</h2>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <!-- end articles:wrapper -->

                    <div class="flex justify-center mt-8">
                        <!-- load more:button -->
                        <button type="button"
                                class="h-11 inline-flex items-center justify-center gap-1 bg-secondary rounded-full text-primary px-8">
                            <span class="font-semibold text-sm">در حال بارگذاری</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="w-5 h-5 animate-spin">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                            </svg>
                        </button>
                        <!-- end load more:button -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
