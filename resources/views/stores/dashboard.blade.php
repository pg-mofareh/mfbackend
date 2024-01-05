@extends('stores.layout')
@section('title','لوحة التجكم | الرئيسية')
@section('page','الرئيسية')
@section('store_name',$store_name)
@section('user_name',$user_name)
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

                            
                            <div class="col-md-6 col-xl-3 p-2">
                                <div class="position-relative">
                                    <li class="material-icons ms-1 text-dark position-absolute" style="font-size: 25px;top:-12px">verified</li>
                                </div>
                                <div class="bg-white rounded p-3 d-flex justify-content-between align-items-center">
                                    <label class="fw-bold d-flex align-items-center">التحقق من المتجر</label>
                                    <button class="btn btn-sm ps-3 pe-3 fw-bold focus-none text-@if($store->is_verify==0){{'warning'}}@else{{'success'}}@endif">@if($store->is_verify==0){{'لم يتم التحقق'}}@else{{'تم التحقق'}}@endif</button>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3 p-2">
                                <div class="position-relative">
                                    <li class="material-icons ms-1 text-dark position-absolute" style="font-size: 25px;top:-12px">info</li>
                                </div>
                                <div class="bg-white rounded p-3 d-flex justify-content-between align-items-center">
                                    <label class="fw-bold d-flex align-items-center">حالة المتجر</label>
                                    <form action="{{route('stores.dashboard.stores.active',['store'=>request()->route('store')])}}" method="post" class="d-flex justify-content-between">@csrf
                                        <button class="btn btn-sm btn-@if($store->is_active==0){{'success'}}@else{{'warning'}}@endif ps-3 pe-3">@if($store->is_active==0){{'تنشيط'}}@else{{'إيقاف'}}@endif</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3 p-2">
                                <div class="position-relative">
                                    <li class="material-icons ms-1 text-dark position-absolute" style="font-size: 25px;top:-12px">public</li>
                                </div>
                                <div class="bg-white rounded p-3 d-flex justify-content-between align-items-center">
                                    <label class="fw-bold d-flex align-items-center">الموقع الإلكتروني</label>
                                    <button class="btn btn-sm ps-3 pe-3 text-white m-0 fw-bold"><a href="{{ route('storespublic.main',['store'=>$store->subdomain]) }}" target="_blank">إستعراض</a></button>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3 p-2">
                                <div class="position-relative">
                                    <li class="material-icons ms-1 text-dark position-absolute" style="font-size: 25px;top:-12px">map</li>
                                </div>
                                <div class="bg-white rounded p-3 d-flex justify-content-between align-items-center">
                                    <label class="fw-bold d-flex align-items-center">الموقع الجغرافي</label>
                                    <button class="btn btn-sm ps-3 pe-3 text-white m-0 fw-bold"><a href="{{ $store->location }}" target="_blank">إستعراض</a></button>
                                </div>
                            </div>


                            <div class="col-12 p-2">
                                <div class="bg-white rounded p-3 d-flex">
                                    <div class="p-2">
                                        <img src="{{$store->logo}}" class="rounded img-height hand" style="border-style:inset; width:75px"/>
                                    </div>    
                                    <div class=" p-2 pt-2 d-flex justify-content-center align-items-center fs-5 fw-bold">{{$store->name}}</div>       
                                    
                                </div>
                            </div>
                            
                        </div>
            </div>
        </div>
    </div>
@endsection