@php
    use App\Helpers\PersianShaper;
    use App\Models\ContactDocumentation;
    use Illuminate\Support\Str;
    $total        = $records->count();
    $successful   = $records->where('contact_status', 'successful')->count();
    $unsuccessful = $records->where('contact_status', 'unsuccessful')->count();
@endphp
    <!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ PersianShaper::shape('مستندات تماس') }}</title>
    <style>
        @if(!empty($fontPath))
        @@font-face {
            font-family: 'PersianFont';
            src: url('file://{{ $fontPath }}') format('truetype');
        }
        @endif

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: @if(!empty($fontPath)) 'PersianFont', @endif 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            direction: rtl;
            color: #1e293b;
            padding: 20px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 3px solid #1d4ed8;
            padding-bottom: 12px;
        }
        .header h1 { font-size: 17px; color: #1d4ed8; margin-bottom: 5px; }
        .header p  { margin: 2px 0; color: #64748b; font-size: 10px; }

        table { border-collapse: collapse; }

        .stats-table { width: auto; margin: 0 auto 14px; }
        .stats-table td { border: none; padding: 0 8px; }
        .stat-box {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 7px 18px;
            background: #f8fafc;
            text-align: center;
            display: block;
        }
        .stat-num { font-size: 18px; font-weight: bold; color: #1d4ed8; }
        .stat-lbl { font-size: 9px; color: #64748b; }
        .stat-success { background: #dcfce7; border-color: #bbf7d0; }
        .stat-success .stat-num { color: #15803d; }
        .stat-danger  { background: #fee2e2; border-color: #fecaca; }
        .stat-danger  .stat-num { color: #dc2626; }

        .main-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .main-table thead th {
            background-color: #1d4ed8;
            color: #ffffff;
            padding: 7px 5px;
            text-align: center;
            font-size: 10px;
            border: 1px solid #1e40af;
            white-space: nowrap;
        }
        .main-table tbody tr:nth-child(even) { background-color: #f1f5f9; }
        .main-table tbody td {
            padding: 6px 5px;
            border: 1px solid #e2e8f0;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
        }
        .desc-cell { text-align: right; font-size: 9.5px; color: #475569; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger  { background: #fee2e2; color: #dc2626; }

        .footer {
            margin-top: 16px;
            text-align: center;
            color: #94a3b8;
            font-size: 9px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

{{-- ── هدر ──────────────────────────────────────────────────────── --}}
<div class="header">
    <h1>{{ PersianShaper::shape('گزارش مستندات تماس') }}</h1>
    <p>{{ PersianShaper::shape('مشاور / پشتیبان') }}: <strong>{{ $adminName }}</strong></p>
    <p>{{ PersianShaper::shape('تاریخ تهیه گزارش') }}: {{ $date }}</p>
</div>

{{-- ── آمار ─────────────────────────────────────────────────────── --}}
<table class="stats-table" align="center">
    <tr>
        <td>
            <span class="stat-box">
                <div class="stat-num">{{ $total }}</div>
                <div class="stat-lbl">{{ PersianShaper::shape('کل تماس‌ها') }}</div>
            </span>
        </td>
        <td>
            <span class="stat-box stat-success">
                <div class="stat-num">{{ $successful }}</div>
                <div class="stat-lbl">{{ PersianShaper::shape('موفق') }}</div>
            </span>
        </td>
        <td>
            <span class="stat-box stat-danger">
                <div class="stat-num">{{ $unsuccessful }}</div>
                <div class="stat-lbl">{{ PersianShaper::shape('ناموفق') }}</div>
            </span>
        </td>
    </tr>
</table>

{{-- ── جدول ─────────────────────────────────────────────────────── --}}
<table class="main-table">
    <thead>
    <tr>
        <th style="width:24px;">#</th>
        <th>{{ PersianShaper::shape('نام دانش‌آموز') }}</th>
        <th>{{ PersianShaper::shape('عنوان') }}</th>
        <th>{{ PersianShaper::shape('توضیحات') }}</th>
        <th style="width:58px;">{{ PersianShaper::shape('وضعیت') }}</th>
        <th style="width:70px;">{{ PersianShaper::shape('تاریخ تماس') }}</th>
        <th style="width:50px;">{{ PersianShaper::shape('پاسخگو') }}</th>
    </tr>
    </thead>
    <tbody>
    @forelse($records as $i => $rec)
        @php
            $studentName     = $rec->student?->user?->personalInformation?->name
                            ?? $rec->student?->user?->name
                            ?? 'نامشخص';
            $respondentLabel = ContactDocumentation::RESPONDENT[$rec->respondent] ?? $rec->respondent;
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ PersianShaper::shape($studentName) }}</strong></td>
            <td>{{ PersianShaper::shape($rec->title) }}</td>
            <td class="desc-cell">
                {{ $rec->description
                    ? PersianShaper::shape(Str::limit($rec->description, 60))
                    : '-' }}
            </td>
            <td>
                @if($rec->contact_status === 'successful')
                    <span class="badge badge-success">{{ PersianShaper::shape('موفق') }}</span>
                @else
                    <span class="badge badge-danger">{{ PersianShaper::shape('ناموفق') }}</span>
                @endif
            </td>
            <td>{{ jdate($rec->contact_date)->format('Y/m/d') }}</td>
            <td>{{ PersianShaper::shape($respondentLabel) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align:center;color:#94a3b8;padding:20px;">
                {{ PersianShaper::shape('هیچ مستند تماسی یافت نشد') }}
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    {{ PersianShaper::shape('این گزارش توسط سیستم مدیریت آموزشی تولید شده است') }}
    &mdash; {{ $date }}
</div>

</body>
</html>
