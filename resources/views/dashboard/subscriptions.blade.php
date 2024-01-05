@extends('dashboard.layout')
@section('title','لوحة التجكم | الإشتراكات')
@section('page','الإشتراكات')
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
                <div class="col-12 bg-white rounded p-3 mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>المتجر</th>
                                <th>يبدأ</th>
                                <th>ينتهي</th>
                                <th>طريقة الدفع</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $subscription)
                                <tr>
                                    <td>{{$subscription->store_name}}</td>
                                    <td>{{$subscription->start_at}}</td>
                                    <td>{{$subscription->end_at}}</td>
                                    <td>@if($subscription->payment_method=='bank_transfer'){{ 'حوالة بنكية' }}@endif</td>
                                    <td class="d-flex justify-content-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-btn focus-none p-0"><li class="material-icons pt-2 text-dark hand">more_vert</li></button>
                                            <ul class="dropdown-menu dropdown-menu-right dropdown-menu-dark">
                                                <li><a class="dropdown-item" href="{{ route('dashboard.subscriptions.viewer',['id'=>$subscription->id]) }}" target="_self">إستعراض</a></li>
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