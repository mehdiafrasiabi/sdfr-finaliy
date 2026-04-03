<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <style>
        /* fallback */
        @font-face {
            font-family: 'Material Symbols Outlined';
            font-style: normal;
            font-weight: 100 700;
            src: url(/admin/admin.woff2) format('woff2');
        }

        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
    </style>
    {!! SEO::generate() !!}
    @include('layouts.admin.link')
    <link rel="stylesheet" href="/admin/assets/css/bootstrap-icons.min.css">
    <link href="/admin/assets/css/select2.min.css" rel="stylesheet">
    <link href="/admin/assets/css/jalalidatepicker.min.css" rel="stylesheet">

</head>
<body class="dark">
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
            </div>
        </div>
    </footer>
    <!-- end::NexLink Footer -->
</div>
<script src="/admin/assets/libs/global/global.min.js"></script>
<script src="/admin/assets/js/appSettings.js"></script>
<script src="/admin/assets/js/main.js"></script>
<script src="/admin/assets/js/jquery-3.6.0.min.js"></script>
<script src="/admin/assets/libs/apexcharts/apexcharts.min.js">
</script>
<script src="/admin/assets/libs/datatables/datatables.min.js">
</script>
<script src="/admin/assets/js/persian-date.min.js"></script>
<script src="/admin/assets/js/persian-datepicker-2.min.js"></script>

<script src="/admin/assets/js/dashboard/dashboard.js"></script>
<script src="/admin/assets/js/plugins/todolist.js"></script>
<script src="/admin/assets/js/select2.min.js"></script>
<script src="/admin/assets/js/jalalidatepicker.min.js"></script>

@include('layouts.admin.script')
</body>
</html>
