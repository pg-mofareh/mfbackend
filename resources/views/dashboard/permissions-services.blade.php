@extends('dashboard.layout')
@section('title','لوحة التجكم | الأذونات')
@section('page','الأذونات')
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
                @if(Route::currentRouteNamed('dashboard.permissions.create'))
                    <div class="col-12">
                        <form action="{{route('dashboard.permissions.create-func')}}" method="post">
                            @csrf
                            <div class="w-100">
                                <input type='text' name="name" class="form-control mt-3 focus-none placeholder-right" dir='ltr' placeholder="إسم الإذن هنا" />
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>إذن جديد</button>
                            </div>
                        </form>
                    </div>
                @elseif(Route::currentRouteNamed('dashboard.permissions.delete'))
                    <div class="col-12">
                        <form action="{{route('dashboard.permissions.delete-func',['id'=>$permission->id])}}" method="post">
                            @csrf
                            <div class="alert alert-danger" role="alert">هل أنت متأكد من حذف هذا الإذن</div>
                            <div class="d-flex justify-content-end mt-4">
                                <input type='hidden' name="id" value="{{$permission->id}}" />
                                <button type="submit" class='btn btn-danger btn-sm border-0 focus-none'>حذف الإذن</button>
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