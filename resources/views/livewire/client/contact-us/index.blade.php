<div class="max-w-7xl space-y-14 px-4 mx-auto">
    <!-- section:title -->
    <div class="flex flex-col items-start space-y-2">
        <h2 class="font-black text-2xl text-foreground">تمـــــاس باما</h2>
        <p class="font-semibold text-sm text-muted">
            در این صفحه میتوانید اطلاعات ارتباطی خود را وارد کرده و کارشناسان ما در اسرع وقت با شما ارتباط برقرار خواهد کرد .
        </p>
    </div>
    <!-- end section:title -->

    <div class="grid md:grid-cols-12 gap-8">
        <div class="md:col-span-7 space-y-8">
            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1">
                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                    </div>
                    <div class="font-black text-foreground">شبکه های اجتماعی</div>
                </div>
                <ul class="flex flex-wrap items-center gap-5">
                    <li>
                        <a href="https://instagram.com/sdfr.me"
                           class="flex items-center justify-center w-12 h-12 bg-secondary rounded-full text-foreground transition-colors hover:text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="w-5 h-5">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="https://t.me/Atghiaeee"
                           class="flex items-center justify-center w-12 h-12 bg-secondary rounded-full text-foreground transition-colors hover:text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="w-5 h-5">
                                <path d="m22 2-7 20-4-9-9-4Z"></path>
                                <path d="M22 2 11 13"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                           class="flex items-center justify-center w-12 h-12 bg-secondary rounded-full text-foreground transition-colors hover:text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="w-5 h-5">
                                <path
                                    d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17">
                                </path>
                                <path d="m10 15 5-3-5-3z"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1">
                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                    </div>
                    <div class="font-black text-foreground">شماره تماس دفتر</div>
                </div>
                <a href="tel: 05135092160"
                   class="inline-flex font-bold text-base text-foreground transition-colors hover:text-primary"
                   dir="ltr">051-35092160</a>
            </div>
            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1">
                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                    </div>
                    <div class="font-black text-foreground">آدرس دفتر مرکزی</div>
                </div>
                <span
                    class="inline-flex font-bold text-base text-foreground transition-colors hover:text-primary">
                      خراسان رضوی ، مشهد
                </span>
            </div>
        </div>

        <div class="md:col-span-5">
            <!-- contact-us:form:wrapper -->
            <div class="bg-gradient-to-b from-secondary to-background rounded-2xl px-5 pb-5">
                <!-- contact-us:title -->
                <div class="bg-background rounded-b-3xl space-y-2 p-5 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">فرم تماس باما</div>
                    </div>
                </div>
                <!-- end contact-us:title -->

                <!-- contact-us:form -->
                <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))" class="space-y-5">
                    <div class="space-y-1">
                        <label for="name" class="font-medium text-xs text-muted">نام
                            و
                            نام خانوادگی (فارسی) :</label>
                        <input type="text" id="name" name="name" wire:model="name"
                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                        @error('name')
                        <div style="color: red;margin-right: 8px">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="space-y-1">
                        <label for="mobile" class="font-medium text-xs text-muted">شماره تماس :</label>
                        <input type="tel" dir="ltr" id="mobile" name="mobile" wire:model="mobile"
                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                        @error('mobile')
                        <div style="color: red;margin-right: 8px">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="space-y-1">
                        <label for="text" class="font-medium text-xs text-muted">متن پیــــام (اختیاری) :</label>
                        <textarea rows="8" id="text" name="text" wire:model="text"
                                  class="form-textarea w-full !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"></textarea>
                        @error('text')
                        <div style="color: red;margin-right: 8px">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-5">
                        <button type="submit" name="submit"
                                class="h-11 inline-flex items-center justify-center bg-primary rounded-full text-white px-8 mr-auto">
                            <span class="font-semibold text-sm" wire:loading.remove>ارسال پیـــام</span>
                            <div wire:loading>
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="40px" height="40px"
                                     style="shape-rendering: auto; display: block; background: transparent;">
                                    <g>
                                        <path stroke="none" fill="#ffffff"
                                              d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                                            <animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1"
                                                              repeatCount="indefinite" dur="0.8130081300813008s"
                                                              type="rotate" attributeName="transform"/>
                                        </path>
                                        <g/>
                                    </g>
                                </svg>
                            </div>
                        </button>
                    </div>
                </form>
                <!-- end contact-us:form -->
            </div>
            <!-- end contact-us:form:wrapper -->
        </div>
    </div>
</div>
