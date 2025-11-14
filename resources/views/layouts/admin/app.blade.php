<!DOCTYPE html>
<html dir="rtl">
<head>
    {!! SEO::generate() !!}
    @include('layouts.admin.link')

</head>
<body class="dark">
<div wire:offline class="text-white ">
    This device is currently offline.
</div>
<!-- Sidebar -->
<div
    class="sidebar-area bg-white dark:bg-[#0c1427] fixed overflow-hidden z-[7] top-0 h-screen transition-all rounded-r-md"
    id="sidebar-area">
    <livewire:admin.layout.sidebar/>

</div>
<!-- End Sidebar -->

<!-- Header -->
<div
    class="header-area bg-white dark:bg-[#0c1427] py-[13px] px-[20px] md:px-[25px] fixed top-0 z-[6] rounded-b-md transition-all"
    id="header-area">
    <livewire:admin.layout.header/>
</div>
<!-- End Header -->

<!-- Main Content -->
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
{{$slot}}



    <!-- Footer -->
    <div class="grow"></div>
    <footer class="bg-white dark:bg-[#0c1427] rounded-t-md px-[20px] md:px-[25px] py-[15px] md:py-[20px] text-center">
        <p>
            © <span class="text-purple-500">1404</span>
            <a href="https://mehdiafrasibi.ir/" target="_blank" class="text-primary-500 transition-all hover:underline"
            >مهدی آبان</a
            >
        </p>
    </footer>
</div>
<!-- End Main Content -->

<!-- Links Of JS File -->
@include('layouts.admin.script')
</body>
</html>
