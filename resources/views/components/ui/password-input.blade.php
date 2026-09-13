@props([
    'model'       => null,     // نام wire:model (رشته) - مثلا new_password
    'label'       => 'رمز عبور',
    'placeholder' => '********',
    'live'        => false,    // اگر true بشه به‌جای wire:model از wire:model.live استفاده می‌شه
])
{{--
    فیلد رمز عبور با دکمه‌ی نمایش/مخفی‌کردن (چشم). چون این ورودی‌ها همیشه dir="ltr" هستن
    (رمز با حروف/ارقام انگلیسی تایپ می‌شه)، دکمه‌ی چشم هم روی همون سمتِ منطقیِ «انتهای» یک
    فیلد LTR (یعنی سمت راستِ فیزیکی) قرار می‌گیره - مستقل از راست‌چین‌بودنِ کلِ صفحه.
--}}
<div class="space-y-2" x-data="{ showPassword: false }">
    <label class="font-semibold text-xs text-foreground">{{ $label }}</label>
    <div class="relative">
        <input
            :type="showPassword ? 'text' : 'password'"
            dir="ltr"
            {{ $live ? 'wire:model.live' : 'wire:model' }}="{{ $model }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->class([
                'w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground pl-4 pr-11 transition-all outline-none',
            ]) }}
        >
        <button type="button"
                @click="showPassword = !showPassword"
                tabindex="-1"
                :aria-label="showPassword ? 'مخفی کردن رمز عبور' : 'نمایش رمز عبور'"
                class="absolute inset-y-0 right-0 flex items-center px-3 text-muted hover:text-foreground transition-colors">
            {{-- eye (نمایش رمز غیرفعاله - یعنی الان مخفیه) --}}
            <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"/>
            </svg>
            {{-- eye-slash (رمز الان نمایش داده می‌شه) --}}
            <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
            </svg>
        </button>
    </div>
    @error($model)<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
</div>
