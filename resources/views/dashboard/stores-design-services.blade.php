@extends('dashboard.layout')
@section('title','لوحة التجكم | تصميم متجر')
@section('page','تصميم متجر')
@section('head')
    @if(Route::currentRouteNamed('dashboard.stores.design.edit'))
        <link href="{{ env('CODEMIRROR_MAIN_CSS_PATH') }}" rel="stylesheet"> 
        <link href="{{ env('CODEMIRROR_MONOKAI_CSS_PATH') }}" rel="stylesheet">
        <style>
            .CodeMirror {
                height: auto;
            }
            .code-editor {
                width: 100%;
                font-family: 'Courier New', monospace;
            }
        </style>
    @endif
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
        <div class="row mt-4">

            @if(Route::currentRouteNamed('dashboard.stores.design.edit'))
                <div class="col-12">
                    <form action="{{ route('dashboard.stores.design.edit-func',['id'=>request()->route('id'),'design'=>request()->route('design')]) }}" method="post" class="text-start">@csrf
                        <input type='text' class="form-control focus-none" name="name" value="{{ old('name', $design->name) }}" />
                        <div class="mt-3 mb-5">
                            <div class="text-start">CSS Code</div>
                            <textarea id="css-editor" name="css" class="code-editor" data-mode="css">{{ old('css', $design->css_styles) }}</textarea>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="text-start">JavaScript Code</div>
                            <textarea id="js-editor" name="js" class="code-editor" data-mode="javascript">{{ old('js', $design->javascript_code) }}</textarea>
                        </div>
                        <div class="mt-3 mb-5">
                            <div class="text-start">JSON File</div>
                            <textarea id="json-editor" name="json">{{ old('json', $design->json_file) }}</textarea>
                        </div>
                        <input type='hidden' name="design" value="{{ request()->route('design') }}" />
                        <button class="btn btn-warning focus-none btn-sm">تحديث التحقق من  التصميم</button>
                    </form>
                </div>
            
            @endif

            @if(Route::currentRouteNamed('dashboard.stores.design.active'))
                <form action="{{ route('dashboard.stores.design.active-func',['id'=>request()->route('id'),'design'=>request()->route('design')]) }}" method="post">@csrf 
                    <div class="col-12 mt-5 text-start">
                        <div class="alert text-dark text-end" role="alert">هل أنت متأكد من تحديث حالة هذا التصميم</div>
                        <input type='hidden' name="design" value="{{ request()->route('design') }}" />
                        <button class="btn btn-warning focus-none btn-sm">تحديث حالة التصميم</button>
                    </div>
                </form>
            @endif

            @if(Route::currentRouteNamed('dashboard.stores.design.verify'))
                <form action="{{ route('dashboard.stores.design.verify-func',['id'=>request()->route('id'),'design'=>request()->route('design')]) }}" method="post">@csrf 
                    <div class="col-12 mt-5 text-start">
                        <div class="alert text-dark text-end" role="alert">هل أنت متأكد من تحديث التحقق من التصميم</div>
                        <input type='hidden' name="design" value="{{ request()->route('design') }}" />
                        <button class="btn btn-warning focus-none btn-sm">تحديث التحقق من  التصميم</button>
                    </div>
                </form>
            @endif

        </div>
    </div>
@endsection
@section('script')
    @if(Route::currentRouteNamed('dashboard.stores.design.edit'))
    <script src="{{ env('CODEMIRROR_MAIN_JS_PATH') }}"></script>
    <script src="{{ env('CODEMIRROR_CSS_JS_PATH') }}"></script>
    <script src="{{ env('CODEMIRROR_JAVASCRIPT_JS_PATH') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.code-editor').forEach(function (textarea) {
                    const editor = CodeMirror.fromTextArea(textarea, {
                        mode: textarea.getAttribute('data-mode'),
                        lineNumbers: true,
                        theme: 'default',
                    });
                });
            });

            var editor = CodeMirror.fromTextArea(document.getElementById("json-editor"), {
                lineNumbers: true,
                mode: { name: "javascript", json: true },
                theme: "monokai"
            });
        </script>
    @endif
@endsection