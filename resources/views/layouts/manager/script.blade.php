<!-- JAVASCRIPT -->
<script src="/manager/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/manager/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/manager/assets/libs/node-waves/waves.min.js"></script>
<script src="/manager/assets/libs/feather-icons/feather.min.js"></script>
<script src="/manager/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="/manager/assets/js/plugins.js"></script>

<!-- apexcharts -->
<script src="/manager/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Swiper Js -->
<script src="/manager/assets/libs/swiper/swiper-bundle.min.js"></script>

<!-- CRM js -->
<script src="/manager/assets/js/pages/dashboard-crypto.init.js"></script>

<!-- App js -->
<script src="/manager/assets/js/app.js"></script>

<!-- Sweet Alerts js -->
<script src="/manager/assets/libs/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="/manager/assets/js/pages/sweetalerts.init.js"></script>

<script>
    window.addEventListener('success', function(event) {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: event.detail,
            showConfirmButton: false,
            timer: 1500
        })
    });
    window.addEventListener('error', function(event) {
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: event.detail,
            showConfirmButton: false,
            timer: 3500,
        })
    });
    window.addEventListener('warning', function(event) {
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: event.detail,
            showConfirmButton: false,
            timer: 5000,
        })
    });
</script>
@stack('script')
