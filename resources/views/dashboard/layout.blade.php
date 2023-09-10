<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ URL::to('/') }}{{ env('APP_PUBLIC') }}/custom-style.css" rel="stylesheet">
    @section('head')
    @show
</head>
<body dir="rtl" class="p-0 m-0">
    
    <div class="container-fluid vh-100 p-0 m-0">
        <div class="row h-100 p-0 m-0">
            <div class="col-md-4 col-lg-3 col-xl-2 d-none d-md-block shadow-lg h-100 translate-1s pe-0 ps-0" style="background-image: linear-gradient(to bottom right, #45526e,  #45526e, #7888ab);">
                <div class="d-flex justify-content-center align-items-center text-dark fw-bold  fs-5" style="height:60px">إسم المتجر</div>
                <div class="d-flex justify-content-center p-2 pt-0 pb-0">
                    <ul class="mt-5 p-0 w-100 m-0" style="list-style:'none'">
                        <li class=" w-100 mt-2 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand shadow-sm border-end border-5 @if(Route::currentRouteName() == 'dashboard.home') border-dark bg-light @else bg-white @endif" link="{{ route('dashboard.home') }}" onclick="openTab(this)">{{__('dashboard.pages-name.home')}}</li> 
                        @canany(['users view', 'users create','users edit', 'users delete'])
                            <li class="w-100 mt-3 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand shadow-sm border-end border-5 @if(Route::currentRouteName() == 'dashboard.users') border-dark bg-light @else bg-white @endif" link="{{ route('dashboard.users') }}" onclick="openTab(this)">{{__('dashboard.pages-name.users')}}</li> 
                        @endcanany
                        @canany(['roles view', 'roles create','roles edit', 'roles delete'])
                            <li class="w-100 mt-3 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand shadow-sm border-end border-5 @if(Route::currentRouteName() == 'dashboard.roles') border-dark bg-light @else bg-white @endif" link="{{ route('dashboard.roles') }}" onclick="openTab(this)">{{__('dashboard.pages-name.roles')}}</li> 
                        @endcanany
                        @canany(['permissions view', 'permissions create','permissions edit', 'permissions delete'])
                            <li class="w-100 mt-3 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand shadow-sm border-end border-5 @if(Route::currentRouteName() == 'dashboard.permissions') border-dark bg-light @else bg-white @endif" link="{{ route('dashboard.permissions') }}" onclick="openTab(this)">{{__('dashboard.pages-name.permissions')}}</li> 
                        @endcanany
                        @canany(['payment view'])
                            <li class="w-100 mt-3 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand shadow-sm border-end border-5 @if(Route::currentRouteName() == 'dashboard.payment') border-dark bg-light @else bg-white @endif" link="{{ route('dashboard.payment') }}" onclick="openTab(this)">إدارة المدفوعات</li> 
                        @endcanany
                        @canany(['files view', 'files create','files edit', 'files delete'])
                            <li class="w-100 mt-3 p-3 pt-2 pb-2 fs-6 fw-bold d-flex hand shadow-sm border-end border-5 @if(Route::currentRouteName() == 'dashboard.files') border-dark bg-light @else bg-white @endif" link="{{ route('dashboard.files') }}" onclick="openTab(this)">إدارة الملفات</li> 
                        @endcanany
                    </ul>
                </div>
            </div>
            <div class="col-md-8 col-lg-9 col-xl-10 bg-white h-100 translate-1s p-0">
                <div class="w-100 bg-light shadow">
                                <div class="row m-0 p-3 ps-2 pe-2 p-md-2">
                                    <div class="col-6 col-md-4 d-flex align-items-center order-1">
                                        <button class="btn m-0 p-0 border-0 text-muted ms-2 focus-none"><li class="material-icons pt-2">menu</li></button>
                                        <span class="fw-bold">@yield('page')</span>
                                    </div>
                                    <div class="col-12 col-md-6 d-flex p-1 order-3 order-md-2  mt-3 mt-md-0">
                                        <div class="bg-white border w-100 d-flex rounded">
                                            <input type="search" class="form-control rounded bg-white border-0 focus-none" placeholder="مالذي تبحث عنه ؟" />
                                            <button class="btn m-0 p-0 border-0 text-muted me-2 ms-2"><li class="material-icons pt-2">search</li></button>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-2 d-flex align-items-center justify-content-end order-2 order-md-3">
                                        
                                    </div>
                                </div> 
                </div>
                <div class="w-100 overflow-auto store-height scrolly p-2 mt-2">
                @section('body')
                @show
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
    <script>
        function openTab(element){
            var link = element.getAttribute('link');
            window.open(link,'_self');
        }
    </script>
    @section('script')
    @show
</body>
</html>

