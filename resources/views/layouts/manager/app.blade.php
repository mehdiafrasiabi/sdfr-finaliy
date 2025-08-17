<!DOCTYPE html>
<html lang="fa" dir="rtl" data-theme="interactive" data-layout="vertical"
      data-layout-style="detached" data-sidebar="light" data-topbar="dark"
      data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
<head>
    {!! SEO::generate() !!}
    @include('layouts.manager.link')
</head>

<body>

<!-- Begin page -->
<div id="layout-wrapper">

    <livewire:manager.layout.header />

    <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
        <!-- modal content -->
    </div>

    <livewire:manager.layout.sidebar />

    <div class="vertical-overlay"></div>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                {{$slot}}
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>document.write(new Date().getFullYear())</script>
                        © مهدی-آبان.
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            طراحی و توسعه توسط
                            <a href="https://mehdiafrasibi.ir" target="_blank">مهدی افراسیابی</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

</div>

<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>

<div id="preloader">
    <div id="status">
        <div class="spinner-border text-primary avatar-sm" role="status">
            <span class="visually-hidden">در حال بارگذاری...</span>
        </div>
    </div>
</div>



@include('layouts.manager.script')

</body>
</html>
