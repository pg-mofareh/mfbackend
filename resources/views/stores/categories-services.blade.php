@extends('stores.layout')
@section('title','لوحة التجكم | التصنيفات')
@section('page','التصنيفات')
@section('store_name',$store_name)
@section('user_name',$user_name)
@section('body')
    <div class="container-fluid mt-5">
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
            @if(Route::currentRouteNamed('stores.dashboard.categories.active'))
                <div class="col-12">
                    <form action="{{ route('stores.dashboard.categories.active-func',['store'=>request()->route('store'),'id'=>request()->route('id')]) }}" method="post">
                        @csrf
                        <div class="alert alert-success" role="alert">هل أنت متأكد من من تحديث حالة هذا التصنيف</div>
                        <div class="d-flex justify-content-end mt-4">
                            <input type='hidden' name="id" value="{{$category->id}}" />
                            <input type='hidden' name="status" value="@if($category->is_active==0){{1}}@else{{0}}@endif" />
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تحديث حالة التصنيف</button>
                        </div>
                    </form>
                </div>
            @elseif(Route::currentRouteNamed('stores.dashboard.categories.delete'))
                <div class="col-12">
                    <form action="{{ route('stores.dashboard.categories.delete-func',['store'=>request()->route('store'),'id'=>request()->route('id')]) }}" method="post">
                        @csrf
                        <div class="alert alert-danger" role="alert">هل أنت متأكد من من حذف هذا التصنيف</div>
                        <div class="d-flex justify-content-end mt-4">
                            <input type='hidden' name="id" value="{{$category->id}}" />
                            <button type="submit" class='btn btn-danger btn-sm border-0 focus-none'>حذف التصنيف</button>
                        </div>
                    </form>
                </div>
            @elseif(Route::currentRouteNamed('stores.dashboard.categories.edit'))
                <div class="col-12">
                    <form action="{{ route('stores.dashboard.categories.edit-func',['store'=>request()->route('store'),'id'=>request()->route('id')]) }}" method="post">
                        @csrf
                        <input type="text" class="form-control focus-none" name="name" placeholder="إسم التصنيف" value="{{$category->name}}" />
                        <div class="d-flex justify-content-end mt-4">
                            <input type='hidden' name="id" value="{{$category->id}}" />
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تعديل التصنيف</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection