<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('meta.title')</title>
    <meta name="description" content="@lang('meta.description')">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta name="robots" content="index, follow">

    {{-- Your facebook domain verification --}}
    <meta name="facebook-domain-verification" content="">

    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="@lang('meta.title')">
    <meta property="og:description" content="@lang('meta.description')">
    <meta property="og:type" content="website">
    {{-- URL or asset of the image --}}
    <meta property="og:image" content="">

    <meta name="twitter:card" content="summary">
    {{-- Content = your twitter profile --}}
    <meta name="twitter:site" content="@nuwebsco">

    @foreach ($locales as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ config('app.url') }}/{{ $locale }}" />
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ config('app.url') }}" />

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src=" {{ asset('js/app.js') }}" defer></script>

</head>

<body>
    <div id="app">
        <h1 class="d-none">@lang('meta.title')</h1>
        <the-index></the-index>
        <noscript>
            <div style="height: 100vh">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-sm-2 offset-sm-2 d-flex justify-content-end">
                            <img src="{{ asset('images/DS-Logo.png') }}" alt="Logo" class="img-fluid"
                                style="max-height: 25vh">
                        </div>
                        <div class="col-sm-6">
                            <h1 class="display-3">@lang('meta.title')</h1>
                            <p class="lead">@lang('meta.no_script.js_warning')</p>
                        </div>
                    </div>
                </div>
            </div>
        </noscript>
    </div>
</body>

</html>
