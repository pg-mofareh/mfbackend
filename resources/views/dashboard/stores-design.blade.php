@extends('dashboard.layout')
@section('title','لوحة التجكم | تصميم متجر')
@section('page','تصميم متجر')
@section('head')
    <style>
        .dropdown .btn::after {
            content: none;
        }
        .dropdown-menu-right {
            right: auto !important;
            left: 0 !important;
        }
    </style>
@endsection
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
                <div class="bg-white p-2 rounded">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>الحالة</th>
                                <th>التحقق</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($designs as $design)
                                <tr>
                                    <th>{{ $design->id }}</th>
                                    <td>{{ $design->name }}</td>
                                    <td>{{ $design->is_active==0?'غير نشط':'نشط' }}</td>
                                    <td>{{ $design->is_verify==0?'لم يتم التحقق':'تم التحقق' }}</td>
                                    <td class="d-flex justify-content-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-btn focus-none p-0"><li class="material-icons pt-2 text-dark hand">more_vert</li></button>
                                            <ul class="dropdown-menu dropdown-menu-right dropdown-menu-dark">
                                                <li><a class="dropdown-item text-center" href="{{ route('dashboard.stores.design.edit',['id'=>request()->route('id'),'design'=>$design->id]) }}">تعديل</a></li>
                                                <li><a class="dropdown-item text-center" href="{{ route('dashboard.stores.design.active',['id'=>request()->route('id'),'design'=>$design->id]) }}">@if($design->is_active==1){{'إيقاف'}}@elseif($design->is_active==0){{'تنشيط'}}@endif</a></li>
                                                <li><a class="dropdown-item text-center" href="{{ route('dashboard.stores.design.verify',['id'=>request()->route('id'),'design'=>$design->id]) }}">@if($design->is_verify==1){{'إلغاء التحقق'}}@elseif($design->is_verify==0){{'تحقق'}}@endif</a></li> 
                                                <li><a class="dropdown-item text-center" href="{{ route('dashboard.stores.design.view-store',['id'=>request()->route('id'),'design'=>$design->id]) }}" target="_blank">إستعراض المتجر</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('.dropdown-btn').on('click', function(){
                var dropdownMenu = $(this).closest('.dropdown').find('.dropdown-menu');
                $('.dropdown-menu').not(dropdownMenu).hide();
                dropdownMenu.toggle();
            });
            $(document).on('click', function(event){
                var dropdown = $('.dropdown');
                if(!dropdown.is(event.target) && dropdown.has(event.target).length === 0){
                    $('.dropdown-menu').hide();
                }
            });
        });
    </script>
@endsection