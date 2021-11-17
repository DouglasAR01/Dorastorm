<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="index, follow">
{{-- Your facebook domain verification --}}
<meta name="facebook-domain-verification" content="">
@foreach ($locales as $locale)
    <link rel="alternate" hreflang="{{ $locale }}" href="{{ config('app.url') }}/{{ $locale }}" />
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ config('app.url') }}" />
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
<script src=" {{ mix('js/app.js') }}" defer></script>
