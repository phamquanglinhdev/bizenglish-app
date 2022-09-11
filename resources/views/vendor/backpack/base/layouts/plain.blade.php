<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('backpack.base.html_direction') }}">
<head>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/855/855601.png">
    @include(backpack_view('inc.head'))
</head>
<body class="app flex-row align-items-center">

@yield('header')

<div class="container">
    @yield('content')
</div>

<footer class="app-footer sticky-footer">
    @include('backpack::inc.footer')
</footer>

@yield('before_scripts')
@stack('before_scripts')

@include(backpack_view('inc.scripts'))

@yield('after_scripts')
@stack('after_scripts')
@yield("js")
</body>
</html>
