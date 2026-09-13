{{--
    این فایل، پارشیالِ مشترکِ کیت UI است و مستقیم استفاده نمی‌شود (کامپوننت نیست).
    هر کدام از کامپوننت‌های components/ui/button.blade.php ،status-badge.blade.php،
    progress-bar.blade.php ،pagination.blade.php و modal.blade.php آن را با
    @once('sdfr-ui-kit-assets') یک‌بار (صرف‌نظر از تعداد نمونه‌های رندر شده در صفحه)
    اینکلود می‌کنند تا:

    ۱) تنظیمات رنگ/رادیوس Tailwind (که از همان متغیرهای CSS پروژه یعنی
       --background ،--foreground ،--primary ،--secondary ،--info ،--success،
       --warning ،--error ،--muted ،--border و --radius که در app.css تعریف
       شده‌اند می‌خواند) را به tailwind.config اضافه/تقویت کند؛ چون tailwind-3.4.17.js
       یک runtime/JIT است، اگر کلاسی مثل bg-warning یا text-info-foreground برای
       اولین‌بار در پروژه استفاده شود و هنوز داخل app.css (خروجی purge-شده) نباشد،
       همین اسکریپت تضمین می‌کند که رنگ درست (نه پالت پیش‌فرض Tailwind) تولید شود.
       darkMode روی 'class' تنظیم می‌شود که دقیقاً همان استراتژی‌ای‌ست که پروژه با
       toggle کردن کلاس dark روی <html>/<body> استفاده می‌کند.

    ۲) استایل خام (غیر Tailwind) لازم برای افکت «فشاری» دکمه‌ها و راه‌راه پیشرفتِ
       آپلود را تزریق کند.

    این پارشیال با @push('link') درون stack('link') قرار می‌گیرد که در انتهای
    <head> در layouts/client/link.blade.php رندر می‌شود؛ یعنی بعد از تگ اسکریپت
    tailwind-3.4.17.js اجرا می‌شود و چون خودِ کتابخانه (بعد از لود شدن) به تغییرِ
    later tailwind.config واکنش نشان می‌دهد، ترتیب درست است.
--}}
@push('link')
    <script>
        (function () {
            function applySdfrUiKitTailwindConfig() {
                if (!window.tailwind) return;

                var prevTheme = (window.tailwind.config && window.tailwind.config.theme) || {};
                var prevExtend = prevTheme.extend || {};

                window.tailwind.config = Object.assign({}, window.tailwind.config, {
                    darkMode: 'class',
                    theme: Object.assign({}, prevTheme, {
                        extend: Object.assign({}, prevExtend, {
                            colors: Object.assign({}, prevExtend.colors, {
                                background: 'hsl(var(--background) / <alpha-value>)',
                                foreground: 'hsl(var(--foreground) / <alpha-value>)',
                                border: 'hsl(var(--border) / <alpha-value>)',
                                muted: 'hsl(var(--muted) / <alpha-value>)',
                                primary: {
                                    DEFAULT: 'hsl(var(--primary) / <alpha-value>)',
                                    foreground: 'hsl(var(--primary-foreground) / <alpha-value>)'
                                },
                                secondary: {
                                    DEFAULT: 'hsl(var(--secondary) / <alpha-value>)',
                                    foreground: 'hsl(var(--secondary-foreground) / <alpha-value>)'
                                },
                                info: {
                                    DEFAULT: 'hsl(var(--info) / <alpha-value>)',
                                    foreground: 'hsl(var(--info-foreground) / <alpha-value>)'
                                },
                                success: {
                                    DEFAULT: 'hsl(var(--success) / <alpha-value>)',
                                    foreground: 'hsl(var(--success-foreground) / <alpha-value>)'
                                },
                                warning: {
                                    DEFAULT: 'hsl(var(--warning) / <alpha-value>)',
                                    foreground: 'hsl(var(--warning-foreground) / <alpha-value>)'
                                },
                                error: {
                                    DEFAULT: 'hsl(var(--error) / <alpha-value>)',
                                    foreground: 'hsl(var(--error-foreground) / <alpha-value>)'
                                }
                            }),
                            borderRadius: Object.assign({}, prevExtend.borderRadius, {
                                lg: 'var(--radius)',
                                xl: 'calc(var(--radius) + 4px)',
                                '2xl': 'calc(var(--radius) + 10px)'
                            })
                        })
                    })
                });
            }

            applySdfrUiKitTailwindConfig();
            document.addEventListener('DOMContentLoaded', applySdfrUiKitTailwindConfig);
        })();
    </script>
    <style>
        /* ═══════════════════════════════════════════════════════════════
           SDFR UI Kit — رفتار لمسیِ دکمه‌ها (Tactile Press)

           دسکتاپ (ماوسِ دقیق، hover واقعی دارد): با hover دکمه کمی به سمت
           کاربر «باز/جلو» می‌آید (بالا می‌رود + سایه‌اش عمیق‌تر می‌شود) و با
           برداشتنِ ماوس به حالت عادی برمی‌گردد؛ با کلیک هم کمی فشرده می‌شود.

           موبایل/تاچ (hover واقعی ندارد): فقط با لمس/نگه‌داشتن پایین می‌رود
           و سایه‌اش جمع می‌شود (انگار دکمه‌ی فیزیکی فشرده شده)، با برداشتنِ
           انگشت به حالت اول برمی‌گردد.
           ═══════════════════════════════════════════════════════════════ */
        .btn-press {
            position: relative;
            will-change: transform;
            transition: transform .18s cubic-bezier(.34, 1.56, .64, 1),
            box-shadow .18s cubic-bezier(.34, 1.56, .64, 1),
            background-color .15s ease, color .15s ease,
            border-color .15s ease, opacity .15s ease;
        }

        .btn-press[data-elevated="true"] {
            box-shadow: 0 3px 0 0 rgb(0 0 0 / .22), 0 4px 10px -2px rgb(0 0 0 / .18);
        }

        .dark .btn-press[data-elevated="true"] {
            box-shadow: 0 3px 0 0 rgb(0 0 0 / .5), 0 4px 12px -2px rgb(0 0 0 / .35);
        }

        .btn-press:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-press:disabled[data-elevated="true"] {
            box-shadow: 0 2px 0 0 rgb(0 0 0 / .15) !important;
        }

        /* ── دسکتاپ: hover = جلو/بالا بیاید، کلیک = کمی فشرده شود ── */
        @media (hover: hover) and (pointer: fine) {
            .btn-press:hover:not(:disabled) {
                transform: translateY(-2px);
            }

            .btn-press[data-elevated="true"]:hover:not(:disabled) {
                box-shadow: 0 5px 0 0 rgb(0 0 0 / .22), 0 10px 18px -4px rgb(0 0 0 / .28);
            }

            .dark .btn-press[data-elevated="true"]:hover:not(:disabled) {
                box-shadow: 0 5px 0 0 rgb(0 0 0 / .5), 0 10px 18px -4px rgb(0 0 0 / .4);
            }

            .btn-press:active:not(:disabled) {
                transform: translateY(1px);
            }

            .btn-press[data-elevated="true"]:active:not(:disabled) {
                box-shadow: 0 1px 0 0 rgb(0 0 0 / .2);
            }
        }

        /* ── موبایل/تاچ: فقط لمس = پایین برود و جمع شود ── */
        @media (hover: none), (pointer: coarse) {
            .btn-press[data-elevated="true"]:active:not(:disabled) {
                transform: translateY(3px);
                box-shadow: 0 0 0 0 transparent;
            }

            .btn-press:not([data-elevated="true"]):active:not(:disabled) {
                transform: scale(.96);
            }
        }

        /* ═══════ راه‌راهِ متحرکِ نوار پیشرفت (حالت در حال آپلود) ═══════ */
        @keyframes sdfr-progress-stripe {
            0% { background-position: 0 0; }
            100% { background-position: 28px 0; }
        }

        .sdfr-progress-stripe {
            background-image: linear-gradient(45deg, rgb(255 255 255 / .18) 25%, transparent 25%, transparent 50%, rgb(255 255 255 / .18) 50%, rgb(255 255 255 / .18) 75%, transparent 75%, transparent);
            background-size: 28px 28px;
            animation: sdfr-progress-stripe .7s linear infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .btn-press, .sdfr-progress-stripe {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
    <script>
        {{--
            قفلِ اسکرولِ پشتِ مودال‌ها — مشترک بین x-ui.modal و هر مودالِ دستی/اینلاینِ
            دیگری در پروژه (مثل مودال‌های session-list). چون ممکنه (به‌ندرت) بیش از
            یک مودال هم‌زمان باز باشه، به‌جای ست‌کردن مستقیمِ overflow، از یک شمارنده
            استفاده می‌کنیم: فقط وقتی شمارنده از ۰ به ۱ می‌رسه قفل می‌کنیم، و فقط وقتی
            به ۰ برمی‌گرده باز می‌کنیم — تا با بسته‌شدنِ یک مودال، اسکرولِ صفحه‌ای که
            یک مودالِ دیگه هنوز روش بازه اشتباهی باز نشه.
        --}}
        window.SdfrModalScrollLock = window.SdfrModalScrollLock || (function () {
            var count = 0;
            var savedOverflow = '';
            return {
                lock: function () {
                    if (count === 0) {
                        savedOverflow = document.documentElement.style.overflow;
                        document.documentElement.style.overflow = 'hidden';
                    }
                    count++;
                },
                unlock: function () {
                    count = Math.max(0, count - 1);
                    if (count === 0) {
                        document.documentElement.style.overflow = savedOverflow;
                    }
                }
            };
        })();
    </script>
@endpush
