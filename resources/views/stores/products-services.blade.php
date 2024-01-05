@extends('stores.layout')
@section('title','لوحة التجكم | المنتجات')
@section('page','المنتجات')
@section('store_name',$store_name)
@section('user_name',$user_name)
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
        <div class="row">
            @if(Route::currentRouteNamed('stores.dashboard.products.create'))
                <div class="col-12">
                    <form action="{{ route('stores.dashboard.products.create-func',['store'=>request()->route('store')]) }}" method="post">
                        @csrf
                        <div class="w-100">
                            <select name="category" class="form-control mt-3 focus-none">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-100">
                            <input type='text' name="name" class="form-control mt-3 focus-none" placeholder="إسم المنتج" value="{{ old('name') }}" />
                        </div>
                        <div class="w-100">
                            <textarea name="description" class="form-control mt-3 focus-none" rows="2" style="resize:none" placeholder="وصف المنتج">{{ old('description') }}</textarea>
                        </div>
                        <div class="w-100">
                            <input type='number' name="price" class="form-control mt-3 focus-none placeholder-right arrow-none" dir='ltr' placeholder="سعر المنتج" value="{{ old('price') }}" />
                        </div> 
                        <div class="w-100">
                            <input type='number' name="discount" class="form-control mt-3 focus-none placeholder-right arrow-none" dir='ltr' placeholder="الخصم" value="{{ old('discount') }}" />
                        </div>
                        <div class="w-100">
                            <input type='number' name="calories" class="form-control mt-3 focus-none placeholder-right arrow-none" dir='ltr' placeholder="السعرات الحرارية" value="{{ old('calories') }}" />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>منتج جديد</button>
                        </div>
                    </form>
                </div>
            @elseif(Route::currentRouteNamed('stores.dashboard.products.active'))
                <div class="col-12">
                    <form action="{{ route('stores.dashboard.products.active-func',['store'=>request()->route('store'),'id'=>request()->route('id')]) }}" method="post">
                        @csrf
                        <div class="alert alert-success" role="alert">هل أنت متأكد من من تحديث حالة هذا المنتج</div>
                        <div class="d-flex justify-content-end mt-4">
                            <input type='hidden' name="id" value="{{$product->id}}" />
                            <input type='hidden' name="status" value="@if($product->is_active==0){{1}}@else{{0}}@endif" />
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تحديث حالة المنتج</button>
                        </div>
                    </form>
                </div>
            @elseif(Route::currentRouteNamed('stores.dashboard.products.delete'))
                <div class="col-12">
                    <form action="{{ route('stores.dashboard.products.delete-func',['store'=>request()->route('store'),'id'=>request()->route('id')]) }}" method="post">
                        @csrf
                        <div class="alert alert-danger" role="alert">هل أنت متأكد من من حذف هذا المنتج</div>
                        <div class="d-flex justify-content-end mt-4">
                            <input type='hidden' name="id" value="{{$product->id}}" />
                            <button type="submit" class='btn btn-danger btn-sm border-0 focus-none'>حذف المنتج</button>
                        </div>
                    </form>
                </div>
            @elseif(Route::currentRouteNamed('stores.dashboard.products.edit'))
                @if($product->image!==null)
                    <div class="col-12">
                        <form action="{{ route('stores.dashboard.products.edit-removeimage',['store'=>request()->route('store'),'id'=>request()->route('id')]) }}" method="post" class="d-flex justify-content-between align-items-bottom border rounded p-2 bg-light">
                            <div class="flex-shrink-0 me-1 ms-1 hand">
                                <img src="{{ env('APP_URL') }}{{$product->image}}" class="rounded" width="118" />
                            </div>
                            @csrf
                            <input type="hidden" name="id" value="{{$product->id}}"/>
                            <button type="submit" class="btn btn-danger focus-none btn-sm border-2 ps-1 pe-1" style="border-style:groove;"><li class="material-icons">remove</li></button>
                        </form>
                    </div>
                @endif
                @if($product->image==null)

                @endif
                <div class="col-12">
                    <form action="{{ route('stores.dashboard.products.edit-func',['store'=>request()->route('store'),'id'=>request()->route('id')]) }}" method="post">
                        @csrf
                        <div class="w-100">
                            <select name="category" class="form-control mt-3 focus-none">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-100">
                            <input type='text' name="name" class="form-control mt-3 focus-none" placeholder="إسم المنتج" value="{{$product->name}}" />
                        </div>
                        <div class="w-100">
                            <textarea name="description" class="form-control mt-3 focus-none" rows="2" style="resize:none" placeholder="وصف المنتج">{{$product->description}}</textarea>
                        </div>
                        <div class="w-100">
                            <input type='number' name="price" class="form-control mt-3 focus-none placeholder-right arrow-none" dir='ltr' placeholder="سعر المنتج" value="{{$product->price}}" />
                        </div> 
                        <div class="w-100">
                            <input type='number' name="discount" class="form-control mt-3 focus-none placeholder-right arrow-none" dir='ltr' placeholder="الخصم" value="{{$product->discount}}" />
                        </div>
                        <div class="w-100">
                            <input type='number' name="calories" class="form-control mt-3 focus-none placeholder-right arrow-none" dir='ltr' placeholder="السعرات الحرارية" value="{{$product->calories}}" />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <input type='hidden' name="id" value="{{$product->id}}" />
                            <button type="submit" class='btn btn-success btn-sm border-0 focus-none'>تعديل المنتج</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection