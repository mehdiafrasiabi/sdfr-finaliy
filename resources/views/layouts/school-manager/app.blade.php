<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل مدیر مدرسه - SDFR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: Tahoma, sans-serif; background: #f3f4f6; }
        .dark body { background: #0f172a; color: #e2e8f0; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-slate-100">
    <nav class="bg-white dark:bg-slate-800 shadow px-4 py-3 mb-6 flex items-center justify-between flex-wrap gap-2">
        <div class="flex items-center gap-4">
            <strong class="text-base">پنل مدیر مدرسه</strong>
            <a href="{{ route('school-manager.dashboard') }}" class="text-sm text-slate-600 dark:text-slate-300 hover:text-blue-500">داشبورد</a>
            <a href="{{ route('school-manager.students') }}" class="text-sm text-slate-600 dark:text-slate-300 hover:text-blue-500">دانش‌آموزان</a>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs text-slate-500">{{ auth('school-manager')->user()?->name }}</span>
            <a href="{{ route('school-manager.logout') }}" class="text-xs text-rose-500 hover:underline">خروج</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 pb-10">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
