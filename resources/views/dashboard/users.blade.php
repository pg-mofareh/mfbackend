@extends('dashboard.layout')
@section('title','لوحة التجكم | المستخدمين')
@section('page','المستخدمين')
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
                <div class="col-12 d-flex justify-content-end">
                    
                </div>
                <div class="col-12 bg-white rounded p-3 mt-3">
                    <div class="p-2">
                        <div class="d-flex justify-content-end">
                            @can('users create')
                                <a class="btn text-warning btn-md fw-bold border-0 focus-none" href="{{ route('dashboard.users.create') }}">إضافة مستخدم</a>
                            @endcan
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الإسم</th>
                                    <th>الإيميل</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->first_name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td class="d-flex justify-content-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-btn focus-none p-0"><li class="material-icons pt-2 text-dark hand">more_vert</li></button>
                                                <ul class="dropdown-menu dropdown-menu-right dropdown-menu-dark">
                                                    @can('users view')<li><a class="dropdown-item active" href="{{ route('dashboard.users.view', ['id' => $user->id]) }}">إستعراض</a></li>@endcan
                                                    @can('users edit')<li><a class="dropdown-item" href="{{ route('dashboard.users.edit', ['id' => $user->id]) }}">تعديل</a></li>@endcan
                                                    @can('users roles')<li><a class="dropdown-item" href="{{ route('dashboard.users.roles', ['id' => $user->id]) }}">الأدوار</a></li>@endcan
                                                    @can('users permissions')<li><a class="dropdown-item" href="{{ route('dashboard.users.permissions', ['id' => $user->id]) }}">الأذونات</a></li>@endcan
                                                    @can('users delete')<li><a class="dropdown-item" href="{{ route('dashboard.users.delete', ['id' => $user->id]) }}">حذف</a></li>@endcan
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