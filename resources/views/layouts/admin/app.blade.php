<!DOCTYPE html>
<html class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
      data-assets-path="/admin/assets/" data-template="vertical-menu-template" data-theme="theme-default" dir="rtl"
      lang="fa">
<head>
    {!! SEO::generate() !!}
    @include('layouts.admin.link')

</head>
<body>
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <livewire:admin.layout.menu/>
        <!-- / Menu -->
        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            <livewire:admin.layout.navbar/>
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        {{$slot}}
                    </div>
                </div>
                <!-- / Content -->

            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>
</div>

<!-- Links Of JS File -->
@include('layouts.admin.script')
</body>
</html>
