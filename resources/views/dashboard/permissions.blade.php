@extends('dashboard.layout')
@section('title','لوحة التجكم | الأذونات')
@section('page','الأذونات')
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
                    @can('permissions create')
                        <button class="btn btn-success btn-sm border-0 focus-none" onclick="createNew()">إضافة إذن</button>
                    @endcan
                </div>
                <div class="col-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الدور</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $permission)
                                <tr>
                                    <td>{{$permission->name}}</td>
                                    <td class="d-flex justify-content-end">
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false" >الخيارات</button>
                                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
                                                @can('permissions delete')<li><a class="dropdown-item" href="{{ route('dashboard.permissions.delete', ['id' => $permission->id]) }}">حذف</a></li>@endcan
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
            window.open("{{ route('dashboard.permissions.create') }}",'_self');
        }
    </script>
@endsection