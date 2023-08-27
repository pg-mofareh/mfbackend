@extends('dashboard.layout')
@section('title','لوحة التجكم | المستخدمين')
@section('page','المستخدمين')
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
                    @can('users create')
                    <button class="btn btn-success btn-sm border-0 focus-none" onclick="createNew()">إضافة مستخدم</button>
                    @endcan
                </div>
                <div class="col-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الإسم</th>
                                <th>الإيميل</th>
                                <th>الدور</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <label class="p-1 ps-2 pe-2 hand bg-success ms-1 me-1 text-white rounded">{{ $role->name }}</label>
                                        @endforeach
                                    </td>
                                    <td class="d-flex justify-content-end">
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false" >الخيارات</button>
                                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
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
@endsection
@section('script')
    <script>
        function createNew(){
            window.open("{{ route('dashboard.users.create') }}",'_self');
        }
    </script>
@endsection