<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    {!! SEO::generate() !!}
    @include('layouts.admin.link')
    <link rel="stylesheet" href="/admin/assets/css/bootstrap-icons.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <link href="/admin/assets/css/select2.min.css" rel="stylesheet">
</head>
<body>
<div class="page-layout">
    <!-- begin::NexLink Page Header -->
    <livewire:admin.layout.menu/>
    <!-- end::NexLink Page Header -->

    <!-- begin::NexLink Sidebar Menu -->
    <livewire:admin.layout.navbar/>
    <!-- end::NexLink Sidebar Menu -->

    <main class="app-wrapper">
        <div class="container-fluid">
            {{$slot}}
        </div>
    </main>
    <!-- begin::NexLink Footer -->
    <footer class="footer-wrapper bg-body">
        <div class="container-fluid">
            <div class="row g-2">
                <div class="col-lg-6 col-md-7 text-center text-md-start">
                    <p class="mb-0">
                        &copy;
                        <span class="">
		</span>
                        تمامی حقوق محفوظ است. با افتخار طراحی شده توسط
                        <a href="https://sdfr.me">
                            SDFR
                        </a>
                        .
                    </p>
                </div>
                <div class="col-lg-6 col-md-5">
                    <ul class="d-flex list-inline mb-0 gap-3 flex-wrap justify-content-center justify-content-md-end">
                        <li>
                            <a class="text-body" href="#">
                                صفحه اصلی
                            </a>
                        </li>
                        <li>
                            <a class="text-body" href="#">
                                سوالات متداول
                            </a>
                        </li>
                        <li>
                            <a class="text-body" href="#">
                                پشتیبانی
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- end::NexLink Footer -->
</div>
<script src="/admin/assets/libs/global/global.min.js"></script>
<script src="/admin/assets/js/appSettings.js"></script>
<script src="/admin/assets/js/main.js"></script>
<script src="/admin/js/jquery-3.6.0.min.js"></script>
<script src="/admin/assets/libs/apexcharts/apexcharts.min.js">
</script>
<script src="/admin/assets/libs/datatables/datatables.min.js">
</script>
<script src="/admin/js/persian-date.min.js"></script>
<script src="/admin/js/persian-datepicker-2.min.js"></script>

<script src="/admin/assets/js/dashboard/dashboard.js"></script>
<script src="/admin/assets/js/plugins/todolist.js"></script>
<script src="/admin/assets/js/select2.min.js"></script>
@include('layouts.admin.script')
</body>
</html>
