<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود مدیر مدرسه - SDFR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: linear-gradient(135deg, #1e293b, #0f172a); min-height: 100vh; font-family: Tahoma, sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen text-white">
    {{ $slot }}
    @livewireScripts
</body>
</html>
