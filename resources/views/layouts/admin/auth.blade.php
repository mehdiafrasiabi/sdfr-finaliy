<!DOCTYPE html>
<html dir="rtl">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Links Of CSS File -->
    <link rel="stylesheet" href="/admin/assets/css/remixicon.css" />
    <link rel="stylesheet" href="/admin/assets/css/apexcharts.css" />
    <link rel="stylesheet" href="/admin/assets/css/simplebar.css" />
    <link rel="stylesheet" href="/admin/assets/css/prism.css" />
    <link rel="stylesheet" href="/admin/assets/css/jsvectormap.min.css" />
    <link rel="stylesheet" href="/admin/assets/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="/admin/assets/css/quill.snow.css" />
    <link rel="stylesheet" href="/admin/assets/css/style.css" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/admin/assets/images/favicon.ico" />

    <!-- Title -->
    <title>پنل مدیریت</title>

    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&amp;display=swap"
        rel="stylesheet"
    />

    <!-- Material Icons -->
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"
    />
</head>
<body class="dark">
<button
    type="button"
    class="light-dark-toggle leading-none inline-block transition-all text-[#fe7a36] absolute top-[20px] md:top-[25px] ltr:right-[20px] rtl:left-[20px] ltr:md:right-[25px] rtl:md:left-[25px]"
    id="light-dark-toggle"
>
    <i class="material-symbols-outlined !text-[20px] md:!text-[22px]">light_mode</i>
</button>

{{$slot}}

<script src="/admin/assets/js/apexcharts.min.js"></script>
<script src="/admin/assets/js/fslightbox.js"></script>
<script src="/admin/assets/js/simplebar.min.js"></script>
<script src="/admin/assets/js/prism.js"></script>
<script src="/admin/assets/js/clipboard.min.js"></script>
<script src="/admin/assets/js/swiper-bundle.min.js"></script>
<script src="/admin/assets/js/fullcalendar.min.js"></script>
<script src="/admin/assets/js/jsvectormap.min.js"></script>
<script src="/admin/assets/js/world-merc.js"></script>
<script src="/admin/assets/js/quill.min.js"></script>
<script src="/admin/assets/js/custom.js"></script>

</body>
</html>
