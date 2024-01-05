<!DOCTYPE html>
<html lang="ar">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>
        <meta charset="utf-8">
        <link href="{{ env('BOOTSTRAP_CSS_PATH') }}" rel="stylesheet">
        <style>
            body {
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            #center {

            }
        </style>
    </head>
    <body dir="rtl">
        @section('body')
        @show
        <script src="{{ env('LOTTIE_PATH') }}"></script>
        @section('script')
        @show
    </body>
</html>

