<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">

<head>
    @include('layouts.client.link')
    {!! SEO::generate() !!}
</head>

<body class="dark">

<div class="flex flex-col min-h-screen bg-background ">

{{--    <div id="loadingOverlay">--}}
{{--        <img src="/client/loading.png" alt="SDFR Loading Logo" class="loading-logo rounded-xl">--}}
{{--    </div>--}}

    <!-- header -->
    <livewire:client.layout.header  />
    <!-- end header -->

    <main class="flex-auto py-5">
        {{$slot}}
    </main>

    <!-- footer -->
    <livewire:client.layout.footer/>
    <!-- end footer -->
</div>

@include('layouts.client.script')
</body>

</html>
