<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-950 dark:to-slate-900 flex items-center justify-center p-4"
     @if($redirectTo) x-data x-init="setTimeout(() => window.location.href = @js($redirectTo), 1500)" @endif>
    <div class="max-w-md w-full rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl p-8 text-center">
        @if($success)
            <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-2">پرداخت موفق</h1>
            @if($refNumber)
                <p class="text-slate-600 dark:text-slate-300 text-sm">شماره پیگیری: <span class="font-mono">{{ $refNumber }}</span></p>
            @endif
            @if($redirectTo)
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-4">در حال انتقال...</p>
            @endif
        @else
            <div class="w-20 h-20 mx-auto rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-2">پرداخت ناموفق</h1>
            <p class="text-slate-600 dark:text-slate-300 text-sm">{{ $message ?? 'تراکنش انجام نشد.' }}</p>
            <a href="{{ route('client.welcome') }}" class="inline-block mt-6 px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold">بازگشت</a>
        @endif
    </div>
</div>
