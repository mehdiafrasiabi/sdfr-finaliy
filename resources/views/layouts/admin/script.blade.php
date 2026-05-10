


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

<script type="text/javascript" src="/admin/assets/css/toast/toastify-js.js"></script>

<script>
    /**
     * Toast System - سیستم مرکزی نمایش پیام‌های Toast
     * سازگار با dispatch های قدیمی و جدید
     */

// تنظیمات پیش‌فرض Toast
    const TOAST_CONFIG = {
        duration: 500,
        gravity: "top",
        position: "center",
        stopOnFocus: true,
        offset: {
            x: 0,
            y: 20
        },
        style: {
            borderRadius: "12px",
            padding: "16px 24px",
            fontSize: "14px",
            fontWeight: "500",
            boxShadow: "0 10px 40px rgba(0, 0, 0, 0.2)",
            direction: "rtl",
            textAlign: "right"
        }
    };

    // تم‌های مختلف Toast
    const TOAST_THEMES = {
        success: {
            background: "linear-gradient(135deg, #10b981, #059669)",
            color: "#ffffff",
            icon: `<svg style="width: 20px; height: 20px; margin-left: 8px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>`
        },
        error: {
            background: "linear-gradient(135deg, #ef4444, #dc2626)",
            color: "#ffffff",
            icon: `<svg style="width: 20px; height: 20px; margin-left: 8px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>`
        },
        warning: {
            background: "linear-gradient(135deg, #f59e0b, #d97706)",
            color: "#ffffff",
            icon: `<svg style="width: 20px; height: 20px; margin-left: 8px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>`
        },
        info: {
            background: "linear-gradient(135deg, #3b82f6, #2563eb)",
            color: "#ffffff",
            icon: `<svg style="width: 20px; height: 20px; margin-left: 8px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>`
        }
    };

    /**
     * نمایش Toast با تنظیمات سفارشی
     */
    function showToast(message, type = 'success', duration = 3000) {
        const theme = TOAST_THEMES[type] || TOAST_THEMES.success;

        const config = {
            ...TOAST_CONFIG,
            text: theme.icon + message,
            duration: duration,
            className: `toast-${type}`,
            style: {
                ...TOAST_CONFIG.style,
                background: theme.background,
                color: theme.color
            },
            escapeMarkup: false
        };

        Toastify(config).showToast();
    }

    /**
     * Event Listeners برای سازگاری با dispatch های قدیمی
     */

    // Success Toast - سبز
    window.addEventListener('success', function (event) {
        const message = typeof event.detail === 'string' ? event.detail : event.detail[0];
        showToast(message, 'success', 3000);
    });

    // Warning/Error Toast - قرمز
    window.addEventListener('warning', function (event) {
        const message = typeof event.detail === 'string' ? event.detail : event.detail[0];
        showToast(message, 'error', 3000);
    });

    // Error Toast (برای dispatch('error', 'message'))
    window.addEventListener('error', function (event) {
        const message = typeof event.detail === 'string' ? event.detail : event.detail[0];
        showToast(message, 'error', 3000);
    });

    // Info Toast
    window.addEventListener('info', function (event) {
        const message = typeof event.detail === 'string' ? event.detail : event.detail[0];
        showToast(message, 'info', 3000);
    });

    // Add to Cart Toast
    window.addEventListener('add-to-cart', function (event) {
        const message = event.detail
            ? (typeof event.detail === 'string' ? event.detail : event.detail[0])
            : 'با موفقیت به سبد خرید شما اضافه شد';
        showToast(message, 'success', 4000);
    });

    /**
     * Event Listener برای Toast های جدید با فرمت Object
     * مثال: dispatch('show-toast', { type: 'success', message: 'پیام' })
     */
    window.addEventListener('show-toast', function (event) {
        const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
        showToast(data.message, data.type || 'success', data.duration || 3000);
    });

    /**
     * Livewire Hook برای نمایش Toast بعد از بارگذاری صفحه
     */
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Livewire !== 'undefined') {
            Livewire.on('toast', (data) => {
                const params = Array.isArray(data) ? data[0] : data;
                showToast(params.message, params.type || 'success', params.duration || 3000);
            });
        }
    });

    /**
     * تابع سراسری برای استفاده مستقیم در JavaScript
     * مثال: window.toast('پیام موفقیت', 'success')
     */
    window.toast = showToast;
</script>
@stack('script')
