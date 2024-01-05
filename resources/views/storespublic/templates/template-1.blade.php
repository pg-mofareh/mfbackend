@extends('storespublic.templates.layout')
@section('title', $store->name)
@section('logo', $store->logo)
@section('head')
    @if(isset(request()->product) && $product)
        <!-- og meta -->
        <meta property="og:title" content="{{ $product->name }}">
        <meta property="og:description" content="{{ $product->name }}">
        <meta property="og:image" content="{{ asset($product->image) }}">
        <meta property="og:type" content="website">
        <!-- twitter card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@_MoSalah_1">
        <meta name="twitter:title" content="{{ $product->name }}">
        <meta name="twitter:description" content="{{ $product->name }}">
        <meta name="twitter:image" content="{{ asset($product->image) }}">
        <meta name="twitter:image:alt" content="{{ $product->name }}">
    @else
        <!-- og meta -->
        <meta property="og:title" content="{{ $store->name }}">
        <meta property="og:description" content="{{ $store->name }}">
        <meta property="og:image" content="{{ asset($store->logo) }}">
        <meta property="og:type" content="website">
        <!-- twitter card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@_MoSalah_1">
        <meta name="twitter:title" content="{{ $store->name }}">
        <meta name="twitter:description" content="{{ $store->name }}">
        <meta name="twitter:image" content="{{ asset($store->logo) }}">
        <meta name="twitter:image:alt" content="{{ $store->name }}">
    @endif
    <link href="{{ asset('/templates/template-1.css') }}" rel="stylesheet">
    <style>
        {!! $design->css_styles !!}
        {!! $design->css_own !!}
    </style>
@endsection
@section('body')

    @php
        $jsonFile = json_decode($design->json_file, true);
    @endphp
    <!--  navbar  -->
    <div class="container-fluid position-fixed" id="navbar">
        <div class="row p-2 ps-2 pe-2">
            <div class="col-12 rounded p-3 fw-bold fs-5 nav-box" >
                {{ $store->name }}
            </div>
        </div>
    </div>

    <!--  header  -->
    <div class="container-fluid vh-100 header-bg-image">
        <div class="row h-100" style="">
            <div class="col-12 d-flex header-info">
                <div class="header-info-width">
                    <div class="text-center fs-5 fw-bold">@if(isset($jsonFile['header']['info']['title'])){{ $jsonFile['header']['info']['title'] }}@endif</div>
                    <div class="text-center">@if(isset($jsonFile['header']['info']['body'])){{ $jsonFile['header']['info']['body'] }}@endif</div>
                </div>
            </div>
        </div>
    </div>

    
    <!--  menu  -->
    <div class="container-fluid mb-4 mt-5">
        <div class="row">
            @foreach($categories_products as $category)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 p-2">
                    <div class="p-2 bg-white rounded">
                        <h4>{{ $category->name }}</h4>
                        <ul class="w-100 p-0" style="list-style:none">
                        @foreach($category->products as $index => $product)
                            <li class="fs-6 fw-bold bg-light rounded w-100 p-3 mt-2 d-flex justify-content-between">
                            <div>{{ $product->name }}</div>
                            <div>
                                @if($product->discount_price !== null && $product->discount_price !== 0)
                                <label class="text-danger line-through ms-2">{{ $product->price }} ر.س</label><label>{{ $product->price - $product->discount_price }}</label><label>ر.س</label>
                                @else
                                <label>{{ $product->price }}</label><label>ر.س</label>
                                @endif
                            </div>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <!--  footer  -->
    <div class="container-fluid p-2" id="footer">
        <footer class="text-center text-lg-start text-white shadow-sm footer-bg">
            <div class="container p-4 pb-0">
                <section class="">
                    <div class="row">
                        <div class="col-md-8 mx-auto mt-3 text-center text-md-end">
                            <h6 class="text-uppercase mb-4 font-weight-bold">
                                من نحن
                            </h6>
                            <p>أضف وصف الموقع هنا, وصف بسيط ومختصر</p>
                        </div>
                        <div class="bg-white w-100 my-3 clearfix d-md-none" style="height:1px;"></div>
                        <div class="col-md-4 mx-auto mt-3">
                            <h6 class="text-uppercase mb-4 font-weight-bold">التواصل معنا</h6>
                            <p dir="ltr"><i class="fas fa-envelope mr-3"></i> ouremail@gmail.com</p>
                        </div>
                    </div>
                </section>
                <div class="bg-white w-100 my-3" style="height:1px;"></div>
                <section class="p-3 pt-0">
                    <div class="row d-flex align-items-center">
                    <div class="col-md-12 text-center">
                        <div class="p-3">© جميع الحقوق محفوظة ل 2023</div>
                    </div>
                    </div>
                </section>
            </div>
        </footer>
    </div>

@endsection
@section('script')
    <script>
        {!! $design->javascript_code !!}
        {!! $design->js_own !!}

        $(document).ready(function() {

            function NavbarControl(){
                var navbarHeight = $("#navbar").height();
                var scrollPosition = $(window).scrollTop();
                if(scrollPosition > navbarHeight){
                    $("#navbar .nav-box").addClass('bg-white shadow-sm');
                } else {
                    $("#navbar .nav-box").removeClass('bg-white shadow-sm');
                }
            }
            NavbarControl();
            $(window).scroll(function () {
                NavbarControl();
            });
            $(window).resize(function () {
                NavbarControl();
            });
        });
            
    </script>
@endsection