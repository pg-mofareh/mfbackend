@extends('dashboard.layout')
@section('title','لوحة التجكم | التصميم')
@section('page','التصميم')
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
                        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-12 bg-white rounded p-3 mt-3 mb-3">
                    <div class="p-2">
                        <div class="d-flex justify-content-end">
                            <a class="btn text-warning btn-md fw-bold border-0 focus-none" href="{{ route('dashboard.design.template.create') }}">إضافة نموذج</a>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>النموذج</th>
                                    <th>الحالة</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                    <tr>
                                        <td>{{ $template->id }}</td>
                                        <td>{{ $template->name }}</td>
                                        <td>{{ $template->is_active=='1'?'متاح':'غير متاح' }}</td>
                                        <td class="d-flex justify-content-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-btn focus-none p-0"><li class="material-icons pt-2 text-dark hand">more_vert</li></button>
                                                <ul class="dropdown-menu dropdown-menu-right dropdown-menu-dark">
                                                    @can('design edit')<li><a class="dropdown-item text-center" href="{{ route('dashboard.design.template.edit',['id'=>$template->id]) }}">تعديل</a></li>@endcan
                                                    @can('design active')<li><a class="dropdown-item text-center" href="{{ route('dashboard.design.template.active',['id'=>$template->id]) }}">{{ $template->is_active=='1'?'إيقاف':'تنشيط' }}</a></li>@endcan         
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