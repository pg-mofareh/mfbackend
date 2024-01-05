<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" type="image" href="@yield('logo')">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ env('BOOTSTRAP_CSS_PATH') }}" rel="stylesheet">
    <link href="{{ env('OWN_MAIN_CSS_PATH') }}" rel="stylesheet">
    <link href="{{ env('MATERIAL_ICONS_PATH') }}" rel="stylesheet">
    @section('head')
    @show
</head>
<body dir="rtl" class="p-0 m-0">
    @section('body')
    @show
    
    <script src="{{ env('JQUERY_PATH') }}"></script>
    @section('script')
    @show
</body>
</html>

