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
    <link href="{{ asset('/templates/template-2.css') }}" rel="stylesheet">
    <style>
        {!! $design->css_styles !!}
        {!! $design->css_own !!}
    </style>
@endsection
@section('body')

    @php
        $jsonFile = json_decode($design->json_file, true);
    @endphp

    
    
@endsection
@section('script')
    <script>
        {!! $design->javascript_code !!}
        {!! $design->js_own !!}
            
    </script>
@endsection