@extends('stores.layout')
@section('title', 'لوحة التحكم | إدارة الإشتراكات')
@section('page', 'إدارة الإشتراكات')
@section('store_name',$store_name)
@section('user_name',$user_name)
@section('head')
    <style>
        #image_box {
            width: 100%;
            height: auto;
        }
    </style>
@endsection
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
            @if(Route::currentRouteNamed('stores.dashboard.subscriptions.create'))
                <div class="col-12">
                    <form action="{{ route('stores.dashboard.subscriptions.create-func',['store'=>request()->route('store'),'id'=>$subscription->id]) }}" method="post">@csrf
                        <div class="row">
                            <div class="col-md-6 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div class="fs-5 fw-normal ps-2">تفاصيل الإشتراك</div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2"> الباقة: </label>
                                        <label>{{$subscription->title}}</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2">سعر الإشتراك : </label>
                                        @if($subscription->discount_price !== null && $subscription->discount_price !== 0)
                                            <label class="text-danger line-through ms-2">{{ $subscription->price }} ر.س</label><label>{{ $subscription->price - $subscription->discount_price }}</label><label>ر.س</label>
                                            <input type="hidden" name="total" value="{{ $subscription->price - $subscription->discount_price }}" />
                                        @else
                                            <label>{{ $subscription->price }}</label><label>ر.س</label>
                                            <input type="hidden" name="total" value="{{ $subscription->price }}" />
                                        @endif
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2">الضريبة : </label>
                                            <label>
                                                @if($subscription->discount_price !== null && $subscription->discount_price !== 0)
                                                    {{ ($subscription->price - $subscription->discount_price)* 0.15 }}
                                                    <input type="hidden" name="tax" value="{{ ($subscription->price - $subscription->discount_price)* 0.15 }}" />
                                                @else
                                                    {{ $subscription->price * 0.15 }}
                                                    <input type="hidden" name="tax" value="{{ $subscription->price * 0.15 }}" />
                                                @endif
                                            </label>
                                            <label>ر.س</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2">المجموع : </label>
                                        @if($subscription->discount_price !== null && $subscription->discount_price !== 0)
                                            <label>{{ $subscription->price - $subscription->discount_price }}</label><label>ر.س</label>
                                            <input type="hidden" name="total" value="{{ $subscription->price - $subscription->discount_price }}" />
                                        @else
                                            <label>{{ $subscription->price }}</label><label>ر.س</label>
                                            <input type="hidden" name="total" value="{{ $subscription->price }}" />
                                        @endif
                                    </div>
                                    <div>
                                        <input type="hidden" name="price" value="{{ $subscription->price }}" />
                                        <input type="hidden" name="discount_price" value="{{ $subscription->discount_price ? $subscription->discount_price : 0 }}" />
                                        <input type="hidden" name="discount" value="0.00" />
                                        <input type="hidden" name="subscription" value="{{ $subscription->id }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 order-3 p-2 " name="payment">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div class="fs-5 fw-normal pe-2">طريقة الدفع</div>
                                    <div class="row mt-2 ps-3 pe-3">
                                        <div class="col-md-6 p-2">
                                            <label class="w-100 border rounded hand bg-light p-3 pt-2 pb-2">
                                                <input type="radio" name="payment_method" value="mada" disabled>
                                                <div class="p-2 d-flex justify-content-center " style="height:60px">
                                                    <img src="/images/icons/mada-ic.jpg" class="h-100" />
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <label class="w-100 border rounded hand bg-light p-3 pt-2 pb-2">
                                                <input type="radio" name="payment_method" value="visa" disabled>
                                                <div class="p-2 d-flex justify-content-center " style="height:60px">
                                                    <img src="/images/icons/visa-ic.png" class="h-100" />
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <label class="w-100 border rounded hand bg-light p-3 pt-2 pb-2">
                                                <input type="radio" name="payment_method" value="bank_transfer" checked>
                                                <div class="p-2 d-flex justify-content-center text-dark fs-5 fw-bold" style="height:60px">
                                                    حوالة بنكية 
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <label class="w-100 border rounded hand bg-light p-3 pt-2 pb-2">
                                                <input type="radio" name="payment_method" value="apple_pay" disabled>
                                                <div class="p-2 d-flex justify-content-center " style="height:60px">
                                                    <img src="/images/icons/apple-pay-ic.png" class="h-100" />
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 order-2 p-2 " name="payment">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div class="fs-5 fw-normal pe-2">مدة الإشتراك</div>
                                    <div class="row mt-2 ps-3 pe-3">
                                        <div class="col-12">
                                            <select class="form-control focus-none" id="subscription_months" name="months">
                                                <option value="1">شهر</option>
                                                <option value="2">شهرين</option>
                                                <option value="3">ثلاثة أشهر</option>
                                            </select>
                                            <label class="mt-3">يبدأ الإشتراك من:</label>
                                            <input type="datetime-local" class="form-control mt-2" id="subscriptionDate" name="start_at" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 order-4 p-2 mt-4" name="payment">
                                <div class="bg-white rounded shadow-sm p-3 d-flex justify-content-between">
                                    <div class="d-flex text-dark fw-bold">
                                        <label class="ms-2">المجموع : </label>
                                        @if($subscription->discount_price !== null && $subscription->discount_price !== 0)
                                            <label id="label_total">{{ $subscription->price - $subscription->discount_price }}</label><label>ر.س</label>
                                            <input type="hidden" name="total" value="{{ $subscription->price - $subscription->discount_price }}" id="input_total" />
                                        @else
                                            <label  id="label_total">{{ $subscription->price }}</label><label>ر.س</label>
                                            <input type="hidden" name="total" value="{{ $subscription->price }}" id="input_total" />
                                        @endif
                                    </div>
                                    <button class="btn btn-sm btn-warning focus-none pe-3 ps-3">إشتراك</button>
                                </div>
                            </div>
                        </div>
                    
                    </form>
                </div>
            @elseif(Route::currentRouteNamed('stores.dashboard.subscriptions.uploadtransfer'))
                <div class="col-12">
                    <form action="{{ route('stores.dashboard.subscriptions.uploadtransfer-func',['store'=>request()->route('store'),'id'=>$subscription->id]) }}" method="post" enctype="multipart/form-data">@csrf
                        <div class="row">
                            <div class="col-md-6 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <div class="fs-5 fw-normal ps-2">تفاصيل الإشتراك</div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2"> الباقة: </label>
                                        <label>{{$subscription->title}}</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2">سعر الإشتراك : </label>
                                        @if($subscription->discount_price !== null && $subscription->discount_price !== 0)
                                            <label class="text-danger line-through ms-2">{{ $subscription->price }} ر.س</label><label>{{ $subscription->price - $subscription->discount_price }}</label><label>ر.س</label>
                                            <input type="hidden" name="total" value="{{ $subscription->price - $subscription->discount_price }}" />
                                        @else
                                            <label>{{ $subscription->price }}</label><label>ر.س</label>
                                            <input type="hidden" name="total" value="{{ $subscription->price }}" />
                                        @endif
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2">الضريبة : </label>
                                            <label>
                                                @if($subscription->discount_price !== null && $subscription->discount_price !== 0)
                                                    {{ ($subscription->price - $subscription->discount_price)* 0.15 }}
                                                    <input type="hidden" name="tax" value="{{ ($subscription->price - $subscription->discount_price)* 0.15 }}" />
                                                @else
                                                    {{ $subscription->price * 0.15 }}
                                                    <input type="hidden" name="tax" value="{{ $subscription->price * 0.15 }}" />
                                                @endif
                                            </label>
                                            <label>ر.س</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <label class="ms-2">المجموع : </label>
                                        @if($subscription->discount_price !== null && $subscription->discount_price !== 0)
                                            <label>{{ $subscription->price - $subscription->discount_price }}</label><label>ر.س</label>
                                            <input type="hidden" name="total" value="{{ $subscription->price - $subscription->discount_price }}" />
                                        @else
                                            <label>{{ $subscription->price }}</label><label>ر.س</label>
                                            <input type="hidden" name="total" value="{{ $subscription->price }}" />
                                        @endif
                                    </div>
                                    <div>
                                        <input type="hidden" name="subscription" value="{{ $subscription->id }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 p-2">
                                <div class="bg-white rounded shadow-sm p-3">
                                    <input type="file" class="w-100" id="file_transfer" name="transfer" accept="image/*" />
                                    <div class=" mb-4 mt-4" id="image_box"></div>
                                    <button class="btn btn-warning btn-sm w-100 focus-none">رفع الحوالة</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
    @if(Route::currentRouteNamed('stores.dashboard.subscriptions.create'))
        <script>
            $(document).ready(function () {
                function updateTotal() {
                    var selectedMonths = $('#subscription_months').val();
                    var subscriptionPrice = parseFloat("{{ $subscription->price }}");
                    var discountPrice = parseFloat("{{ $subscription->discount_price }}") || 0;

                    var total = subscriptionPrice - discountPrice;
                    total *= selectedMonths;

                    $('#label_total').text(total.toFixed(2));
                    $('#input_total').val(total.toFixed(2));
                }
                updateTotal();
                $('#subscription_months').change(function () {
                    updateTotal();
                });
            });



            var currentDate = new Date();
            var oneMonthLater = new Date();
            oneMonthLater.setMonth(currentDate.getMonth() + 1);

            var formattedDate = formatDateToLocalISOString(currentDate);
            var formattedOneMonthLater = formatDateToLocalISOString(oneMonthLater);

            document.getElementById('subscriptionDate').min = formattedDate;
            document.getElementById('subscriptionDate').max = formattedOneMonthLater;

            document.getElementById('subscriptionDate').value = formattedDate;

            function formatDateToLocalISOString(date) {
                var tzoffset = (new Date()).getTimezoneOffset() * 60000;
                return (new Date(date - tzoffset)).toISOString().slice(0, 16);
            }

        </script>
    @endif
    @if(Route::currentRouteNamed('stores.dashboard.subscriptions.uploadtransfer'))
        <script>
            $(document).ready(function () {
                $('#file_transfer').change(function (e) {
                    var file = e.target.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var img = $('<img class="w-100">').attr('src', e.target.result);
                            $('#image_box').empty().append(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                function updateTotal() {
                    var selectedMonths = $('#subscription_months').val();
                    var subscriptionPrice = parseFloat("{{ $subscription->price }}");
                    var discountPrice = parseFloat("{{ $subscription->discount_price }}") || 0;

                    var total = subscriptionPrice - discountPrice;
                    total *= selectedMonths;

                    $('#label_total').text(total.toFixed(2));
                    $('#input_total').val(total.toFixed(2));
                }
                updateTotal();
                $('#subscription_months').change(function () {
                    updateTotal();
                });
            });
        </script>
    @endif
@endsection
