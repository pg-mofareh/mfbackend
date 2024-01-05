<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" type="image" href="{{ env('APP_ICON') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ env('BOOTSTRAP_CSS_PATH') }}" rel="stylesheet">
    <link href="{{ env('OWN_MAIN_CSS_PATH') }}" rel="stylesheet">
    <link href="{{ env('MATERIAL_ICONS_PATH') }}" rel="stylesheet">
    @section('head')
    @show
</head>
<body dir="rtl" class="p-0 m-0"  style="background-color:#f1cccc">
    
    <div class="container-fluid vh-100 p-0 m-0"  style="background-color: transparent;">
        <div class="row h-100 p-0 m-0">
            <div class="col-md-4 col-lg-3 col-xl-2 d-none d-md-block h-100 translate-1s p-2" >
                <div class="shadow-lg bg-white h-100 rounded p-3 pe-2 ps-2">
                    <div class="d-flex align-items-center mt-2">
                        <div>
                            <i class="material-icons" style="font-size:55px">store</i>
                        </div>
                        <div class="me-2">
                            <div class="fs-6 fw-bold">لوحة التحكم</div>
                            <div class="text-muted" style="font-size:15px">مشرف</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center p-2 pt-0 pb-0">
                        <ul class="mt-3 p-0 w-100 m-0" style="list-style:'none'">
                            <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.home') text-warning @else bg-white @endif" link="{{ route('dashboard.home') }}" onclick="openTab(this)">
                                <i class="material-icons ms-2">home</i>
                                <span>الرئيسية</span>
                            </li>
                            @canany(['users view', 'users create','users edit', 'users delete'])
                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.users') text-warning @else bg-white @endif" link="{{ route('dashboard.users') }}" onclick="openTab(this)">
                                    <i class="material-icons ms-2">person</i>
                                    <span>العملاء</span>
                                </li>
                            @endcanany
                            @canany(['roles view', 'roles create','roles edit', 'roles delete'])
                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.roles') text-warning @else bg-white @endif" link="{{ route('dashboard.roles') }}" onclick="openTab(this)">
                                    <i class="material-icons ms-2">admin_panel_settings</i>
                                    <span>الأدوار</span>
                                </li>
                            @endcanany
                            @canany(['permissions view', 'permissions create','permissions edit', 'permissions delete'])
                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.permissions') text-warning @else bg-white @endif" link="{{ route('dashboard.permissions') }}" onclick="openTab(this)">
                                    <i class="material-icons ms-2">manage_accounts</i>
                                    <span>الأذونات</span>
                                </li>
                            @endcanany
                            @canany(['stores view','stores edit'])
                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.stores') text-warning @else bg-white @endif" link="{{ route('dashboard.stores') }}" onclick="openTab(this)">
                                    <i class="material-icons ms-2">store</i>
                                    <span>المتاجر</span>
                                </li>
                            @endcanany
                            @canany(['subscriptions view','subscriptions edit'])
                                <li class="w-100 mt-3 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.subscriptions') text-warning @else bg-white @endif" link="{{ route('dashboard.subscriptions') }}" onclick="openTab(this)">
                                    <i class="material-icons ms-2">receipt</i>
                                    <span>إدارة الإشتراكات</span>
                                </li>
                            @endcanany
                            @canany(['coupons view'])
                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.coupons') text-warning @else bg-white @endif" link="{{ route('dashboard.coupons') }}" onclick="openTab(this)">
                                    <i class="material-icons ms-2">sell</i>
                                    <span>الكوبونات</span>
                                </li>
                            @endcanany
                            @canany(['design view'])
                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.design') text-warning @else bg-white @endif" link="{{ route('dashboard.design') }}" onclick="openTab(this)">
                                    <i class="material-icons ms-2">grid_view</i>
                                    <span>التصميم</span>
                                </li>
                            @endcanany
                            @canany(['payment view'])
                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.payment') text-warning @else bg-white @endif" link="{{ route('dashboard.payment') }}" onclick="openTab(this)">
                                    <i class="material-icons ms-2">home</i>
                                    <span>إدارة المدفوعات</span>
                                </li>
                            @endcanany
                            @canany(['files view'])
                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.files') text-warning @else bg-white @endif" link="{{ route('dashboard.files') }}" onclick="openTab(this)">
                                    <i class="material-icons ms-2">folder</i>
                                    <span>الملفات</span>
                                </li>
                            @endcanany
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-9 col-xl-10 h-100 translate-1 p-0">
                <div class="w-100">
                                <div class="row m-0 p-3 ps-2 pe-2 p-md-2 bg-sm-white mf-navbar rounded-bottom" style="z-index:1" id="navbar">
                                    <div class="col-6 col-md-2 d-flex align-items-center order-1">
                                        <i class="material-icons d-md-none" style="font-size:35px">store</i>
                                        <span class="fw-bold d-md-none">لوحة التحكم</span>
                                    </div>
                                    <div class="col-12 col-md-6 d-flex order-3 order-md-2  mt-3 mt-md-0">
                                        <div class="mf-navbar-search w-100 d-flex rounded p-1">
                                            <input type="search" class="form-control rounded bg-white border-0 focus-none" placeholder="مالذي تبحث عنه ؟" />
                                            <button class="btn m-0 p-0 border-0 text-muted me-2 ms-2"><li class="material-icons pt-2">search</li></button>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 d-flex align-items-center justify-content-end order-2 order-md-3">
                                        <a href="{{ route('auth.logout') }}" class="btn text-danger fw-bold text-decoration-underline focus-none ms-1 me-1 d-none d-md-block">تسجيل الخروج</a>
                                        <button class="btn m-0 p-0 border-0 text-muted ms-2 focus-none d-md-none" id="menu-button"><li class="material-icons pt-2">menu</li></button>
                                    </div>
                                    <div class="col-12 order-4 mt-2 mb-2 d-md-none" style="display:none" id="navbar-ul-box">
                                        <ul class="mt-1 p-0 w-100 m-0" style="list-style:'none'">
                                            <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.home') text-warning @else bg-white @endif" link="{{ route('dashboard.home') }}" onclick="openTab(this)">
                                                <i class="material-icons ms-2">home</i>
                                                <span>الرئيسية</span>
                                            </li>
                                            @canany(['users view', 'users create','users edit', 'users delete'])
                                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.users') text-warning @else bg-white @endif" link="{{ route('dashboard.users') }}" onclick="openTab(this)">
                                                    <i class="material-icons ms-2">person</i>
                                                    <span>العملاء</span>
                                                </li>
                                            @endcanany
                                            @canany(['roles view', 'roles create','roles edit', 'roles delete'])
                                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.roles') text-warning @else bg-white @endif" link="{{ route('dashboard.roles') }}" onclick="openTab(this)">
                                                    <i class="material-icons ms-2">admin_panel_settings</i>
                                                    <span>الأدوار</span>
                                                </li>
                                            @endcanany
                                            @canany(['permissions view', 'permissions create','permissions edit', 'permissions delete'])
                                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.permissions') text-warning @else bg-white @endif" link="{{ route('dashboard.permissions') }}" onclick="openTab(this)">
                                                    <i class="material-icons ms-2">manage_accounts</i>
                                                    <span>الأذونات</span>
                                                </li>
                                            @endcanany
                                            @canany(['stores view','stores edit'])
                                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.stores') text-warning @else bg-white @endif" link="{{ route('dashboard.stores') }}" onclick="openTab(this)">
                                                    <i class="material-icons ms-2">store</i>
                                                    <span>المتاجر</span>
                                                </li>
                                            @endcanany
                                            @canany(['subscriptions view','subscriptions edit'])
                                                <li class="w-100 mt-3 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.subscriptions') text-warning @else bg-white @endif" link="{{ route('dashboard.subscriptions') }}" onclick="openTab(this)">
                                                    <i class="material-icons ms-2">receipt</i>
                                                    <span>إدارة الإشتراكات</span>
                                                </li>
                                            @endcanany
                                            @canany(['coupons view'])
                                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.coupons') text-warning @else bg-white @endif" link="{{ route('dashboard.coupons') }}" onclick="openTab(this)">
                                                    <i class="material-icons ms-2">sell</i>
                                                    <span>الكوبونات</span>
                                                </li>
                                            @endcanany
                                            @canany(['files view'])
                                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.design') text-warning @else bg-white @endif" link="{{ route('dashboard.design') }}" onclick="openTab(this)">
                                                    <i class="material-icons ms-2">grid_view</i>
                                                    <span>التصميم</span>
                                                </li>
                                            @endcanany
                                            @canany(['payment view'])
                                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.payment') text-warning @else bg-white @endif" link="{{ route('dashboard.payment') }}" onclick="openTab(this)">
                                                    <i class="material-icons ms-2">store</i>
                                                    <span>إدارة المدفوعات</span>
                                                </li>
                                            @endcanany
                                            @canany(['files view'])
                                                <li class="w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand @if(Route::currentRouteName() == 'dashboard.files') text-warning @else bg-white @endif" link="{{ route('dashboard.files') }}" onclick="openTab(this)">
                                                    <i class="material-icons ms-2">folder</i>
                                                    <span>الملفات</span>
                                                </li>
                                            @endcanany
                                            <li class="w-100 mt-3 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand text-danger" link="{{ route('auth.logout') }}" onclick="openTab(this)">
                                                <i class="material-icons ms-2">logout</i>
                                                <span>تسجيل الخروج</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div> 
                </div>
                <div id="top_contents"></div>
                <div class="w-100 overflow-auto store-height scrolly p-2 pe-3 ps-3 mt-2" id="contents">
                    @section('body')
                    @show
                </div>
            </div>
        </div>
    </div>
    <script src="{{ env('JQUERY_PATH') }}"></script>
    <script src="{{ env('POPPER_PATH') }}"></script>
    <script src="{{ env('BOOTSTRAP_JS_PATH') }}"></script>
    <script src="{{ env('BOOTSTRAP_BUNDLE_JS_PATH') }}"></script>
    <script>
        function openTab(element){
            var link = element.getAttribute('link');
            window.open(link,'_self');
        }

        $(document).ready(function () {
            updateStyles();
            $(window).resize(function () {
                updateStyles();
            });

            function updateStyles() {
                $("#top_contents").height('0px');
                $("#navbar").removeClass('position-fixed');
            }

            $("#menu-button").click(function () {
                if ($("#navbar-ul-box").is(":hidden")) {
                    $("#top_contents").height($("#navbar").height()+32);
                    $("#navbar").addClass('position-fixed');
                    $("#navbar-ul-box").slideDown();
                }else{
                    $("#navbar-ul-box").slideUp(function () {
                        $("#top_contents").height('0px');
                        $("#navbar").removeClass('position-fixed');
                    });
                }
            });

        });
    </script>
    @section('script')

    @show
</body>
</html>

