<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="space-y-8">
            <!-- section:title -->
            <div class="flex items-center gap-5 bg-gradient-to-l from-secondary to-background rounded-2xl p-5">
                        <span class="flex items-center justify-center w-12 h-12 bg-primary text-primary-foreground rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                <div class="flex flex-col space-y-2">
                    <span class="font-black xs:text-2xl text-lg text-primary">دوره های آموزشی  SDFR</span>
                    <span class="font-semibold text-xs text-muted">با ما باش تا بهترین باشی !</span>
                </div>
            </div>
            <!-- end section:title -->

            <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">


                <div class="lg:col-span-9 md:col-span-8">
                    <!-- sort & filter(offcanvas) -->
                    <div class="flex items-center gap-3 mb-3" x-data="{ offcanvasOpen: false }">
                        <!-- sort -->
                        <div
                            x-data="{ range: function(start, end) { return Array(end - start + 1).fill().map((_, idx) => start + idx) } }">
                            <!-- form:select container -->
                            <div class="flex items-center gap-3">
                                <!-- form:select:label -->
                                <label
                                    class="sm:flex hidden items-center gap-1 font-semibold text-xs text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                         fill="currentColor" class="w-5 h-5">
                                        <path
                                            d="M10 3.75a2 2 0 1 0-4 0 2 2 0 0 0 4 0ZM17.25 4.5a.75.75 0 0 0 0-1.5h-5.5a.75.75 0 0 0 0 1.5h5.5ZM5 3.75a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 .75.75ZM4.25 17a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5h1.5ZM17.25 17a.75.75 0 0 0 0-1.5h-5.5a.75.75 0 0 0 0 1.5h5.5ZM9 10a.75.75 0 0 1-.75.75h-5.5a.75.75 0 0 1 0-1.5h5.5A.75.75 0 0 1 9 10ZM17.25 10.75a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5h1.5ZM14 10a2 2 0 1 0-4 0 2 2 0 0 0 4 0ZM10 16.25a2 2 0 1 0-4 0 2 2 0 0 0 4 0Z" />
                                    </svg>
                                    مرتب سازی:
                                </label><!-- end form:select:label -->
                                <!-- form:select -->
                                <div class="w-52 relative"
                                     x-data="{ open: false, selectedOption: 'انتخاب کنید', selectedValue: '', options: ['جدید‌ترین', 'در حال برگزاری', 'تکمیل ضبط‌', 'دوره‌های خریداری شده', 'در حال مشاهده', 'قدیمی‌ترین'] }">

                                    <!-- The selected value is stored in this input. -->
                                    <select wire:model.live.debounce.350ms="categoryId" id="category" class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                        <option value="">همه دسته‌بندی‌ها</option>

                                    </select>

                                    <!-- form:select:button -->

                                    <!-- end form:select:button -->


                                </div><!-- end form:select -->
                            </div><!-- end form:select container -->
                        </div>
                        <!-- end sort -->

                    </div>
                    <div class="flex flex-col items-center justify-center text-center">
                        <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="..." />
                        <div class="text-center space-y-3">
                            <h2 class="font-bold text-xl text-foreground">
                                دوره آموزشی وجود ندارد (بزودی)
                            </h2>
                        </div>
                    </div>
                    <!-- articles:wrapper -->
                    <div class="grid lg:grid-cols-3 sm:grid-cols-2 gap-x-5 gap-y-10">



                    </div>
                    <!-- end articles:wrapper -->

                    <div class="flex justify-center mt-8">
                        <!-- load more:button -->
                        <button type="button"
                                class="h-11 inline-flex items-center justify-center gap-1 bg-secondary rounded-full text-primary px-8">
                            <span class="font-semibold text-sm">در حال بارگذاری</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="w-5 h-5 animate-spin">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                            </svg>
                        </button>
                        <!-- end load more:button -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
