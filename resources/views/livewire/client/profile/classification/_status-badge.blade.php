{{--
    این پارشیال دیگه خودش رنگ/آیکون رو تعیین نمی‌کنه؛ فقط mode/submitted رو به
    یکی از وضعیت‌های استانداردِ x-ui.status-badge (کامپوننتِ مشترکِ نشانِ وضعیت
    در کل پروژه) map می‌کنه، تا badge اینجا هم دقیقاً هم‌شکلِ بقیه‌ی جاهایی باشه
    که از x-ui.status-badge استفاده می‌کنن.
--}}
@php
    if ($submitted) {
        $statusKey = 'paid'; // سبز + تیک، برای «ارسال شده»
        $labelOverride = 'ارسال شده';
    } else {
        $statusKey = match ($mode) {
            'trial'    => 'trial',
            'active'   => 'active',
            'upcoming' => 'pending',
            'ended'    => 'inactive',
            default    => 'inactive',
        };
        $labelOverride = match ($mode) {
            'upcoming' => 'در انتظار',
            'ended'    => 'تمام شده',
            default    => null,
        };
    }
@endphp
<x-ui.status-badge :status="$statusKey" :label="$labelOverride" class="flex-shrink-0"/>
