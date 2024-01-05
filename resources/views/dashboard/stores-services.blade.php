@extends('dashboard.layout')
@section('title','لوحة التجكم | المتاجر')
@section('page','المتاجر')
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
            @if(Route::currentRouteNamed('dashboard.stores.edit'))
                <div class="col-12">
                <a href="{{ route('dashboard.stores.viewer',['id'=>request()->route('id')]) }}" class="text-decoration-none"><i class="material-icons btn m-0 p-0 focus-none text-dark">reply</i> <label class="btn pt-0 pe-0 me-0 focus-none text-dark">العودة للمعرض</label></a>
                </div>
                <div class="col-12 mt-2">
                    <form action="{{route('dashboard.stores.edit-func',['id'=>request()->route('id')])}}" method="post">
                        @csrf
                        <div class="w-100">
                            <input type='text' name="name" class="form-control mt-3 focus-none" placeholder="إسم المتجر" value="{{ old('name', $store->name) }}" />
                        </div>
                        <div class="w-100">
                            <input type='text' name="location" dir="ltr" class="form-control mt-3 focus-none placeholder-right" placeholder="خصم المتجر" value="{{ old('location', $store->location) }}" />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تحديث الإسم و الموقع</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 mt-4">
                    <form action="{{route('dashboard.stores.edit-subdomain-func',['id'=>request()->route('id')])}}" method="post">
                        @csrf
                        <div class="w-100">
                            <input type='text' name="subdomain" dir="ltr" class="form-control mt-3 focus-none placeholder-right" placeholder="مجال المتجر" value="{{ old('subdomain', $store->subdomain) }}" />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تحديث مجال المتجر</button>
                        </div>
                    </form>
                </div>
            @endif
            </div>
    </div>
@endsection