<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('partials.head')
    @section('meta')
        Meta tags
    @show
    @section('extra-headers')
        
    @show
</head>

<body>
    <div id="app">
        <h1 class="d-none">
            @yield('seo-hidden-title')
        </h1>
        @section('body-extra-content')
            {{-- Extra content you would like to server render             --}}
        @show
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
