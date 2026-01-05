@extends('errors::layout')


@section('code', '500')


@section('title', 'خطای داخلی سرور')


@section('message', 'متأسفانه مشکلی در سرور پیش آمده است. تیم فنی ما در حال بررسی موضوع هستند.')


@section('image', 'https://hostiran.net/assets/img/error/404.png')


@section('button-link', '/')


@section('button-text', 'بازگشت به صفحه اصلی')



@section('extra-buttons')

    <div class="mt-4">

        <button onclick="location.reload()"

                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-full transition-all duration-300">

            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>

            </svg>

            <span>تلاش مجدد</span>

        </button>

    </div>

@endsection
