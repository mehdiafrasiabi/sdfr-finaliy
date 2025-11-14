<div class="min-h-screen flex items-center justify-center bg-background p-5">
    @push('link')
        <style>
            .circle {
                display: inline-block;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                margin-right: 5px;
            }

            .bg-green-500 {
                background-color: #10b981;
            }

            .bg-red-500 {
                background-color: #ef4444;
            }

            .requirement-item {
                margin-bottom: 5px;
            }
            .hidden { display: none; }
            .circle {
                display: inline-block;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                margin-right: 5px;
            }
            .bg-green-500 { background-color: #10b981; }
            .bg-red-500 { background-color: #ef4444; }
            .requirement-item { margin-bottom: 5px; }
        </style>
    @endpush
    <div class="w-full max-w-sm space-y-5">
        <div class="bg-gradient-to-b from-secondary to-background rounded-3xl space-y-5 px-5 pb-5">
            <div class="bg-background rounded-b-3xl space-y-2 p-5 " style="    text-align: center;">
                <a href="{{route('client.home')}}" class="inline-flex items-center gap-2 text-primary">

                    <img src="/client/assets/images/theme/intro/header.png" style="width: 100px;">

                </a>
            </div>

            @if ($step === 'mobile')
                <!-- auth:mobile:form -->
                <form wire:submit="sendOtp(Object.fromEntries(new FormData($event.target)))" method="post"
                      class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">ثبت نام</div>
                    </div>
                    <div class="text-sm text-muted space-y-3">
                        <p>درود 👋</p>
                        <p>لطفا نام کامل و شماره موبایل خودرا وارد کنید.</p>
                    </div>

                    <!-- form:field:wrapper -->
                    <div class="text-sm text-muted space-y-3">

                        <p>نام و نام خانوادگی :</p>
                    </div>
                    <div class="flex items-center relative">
                        <input type="text" dir="rtl"
                               wire:model="name"
                               placeholder="بطور مثال:مهدی آبان"
                               name="name"
                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm
                            text-foreground placeholder:text-right px-5  @error('name') is-invalid @enderror"/>


                    </div>
                    @error('name')
                    <div style="margin-top: 6px;color: red;font-size: 10px">{{ $message }}</div>
                    @enderror
                    <div class="text-sm text-muted space-y-3">

                        <p>شماره موبایل :</p>
                    </div>
                    <div class="flex items-center relative">
                        <input type="tel" dir="rtl"
                               wire:model="userMobile"
                               placeholder="بطور مثال:09121234567"
                               maxlength="11"
                               name="mobile"
                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl
                           text-sm text-foreground placeholder:text-right px-5 @error('mobile') is-invalid @enderror"/>


                    </div>
                    @error('mobile')
                    <div style="margin-top: 6px;color: red;font-size: 10px">{{ $message }}</div>
                    @enderror
                    <br>
                    @if($sendSmsError)
                        <div style="margin-top: 6px;color: red">{{ $sendSmsError }}</div>
                    @endif
                    <div style="" class="font-medium text-xs text-right text-muted">

                        <span class="text-right transition-colors">
                        </span>

                    </div>
                    <!-- end form:field:wrapper -->

                    <!-- form:submit button -->
                    <button type="submit"
                            class="flex items-center justify-center gap-1 w-full h-10 bg-primary rounded-full
                            text-primary-foreground transition-all hover:opacity-80 px-4 mt-20"
                            wire:loading.attr="disabled"
                            wire:target="sendOtp,verifyOtp,register">
                        <span class="font-semibold text-sm" wire:loading wire:target="sendOtp,verifyOtp,register">در حال ارسال...</span>
                        <span class="font-semibold text-sm" wire:loading.remove wire:target="sendOtp,verifyOtp,register">ثبت</span>
                    </button>
                    <hr class="border-dashed">
                    <!-- end form:submit button -->
                    <div class="font-medium text-xs text-center text-muted">

                        <a href="{{route('client.auth.otp')}}"
                           class=" rounded-full text-white transition-colors hover:text-white h-8 w-full">
                            ورود از طریق رمز یکبار مصرف
                        </a>

                    </div>
                    <div class="font-medium text-xs text-center text-muted">
                        حساب کاربری داری؟همین حالا
                        <a href="{{route('client.auth.login')}}"
                           class="text-foreground transition-colors hover:text-primary text-green-500">
                            وارد
                        </a> شو.

                    </div>
                    <div class="font-medium text-xs text-center text-muted">

                        <a href="{{route('client.auth.forgotPassword')}}"
                           class="text-foreground transition-colors hover:text-primary text-red-500 ">
                            فراموشی رمز عبور
                        </a>

                    </div>
                </form>
                <!-- end auth:mobile:form -->
            @elseif ($step === 'otp')
                <form wire:submit.prevent.debounce.500ms="verifyOtp(Object.fromEntries(new FormData($event.target)))" method="post"
                      class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">اعتبار سنجی</div>
                    </div>
                    <div class="text-sm text-muted space-y-3">
                        <p>{{$name}} عزیز 👋</p>
                        <p>کد اعتبار سنجی برای شما ارسال گردید.</p>
                    </div>

                    <!-- form:field:wrapper -->
                    <div class="text-sm text-muted space-y-3">

                        <p>کد :</p>
                    </div>
                    <div class="flex items-center relative">
                        <input type="tel" dir="rtl"
                               maxlength="6"
                               name="code"
                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl
                           text-sm text-foreground placeholder:text-right px-5 @error('code') is-invalid @enderror"/>


                    </div>
                    @error('code')
                    <div style="margin-top: 6px;color: red;font-size: 10px">{{ $message }}</div>
                    @enderror

                    @if($codeErrorMessage)
                        <div style="margin-top: 6px;color: red;font-size: 13px">{{ $codeErrorMessage }}</div>
                    @endif
                    <div class="font-medium text-xs text-right text-muted">

                        <div class="text-right transition-colors">
                            <div class="font-medium text-xs text-right text-muted">
                                <span class="text-right transition-colors">
                                    <div class="mb-3">
                                        <p id="countdown-text" class="{{ $countdown > 0 ? '' : 'hidden' }}">
                                            تا ارسال مجدد کد: <span id="countdown">{{ $countdown }}</span> ثانیه
                                        </p>
                                        <button type="button" id="resend-otp" class="btn btn-success {{ $countdown > 0 ? 'hidden' : '' }}"
                                                wire:click="resendOtp"
                                                wire:loading.attr="disabled"
                                                wire:target="resendOtp">
                                            <span wire:loading wire:target="resendOtp">در حال ارسال...</span>
                                            <span wire:loading.remove wire:target="resendOtp">ارسال مجدد OTP</span>
                                        </button>
                                    </div>
                                </span>
                            </div>
                        </div>

                    </div>
                    <!-- end form:field:wrapper -->

                    <!-- form:submit button -->
                    <button type="submit"
                            class="flex items-center justify-center gap-1 w-full h-10 bg-primary rounded-full
                            text-primary-foreground transition-all hover:opacity-80 px-4 mt-20"
                            wire:loading.attr="disabled"
                            wire:target="sendOtp,verifyOtp,register">
                        <span class="font-semibold text-sm" wire:loading wire:target="sendOtp,verifyOtp,register">بررسی اعتبار سنجی...</span>
                        <span class="font-semibold text-sm" wire:loading.remove wire:target="sendOtp,verifyOtp,register">ثبت</span>
                    </button>
                    <hr class="border-dashed">
                    <!-- end form:submit button -->

                </form>
            @elseif ($step === 'password')
                <form wire:submit.prevent.debounce.500ms="register(Object.fromEntries(new FormData($event.target)))" method="post"
                      class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">ایجاد رمز عبور</div>
                    </div>
                    <div class="text-sm text-muted space-y-3">
                        <p>{{$name}} عزیز 👋</p>
                        <p>لطفا یک رمز عبور ایمن ایجاد کنید.</p>
                    </div>

                    <!-- form:field:wrapper -->
                    <div class="text-sm text-muted space-y-3">

                        <p>رمز عبور :</p>
                    </div>
                    <div class="flex items-center relative form-group">

                        <input type="password"
                               wire:model.live="password"
                               dir="rtl"
                               id="password"
                               name="password"
                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground placeholder:text-right px-5"/>
                        <button type="button" onclick="togglePassword()" style="
                        position: absolute;
                        top: 50%;
                        right: 308px;
                        transform: translateY(-50%);
                        background: none;
                        border: none;
                        cursor: pointer; ">
                            <!-- چشم باز -->
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                <path
                                    d="M10 3C5.5 3 1.7 6.1.4 10c1.3 3.9 5.1 7 9.6 7s8.3-3.1 9.6-7c-1.3-3.9-5.1-7-9.6-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                                <path d="M10 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                            </svg>

                            <!-- چشم بسته -->
                            <svg hidden id="eye-closed" class="w-5 h-5 text-white" width="20" height="20"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <title>eye-disabled-glyph</title>
                                <path
                                    d="M409.84,132.33l95.91-95.91A21.33,21.33,0,1,0,475.58,6.25L6.25,475.58a21.33,21.33,0,1,0,30.17,30.17L140.77,401.4A275.84,275.84,0,0,0,256,426.67c107.6,0,204.85-61.78,253.81-161.25a21.33,21.33,0,0,0,0-18.83A291,291,0,0,0,409.84,132.33ZM256,362.67a105.78,105.78,0,0,1-58.7-17.8l31.21-31.21A63.29,63.29,0,0,0,256,320a64.07,64.07,0,0,0,64-64,63.28,63.28,0,0,0-6.34-27.49l31.21-31.21A106.45,106.45,0,0,1,256,362.67ZM2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.15,147.11,148.4,85.33,256,85.33a277,277,0,0,1,70.4,9.22l-55.88,55.88A105.9,105.9,0,0,0,150.44,270.52L67.88,353.08A295.2,295.2,0,0,1,2.19,265.42Z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <div style="margin-top: 6px;color: red;font-size: 10px">{{ $message }}</div>
                    @enderror

                    <div class="font-medium text-xs text-right text-muted">

                        <span class="text-right transition-colors">
                            <div class="password-requirements">
                                <div class="requirement-item">
                                    <span class="circle {{ $passwordStrength['length'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    حداقل 8 کاراکتر
                                </div>
                                <div class="requirement-item">
                                    <span
                                        class="circle {{ $passwordStrength['number'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    حداقل یک عدد
                                </div>
                                <div class="requirement-item">
                                    <span
                                        class="circle {{ $passwordStrength['case'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    حداقل یک حرف بزرگ یا کوچک
                                </div>

                            </div>
                        </span>

                    </div>
                    <!-- end form:field:wrapper -->

                    <!-- form:submit button -->
                    <button type="submit"
                            class="flex items-center justify-center gap-1 w-full h-10 bg-primary rounded-full
                            text-primary-foreground transition-all hover:opacity-80 px-4 mt-20"
                            wire:loading.attr="disabled"
                            wire:target="sendOtp,verifyOtp,register">
                        <span class="font-semibold text-sm" wire:loading wire:target="sendOtp,verifyOtp,register">درحال ثبت نام...</span>
                        <span class="font-semibold text-sm" wire:loading.remove wire:target="sendOtp,verifyOtp,register">ثبت</span>
                    </button>
                    <hr class="border-dashed">
                    <!-- end form:submit button -->

                </form>
            @endif

        </div>
        <div class="bg-secondary rounded-xl space-y-5 p-5">
            <div class="font-medium text-xs text-center text-muted">
                ورود شما به معنای پذیرش <a href="{{route('client.terms')}}"
                                           class="text-foreground transition-colors hover:text-primary hover:underline">شرایط</a>
                و
                <a href="{{route('client.terms')}}"
                   class="text-foreground transition-colors hover:text-primary hover:underline">قوانین
                    حریم خصوصی</a> است.
            </div>
        </div>
    </div>
    @push('script')
        <script>
            document.addEventListener('livewire:init', () => {
                let countdown = {{ $countdown }};
                const countdownElement = document.getElementById('countdown');
                const countdownText = document.getElementById('countdown-text');
                const resendButton = document.getElementById('resend-otp');

                console.log('Initial countdown:', countdown);

                function startTimer() {
                    if (countdown > 0 && countdownElement && countdownText && resendButton) {
                        countdownText.classList.remove('hidden');
                        resendButton.classList.add('hidden');
                        countdownElement.textContent = countdown;

                        const timer = setInterval(() => {
                            countdown--;
                            countdownElement.textContent = countdown;
                            console.log('Countdown:', countdown);

                            if (countdown <= 0) {
                                clearInterval(timer);
                                countdownText.classList.add('hidden');
                                resendButton.classList.remove('hidden');
                            }
                        }, 1000);
                    }
                }

                startTimer();

                // گوش دادن به رویداد ریست تایمر
                Livewire.on('reset-timer', (event) => {
                    countdown = event.countdown;
                    console.log('Reset countdown:', countdown);
                    startTimer();
                });
            });

            function togglePassword() {
                const input = document.getElementById("password");
                const eyeOpen = document.getElementById("eye-open");
                const eyeClosed = document.getElementById("eye-closed");

                if (input.type === "password") {
                    input.type = "text";
                    eyeOpen.style.display = "none";
                    eyeClosed.style.display = "inline";
                } else {
                    input.type = "password";
                    eyeOpen.style.display = "inline";
                    eyeClosed.style.display = "none";
                }
            }
        </script>
    @endpush
</div>
