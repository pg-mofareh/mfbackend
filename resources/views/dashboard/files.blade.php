@extends('dashboard.layout')
@section('title','لوحة التجكم | إدارة الملفات')
@section('page','إدارة الملفات')
@section('head')
    <style>
        .dropdown .btn::after {
            content: none;
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
            @if ($errors->any())
                <div class="row mt-3">
                    <div class="col-12">
                    <ul class="text-danger" style="list-style: none;">
                    @foreach($errors->all() as $error)
                        <li>
                            <div class="alert alert-danger p-2 m-1" role="alert">{{ $error }}</div>
                        </li>
                    @endforeach
                    </ul>
                    </div>
                </div>
            @endif
            <div class="row" dir="ltr">
                <div class="col-12 d-flex justify-content-end">
                    @can('files create')
                        <button class="btn btn-warning btn-sm border-0 focus-none" onclick="createNew()">إضافة ملف أو مجلد</button>
                    @endcan
                </div>
                <div class="col-10 d-flex text-dark fw-bold">
                    Files ManageMent:
                    <div class="ms-2 fw-normal">
                        @foreach($directories_line as $key => $directory)
                            @if($loop->first)
                             <span class="text-muted hand">{{ $directory }}</span>
                            @else
                                /<span class="text-muted hand">{{ $directory }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-2 text-dark fw-bold text-end">
                    @if(count($directories_line)>0)
                    <li class="material-icons pt-2 text-muted hand" onclick="replyDir()">reply</li>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-hover" dir="ltr">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contents as $content)
                                <tr class="hand" subdir="{{$content['name']}}" ondblclick="@if($content['type']=='folder') openDir(this) @endif">
                                    <td style="width:35px">@if($content['type']=="folder") <li class="material-icons pt-2 text-warning hand">folder</li> @elseif($content['type']=="file") <li class="material-icons pt-2 text-muted hand">article</li> @endif</td>
                                    <td class="pt-3">@if($content['type']=="folder") {{ $content['name'] }} @elseif($content['type']=="file") {{ $content['name'] }}.{{ $content['extension'] }} @endif</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle focus-none p-0 m-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false" ><li class="material-icons pt-2 text-muted hand">more_vert</li></button>
                                            <ul class="dropdown-menu dropdown-menu-white" aria-labelledby="dropdownMenuButton2">
                                                @if($content['type']=="file")<li class="mt-1"><a class="dropdown-item active bg-secondary hand" href="{{$content['path']}}" target="_black">إستعراض</a></li>@endif
                                                @if($content['type']=="file")<li class="mt-1"><a class="dropdown-item active bg-danger hand" onclick="deleteFile('{{$content['name'].'.'.$content['extension']}}')">حذف</a></li>@endif
                                                @if($content['type']=="folder")<li class="mt-1"><a class="dropdown-item active bg-danger hand" onclick="deleteFolder('{{$content['name']}}')">حذف</a></li>@endif
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
        function openDir(element){
            var subdir = element.getAttribute('subdir');
            var directories = "@foreach($directories_line as $directory){{ $directory }}/@endforeach";
            window.open("{{ route('dashboard.files') }}/"+directories+subdir,'_self');
        }

        function replyDir(){
            window.open("{{ route('dashboard.files') }}/{{ $reply }}",'_self');
        }

        function createNew(){
            window.open("{{ route('dashboard.files.create') }}{{$directoryPath}}",'_self');
        }

        function deleteFile(file){
            window.open("{{ route('dashboard.files.delete') }}{{$directoryPath}}?file="+file,'_self');
        }
        function deleteFolder(folder){
            window.open("{{ route('dashboard.files.delete') }}{{$directoryPath}}?folder="+folder,'_self');
        }
    </script>
@endsection