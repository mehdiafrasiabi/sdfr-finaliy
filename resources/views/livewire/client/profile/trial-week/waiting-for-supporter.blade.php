<div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50 dark:from-slate-950 dark:to-slate-900 flex items-center justify-center p-4" wire:poll.30s>
    <div class="max-w-xl w-full rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl p-8 text-center">
        @if(session('paymentSuccess'))
            <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-4 py-3 mb-5">
                {{ session('paymentSuccess') }}
            </div>
        @endif

        <div class="w-20 h-20 mx-auto rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center mb-5">
            <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-3">
            در انتظار تخصیص پشتیبان
        </h1>

        @if($mode === 'enrollment')
            <p class="text-slate-600 dark:text-slate-300 leading-7">
                پرداخت شما با موفقیت ثبت شد. مدیر آموزش به‌زودی برای شما پشتیبان تخصیص می‌دهد. این صفحه به‌صورت خودکار به‌روزرسانی می‌شود.
            </p>
        @else
            <p class="text-slate-600 dark:text-slate-300 leading-7">
                درخواست هفته آزمایشی شما ثبت شد. مدیر به‌زودی پشتیبان شما را تعیین می‌کند. این صفحه به‌صورت خودکار به‌روزرسانی می‌شود.
            </p>
        @endif

        <a href="{{ route('client.logout') }}" class="inline-block mt-6 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">خروج</a>
    </div>
</div>
