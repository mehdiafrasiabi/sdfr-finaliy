@extends('errors::layout')


@section('code', '419')


@section('title', 'نشست منقضی شده')


@section('message', 'نشست شما منقضی شده است. لطفاً صفحه را رفرش کرده و دوباره تلاش کنید.')


@section('image', 'https://hostiran.net/assets/img/error/404.png')


@section('button-link', 'javascript:location.reload()')


@section('button-text', 'بارگذاری مجدد صفحه')



@section('extra-buttons')

    <div class="mt-4">

        <a href="/"

           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-full transition-all duration-300">

            <span>بازگشت به صفحه اصلی</span>

        </a>

    </div>

@endsection
