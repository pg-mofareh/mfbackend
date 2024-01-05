<!DOCTYPE html>
<html lang="ar">
    <head>
        <title>الإنضمام لنا</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image" href="{{ env('APP_ICON') }}">
        <link href="{{ env('BOOTSTRAP_CSS_PATH') }}" rel="stylesheet">
        <link href="{{ env('OWN_MAIN_CSS_PATH') }}" rel="stylesheet">
        <link href="{{ env('MATERIAL_ICONS_PATH') }}" rel="stylesheet">
    </head>
    <body dir="rtl">
            
        <div class="container-fluid vh-100 d-flex flex-column">
            <div class="row">
                <div class="col-12 d-flex align-items-center text-primary pt-3 pb-3">
                    <a href="{{route('main')}}" class="text-decoration-none"><i class="material-icons btn m-0 p-0 focus-none text-warning">home</i> <label class="btn pt-0 pe-0 me-0 focus-none text-warning">الصفحة الرئيسية</label></a>
                </div>
            </div>
            <div class="row flex-grow-1 align-items-center justify-content-around h-100">
                <div class="col-md-5">
                    <div id="lottie" class="w-100"></div>
                </div>

                <div class="col-md-4">
                    @if(Route::currentRouteNamed('auth.repassword') || Route::currentRouteNamed('auth.repassword'))
                        <div class="row p-2">
                            <div class="col-12 mt-2 d-flex align-items-center mb-4 border-bottom text-primary">
                                <a href="{{route('auth.login')}}" class="text-decoration-none"><i class="material-icons btn m-0 p-0 focus-none text-primary">arrow_forward</i> <label class="btn pt-0 pe-0 me-0 focus-none text-primary">صفحة الدخول</label></a>
                            </div>
                        </div>
                    @endif
                    <div class="row p-0">
                        @if (session('success'))
                            <div class="col-12 mt-2">
                                <div class="text-success p-2 d-flex align-items-center"><i class="material-icons ms-2 me-2">done</i>{{ session('success') }}</div>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="col-12 mt-2">
                                <ul class="text-danger p-0" style="list-style: none;">
                                    @foreach($errors->all() as $error)
                                        <li>
                                            <div class="text-danger p-2 d-flex align-items-center"><i class="material-icons ms-2 me-2">error</i>{{ $error }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="col-12">
                            <form action="{{route('main.join-us.create')}}" method="post">
                                @csrf
                                <input type="text" class="form-control focus-none" name="name" placeholder="إسم المتجر" value="{{ old('name') }}" />
                                <input type="text" class="form-control focus-none mt-2 placeholder-right" dir="ltr" name="subdomain" placeholder="دومين المتجر" value="{{ old('subdomain') }}" />
                                <input type="text" class="form-control focus-none mt-2" name="location" placeholder="رابط موقع المتجر" value="{{ old('location') }}" />
                                <button class="btn btn-warning btn-md focus-none mt-2 w-100">إنشاء المتجر</button>
                            </form>
                        </div>
                                
                    </div>
                </div>
            </div>
        </div>

    <script src="{{ env('LOTTIE_PATH') }}"></script>
    <script>
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('lottie'),
            path: "{{ URL::to('/') }}{{ env('APP_PUBLIC') }}/lottie/join-us.json",
            renderer: 'svg',
            loop: true,
            autoplay: true, 
        });
    </script>
    </body>
</html>