@extends('stores.layout')
@section('title','لوحة التجكم | المنتجات')
@section('page','المنتجات')
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
        <div class="row mt-3">
            <div class="col-12">
                <div class="row">

                        <div class="col-12 bg-white rounded "> 
                            <div class="p-2">
                                <div class="d-flex justify-content-end">
                                    <a class="btn text-warning fw-bold btn-md border-0 focus-none" href="{{ route('stores.dashboard.products.create', ['store'=>request()->route('store')]) }}">إضافة منتج</a>
                                </div>
                                <table class="table table-striped mt-2">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>إسم المنتج</th>
                                            <th>تصنيف المنتج</th>
                                            <th>السعر</th>
                                            <th>حالة المنتج</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $product) 
                                            <tr>
                                                <th>{{ $product->id }}</th>
                                                <td>{{ $product->name }}</td>
                                                <td class="text-primary">#{{$product->category}}</td>
                                                <td>
                                                    @if($product->discount_price !== null && $product->discount_price !== 0)
                                                        <label class="text-danger line-through ms-2">{{ $product->price }} ر.س</label><label>{{ $product->price - $product->discount_price }}</label><label>ر.س</label>
                                                    @else
                                                        <label>{{ $product->price }}</label><label>ر.س</label>
                                                    @endif
                                                </td>
                                                <td>@if($product->is_active==0){{"غير نشط"}}@elseif($product->is_active==1){{"نشط"}}@endif</td>
                                                <td class="d-flex justify-content-end">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm dropdown-btn focus-none p-0"><li class="material-icons pt-2 text-dark hand">more_vert</li></button>
                                                        <ul class="dropdown-menu dropdown-menu-right dropdown-menu-dark">
                                                            <li><a class="dropdown-item active" href="{{ route('stores.dashboard.products.edit', ['store'=>request()->route('store'),'id' => $product->id]) }}">تعديل</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('stores.dashboard.products.active', ['store'=>request()->route('store'),'id' => $product->id]) }}">@if($product->is_active==1) إيقاف @elseif($product->is_active==0) تنشيط @endif</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('stores.dashboard.products.delete', ['store'=>request()->route('store'),'id' => $product->id]) }}">حذف</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    
                                </div>
                            </div>
                        </div>
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

            $('#show_create_category_modal').on('click', function() {
                $('#create_category_modal').modal('show');
            });
        });
    </script>
@endsection