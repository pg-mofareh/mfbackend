<div class="container-fluid vh-100">
    <div class="row h-100">
        <div class="col-md-4 col-lg-3 col-xl-2 d-none d-md-block shadow-lg bg-light h-100 translate-1s pe-0 ps-0">
            <div class="d-flex justify-content-start align-items-center text-dark fw-bold  fs-5" style="height:60px">إسم المتجر</div>
            <div class="d-flex justify-content-center p-2 pt-0 pb-0">
                <ul class="mt-5 p-0 w-100 m-0" style="list-style:'none'">
                    <li class="bg-white w-100 rounded mt-2 p-2 fs-6 fw-bold d-flex hand shadow-sm" link="{{ route('dashboard.home') }}" onclick="openTab(this)">{{__('dashboard.pages-name.home')}}</li> 
                    @canany(['users view', 'users create','users edit', 'users delete'])
                        <li class="bg-white w-100 rounded mt-3 p-2 fs-6 fw-bold d-flex hand shadow-sm" link="{{ route('dashboard.users') }}" onclick="openTab(this)">{{__('dashboard.pages-name.users')}}</li> 
                    @endcanany
                    @canany(['roles view', 'roles create','roles edit', 'roles delete'])
                        <li class="bg-white w-100 rounded mt-3 p-2 fs-6 fw-bold d-flex hand shadow-sm" link="{{ route('dashboard.roles') }}" onclick="openTab(this)">{{__('dashboard.pages-name.roles')}}</li> 
                    @endcanany
                    @canany(['permissions view', 'permissions create','permissions edit', 'permissions delete'])
                        <li class="bg-white w-100 rounded mt-3 p-2 fs-6 fw-bold d-flex hand shadow-sm" link="{{ route('dashboard.permissions') }}" onclick="openTab(this)">{{__('dashboard.pages-name.permissions')}}</li> 
                    @endcanany
                </ul>
            </div>
        </div>
        <div class="col-md-8 col-lg-9 col-xl-10 bg-white h-100 translate-1s p-0">
            <div class="w-100 bg-white">
                            <div class="row m-0 p-3 ps-2 pe-2 p-md-2">
                                <div class="col-6 col-md-4 d-flex align-items-center order-1">
                                    <button class="btn m-0 p-0 border-0 text-muted ms-2 focus-none"><li class="material-icons pt-2">menu</li></button>
                                    <span class="fw-bold">{{$page}}</span>
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
            <div class="w-100 overflow-auto store-height scrolly p-2">
                {{$slot}}
            </div>
        </div>
    </div>
</div>
<script>
    function openTab(element){
        var link = element.getAttribute('link');
        window.open(link,'_self');
    }
</script>