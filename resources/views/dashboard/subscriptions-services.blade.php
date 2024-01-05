@extends('dashboard.layout')
@section('title','لوحة التجكم | إستعراض الإشتراك')
@section('page','إستعراض الإشتراك')
@section('body')
    <div class="container-fluid mt-4">
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
                <div class="col-12">
                    @if(Route::currentRouteNamed('dashboard.subscriptions.viewer'))
                        <div class="row">
                            <div class="col-md-4 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div class="fs-5 fw-normal ps-2">تفاصيل الباقة</div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> رقم الباقة: </label>
                                        <label>{{$package->id}}</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> عنوان الباقة: </label>
                                        <label>{{$package->title}}</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> وصف الباقة: </label>
                                        <label>{{$package->description}}</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> سعر الباقة الحالي : </label>
                                        <label>{{$package->price}}</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> خصم الباقة الحالي : </label>
                                        <label>{{$package->discount}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="fs-5 fw-normal ps-2">تفاصيل الإشتراك</div>
                                        <a href="{{ route('dashboard.subscriptions.editor',['id'=>$subscription->id]) }}" class="text-dark"><i class="material-icons">edit</i></a>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> الباقة: </label>
                                        <label>{{$subscription->title}}</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> طريقة الدفع: </label>
                                        <label>
                                            @if($subscription->payment_method=="bank_transfer")
                                                حوالة بنكية
                                            @endif
                                        </label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> سعر الباقة: </label>
                                        <label>{{$subscription->price}}</label>
                                        <label>ر.س</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> خصم الباقة: </label>
                                        <label>{{$subscription->discount_price}}</label>
                                        <label>ر.س</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> الضريبة : </label>
                                        <label>{{$subscription->tax}}</label>
                                        <label>ر.س</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> الخصم الخاص : </label>
                                        <label>{{$subscription->discount}}</label>
                                        <label>ر.س</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> المجموع : </label>
                                        <label>{{$subscription->total}}</label>
                                        <label>ر.س</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> يبدأ الإشتراك : </label>
                                        <label>{{$subscription->start_at}}</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> ينتهي الإشتراك : </label>
                                        <label>{{$subscription->end_at}}</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold"> حالة الإشتراك  : </label>
                                        <label>
                                            @if($subscription->status=="active")
                                                نشط
                                            @elseif($subscription->status=="disactive")
                                                غير نشط
                                            @endif
                                        </label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2 fw-bold">  ملاحظة : </label>
                                        <label>{{$subscription->note}}</label>
                                    </div>
                                    <div>
                                        <input type="hidden" name="subscription" value="{{ $subscription->id }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div class="fs-5 fw-normal ps-2">تفاصيل الحوالات</div>
                                    <div>
                                        @foreach($transfers as $transfer)
                                            <div class="p-2">
                                                <div class="border rounded p-2">
                                                    <div class="d-flex justify-content-between mt-2 mb-2">
                                                        <a href="{{ $transfer->image }}" target="_blank" class="text-dark"><i class="material-icons">image</i></a>
                                                        <a href="{{ route('dashboard.subscriptions.transfer.edit',['id'=>$transfer->id]) }}" class="text-dark"><i class="material-icons">edit</i></a>
                                                    </div>
                                                    <div>
                                                        <label class="fw-bold">تاريخ الرفع</label>
                                                        <label>{{ $transfer->created_at }}</label>
                                                    </div>
                                                    <div>
                                                        <label class="fw-bold">حالة الحوالة</label>
                                                        <label>
                                                        @if ($transfer->status == "new")
                                                            جديد
                                                        @elseif ($transfer->status == "accepted")
                                                            مقبول
                                                        @elseif ($transfer->status == "rejected")
                                                            مرفوض
                                                        @endif
                                                        </label>
                                                    </div>
                                                    @if($transfer->status=='new')
                                                        <img src="{{ $transfer->image }}" class="w-100" />
                                                        <div class="mt-3">
                                                            <form action="{{ route('dashboard.subscriptions.update-transfer-func',['id'=>$transfer->id]) }}" method="post">@csrf
                                                                <input type="hidden" name="transfer" value="{{ $transfer->id }}" />
                                                                <textarea class="form-control rounded-0 w-100 focus-none border-0" name="note" placeholder="هل تريد إضافة ملاحظة..." style="resize:none"></textarea>
                                                                <div class="d-flex justify-content-end mt-2">
                                                                    <button name="response" value="accepted" class="btn btn-success btn-sm focus-none ms-1">قبول</button>
                                                                    <button name="response" value="rejected" class="btn btn-danger btn-sm focus-none ">رفض</button> 
                                                                </div>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(Route::currentRouteNamed('dashboard.subscriptions.transfer.edit'))
                        <div class="row">
                            <div class="col-md-5 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div class="d-flex justify-content-end mt-2 mb-2">
                                        <a href="{{ $transfer->image }}" target="_blank" class="text-dark"><i class="material-icons">image</i></a>
                                    </div>
                                    <img src="{{ $transfer->image }}" class="w-100" />
                                    <div class="mt-3">
                                        <form action="{{ route('dashboard.subscriptions.transfer.edit-func',['id'=>$transfer->id]) }}" method="post">@csrf
                                            <input type="hidden" name="transfer" value="{{ $transfer->id }}" />
                                            <div>
                                                <label class="fw-bold">المبلغ:</label>
                                                <label>{{ $transfer->total }}</label>
                                                <label>ر.س</label>
                                            </div>
                                            <label class="fw-bold">ملاحظة:</label>
                                            <div class="border p-2 rounded mt-1">
                                                <textarea class="form-control rounded-0 w-100 focus-none border-0" name="note" placeholder="هل تريد إضافة ملاحظة..." style="resize:none">{{ $transfer->note }}</textarea>
                                                <button name="response" value="update_note" class="btn btn-warning btn-sm focus-none ms-1 mt-3 ps-3 pe-3 w-100">تحديث</button>
                                            </div>
                                            <div class="mt-2">
                                                <label class="fw-bold">حالة الحوالة</label>
                                                <label>
                                                    @if ($transfer->status == "new")
                                                        جديد
                                                    @elseif ($transfer->status == "accepted")
                                                        مقبول
                                                    @elseif ($transfer->status == "rejected")
                                                        مرفوض
                                                    @endif
                                                </label>
                                            </div>
                                            <div class="d-flex justify-content-end mt-2">
                                                @if ($transfer->status == "new")
                                                    <button name="response" value="accepted" class="btn btn-success btn-sm focus-none ms-1 ps-3 pe-3" @if($transfer->status == 'accepted') disabled @endif>قبول</button>
                                                    <button name="response" value="rejected" class="btn btn-danger btn-sm focus-none ps-3 pe-3" @if($transfer->status == 'rejected') disabled @endif>رفض</button> 
                                                @elseif ($transfer->status == "accepted")
                                                    <button name="response" value="rejected" class="btn btn-danger btn-sm focus-none ps-3 pe-3" @if($transfer->status == 'rejected') disabled @endif>رفض</button> 
                                                @endif
                                            </div>
                                            <div class="mt-2">
                                                <label class="fw-bold">تاريخ الرفع</label>
                                                <label>{{ $transfer->created_at }}</label>
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(Route::currentRouteNamed('dashboard.subscriptions.editor'))
                        <div class="row">
                            <div class="col-md-4 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div>تحديث تاريخ الإشتراك</div>
                                    <form action="{{ route('dashboard.subscriptions.update-date',['id'=>request()->route('id')]) }}" method="post">@csrf
                                        <label class="text-muted mt-2">يبدأ</label>
                                        <input type="datetime-local" name="start_at" value="{{ $subscription->start_at }}" class="form-control mt-1 focus-none"/>
                                        <label class="text-muted mt-2">ينتهي</label>
                                        <input type="datetime-local" name="end_at" value="{{ $subscription->end_at }}" class="form-control mt-1 focus-none"/>
                                        <input type="hidden" name="id" value="{{ request()->route('id') }}" />
                                        <button class="btn btn-warning focus-none mt-3 w-100">تحديث تاريخ الإشتراك</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-4 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div>تحديث تاريخ الإشتراك</div>
                                    <form action="{{ route('dashboard.subscriptions.update-payment',['id'=>request()->route('id')]) }}" method="post">@csrf

                                        <label class="text-muted mt-2">سعر الباقة</label>
                                        <input type="number" name="price" value="{{ $subscription->price }}" class="form-control mt-1 focus-none arrow-none"/>
                                        <label class="text-muted mt-2">خصم الباقة</label>
                                        <input type="number" name="discount_price" value="{{ $subscription->discount_price }}" class="form-control mt-1 focus-none arrow-none"/>
                                        <label class="text-muted mt-2">الضريبة</label>
                                        <input type="number" name="tax" value="{{ $subscription->tax }}" class="form-control mt-1 focus-none arrow-none"/>
                                        <label class="text-muted mt-2">الخصم الخاص</label>
                                        <input type="number" name="discount" value="{{ $subscription->discount }}" class="form-control mt-1 focus-none arrow-none"/>
                                        <label class="text-muted mt-2">المجموع</label>
                                        <input type="number" name="total" value="{{ $subscription->total }}" class="form-control mt-1 focus-none arrow-none"/>

                                        <input type="hidden" name="id" value="{{ request()->route('id') }}" />
                                        <button class="btn btn-warning focus-none mt-3 w-100">تحديث بيانات الدفع</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-4 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div>تحديث حالة الإشتراك</div>
                                    <form action="{{ route('dashboard.subscriptions.update-status',['id'=>request()->route('id')]) }}" method="post">@csrf
                                        <label class="text-muted mt-2">@if($subscription->status=="active"){{ 'هذا الإشتراك نشط' }}@else{{ 'هذا الإشتراك غير نشط' }}@endif</label>
                                        <input type="hidden" name="id" value="{{ request()->route('id') }}" />
                                        <button class="btn focus-none mt-4 w-100 @if($subscription->status=='active'){{ 'btn-danger' }}@else{{ 'btn-success' }}@endif">@if($subscription->status=="active"){{ 'إيقاف' }}@else{{ 'تنشيط' }}@endif</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-4 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div>الملاحظة</div>
                                    <form action="{{ route('dashboard.subscriptions.update-note',['id'=>request()->route('id')]) }}" method="post">@csrf
                                        <textarea class="form-control focus-none mt-2 border-0" style="resize:none" name="note" placeholder="هل لديك ملاحظة...">{{ $subscription->note }}</textarea>
                                        <input type="hidden" name="id" value="{{ request()->route('id') }}" />
                                        <button class="btn btn-warning focus-none mt-4 w-100">تحديث</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
        </div>
    </div>
@endsection