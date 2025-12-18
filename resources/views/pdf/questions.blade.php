<!DOCTYPE html>

<html lang="fa" dir="rtl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>سوالات - {{ $subject->name ?? 'بدون درس' }}</title>

    <style>

        @font-face {

            font-family: 'Vazir';

            src: url('{{ public_path('fonts/Vazir.ttf') }}') format('truetype');

            font-weight: normal;

        }

        * {

            font-family: 'Vazir', sans-serif;

            box-sizing: border-box;

        }

        body {

            direction: rtl;

            text-align: right;

            font-size: 12px;

            line-height: 1.8;

            color: #333;

            margin: 0;

            padding: 15px;

        }

        .header {

            border: 2px solid #333;

            border-radius: 10px;

            padding: 15px;

            margin-bottom: 20px;

            background: #f8f9fa;

        }

        .header-title {

            text-align: center;

            font-size: 18px;

            font-weight: bold;

            margin-bottom: 10px;

            color: #333;

        }

        .header-logo {

            text-align: center;

            font-size: 24px;

            font-weight: bold;

            color: #0066cc;

            margin-bottom: 5px;

        }

        .header-info {

            display: flex;

            justify-content: space-between;

            font-size: 11px;

            color: #666;

        }

        .question-card {

            border: 1px solid #ddd;

            border-radius: 8px;

            margin-bottom: 15px;

            page-break-inside: avoid;

            background: #fff;

        }

        .question-header {

            background: #e9ecef;

            padding: 8px 12px;

            border-radius: 8px 8px 0 0;

            border-bottom: 1px solid #ddd;

        }

        .question-number {

            display: inline-block;

            background: #0066cc;

            color: #fff;

            width: 28px;

            height: 28px;

            line-height: 28px;

            text-align: center;

            border-radius: 50%;

            font-weight: bold;

            font-size: 11px;

        }

        .question-meta {

            display: inline-block;

            margin-right: 10px;

            font-size: 10px;

            color: #666;

        }

        .question-body {

            padding: 12px;

        }

        .question-image {

            max-width: 100%;

            height: auto;

            display: block;

            margin: 10px auto;

            border-radius: 4px;

        }

        .explanation-card {

            margin-top: 10px;

            padding: 10px;

            background: #fff3cd;

            border: 1px solid #ffc107;

            border-radius: 6px;

        }

        .explanation-title {

            font-weight: bold;

            color: #856404;

            margin-bottom: 5px;

        }

        .correct-answer {

            margin-top: 8px;

            padding: 8px;

            background: #d4edda;

            border: 1px solid #28a745;

            border-radius: 4px;

            color: #155724;

            font-weight: bold;

        }

        .page-break {

            page-break-after: always;

        }

        .answers-section {

            margin-top: 30px;

            border-top: 2px solid #333;

            padding-top: 20px;

        }

        .answers-title {

            text-align: center;

            font-size: 16px;

            font-weight: bold;

            margin-bottom: 15px;

            background: #333;

            color: #fff;

            padding: 10px;

            border-radius: 8px;

        }

        .footer {

            position: fixed;

            bottom: 10px;

            left: 0;

            right: 0;

            text-align: center;

            font-size: 10px;

            color: #999;

        }

        .difficulty-badge {

            display: inline-block;

            padding: 2px 8px;

            border-radius: 4px;

            font-size: 10px;

            font-weight: bold;

        }

        .difficulty-easy {
            background: #d4edda;
            color: #155724;
        }

        .difficulty-medium {
            background: #fff3cd;
            color: #856404;
        }

        .difficulty-hard {
            background: #f8d7da;
            color: #721c24;
        }

        .difficulty-special {
            background: #d1ecf1;
            color: #0c5460;
        }

    </style>

</head>

<body>

<!-- Header -->

<div class="header">

    <div class="header-logo">SDF</div>

    <div class="header-title">

        @if($subject)

            {{ $subject->name }}

            @if($subject->grade)

                - {{ $subject->grade->name }}

            @endif

            @if($subject->field)

                - {{ $subject->field->name }}

            @endif

        @endif

    </div>

    <div class="header-info">

        <span>تعداد سوالات: {{ $questions->count() }}</span>

        <span>تاریخ: {{ jdate(now())->format('Y/m/d') }}</span>

    </div>

</div>


<!-- Questions -->

@foreach($questions as $index => $question)

    <div class="question-card">

        <div class="question-header">

            <span class="question-number">{{ $index + 1 }}</span>

            <span class="question-meta">کد: {{ $question->code }}</span>

            <span class="difficulty-badge difficulty-{{ $question->difficulty }}">

                    @switch($question->difficulty)

                    @case('easy') آسان @break

                    @case('medium') متوسط @break

                    @case('hard') سخت @break

                    @case('special') ویژه @break

                @endswitch

                </span>

        </div>

        <div class="question-body">

            @if($question->content && $question->content->hasQuestionImage())

                <img
                    src="{{ public_path('questions/' . $question->content->folder_hash . '/' . $question->content->question_image) }}"

                    alt="سوال {{ $index + 1 }}"

                    class="question-image">

            @endif



            @if(!$explanationAtEnd)

                <!-- Show explanation under each question -->

                <div class="correct-answer">

                    پاسخ صحیح: گزینه {{ $question->correct_option }}

                </div>



                @if($question->content && $question->content->hasExplanationImage())

                    <div class="explanation-card">

                        <div class="explanation-title">پاسخ تشریحی:</div>

                        <img
                            src="{{ public_path('questions/' . $question->content->folder_hash . '/' . $question->content->explanation_image) }}"

                            alt="پاسخ تشریحی"

                            class="question-image">

                    </div>

                @endif

            @endif

        </div>

    </div>



    @if(($index + 1) % 10 == 0 && $index + 1 < $questions->count())

        <div class="page-break"></div>

    @endif

@endforeach



@if($explanationAtEnd)

    <!-- Answers Section at End -->

    <div class="page-break"></div>

    <div class="answers-section">

        <div class="answers-title">پاسخنامه تشریحی</div>


        @foreach($questions as $index => $question)

            <div class="question-card">

                <div class="question-header">

                    <span class="question-number">{{ $index + 1 }}</span>

                    <span class="question-meta">کد: {{ $question->code }}</span>

                </div>

                <div class="question-body">

                    <div class="correct-answer">

                        پاسخ صحیح: گزینه {{ $question->correct_option }}

                    </div>


                    @if($question->content && $question->content->hasExplanationImage())

                        <div class="explanation-card">

                            <div class="explanation-title">پاسخ تشریحی:</div>

                            <img
                                src="{{ public_path('questions/' . $question->content->folder_hash . '/' . $question->content->explanation_image) }}"

                                alt="پاسخ تشریحی"

                                class="question-image">

                        </div>

                    @endif

                </div>

            </div>



            @if(($index + 1) % 10 == 0 && $index + 1 < $questions->count())

                <div class="page-break"></div>

            @endif

        @endforeach

    </div>

@endif


<div class="footer">

    SDF - سامانه مدیریت آزمون

</div>

</body>

</html>
