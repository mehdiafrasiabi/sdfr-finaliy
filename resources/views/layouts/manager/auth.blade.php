<!DOCTYPE html>
<html lang="fa" dir="rtl" data-theme="interactive" data-layout="vertical" data-layout-style="detached" data-sidebar="light" data-topbar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8">
    <title>ورود مدیران</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
    <meta content="Mordad" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="/manager/assets/images/favicon.ico">

    <!-- Layout config Js -->
    <script src="/manager/assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="/manager/assets/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="/manager/assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="/manager/assets/css/app-rtl.min.css" rel="stylesheet" type="text/css">
    <!-- custom Css-->
    <link href="/manager/assets/css/custom-rtl.min.css" rel="stylesheet" type="text/css">


</head>

<body>

<div class="auth-page-wrapper pt-5">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>

        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewbox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- auth page content -->
    {{$slot}}
    <!-- end auth page content -->


</div>
<!-- end auth-page-wrapper -->

<!-- JAVASCRIPT -->
<script src="/manager/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/manager/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/manager/assets/libs/node-waves/waves.min.js"></script>
<script src="/manager/assets/libs/feather-icons/feather.min.js"></script>
<script src="/manager/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="/manager/assets/js/plugins.js"></script>

<!-- particles js -->
<script src="/manager/assets/libs/particles.js/particles.js"></script>
<!-- particles app js -->
<script src="/manager/assets/js/pages/particles.app.js"></script>
<!-- password-addon init -->
<script src="/manager/assets/js/pages/password-addon.init.js"></script>
</body>

</html>
