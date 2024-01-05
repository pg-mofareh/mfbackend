@extends('dashboard.layout')
@section('title','لوحة التجكم | الكوبونات')
@section('page','الكوبونات')
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
                        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-12 bg-white rounded p-3 mt-3 mb-3">
                    <div class="p-2">
                        <div class="d-flex justify-content-end">
                            <a class="btn text-warning btn-md fw-bold border-0 focus-none" href="{{ route('dashboard.coupons.create') }}">إضافة كوبون</a>
                        </div>
                        @if(isset($coupons) && count($coupons) > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الكوبون</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coupons as $coupon)
                                    <tr>
                                        <td>{{$coupon->code}}</td>
                                        <td class="d-flex justify-content-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-btn focus-none p-0"><li class="material-icons pt-2 text-muted hand">more_vert</li></button>
                                                <ul class="dropdown-menu dropdown-menu-right dropdown-menu-dark">
                                                    @can('coupons edit')<li><a class="dropdown-item" href="{{ route('dashboard.coupons.edit', ['id' => $coupon->id]) }}">تعديل</a></li>@endcan   
                                                    @can('coupons active')<li><a class="dropdown-item" href="{{ route('dashboard.coupons.active', ['id' => $coupon->id]) }}">@if($coupon->is_active==1) إيقاف @elseif($coupon->is_active==0) تنشيط @endif</a></li>@endcan 
                                                    @can('coupons delete')<li><a class="dropdown-item" href="{{ route('dashboard.coupons.delete', ['id' => $coupon->id]) }}">حذف</a></li>@endcan   
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="row justify-content-center">
                                <div class="col-md-3">
                                    <div id="lottie" class="w-100"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('script')
    <script src="{{ env('LOTTIE_PATH') }}"></script>
    <script>
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('lottie'),
            path: "{{ URL::to('/') }}{{ env('APP_PUBLIC') }}/lottie/no-results-found.json",
            renderer: 'svg',
            loop: false,
            autoplay: true, 
        });

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