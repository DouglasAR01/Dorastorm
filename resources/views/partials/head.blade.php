<meta charset="utf-8">
<meta name="viewport" content="{{ config('meta.viewport') }}">
<meta name="robots" content="{{ config('meta.robots') }}">
@if(config('meta.facebook-domain-verification') !== null)
    <meta name="facebook-domain-verification" content="{{ config('meta.facebook-domain-verification') }}">
@endif
@foreach ($locales as $locale)
    <link rel="alternate" hreflang="{{ $locale }}"
        href="{{ config('meta.canonical-url') }}/{{ $locale }}" />
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ asset(config('meta.canonical-url')) }}" />
<link rel="icon" type="{{ config('meta.favicon.type') }}" href="{{ config('meta.favicon.url') }}">
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
<script src=" {{ mix('js/app.js') }}" defer></script>
