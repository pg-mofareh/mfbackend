@extends('dashboard.layout')
@section('title','لوحة التجكم | إستعراض متجر')
@section('page','إستعراض متجر')
@section('body')
    <div class="container-fluid mt-4">
        @if (session('success'))
            <div class="row">
                <div class="col-12">
                    <div class="text-success p-2 d-flex align-items-center"><i class="material-icons ms-2 me-2">done</i>{{ session('success') }}</div>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="row mt-1">
                <div class="col-12">
                    <ul class="text-danger p-0" style="list-style: none;">
                    @foreach($errors->all() as $error)
                        <li>
                            <div class="text-danger p-2 d-flex align-items-center"><i class="material-icons ms-2 me-2">error</i>{{ $error }}</div>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-4 p-2">
                        <div class="bg-white rounded p-3">
                            <div class="d-flex justify-content-end">
                                <form action="{{ route('dashboard.stores.edit',['id'=>request()->route('id')]) }}" method="get">
                                    <button class="btn btn-sm text-dark focus-none"><li class="material-icons">edit</li></button>
                                </form>
                            </div>
                            <div class="w-100 p-3">
                                <img src="{{$store->logo}}" class="rounded-circle w-100 img-height hand border border-3" style="border-style:inset;"/>
                            </div>    
                            <div class="w-100 p-3 pt-2 d-flex justify-content-center fs-5 fw-bold">{{$store->name}}</div>   
                            <div class="d-flex justify-content-between border-bottom p-2">
                                <label class="fw-bold d-flex align-items-center"><li class="material-icons ms-1">public</li>التحقق من المتجر</label>
                                <div class="d-flex">
                                    <label class="ms-1">@if($store->is_verify==0){{'غير نشط'}}@else{{'نشط'}}@endif</label>
                                    <form action="{{ route('dashboard.stores.viewer.verify-store',['id'=>request()->route('id')]) }}" method="post">@csrf
                                        <button class="btn btn-sm btn-@if($store->is_verify==0){{'success'}}@else{{'warning'}}@endif">@if($store->is_verify==0){{'تنشيط'}}@else{{'إيقاف'}}@endif</button>
                                    </form>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between border-bottom p-2">
                                <label class="fw-bold d-flex align-items-center"><li class="material-icons ms-1">info</li>حالة المتجر</label>
                                <div class="d-flex">
                                    <label class="ms-1">@if($store->is_active==0){{'غير نشط'}}@else{{'نشط'}}@endif</label>
                                    <form action="{{ route('dashboard.stores.viewer.active-store',['id'=>request()->route('id')]) }}" method="post">@csrf
                                        <button class="btn btn-sm btn-@if($store->is_active==0){{'success'}}@else{{'warning'}}@endif">@if($store->is_active==0){{'تنشيط'}}@else{{'إيقاف'}}@endif</button>
                                    </form>
                                </div>
                            </div>  
                            <div class="d-flex justify-content-between border-bottom p-2">
                                <label class="fw-bold d-flex align-items-center"><li class="material-icons ms-1">public</li>الموقع الإلكتروني</label>
                                <label><a href="{{ URL::to('/') }}/{{ $store->subdomain }}" target="_blank">mofareh.net/{{$store->subdomain}}</a></label>
                            </div>
                            <div class="d-flex justify-content-between p-2">
                                <label class="fw-bold d-flex align-items-center"><li class="material-icons ms-1">map</li>الموقع</label>
                                <label class="mt-2"><a href="{{ $store->location }}" target="_blank"><li class="material-icons">travel_explore</li></a></label>
                            </div>         
                        </div>
                    </div>
                    <div class="col-md-4 order-2 order-md-3 p-2">
                        <div class="bg-white rounded p-3 pb-4">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('dashboard.users.edit',['id'=>$user->id]) }}" target="_blank" class="btn btn-sm text-dark focus-none"><li class="material-icons">edit</li></a>
                            </div>  
                            <div class="d-flex justify-content-between border-bottom p-2">
                                <label class="fw-bold d-flex align-items-center"><li class="material-icons ms-1">feed</li>الإسم الأول</label>
                                <label>{{ $user->first_name }}</label>
                            </div>    
                            <div class="d-flex justify-content-between border-bottom p-2">
                                <label class="fw-bold d-flex align-items-center"><li class="material-icons ms-1">feed</li>الإسم الأخير</label>
                                <label>{{ $user->last_name }}</label>
                            </div>  
                            <div class="d-flex justify-content-between border-bottom p-2">
                                <label class="fw-bold d-flex align-items-center"><li class="material-icons ms-1">phone</li>رقم الجوال</label>
                                <label>{{ $user->phone_number }}</label>
                            </div>
                            <div class="d-flex justify-content-between border-bottom p-2">
                                <label class="fw-bold d-flex align-items-center"><li class="material-icons ms-1">email</li>الإيميل</label>
                                <label>{{ $user->email }}</label>
                            </div>  
                            <div class="d-flex justify-content-between border-bottom p-2">
                                <label class="fw-bold d-flex align-items-center"><li class="material-icons ms-1">schedule</li>تاريخ الإنضمام</label>
                                <label>{{ $user->created_at }}</label>
                            </div>  
                                    
                                                     
                        </div>
                    </div>
                    
                    <div class="col-md-4 order-2 order-md-3 p-2">
                        <div class="bg-white rounded p-3 pb-4 text-start">
                            @if(isset($qr_code))
                                <img src="{{ $qr_code->image }}" class="w-100 " />
                                <form action="{{ route('dashboard.files.delete-qrcode') }}" method="post" enctype="multipart/form-data">@csrf
                                    <input type="hidden" name="store" value="{{ isset($store->id) ? $store->id : '' }}" />
                                    <button class="btn btn-danger btn-sm focus-none">حذف الباركود</button>
                                </form>
                            @else
                                <form action="{{ route('dashboard.files.create-qrcode') }}" method="post" enctype="multipart/form-data">@csrf
                                    <div class="text-muted text-end mb-4 mt-2">إنشاء باركود للمتجر</div>
                                    <input type="file" class="form-control mb-2" name="file" accept="image/*" />
                                    <input type="hidden" name="store" value="{{ isset($store->id) ? $store->id : '' }}" />
                                    <button class="btn btn-warning btn-sm focus-none">إنشاء الباركود</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

    </script>
@endsection
