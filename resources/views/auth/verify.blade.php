@extends('auth.layout')
@section('body')

    <div class="container-fluid vh-100">
        <div class="row align-items-center justify-content-around h-100">

            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4 col-xll-3">
                <div class=" p-3">
                    @if (session('success'))
                        <div class="alert alert-success p-1 ps-2 pe-2 mt-2 mb-3" role="alert">{{ session('success') }}</div>
                    @endif
                    <form class="mb-2" action="{{ route('auth.verify_func') }}" method="post">
                            @csrf
                            <label class="">تحقق من الإيميل</label>
                            <div class="d-flex w-100 mt-2">
                                <input type="number" dir="ltr" class="form-control mb-2 mr-sm-2 focus-none placeholder-right" name="number" placeholder="رمز التحقق">
                            </div>
                            <div class="d-flex justify-content-end mt-2" >
                                <button type="submit" class="btn btn-success btn-sm mb-2 focus-none" >تحقق من الإيميل <div class="spinner-border spinner-border-sm" style="display:none" id="loading" role="status"><span class="visually-hidden">Loading...</span></div> </button>
                            </div>
                    </form>
                    @if ($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger p-1 ps-2 pe-2 mt-2" role="alert">{{ $error }}</div>
                        @endforeach
                    @endif
                </div>
            </div>



        
        
        </div>
    </div>
    <script>
        $("#verify").on( "click", function( event ) {
            $('#loading').show();
            $.ajax({
                type: "POST",
                url: "{{ URL::to('/') }}/email/verify/check",
                data: { _token: "{{ csrf_token() }}", name: "John" }
            }).done(function( msg ) {
                console.warn(msg);
                $('#loading').hide();
            });
        });
    </script>
@endsection