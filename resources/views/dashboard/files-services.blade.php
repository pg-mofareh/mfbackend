@extends('dashboard.layout')
@section('title','لوحة التجكم | إدارة الملفات')
@section('page','إدارة الملفات')
@section('body')
    <div class="container-fluid mt-5">
            @if (session('success'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                    </div>
                </div>
            @endif
            <div class="row">
                @if(Route::currentRouteNamed('dashboard.files.create'))
                    <div class="col-12">
                            <div class="row">
                                <div class="col-12 border pt-3 pb-3">
                                    <form action="{{route('dashboard.files.create-func')}}{{$directoryPath}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type='text' name="folder" class="form-control focus-none placeholder-right rounded-0 border-1 mt-2" dir='ltr' placeholder="إسم المجلد" />
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" name="type" value="folder" class='btn btn-warning btn-sm border-0 focus-none'>إنشاء مجلد</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 border pt-3 pb-3 mt-3">
                                    <form action="{{route('dashboard.files.create-func')}}{{$directoryPath}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type='file' name="file" class="form-control focus-none placeholder-right rounded-0 border-1 mt-2" dir='ltr' />
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" name="type" value="file" class='btn btn-warning btn-sm border-0 focus-none'>إضافة ملف</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </div>
                @elseif(Route::currentRouteNamed('dashboard.files.delete'))
                    <div class="col-12">
                        <form action="{{route('dashboard.coupons.delete-func',['id'=>$coupon->id])}}" method="post">
                            @csrf
                            <div class="alert alert-danger" role="alert">هل أنت متأكد من حذف هذا الكوبون <label class="text-dark me-3">{{$coupon->coupon}}</label></div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class='btn btn-danger btn-sm border-0 focus-none'>حذف الكوبون</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
            @if ($errors->any())
                <div class="row mt-3">
                    <div class="col-12">
                    <ul class="text-danger" style="list-style: none;">
                    @foreach($errors->all() as $error)
                        <li>
                            <div class="alert alert-danger p-2 m-1" role="alert">{{ $error }}</div>
                        </li>
                    @endforeach
                    </ul>
                    </div>
                </div>
            @endif
        </div>  
@endsection