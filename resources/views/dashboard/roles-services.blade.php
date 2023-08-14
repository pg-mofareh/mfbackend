<!DOCTYPE html>
<html lang="ar">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard roles services</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ URL::to('/') }}{{ env('APP_PUBLIC') }}/custom-style.css" rel="stylesheet">
</head>
<body dir="rtl" class="p-0">
    <x-dashboard.cpanel-1 page="{{__('dashboard.pages-name.roles')}}">
        <div class="container-fluid mt-5">
            @if (session('success'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                    </div>
                </div>
            @endif
            <div class="row">
                @if(Route::currentRouteNamed('dashboard.roles.create'))
                    <div class="col-12">
                        <form action="{{route('dashboard.roles.create-func')}}" method="post">
                            @csrf
                            <div class="w-100">
                                <input type='text' name="name" class="form-control mt-3 focus-none placeholder-right" dir='ltr' placeholder="أضف الإسم هنا" />
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>دور جديد</button>
                            </div>
                        </form>
                    </div>
                @elseif(Route::currentRouteNamed('dashboard.roles.delete'))
                    <div class="col-12">
                        <form action="{{route('dashboard.roles.delete-func',['id'=>$role->id])}}" method="post">
                            @csrf
                            <div class="alert alert-danger" role="alert">هل أنت متأكد من حذف هذا الدور</div>
                            <div class="d-flex justify-content-end mt-4">
                                <input type='hidden' name="id" value="{{$role->id}}" />
                                <button type="submit" class='btn btn-danger btn-sm border-0 focus-none'>حذف الدور</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
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
        </div>
        
    </x-dashboard.cpanel-1>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
</body>
</html>