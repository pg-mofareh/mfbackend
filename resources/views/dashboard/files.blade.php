@extends('dashboard.layout')
@section('title','لوحة التجكم | الملفات')
@section('page','الملفات')
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
            <div class="row mt-4">
                <div class="col-12">
                    <div class="row">

                        <div class="col-12 bg-white rounded mt-3"> 
                            <div class="p-2">
                                <div class="d-flex justify-content-end">
                                    <button class="btn text-warning fw-bold btn-md border-0 focus-none" id="show_create_image_modal">إضافة ملف</button>
                                </div>
                                <table class="table table-borderless mt-2">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($files as $file) 
                                            <tr class="border-top">
                                                <td style="width: 50px;">
                                                    <img src="{{$file->image}}" class="rounded" style="width:35px;height:35px" />
                                                </td>
                                                <td>{{ $file->name }}.{{ $file->extension }}</td>
                                                <td class="d-flex justify-content-end">
                                                    <button class="btn btn-sm focus-none p-0 tr-menu"><li class="material-icons pt-2 text-dark hand">keyboard_arrow_down</li></button>
                                                </td>
                                            </tr>
                                            <tr class="border-bottom tr-box-menu" style="display:none;">
                                                <td colspan="4">
                                                    <form action="{{route('dashboard.files.delete-pfile')}}" method="post">
                                                        @csrf
                                                        <div class="alert alert-danger d-flex justify-content-between p-2" role="alert">
                                                            <label>حذف الصورة</label>
                                                            <input type="hidden" name="id" value="{{$file->id}}"/>
                                                            <button type="submit" class='btn btn-danger btn-sm border-0 focus-none'>حذف</button>
                                                        </div>
                                                    </form>
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

        <div class="modal" id="create_image_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body m-0 border-0">
                        <button type="button" class="btn btn-md text-dark focus-none btn-sm" data-bs-dismiss="modal"><li class="material-icons">reply</li></button>
                        <form action="{{ route('dashboard.files.create-pfile') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <textarea rows="2" class="form-control focus-none mt-3" name="title" placeholder="عنوان للصورة (إختياري)" style="resize:none"></textarea>
                            <input type="file" class="form-control focus-none mt-2" name="file" accept="image/*" />
                            <button class="btn btn-warning w-100 btn-sm focus-none border-0  mt-3">رفع الملف</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('script')
    <script>

        $(document).ready(function () {

            $('#show_create_image_modal').on('click', function() {
                $('#create_image_modal').modal('show');
            });


            $(".tr-menu").click(function () {
                var clickedRowMenu = $(this).closest('tr').next('.tr-box-menu');
                var otherElements = $(".tr-box-menu").not(clickedRowMenu);
                var numberOfOtherElements = otherElements.length;
                if(numberOfOtherElements>0){
                    otherElements.slideUp();
                }                
                clickedRowMenu.slideToggle();
            });


        });

        
    </script>
@endsection