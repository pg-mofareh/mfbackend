<!DOCTYPE html>
<html lang="ar">
    <head>
        <title>صفحة الدخول</title>
        <link rel="icon" type="image" href="{{ env('APP_ICON') }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ env('BOOTSTRAP_CSS_PATH') }}" rel="stylesheet">
        <link href="{{ env('OWN_MAIN_CSS_PATH') }}" rel="stylesheet">
        <link href="{{ env('MATERIAL_ICONS_PATH') }}" rel="stylesheet">
    </head>
    <body dir="rtl">
            
        <div class="container-fluid vh-100 d-flex flex-column">
            <div class="row">
                <div class="col-12 d-flex align-items-center text-primary pt-3 pb-3">
                    <a href="{{route('main')}}" class="text-decoration-none"><i class="material-icons btn m-0 p-0 focus-none text-dark">home</i> <label class="btn pt-0 pe-0 me-0 focus-none text-dark">الصفحة الرئيسية</label></a>
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
                        @if(Route::currentRouteNamed('auth.login'))
                            <div class="col-12 p-3">
                                <form method="post" id="login_form">
                                    @csrf
                                    <div class="w-100">
                                        <div class='d-flex fs-6 site-title-font mt-4 text-dark'>
                                            <div>هل لديك حساب ؟</div>
                                            <a class="text-primary me-2 text-decoration-none" href="{{ route('auth.register') }}">إنشاء</a>
                                        </div>
                                        <input type='email' name="email" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none placeholder-right" dir='ltr' placeholder="أضف الإيميل هنا" value="{{ old('email') }}" />
                                        <input type='password' name="password" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none placeholder-right" dir='ltr' placeholder="أضف كلمة السر هنا" value="{{ old('password') }}" />
                                    </div>
                                    <div class="w-100 d-flex justify-content-between mt-4">
                                        <button type="button" class='btn text-primary btn-sm border-0 focus-none fw-bold loginorpass' link="{{ route('auth.repasswordreq') }}">نسيت كلمة السر!</button>
                                        <button type="button" class='btn btn-success btn-sm border-0 focus-none loginorpass' link="{{route('auth.login-func')}}">الدخول</button>
                                    </div>
                                </form>
                            </div>
                        @elseif(Route::currentRouteNamed('auth.register'))
                            <div class="col-12 p-3">
                                <form action="{{route('auth.register-func')}}" method="post">
                                    @csrf
                                    <div class="w-100">
                                        <div class='d-flex fs-6 site-title-font mt-4 text-dark'>
                                            <div>لدي حساب ؟</div>
                                            <a class="text-primary me-2 text-decoration-none" href="{{ route('auth.login') }}">الدخول</a>
                                        </div>
                                        <input type='text' name="first_name" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none" dir='' placeholder="الإسم الأول" value="{{ old('first_name') }}" />
                                        <input type='text' name="last_name" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none" dir='' placeholder="الإسم الأخير" value="{{ old('last_name') }}" />
                                        <input type='number' name="phone_number" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none placeholder-right arrow-none" dir='ltr' placeholder="رقم الجوال" value="{{ old('phone_number') }}" />
                                        <div class="text-muted d-flex align-items-center" dir="ltr">5*********</div>
                                        <input type='email' name="email" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none placeholder-right" dir='ltr' placeholder="أضف الإيميل هنا" value="{{ old('email') }}" />
                                        <input type='password' name="password" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none placeholder-right" dir='ltr' placeholder="أضف كلمة السر هنا" value="{{ old('password') }}" />
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>إنشاء حساب</button>
                                    </div>
                                </form>
                            </div>
                        @elseif(Route::currentRouteNamed('auth.verify'))
                            <form class="mb-2" action="{{ route('auth.verify_func') }}" method="post">
                                @csrf
                                <label class="">تحقق من الإيميل</label>
                                <div class="d-flex w-100 mt-2">
                                    <input type="number" dir="ltr" class="form-control mb-2 mr-sm-2 focus-none placeholder-right arrow-none" name="number" placeholder="رمز التحقق">
                                </div>
                                <div class="d-flex justify-content-end mt-2" >
                                    <button type="submit" class="btn btn-success btn-sm mb-2 focus-none" >تحقق من الإيميل <div class="spinner-border spinner-border-sm" style="display:none" id="loading" role="status"><span class="visually-hidden">Loading...</span></div> </button>
                                </div>
                            </form>
                        @elseif(Route::currentRouteNamed('auth.repassword'))
                            <form class="mb-2" action="{{ route('auth.repassword_func') }}" method="post">
                                @csrf
                                <label class="">تحقق من الإيميل</label>
                                <div class="d-flex w-100 mt-2">
                                    <input type="number" dir="ltr" class="form-control mb-2 mr-sm-2 focus-none placeholder-right arrow-none" name="number" placeholder="رمز التحقق">
                                </div>
                                <div class="d-flex w-100 mt-2">
                                    <input type="hidden" dir="ltr" class="form-control mb-2 mr-sm-2 focus-none placeholder-right" name="email" placeholder="الإيميل" value="{{ old('email') }}">
                                </div>
                                <div class="d-flex w-100 mt-2">
                                    <input type="password" dir="ltr" class="form-control mb-2 mr-sm-2 focus-none placeholder-right" name="password" placeholder="كلمة السر هنا">
                                </div>
                                <div class="d-flex w-100 mt-2">
                                    <input type="password" dir="ltr" class="form-control mb-2 mr-sm-2 focus-none placeholder-right" name="repassword" placeholder="إعادة كلمة السر">
                                </div>
                                <div class="d-flex justify-content-end mt-2" >
                                    <button type="submit" class="btn btn-success btn-sm mb-2 focus-none" >تحقق من الإيميل <div class="spinner-border spinner-border-sm" style="display:none" id="loading" role="status"><span class="visually-hidden">Loading...</span></div> </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    <script src="{{ env('LOTTIE_PATH') }}"></script>
    <script src="{{ env('JQUERY_PATH') }}"></script>
    <script>
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('lottie'),
            path: "{{ URL::to('/') }}{{ env('APP_PUBLIC') }}/lottie/login.json",
            renderer: 'svg',
            loop: true,
            autoplay: true, 
        });
        $('.loginorpass').on('click', function(e) {
            e.preventDefault();
            var link = $(this).attr('link');
            $('#login_form').attr('action', link);
            $('#login_form').submit();
        });
    </script>
    </body>
</html>