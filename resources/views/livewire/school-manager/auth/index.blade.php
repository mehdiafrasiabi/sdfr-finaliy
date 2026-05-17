<div class="w-full max-w-md p-6 bg-slate-800 rounded-2xl shadow-xl">
    <h1 class="text-xl font-bold text-center mb-1">پنل مدیر مدرسه</h1>
    <p class="text-center text-slate-400 text-sm mb-6">SDFR - School Manager</p>

    @if(session('messageSuccess'))
        <div class="mb-3 px-3 py-2 rounded-lg bg-emerald-500/20 text-emerald-300 text-sm">
            {{ session('messageSuccess') }}
        </div>
    @endif

    <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
        <div class="mb-4">
            <label class="block text-xs text-slate-300 mb-1">شماره موبایل مدیر</label>
            <input name="mobile" type="text"
                   class="w-full px-3 py-2 rounded-lg bg-slate-700 text-white outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="09xxxxxxxxx">
            @error('mobile') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-xs text-slate-300 mb-1">رمز عبور</label>
            <input name="password" type="password"
                   class="w-full px-3 py-2 rounded-lg bg-slate-700 text-white outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="SDFR<کد مدرسه>">
            @error('password') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
        </div>
        <button class="w-full py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold transition">
            <span wire:loading.remove>ورود</span>
            <span wire:loading>در حال ورود...</span>
        </button>
    </form>

    <p class="text-xs text-slate-500 text-center mt-4">
        نام کاربری: تلفن مدیر | رمز: SDFR + کد عضویت مدرسه
    </p>
</div>
