@extends('layouts.app')

@section('meta')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    @isset($og)
        @foreach ($og as $ogKey => $content)
            <meta property="{{ $ogKey }}" content="{{ $content }}">
        @endforeach
    @endisset

    @isset($twitter)
        @foreach ($twitter as $twKey => $content)
            <meta property="{{ $twKey }}" content="{{ $content }}">
        @endforeach
    @endisset
@endsection

@section('seo-hidden-title', $meta['title'])

@section('body-extra-content')
    <div class="d-none">
        {!! $obj->content !!}
    </div>
@endsection