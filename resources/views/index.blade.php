@extends('layouts.app')

@section('meta')
    <title>@lang('meta.title')</title>
    <meta name="description" content="@lang('meta.description')">

    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="@lang('meta.title')">
    <meta property="og:description" content="@lang('meta.description')">
    <meta property="og:type" content="{{ config('meta.og.type') }}">
    @if(config('meta.og.image') !== null)
        <meta property="og:image" content="{{ config('meta.og.image') }}">
    @endif

    @if (config('meta.twitter.enabled'))
        <meta name="twitter:card" content="{{ config('meta.twitter.card') }}">
        <meta name="twitter:site" content="{{ config('meta.twitter.site') }}">
    @endif
@endsection

@section('seo-hidden-title', trans('meta.title'))
