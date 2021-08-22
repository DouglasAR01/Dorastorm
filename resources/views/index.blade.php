<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('initMeta.title')</title>
    <meta name="description" content="@lang('initMeta.description')">
    <meta property="og:url" content="{{config('app.url')}}">
    <meta property="og:title" content="@lang('initMeta.title')">
    <meta property="og:description" content="@lang('initMeta.description')">
    <meta name="keywords" content="@lang('initMeta.keywords')">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src=" {{ asset('js/app.js') }}" defer></script>

</head>

<body>
    <div id="app">
        <the-index></the-index>
    </div>
</body>

</html>
