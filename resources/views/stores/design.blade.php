@extends('stores.layout')
@section('title','لوحة التجكم | التصميم')
@section('page','التصميم')
@section('store_name',$store_name)
@section('user_name',$user_name)
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
    </div>

        <div class="row">
            <div class="col-12 mb-4 mt-2 text-start">
                @if(isset($qr_code))
                    <a href="{{ route('stores.dashboard.design.card-download',['store'=>request()->route('store'),'id'=>'1']) }}" class="btn btn-warning focus-none btn-sm">تحميل بطاقة QrCode</a>
                @else
                    <label class="bg-light p-2 rounded">قم بطلب إنشاء QrCode من المشرف</label>
                @endif
            </div>
            <div class="col-12">
                <div class="d-flex overflow-auto ">
                    @foreach($templates as $template)
                        <div class="col-10 col-md-3 mb-4 ms-3 me-3">
                            <div class="card shadow border-0 hand">
                                <div class="card-body">
                                    <div class="card-header p-0 rounded-top">
                                        @if($template->image!==null)
                                            <img src="{{ $template->image }}" class="w-100 img-height rounded-top" />
                                        @else
                                            <div class="w-100 img-height rounded-top text-muted d-flex justify-content-center align-items-center">لاتوجد أيقونة</div>
                                        @endif
                                    </div>
                                    <h5 class="card-title">{{ $template->title }}</h5>
                                    <p class="card-text">{{ $template->description }}</p>
                                    <div class="d-flex justify-content-end">
                                        <form action="{{ route('stores.dashboard.design.add', ['store'=>request()->route('store'),'id'=>$template->id]) }}" method="post">@csrf
                                            <input type="hidden" name="template" value="{{ $template->id }}" />
                                            <button class="btn btn-warning btn-sm focus-none">إختيار</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
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
                                                <li><a class="dropdown-item active" href="{{ route('stores.dashboard.design.edit', ['store'=>request()->route('store'),'id' => $design->id]) }}">تعديل</a></li>
                                                <li><a class="dropdown-item" href="{{ route('stores.dashboard.design.active', ['store'=>request()->route('store'),'id' => $design->id]) }}">@if($design->is_active==1){{'إيقاف'}}@elseif($design->is_active==0){{'تنشيط'}}@endif</a></li>
                                                <li><a class="dropdown-item" href="{{ route('stores.dashboard.design.view-store', ['store'=>request()->route('store'),'id' => $design->id]) }}" taget="_blank">إستعراض</a></li>
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

            $('#show_create_category_modal').on('click', function() {
                $('#create_category_modal').modal('show');
            });
        });
    </script>
@endsection