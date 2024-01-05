<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <meta name="description" content="منصة تتيح خدمات متنوعة للمتاجر">
    <link rel="icon" type="image" href="{{ env('APP_ICON') }}">
    <meta charset="utf-8">
    <!-- og meta -->
    <meta property="og:title" content="منصة منيو">
    <meta property="og:description" content="منصة تتيح خدمات متنوعة للمتاجر">
    <meta property="og:image" content="{{ asset(env('APP_ICON')) }}">
    <meta property="og:url" content="{{ route('main') }}">
    <meta property="og:type" content="website">
    <!-- twitter card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@_MoSalah_1">
    <meta name="twitter:title" content="منصة منيو">
    <meta name="twitter:description" content="منصة تتيح خدمات متنوعة للمتاجر">
    <meta name="twitter:image" content="{{ asset(env('APP_ICON')) }}">
    <meta name="twitter:image:alt" content="منصة منيو">
    <link href="{{ env('BOOTSTRAP_CSS_PATH') }}" rel="stylesheet">
    <link href="{{ env('OWN_MAIN_CSS_PATH') }}" rel="stylesheet">
    <link href="{{ env('MATERIAL_ICONS_PATH') }}" rel="stylesheet">
    <style>
        .dropdown .btn::after {
            content: none;
        }
    </style>
    @section('head')
    @show
</head>
<body dir="rtl">
    
    @section('body')
    @show

    <script src="{{ env('JQUERY_PATH') }}"></script>
    <script>
        $(document).ready(function() {
            $('#search-form').submit(function(event) {
                event.preventDefault();
                var inputValue = $(this).find('input').val();
                var searchUrl = '/search/' + encodeURIComponent(inputValue);
                window.location.href = searchUrl;
            });

            function updateSearchHeight() {
                var screenHeight = $(window).height();
                var navbarHeight = $("#navbar").height();
                var footerHeight = $("#footer").height();
                var scrollPosition = $(window).scrollTop();
                

                var minH = screenHeight-navbarHeight-footerHeight;
                $("#subBody").css('min-height', minH);
            }
            updateSearchHeight();
            $(window).scroll(function () {
                updateSearchHeight();
            });
            $(window).resize(function () {
                updateSearchHeight();
            });

            $("#menu-button").click(function () {
                $("#navbar-ul-box").slideToggle();
            });

            $("#menu-stores-button").click(function () {
                $("#navbar-stores-ul-box").slideToggle();
            });
        });
    </script>
    @section('script')
    @show
</body>
</html>

