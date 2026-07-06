<div class="space-y-4">
    <div class="relative">
        <label class="block text-xs font-semibold mb-2 text-muted">جنسیت</label>
        <div class="grid grid-cols-2 gap-3">

            {{-- دکمه پسر --}}
            <button type="button"
                    @click="if(gender !== 'male') { avatar = ''; } gender = 'male'; $dispatch('open-avatar')"
                    class="gender-opt" :class="gender === 'male' ? 'gender-opt--boy' : ''">
                <span class="gender-ava gender-ava--boy">
                    {{-- اگر آواتار انتخاب شده بود عکس آواتار را نشان می‌دهد، در غیر این صورت عکس پیش‌فرض --}}
                    <img :src="gender === 'male' && avatar ? avatar : '/client/assets/images/avatars/student-boy.png'" alt="پسر">
                </span>
                <span class="text-sm font-bold">پسر</span>
            </button>

            {{-- دکمه دختر --}}
            <button type="button"
                    @click="if(gender !== 'female') { avatar = ''; } gender = 'female'; $dispatch('open-avatar')"
                    class="gender-opt" :class="gender === 'female' ? 'gender-opt--girl' : ''">
                <span class="gender-ava gender-ava--girl">
                    <img :src="gender === 'female' && avatar ? avatar : '/client/assets/images/avatars/student-girl.png'" alt="دختر">
                </span>
                <span class="text-sm font-bold">دختر</span>
            </button>

        </div>
        @error('gender')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
        @error('avatar')<div class="text-xs text-rose-500 mt-1.5">لطفاً یک آواتار انتخاب کنید. (برای انتخاب مجدد، روی کارت جنسیت خود کلیک کنید)</div>@enderror
    </div>
</div>
