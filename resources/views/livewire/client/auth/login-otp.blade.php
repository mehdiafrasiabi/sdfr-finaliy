<div class="min-h-screen flex items-center justify-center bg-background p-5">
    <div class="w-full max-w-sm space-y-5">
        <div class="bg-gradient-to-b from-secondary to-background rounded-3xl space-y-5 px-5 pb-5">
            <div class="bg-background rounded-b-3xl space-y-2 p-5 " style="    text-align: center;">
                <a href="{{route('client.home')}}" class="inline-flex items-center gap-2 text-primary">

                    <img src="/client/assets/images/theme/intro/header.png" style="width: 100px;">

                </a>
            </div>

            <!-- auth:verification:form -->
            @if ($step === 1)
                <form wire:submit.prevent="sendOtp">
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">ورود سریع</div>
                        </div>
                        <div class="text-sm text-muted space-y-3">
                            <p>درود 👋</p>
                            <p>لطفا شماره موبایل متصل به حساب کاربری را وارد کنید</p>
                        </div>

                        <!-- form:field:wrapper -->
                        <div class="text-sm text-muted space-y-3">

                            <p>نام کاربری :</p>
                        </div>
                        <div class="flex items-center relative">
                            <input type="tel" dir="rtl"
                                   wire:model="mobile"
                                   placeholder="بطور مثال:09121234567"
                                   maxlength="11"
                                   class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground placeholder:text-right px-5"/>


                        </div>
                        @error('mobile')
                        <div style="margin-top: 6px;color: red;font-size: 10px">{{ $message }}</div>
                        @enderror
                        <div style="" class="font-medium text-xs text-right text-muted">

                        <span class="text-right transition-colors">
                        </span>

                        </div>
                        <!-- end form:field:wrapper -->

                        <!-- form:submit button -->
                        <button type="submit"
                                class="flex items-center justify-center gap-1 w-full h-10 bg-primary rounded-full text-primary-foreground transition-all hover:opacity-80 px-4 mt-20">
                            <div wire:loading.remove>
                                <span class="font-semibold text-sm">برو بریم</span>

                            </div>
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
                        <hr class="border-dashed">
                        <!-- end form:submit button -->

                        <div class="font-medium text-xs text-center text-muted">
                            حساب کاربری نداری؟همین حالا
                            <a href="{{route('client.auth.signup')}}" class="text-foreground transition-colors hover:text-primary text-green-500">
                                ثبت نام
                            </a> کن.

                        </div>
                        <div class="font-medium text-xs text-center text-muted">

                            <a href="{{route('client.auth.forgotPassword')}}"
                               class="text-foreground transition-colors hover:text-primary text-red-500 ">
                                فراموشی رمز عبور
                            </a>

                        </div>
                    </div>
                </form>
            @elseif ($step === 2)
                <form wire:submit.prevent="verifyOtp">
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">اعتبار سنجی</div>
                        </div>
                        <div class="text-sm text-muted space-y-3">
                            <p>کاربر عزیز 👋</p>
                            <p>کد اعتبار سنجی برای شما ارسال گردید.</p>
                        </div>

                        <!-- form:field:wrapper -->
                        <div class="text-sm text-muted space-y-3">

                            <p>کد :</p>
                        </div>
                        <div class="flex items-center relative">
                            <input type="tel" dir="rtl"
                                   wire:model="code"
                                   maxlength="6"
                                   class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground placeholder:text-right px-5"/>


                        </div>
                        @error('code')
                        <div style="margin-top: 6px;color: red;font-size: 10px">{{ $message }}</div>
                        @enderror
                        <div style="" class="font-medium text-xs text-right text-muted">

                        <span class="text-right transition-colors">
                        </span>

                        </div>
                        @if ($countdown > 0)
                            <p class="text-sm text-gray-500">ارسال مجدد تا {{ $countdown }} ثانیه دیگر</p>
                        @else
                            <button wire:click="sendOtp">ارسال مجدد</button>
                        @endif
                        <!-- form:submit button -->
                        <button type="submit"
                                class="flex items-center justify-center gap-1 w-full h-10 bg-primary rounded-full text-primary-foreground transition-all hover:opacity-80 px-4 mt-20">
                            <div wire:loading.remove>
                                <span class="font-semibold text-sm">اعتبارسنجی</span>

                            </div>
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
                        <hr class="border-dashed">
                        <!-- end form:submit button -->
                    </div>
                </form>
            @endif
        </div>
        <div class="bg-secondary rounded-xl space-y-5 p-5">
            <div class="font-medium text-xs text-center text-muted">
                ورود شما به معنای پذیرش <a href="{{route('client.terms')}}"
                                           class="text-foreground transition-colors hover:text-primary hover:underline">شرایط</a>
                و
                <a href="{{route('client.terms')}}" class="text-foreground transition-colors hover:text-primary hover:underline">قوانین
                    حریم خصوصی</a> است.
            </div>
        </div>
    </div>
    @push('script')
        <script>
            Livewire.on('start-timer', () => {
                let interval = setInterval(() => {
                    Livewire.dispatch('tick')
                }, 1000);
            });
        </script>
    @endpush
</div>
