@extends('errors::layout')


@section('code', '503')


@section('title', 'سرویس در دسترس نیست')


@section('message', 'سایت در حال تعمیر و نگهداری است. لطفاً چند دقیقه دیگر مراجعه کنید.')


@section('image', 'https://hostiran.net/assets/img/error/404.png')


@section('button-link', 'javascript:location.reload()')


@section('button-text', 'بارگذاری مجدد')



@section('extra-buttons')

    <div class="mt-4 space-y-4">

        <p class="text-sm text-gray-500 dark:text-gray-400">

            ما به زودی برمی‌گردیم! تیم فنی در حال بهبود سرویس است.

        </p>

        <div class="flex justify-center lg:justify-end gap-4 text-gray-600 dark:text-gray-400">

            <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>

            </svg>

            <span class="text-sm">در حال بهبود سرویس...</span>

        </div>

    </div>

@endsection
