@extends('errors::layout')



@section('code', '429')



@section('title', 'درخواست‌های زیاد')



@section('message', 'شما درخواست‌های زیادی ارسال کرده‌اید. لطفاً چند لحظه صبر کنید و دوباره تلاش کنید.')



@section('image', 'https://hostiran.net/assets/img/error/404.png')



@section('button-link', 'javascript:history.back()')



@section('button-text', 'بازگشت به صفحه قبل')



@section('extra-buttons')

    <div class="mt-4">

        <p class="text-sm text-gray-500 dark:text-gray-400">

            لطفاً چند دقیقه صبر کنید و سپس دوباره تلاش کنید.

        </p>

    </div>

@endsection
