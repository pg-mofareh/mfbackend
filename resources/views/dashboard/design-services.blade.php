@extends('dashboard.layout')
@section('title','لوحة التحكم | التصميم')
@section('page','التصميم')
@section('head')
    <link href="{{ env('CODEMIRROR_MAIN_CSS_PATH') }}" rel="stylesheet"> 
    <link href="{{ env('CODEMIRROR_MONOKAI_CSS_PATH') }}" rel="stylesheet">
    <style>
        .dropdown .btn::after {
            content: none;
        }
        .dropdown-menu-right {
            right: auto !important;
            left: 0 !important;
        }

        .CodeMirror {
            height: auto;
        }
        .code-editor {
            width: 100%;
            font-family: 'Courier New', monospace;
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
                @if(Route::currentRouteNamed('dashboard.design.template.create'))
                    <form action="{{ route('dashboard.design.template.create-func') }}" method="post">@csrf
                        <div class="col-12">
                            <div class="text-start">Template Name</div>
                            <input type="text" name="name" class="form-control focus-none rounded-0" value="{{ old('name') }}" />
                        </div>
                        <div class="col-12">
                            <div class="text-start">Template Description</div>
                            <input type="text" name="description" class="form-control focus-none rounded-0" value="{{ old('description') }}" />
                        </div>
                        <div class="col-12 mt-3">
                            <div class="text-start">CSS Code</div>
                            <textarea id="css-editor" name="css" class="code-editor" data-mode="css">{{ old('css') }}</textarea>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="text-start">JavaScript Code</div>
                            <textarea id="js-editor" name="js" class="code-editor" data-mode="javascript">{{ old('js') }}</textarea>
                        </div>
                        <div class="mt-3 mb-5">
                            <div class="text-start">JSON File</div>
                            <textarea id="json-editor" name="json">{{ old('json') }}</textarea>
                        </div>
                        <div class="col-12 mt-5 text-start">
                            <button class="btn btn-warning focus-none btn-sm">إنشاء النموذج</button>
                        </div>
                    </form>
                @endif

                @if(Route::currentRouteNamed('dashboard.design.template.active'))
                    <form action="{{ route('dashboard.design.template.active-func',['id'=>request()->route('id')]) }}" method="post">@csrf 
                        <div class="col-12 mt-5 text-start">
                            <div class="alert text-dark text-end" role="alert">هل أنت متأكد من تحديث حالة هذا النموذج</div>
                            <input type='hidden' name="id" value="{{ request()->route('id') }}" />
                            <button class="btn btn-warning focus-none btn-sm">تحديث حالة النموذج</button>
                        </div>
                    </form>
                @endif

                @if(Route::currentRouteNamed('dashboard.design.template.edit'))
                    <form action="{{ route('dashboard.design.template.edit-func',['id'=>request()->route('id')]) }}" method="post">@csrf
                        <div class="col-12">
                            <div class="text-start">Template Name</div>
                            <input type="text" name="name" class="form-control focus-none rounded-0" value="{{ old('name', $template->name) }}" />
                        </div>
                        <div class="col-12">
                            <div class="text-start">Template Description</div>
                            <input type="text" name="description" class="form-control focus-none rounded-0" value="{{ old('description', $template->description) }}" />
                        </div>
                        <div class="col-12 mt-3">
                            <div class="text-start">CSS Code</div>
                            <textarea id="css-editor" name="css" class="code-editor" data-mode="css">{{ old('css', $template->css_styles) }}</textarea>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="text-start">JavaScript Code</div>
                            <textarea id="js-editor" name="js" class="code-editor" data-mode="javascript">{{ old('js', $template->javascript_code) }}</textarea>
                        </div>
                        <div class="mt-3 mb-5">
                            <div class="text-start">JSON File</div>
                            <textarea id="json-editor" name="json">{{ old('json', $template->json_instructions) }}</textarea>
                        </div>
                        <div class="col-12 mt-5 text-start">
                            <input type='hidden' name="id" value="{{ request()->route('id') }}" />
                            <button class="btn btn-warning focus-none btn-sm">تحديث النموذج</button>
                        </div>
                    </form>
                @endif

                @if(Route::currentRouteNamed('dashboard.design.template.delete'))
                    <form action="{{ route('dashboard.design.template.delete-func',['id'=>request()->route('id')]) }}" method="post">@csrf 
                        <div class="col-12 mt-5 text-start">
                            <div class="alert text-danger text-end" role="alert">هل أنت متأكد من حذف هذا النموذج</div>
                            <input type='hidden' name="id" value="{{ request()->route('id') }}" />
                            <button class="btn btn-danger focus-none btn-sm">حذف النموذج</button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
@endsection
@section('script')
    <script src="{{ env('CODEMIRROR_MAIN_JS_PATH') }}"></script>
    <script src="{{ env('CODEMIRROR_CSS_JS_PATH') }}"></script>
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
@endsection