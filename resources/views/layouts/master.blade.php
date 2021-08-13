<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/mmon.css') }}">
    </head>
    <body>
        @include('partials.header')
        <main>
            @yield('content')
        </main>
        {{-- <script src="{{ asset('js/index.js') }}"></script> --}}
        @stack('scripts')
    </body>
</html>
