@extends('stores.layout')
@section('title','لوحة التجكم | التصميم')
@section('page','التصميم')
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

            @if(Route::currentRouteNamed('stores.dashboard.design.active'))
                <form action="{{ route('stores.dashboard.design.active-func',['store'=>request()->route('store'),'id'=>request()->route('id')]) }}" method="post">@csrf 
                    <div class="col-12 mt-5 text-start">
                        <div class="alert text-dark text-end" role="alert">هل أنت متأكد من تحديث حالة هذا التصميم</div>
                        <input type='hidden' name="id" value="{{ request()->route('id') }}" />
                        <button class="btn btn-warning focus-none btn-sm">تحديث حالة التصميم</button>
                    </div>
                </form>
            @endif

            @if(Route::currentRouteNamed('stores.dashboard.design.edit'))
                <form action="{{ route('stores.dashboard.design.edit-func',['store'=>request()->route('store'),'id'=>request()->route('id')]) }}" method="post">@csrf 
                    <div class="col-12 mt-5 text-start">
                        <input type="text" class="form-control focus-none" name="name" value="{{ old('name', $design->name) }}"/>
                        <input type='hidden' name="id" value="{{ request()->route('id') }}" />
                        <button class="btn btn-warning focus-none btn-sm mt-3">تحديث التصميم</button>
                    </div>
                </form>
            @endif
            
        </div>
    </div>


@endsection