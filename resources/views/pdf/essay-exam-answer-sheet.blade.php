<!DOCTYPE html>
@php
    use App\Helpers\PersianShaper;
      // مسیر فونت — باید با base_path('public_html') شروع شود
    $fontPath = base_path('public_html/manager/assets/fonts/dana/DanaFaNum-Regular.ttf');
    if (!file_exists($fontPath)) {
        $fontPath = null;
    }
    $logoPath = base_path('public_html/client/assets/images/logo.png');
    if (!file_exists($logoPath)) {
        $logoPath = null;
    }
    $shape = fn($s) => PersianShaper::shape((string) $s);
@endphp
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        @if(file_exists($fontPath))
        @font-face {
            font-family: 'PersianFont';
            src: url('file://{{ $fontPath }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @endif
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: @if(file_exists($fontPath)) 'PersianFont', @endif 'DejaVu Sans', sans-serif;
            font-size: 11px;
            direction: rtl;
            color: #111;
            padding: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .header .brand {
            font-size: 22px;
            font-weight: bold;
            color: #1d4ed8;
            letter-spacing: 2px;
        }
        .header .title {
            font-size: 14px;
            margin-top: 4px;
        }
        .meta {
            display: block;
            margin-bottom: 10px;
        }
        .meta table { width: 100%; border-collapse: collapse; }
        .meta td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            font-size: 11px;
        }
        .meta td.label { background: #f3f4f6; font-weight: bold; width: 18%; }

        .answers table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            table-layout: fixed;
        }
        .answers td {
            border: 1px solid #999;
            vertical-align: top;
            padding: 4px;
        }
        .col-num {
            width: 40px;
            text-align: center;
            background: #f3f4f6;
            font-weight: bold;
        }
        .col-score {
            width: 60px;
            text-align: center;
            background: #f3f4f6;
            font-weight: bold;
        }
        .col-score .lbl { font-size: 9px; color: #666; display:block; }
        .col-answer {
            background:
                repeating-linear-gradient(to bottom, transparent, transparent 22px, #ddd 23px);
        }
        .footer {
            position: fixed;
            bottom: 6px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="header">
    <div class="brand">SDFR</div>
    <div class="title">{{ $shape('پاسخ‌برگ آزمون تشریحی') }}</div>
</div>

<div class="meta">
    <table>
        <tr>
            <td class="label">{{ $shape('عنوان آزمون') }}</td>
            <td colspan="3">{{ $shape($examTitle) }}</td>
        </tr>
        <tr>
            <td class="label">{{ $shape('نام دانش‌آموز') }}</td>
            <td>{{ $studentName ? $shape($studentName) : '—' }}</td>
            <td class="label">{{ $shape('نمره کل') }}</td>
            <td>{{ number_format((float) $exam->total_score, 2) }}</td>
        </tr>
    </table>
</div>

<div class="answers">
    <table>
        <tr>
            <td class="col-num">{{ $shape('ردیف') }}</td>
            <td>{{ $shape('پاسخ') }}</td>
            <td class="col-score">{{ $shape('نمره') }}</td>
        </tr>
        @foreach($questions as $q)
            <tr>
                <td class="col-num">{{ $q->question_number }}</td>
                <td class="col-answer" style="height: {{ max(60, min(400, (int) $q->row_height)) }}px;">&nbsp;</td>
                <td class="col-score">
                    <span class="lbl">{{ $shape('از') }} {{ $q->score }}</span>
                </td>
            </tr>
        @endforeach
    </table>
</div>

<div class="footer">SDFR &mdash; {{ $shape('سامانه آزمون تشریحی') }}</div>
</body>
</html>
