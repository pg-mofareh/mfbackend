@extends('stores.layout')
@section('title', 'لوحة التحكم | إدارة الإشتراكات')
@section('page', 'إدارة الإشتراكات')
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
            <div class="col-12">
                <div class="row">
                    @foreach($subscriptions as $subscription)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0 hand">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $subscription->title }}</h5>
                                    <p class="card-text">{{ $subscription->description }}</p>
                                    <div class="d-flex">
                                        @if($subscription->discount_price !== null && $subscription->discount_price !== 0)
                                            <label class="text-danger line-through ms-2">{{ $subscription->price }} ر.س</label><label>{{ $subscription->price - $subscription->discount_price }}</label><label>ر.س</label>
                                        @else
                                            <label>{{ $subscription->price }}</label><label>ر.س</label>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        @if($subscriptionable)
                                        <a href="{{ route('stores.dashboard.subscriptions.create',['store'=>request()->route('store'),'id'=>$subscription->id]) }}" class="btn btn-warning btn-sm focus-none">إشتراك</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if($mysubscriptions)
            <div class="col-12 p-2 mt-4">
                <div class="bg-white p-3 rounded">
                    <div class="fs-6 fw-bold ">قائمة إشتراكاتي</div>
                    <table class="table mt-2">
                        <thead>
                            <tr>
                                <th class="text-muted">بداية الإشتراك</th>
                                <th class="text-muted">نهاية الإشتراك</th>
                                <th class="text-muted">سعر الإشتراك</th>
                                <th class="text-muted">الضريبة</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mysubscriptions as $subscription)
                                <tr>
                                    <td class="d-flex1"><label class="ms-1">{{ $subscription->start_at }}</td>
                                    <td class="d-flex1"><label class="ms-1">{{ $subscription->end_at }}</td>
                                    <td class="d-flex1"><label class="ms-1">{{ $subscription->total }}</label><label>ر.س</label></td>
                                    <td class="d-flex1"><label class="ms-1">{{ $subscription->tax }}</label><label>ر.س</label></td>
                                    <td class="text-start">
                                        @if($subscription->transfer_action=="upload")
                                            <a class="btn btn-warning btn-sm focus-none" href="{{ route('stores.dashboard.subscriptions.uploadtransfer',['store'=>request()->route('store'),'id'=>$subscription->id]) }}">رفع الحوالة</a>
                                        @elseif($subscription->transfer_action=="new")
                                            <label class="text-success">جاري التحقق من الحوالة</label>
                                        @elseif($subscription->transfer_action=="accepted")
                                            <label class="text-dark">تم التحقق</label>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
