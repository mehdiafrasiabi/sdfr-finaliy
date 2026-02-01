


<!-- end::NexLink Page Scripts -->
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
