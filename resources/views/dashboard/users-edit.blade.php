@extends('dashboard.layout')
@section('title','لوحة التجكم | تعديل العميل')
@section('page','تعديل العميل')
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
            @if(Route::currentRouteNamed('dashboard.users.edit'))
                <div class="col-12 mt-2">
                    <form action="{{route('dashboard.users.edit-func',['id'=>request()->route('id')])}}" method="post">
                        @csrf
                        <div class="w-100">
                            <input type='text' name="first_name" class="form-control mt-3 focus-none" placeholder="الإسم الأول" value="{{ old('first_name', $user->first_name) }}" />
                        </div>
                        <div class="w-100">
                            <input type='text' name="last_name" class="form-control mt-3 focus-none" placeholder="الإسم الأخير" value="{{ old('last_name', $user->last_name) }}" />
                        </div>
                        <div class="w-100">
                            <input type='number' name="phone_number" dir="ltr" class="form-control mt-3 focus-none placeholder-right arrow-none" maxlength="10" placeholder="رقم الجوال" value="{{ old('phone_number', $user->phone_number) }}" />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تحديث الإسم والجوال</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 mt-4">
                    <form action="{{route('dashboard.users.upemail-user',['id'=>request()->route('id')])}}" method="post">
                        @csrf
                        <div class="w-100">
                            <input type='text' name="email" dir="ltr" class="form-control mt-3 focus-none placeholder-right" placeholder="مجال المتجر" value="{{ old('email', $user->email) }}" />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تحديث إيميل العميل</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 mt-4">
                    <form action="{{route('dashboard.users.uppassword-user',['id'=>request()->route('id')])}}" method="post">
                        @csrf
                        <div class="w-100">
                            <input type='text' name="password" dir="ltr" class="form-control mt-3 focus-none placeholder-right" placeholder="كلمة السر الجديدة" value="{{ old('password') }}" />
                        </div>
                        <div class="w-100">
                            <input type='text' name="password_confirmation" dir="ltr" class="form-control mt-3 focus-none placeholder-right" placeholder="إعادة كلمة السر" value="{{ old('password_confirmation') }}" />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تحديث إيميل العميل</button>
                        </div>
                    </form>
                </div>
            @endif
            </div>
    </div>
@endsection