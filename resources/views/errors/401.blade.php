@extends('errors.layout')
@section('title', "غير مسموح بالوصول لهذه الصفحة")
@section('body')
    <div class="text-muted" id="center">
        <div class="text-center">
            <div id="lottie" class="w-100"></div>
        </div>
        <div class="text-center fs-5">غير مسموح بالوصول لهذه الصفحة <a href="/" class=" text-decoration-none">الرئيسية</a></div>
    </div>
@endsection
@section('script')
    <script>
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('lottie'),
            path: "{{ URL::to('/') }}{{ env('APP_PUBLIC') }}/lottie/unauthorized.json",
            renderer: 'svg',
            loop: false,
            autoplay: true, 
        });
    </script>
@endsection
