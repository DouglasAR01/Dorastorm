@extends('layouts.app')

@section('meta')
    <title>@lang('meta.title')</title>
    <meta name="description" content="@lang('meta.description')">

    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="@lang('meta.title')">
    <meta property="og:description" content="@lang('meta.description')">
    <meta property="og:type" content="website">
    {{-- URL or asset of the image --}}
    <meta property="og:image" content="">

    <meta name="twitter:card" content="summary">
    {{-- Content = your twitter profile --}}
    <meta name="twitter:site" content="@nuwebsco">
@endsection

@section('seo-hidden-title', trans('meta.title'))
