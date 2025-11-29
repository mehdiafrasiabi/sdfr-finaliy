<script src="/admin/assets/vendor/libs/jquery/jquery.js"></script>
<script src="/admin/assets/vendor/libs/popper/popper.js"></script>
<script src="/admin/assets/vendor/js/bootstrap.js"></script>
<script src="/admin/assets/vendor/libs/node-waves/node-waves.js"></script>
<script src="/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="/admin/assets/vendor/libs/hammer/hammer.js"></script>
<script src="/admin/assets/vendor/libs/i18n/i18n.js"></script>
<script src="/admin/assets/vendor/libs/typeahead-js/typeahead.js"></script>
<script src="/admin/assets/vendor/js/menu.js"></script>
<!-- endbuild -->
<!-- Vendors JS -->
<script src="/admin/assets/vendor/libs/apex-charts/apexcharts.js"></script>
<script src="/admin/assets/vendor/libs/swiper/swiper.js"></script>
<script src="/admin/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="/admin/assets/vendor/libs/datatables-bs5/i18n/fa.js"></script>
<!-- Main JS -->
<script src="/admin/assets/js/main.js"></script>
<!-- Page JS -->
<script src="/admin/assets/js/dashboards-analytics.js"></script>
<script src="/admin/assets/js/tables-datatables-basic.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const target = document.documentElement;

    const observer = new MutationObserver(() => {
        if (target.classList.contains("sf-js-enabled")) {
            target.classList.remove("sf-js-enabled");
        }
    });

    observer.observe(target, { attributes: true, attributeFilter: ["class"] });

</script>


@include('layouts.admin.theme-toggle-script')

<script>

    ecommerceList = $('#ecommerce-list').DataTable({
        headerCallback:function(e, a, t, n, s) {
            e.getElementsByTagName("th")[0].innerHTML=`
                <div class="form-check form-check-primary d-block new-control">
                    <input class="form-check-input chk-parent" type="checkbox" id="form-check-default">
                </div>`
        },
        columnDefs:[ {
            targets:0, width:"30px", className:"", orderable:!1, render:function(e, a, t, n) {
                return `
                    <div class="form-check form-check-primary d-block new-control">
                        <input class="form-check-input child-chk" type="checkbox" id="form-check-default">
                    </div>`
            }
        }],
        "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [7, 10, 20, 50],
        "pageLength": 10
    });
    multiCheck(ecommerceList);
</script>

<script>
    window.addEventListener('success', function(event) {
        Swal.fire({
            theme: 'auto',
            position: 'center',
            icon: 'success',
            title: event.detail,
            showConfirmButton: false,
            timer: 1500
        })
    });
    window.addEventListener('warning', function(event) {
        Swal.fire({
            theme: 'auto',
            position: 'center',
            icon: 'error',
            title: event.detail,
            showConfirmButton: false,
            timer: 5000,
        })
    });
</script>
@stack('script')
