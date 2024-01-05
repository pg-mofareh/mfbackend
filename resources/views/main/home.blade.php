@extends('main.layout')
@section('title','الصفحة الرئيسية')
@section('body')

    <div class="fixed-top p-2" id="navbar">
        <div class="container-fluid">
            <div class="row align-items-center p-3 rounded bg-white" id="navbar-box">
                <div class="col-7 col-md-8 d-flex align-items-center justify-content-between justify-content-md-start">
                    <label for="" class="fs-4 fw-bold">منصة منيو</label>
                </div>
                <div class="col-5 col-md-4 mt-3 mt-md-0">
                    @auth
                        <div class="d-flex">
                            <a href="{{ route('main.join-us') }}" class="btn text-primary fw-bold text-start focus-none flex-fill  ms-1 me-1">الذهاب لصفحة المتاجر</a>
                            <a href="{{ route('auth.logout') }}" class="btn text-danger fw-bold text-decoration-underline focus-none ms-1 me-1">الخروج</a>
                        </div>
                    @else
                        <a href="{{ route('auth.login') }}" class="btn text-primary fw-bold text-start focus-none w-100">الإنضمام لنا</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-0 pt-5 pt-md-0 vh-100">
        <div class="row align-items-center h-100 pt-5 m-md-0 p-0 m-0" >
            <div class="col-12 col-md-6 text-dark text-center">
                <div  class="w-100 d-flex justify-content-center mt-5 mt-md-0 fs-6 fw-bold"><div class="wmd-75 wmd-50">نقدم خدمات للكافيهات والمطاعم، باستخدام التقنيات الحديثة، تتضمن معلومات شاملة حول المتجر وقائمة المتجر</div></div>
            </div>
            <div class="col-12 col-md-6">
                <div id="header-lottie" class="w-100"></div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ env('LOTTIE_PATH') }}"></script>
    <script>
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('header-lottie'),
            path: "{{ URL::to('/') }}{{ env('APP_PUBLIC') }}/lottie/menu-list.json",
            renderer: 'svg',
            loop: true,
            autoplay: true,
        });

        $(document).ready(function () {
            function updateNavbarBackground() {
                var screenHeight = $(window).height();
                var bodyHeight = $("body").height();
                var scrollPosition = $(window).scrollTop();
            }
            updateNavbarBackground();
            $(window).scroll(function () {
                updateNavbarBackground();
            });
        });
    </script>
@endsection