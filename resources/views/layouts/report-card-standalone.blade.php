<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">
<head>
    <meta name="color-scheme" content="dark">
    <style>
        :root { color-scheme: dark; }
    </style>
    @include('layouts.client.link')
    {!! SEO::generate() !!}
    <link rel="icon" type="image/svg+xml" href="/client/assets/images/favicon.svg">
    <link rel="shortcut icon" href="/client/assets/images/favicon.svg">
</head>

<body>
<div class="flex flex-col min-h-screen bg-background">
    <main class="flex-auto py-6">
        {{ $slot }}
    </main>
</div>
@include('layouts.client.script')
</body>
</html>
