@extends('dashboard.layout')
@section('title','لوحة التجكم | الكوبونات')
@section('page','الكوبونات')
@section('body')
    <div class="container-fluid mt-5">
        @if (session('success'))
            <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                    </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="row mt-1">
                <div class="col-12">
                    <ul class="text-danger p-0" style="list-style: none;">
                    @foreach($errors->all() as $error)
                        <li>
                            <div class="alert alert-danger p-2 m-1" role="alert">{{ $error }}</div>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="row">
            @if(Route::currentRouteNamed('dashboard.coupons.create'))
                <div class="col-12">
                    <form action="{{route('dashboard.coupons.create-func')}}" method="post">
                        @csrf
                        <div class="w-100">
                            <input type='text' name="user" class="form-control mt-3 focus-none" placeholder="رقم أو إيميل مالك الكوبون" />
                        </div>
                        <div class="w-100">
                            <input type='text' name="code" class="form-control mt-3 focus-none" placeholder="كود الكوبون" />
                        </div>
                        <div class="w-100">
                            <input type='number' name="discount_percent" class="form-control mt-3 focus-none" placeholder="نسبة الخصم" />
                        </div>
                        <div class="w-100">
                            <input type='datetime-local' name="started" class="form-control mt-3 focus-none placeholder-right" dir='ltr' placeholder="بداية فعالية الكوبون" />
                        </div>
                        <div class="w-100">
                            <input type='datetime-local' name="expiry" class="form-control mt-3 focus-none placeholder-right" dir='ltr' placeholder="نهاية فعالية الكوبون" />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>كوبون جديد</button>
                        </div>
                    </form>
                </div>
            @elseif(Route::currentRouteNamed('dashboard.coupons.edit'))
                <div class="col-12">
                    <form action="{{route('dashboard.coupons.edit-func',['id'=>$coupon->id])}}" method="post">
                        @csrf
                        <div class="w-100">
                            <input type='text' name="user" disabled value="{{$coupon->user}}" class="form-control mt-3 focus-none" placeholder="رقم أو إيميل مالك الكوبون" />
                        </div>
                        <div class="w-100">
                            <input type='text' name="code" value="{{$coupon->code}}" class="form-control mt-3 focus-none placeholder-right" dir="ltr" placeholder="كود الكوبون" />
                        </div>
                        <div class="w-100">
                            <input type='number' name="discount_percent" class="form-control mt-3 focus-none" placeholder="نسبة الخصم" />
                        </div>
                        <div class="w-100">
                            <input type='datetime-local' name="started" value="{{$coupon->started}}" class="form-control mt-3 focus-none placeholder-right" dir='ltr' placeholder="بداية فعالية الكوبون" />
                        </div>
                        <div class="w-100">
                            <input type='datetime-local' name="expiry" value="{{$coupon->expiry}}" class="form-control mt-3 focus-none placeholder-right" dir='ltr' placeholder="نهاية فعالية الكوبون" />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <input type="hidden" name="id" value="{{$coupon->id}}" />
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تحديث الكوبون</button>
                        </div>
                    </form>
                </div>
            @elseif(Route::currentRouteNamed('dashboard.coupons.active'))
                <div class="col-12">
                        <form action="{{route('dashboard.coupons.active-func',['id'=>$coupon->id])}}" method="post">
                            @csrf
                            <div class="alert alert-success" role="alert">هل أنت متأكد من من تحديث حالة هذا الكوبون</div>
                            <div class="d-flex justify-content-end mt-4">
                                <input type='hidden' name="id" value="{{$coupon->id}}" />
                                <input type='hidden' name="status" value="@if($coupon->is_active==0){{1}}@else{{0}}@endif" />
                                <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تحديث حالة الكوبون</button>
                            </div>
                        </form>
                </div>
            @elseif(Route::currentRouteNamed('dashboard.coupons.delete'))
                <div class="col-12">
                    <form action="{{route('dashboard.coupons.delete-func',['id'=>$coupon->id])}}" method="post">
                        @csrf
                        <div class="alert alert-danger" role="alert">هل أنت متأكد من حذف هذا الكوبون</div>
                        <div class="d-flex justify-content-end mt-4">
                            <input type='hidden' name="id" value="{{$coupon->id}}" />
                            <button type="submit" class='btn btn-danger btn-sm border-0 focus-none'>حذف الكوبون</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

@endsection
