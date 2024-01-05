@extends('stores.layout')
@section('title','لوحة التجكم | التصنيفات')
@section('page','التصنيفات')
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
                                    <button class="btn text-warning fw-bold btn-md border-0 focus-none" id="show_create_category_modal">إضافة تصنيف</button>
                                </div>
                                <table class="table table-striped mt-2">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>إسم التصنيف</th>
                                            <th>حالة التصنيف</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categories as $category) 
                                            <tr>
                                                <th>{{ $category->id }}</th>
                                                <td>{{ $category->name }}</td>
                                                <td>@if($category->is_active==0){{"غير نشط"}}@elseif($category->is_active==1){{"نشط"}}@endif</td>
                                                <td class="d-flex justify-content-end">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm dropdown-btn focus-none p-0"><li class="material-icons pt-2 text-dark hand">more_vert</li></button>
                                                        <ul class="dropdown-menu dropdown-menu-right dropdown-menu-dark">
                                                            <li><a class="dropdown-item active" href="{{ route('stores.dashboard.categories.edit', ['store'=>request()->route('store'),'id' => $category->id]) }}">تعديل</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('stores.dashboard.categories.active', ['store'=>request()->route('store'),'id' => $category->id]) }}">@if($category->is_active==1) إيقاف @elseif($category->is_active==0) تنشيط @endif</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('stores.dashboard.categories.delete', ['store'=>request()->route('store'),'id' => $category->id]) }}">حذف</a></li>
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

        <div class="modal" id="create_category_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body m-0 border-0">
                        <button type="button" class="btn btn-md text-dark focus-none btn-sm" data-bs-dismiss="modal"><li class="material-icons">reply</li></button>
                        <form action="{{ route('stores.dashboard.categories.create',['store'=>request()->route('store')]) }}" method="post">
                            @csrf
                            <input type="text" class="form-control focus-none" name="name" placeholder="إسم المتجر" value="{{ old('name') }}" />
                            <button class="btn btn-warning w-100 btn-sm focus-none border-0  mt-3">إنشاء تصنيف جديد</button>
                        </form>
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