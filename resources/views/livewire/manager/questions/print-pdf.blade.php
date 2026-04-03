<div>
    <!DOCTYPE html>

    <html lang="fa" dir="rtl">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>خروجی سوالات - {{ $subject->name }}</title>

        <style>

            /*@import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700&display=swap');*/


            * {

                box-sizing: border-box;

                margin: 0;

                padding: 0;

            }


            body {

                font-family: 'Vazirmatn', Tahoma, Arial, sans-serif;

                font-size: 14px;

                line-height: 1.8;

                direction: rtl;

                background: #fff;

                color: #333;

                padding: 20px;

            }


            .header {

                text-align: center;

                margin-bottom: 30px;

                padding-bottom: 20px;

                border-bottom: 2px solid #333;

            }


            .header h1 {

                font-size: 24px;

                margin-bottom: 10px;

            }


            .header .info {

                font-size: 14px;

                color: #666;

            }


            .header .info span {

                margin: 0 10px;

            }


            .question-container {

                margin-bottom: 30px;

                padding: 15px;

                border: 1px solid #ddd;

                border-radius: 8px;

                page-break-inside: avoid;

            }


            .question-header {

                display: flex;

                justify-content: space-between;

                align-items: center;

                margin-bottom: 15px;

                padding-bottom: 10px;

                border-bottom: 1px solid #eee;

            }


            .question-number {

                background: #333;

                color: #fff;

                padding: 5px 15px;

                border-radius: 20px;

                font-weight: bold;

            }


            .question-meta {

                font-size: 12px;

                color: #666;

            }


            .question-meta span {

                margin-right: 15px;

            }


            .question-body {

                margin-bottom: 20px;

            }


            .question-image {

                text-align: center;

                margin: 15px 0;

            }


            .question-image img {

                max-width: 100%;

                max-height: 300px;

                border-radius: 5px;

            }


            .options-list {

                margin: 15px 0;

            }


            .option-item {

                display: flex;

                align-items: flex-start;

                gap: 10px;

                padding: 8px 12px;

                margin-bottom: 8px;

                background: #f8f9fa;

                border-radius: 5px;

            }


            .option-item.correct {

                background: #d4edda;

                border: 1px solid #28a745;

            }


            .option-number {

                background: #6c757d;

                color: #fff;

                width: 25px;

                height: 25px;

                border-radius: 50%;

                display: flex;

                align-items: center;

                justify-content: center;

                font-size: 12px;

                flex-shrink: 0;

            }


            .option-item.correct .option-number {

                background: #28a745;

            }


            .option-content {

                flex: 1;

            }


            .option-content img {

                max-width: 100%;

                max-height: 80px;

            }


            .correct-answer {

                margin-top: 15px;

                padding: 10px;

                background: #d4edda;

                border: 1px solid #28a745;

                border-radius: 5px;

                color: #155724;

            }


            .correct-answer strong {

                margin-left: 10px;

            }


            .explanation {

                margin-top: 15px;

                padding: 15px;

                background: #e7f3ff;

                border: 1px solid #0066cc;

                border-radius: 5px;

            }


            .explanation-title {

                font-weight: bold;

                color: #0066cc;

                margin-bottom: 10px;

            }


            .explanation-image img {

                max-width: 100%;

                max-height: 250px;

                border-radius: 5px;

            }


            .print-actions {

                position: fixed;

                top: 20px;

                left: 20px;

                z-index: 1000;

            }


            .print-actions button {

                padding: 10px 20px;

                font-size: 14px;

                font-family: 'Vazirmatn', Tahoma;

                border: none;

                border-radius: 5px;

                cursor: pointer;

                margin-left: 10px;

            }


            .btn-print {

                background: #28a745;

                color: #fff;

            }


            .btn-close {

                background: #dc3545;

                color: #fff;

            }


            .no-questions {

                text-align: center;

                padding: 50px;

                color: #666;

            }


            .footer {

                margin-top: 40px;

                padding-top: 20px;

                border-top: 1px solid #ddd;

                text-align: center;

                font-size: 12px;

                color: #999;

            }


            @media print {

                .print-actions {

                    display: none !important;

                }


                body {

                    padding: 0;

                }


                .question-container {

                    page-break-inside: avoid;

                    border: 1px solid #ccc;

                }


                .header {

                    page-break-after: avoid;

                }

            }

        </style>

    </head>

    <body>

    <div class="print-actions">

        <button class="btn-print" onclick="window.print()">

            چاپ / ذخیره PDF

        </button>

        <button class="btn-close" onclick="window.close()">

            بستن

        </button>

    </div>


    <div class="header">

        <h1>بانک سوالات</h1>

        <div class="info">

            <span><strong>دوره:</strong> {{ $subject->grade->educationLevel->name ?? '-' }}</span>

            <span><strong>پایه:</strong> {{ $subject->grade->name ?? '-' }}</span>

            @if($subject->field)

                <span><strong>رشته:</strong> {{ $subject->field->name }}</span>

            @endif

            <span><strong>درس:</strong> {{ $subject->name }}</span>

            @if($chapter)

                <span><strong>فصل:</strong> {{ $chapter->name }}</span>

            @endif

            @if($topic)

                <span><strong>مبحث:</strong> {{ $topic->name }}</span>

            @endif

            @if($difficulty)

                <span><strong>سختی:</strong> {{ $difficulties[$difficulty] ?? $difficulty }}</span>

            @endif

        </div>

        <div class="info" style="margin-top: 10px;">

            <span><strong>تعداد سوالات:</strong> {{ $questions->count() }}</span>

            <span><strong>تاریخ:</strong> {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}</span>

        </div>

    </div>


    @if($questions->isEmpty())

        <div class="no-questions">

            <h3>سوالی با فیلترهای انتخاب شده یافت نشد.</h3>

        </div>

    @else

        @foreach($questions as $index => $question)

            <div class="question-container">

                <div class="question-header">

                    <span class="question-number">سوال {{ $index + 1 }}</span>

                    <div class="question-meta">

                        <span>کد: {{ $question->code }}</span>

                        <span>سختی: {{ $difficulties[$question->difficulty] ?? $question->difficulty }}</span>

                        @if($question->topic)

                            <span>مبحث: {{ $question->topic->name }}</span>

                        @endif

                    </div>

                </div>


                <div class="question-body">

                    @if($question->question_image)

                        <div class="question-image">

                            <img src="{{ $question->question_image_url }}" alt="تصویر سوال">

                        </div>

                    @elseif($question->content?->body)

                        <div class="question-text">

                            {!! $question->content->body !!}

                        </div>

                    @endif

                </div>


                <!-- Options -->

                @if($question->options->count() > 0)

                    <div class="options-list">

                        @foreach($question->options as $option)

                            <div class="option-item {{ ($withAnswers && $option->is_correct) ? 'correct' : '' }}">

                                <span class="option-number">{{ $option->option_number }}</span>

                                <div class="option-content">

                                    {!! $option->content !!}

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif



                <!-- Correct Answer (if enabled but no options shown) -->

                @if($withAnswers)

                    @if($question->options->count() == 0)

                        <div class="correct-answer">

                            <strong>پاسخ صحیح:</strong>

                            گزینه {{ $question->correct_option }}

                        </div>

                    @else

                        @php

                            $correctOption = $question->options->firstWhere('is_correct', true);

                        @endphp

                        @if($correctOption && !$withAnswers)

                            <div class="correct-answer">

                                <strong>پاسخ صحیح:</strong>

                                گزینه {{ $correctOption->option_number }}

                            </div>

                        @endif

                    @endif

                @endif



                <!-- Explanation (if enabled) -->

                @if($withExplanations)

                    @if($question->explanation_image_url)

                        <div class="explanation">

                            <div class="explanation-title">توضیح تشریحی:</div>

                            <div class="explanation-image">

                                <img src="{{ $question->explanation_image_url }}" alt="تصویر پاسخ تشریحی">

                            </div>

                        </div>

                    @elseif($question->content?->explanation)

                        <div class="explanation">

                            <div class="explanation-title">توضیح تشریحی:</div>

                            <div class="explanation-content">

                                {!! $question->content->explanation !!}

                            </div>

                        </div>

                    @endif

                @endif

            </div>

        @endforeach

    @endif


    <div class="footer">

        این سند توسط سیستم بانک سوالات تولید شده است.

    </div>

    </body>

    </html>
</div>
