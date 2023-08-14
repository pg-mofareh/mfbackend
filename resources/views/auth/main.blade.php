<!DOCTYPE html>
<html lang="ar">
    <head>
        <title>Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="{{ URL::to('/') }}{{ env('APP_PUBLIC') }}/custom-style.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    </head>
    <body dir="rtl">
            
        <div class="container-fluid vh-100">
            <div class="row align-items-center justify-content-around h-100">
                <div class="col-md-5">
                    <div id="lottie" class="w-100"></div>
                </div>

                <div class="col-md-4">
                    <div class="row p-0">
                        @if(Route::currentRouteNamed('auth.login'))
                            <div class="col-12 p-3">
                                <form method="post" id="login_form">
                                    @csrf
                                    <div class="w-100">
                                        <div class='d-flex fs-6 site-title-font mt-4 text-dark'>
                                            <div>هل لديك حساب ؟</div>
                                            <a class="text-primary me-2 text-decoration-none" href="{{ route('auth.register') }}">إنشاء</a>
                                        </div>
                                        <input type='email' name="email" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none placeholder-right" dir='ltr' placeholder="أضف الإيميل هنا" />
                                        <input type='password' name="password" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none placeholder-right" dir='ltr' placeholder="أضف كلمة السر هنا" />
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
                                        <input type='text' name="name" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none placeholder-right" dir='ltr' placeholder="أضف الإسم هنا" />
                                        <input type='email' name="email" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none placeholder-right" dir='ltr' placeholder="أضف الإيميل هنا" />
                                        <input type='password' name="password" class="form-control mt-3 rounded-0 border-0 border-bottom focus-none placeholder-right" dir='ltr' placeholder="أضف كلمة السر هنا" />
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>إنشاء حساب</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                    @if ($errors->any())
                        <div class="row">
                            <ul class="text-danger" style="list-style: square;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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