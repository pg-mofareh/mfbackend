<!DOCTYPE html>
<html lang="ar">
    <head>
        <title>{{ $store }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ env('BOOTSTRAP_CSS_PATH') }}" rel="stylesheet">
        <link href="{{ env('OWN_MAIN_CSS_PATH') }}" rel="stylesheet">
        <link href="{{ env('MATERIAL_ICONS_PATH') }}" rel="stylesheet">
    </head>
    <body dir="rtl">
            
        <div class="container-fluid vh-100 d-flex flex-column">
            <div class="row flex-grow-1 align-items-center justify-content-around h-100">
                <div class="col-md-5 order-2 order-md-1">
                    <div id="lottie" class="w-100"></div>
                </div>

                <div class="col-md-4 order-1 order-md-2">
                    <div class="row p-0">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <div class="d-flex justify-content-center">
                                    <div class="rounded-circle d-flex justify-content-center align-items-center text-muted" style="width:95px;height:95px;"><i class="material-icons" style="font-size:75px">store</i></div>
                                </div>
                                <div class="fs-5 fw-bold mt-2">{{ $store }}</div>
                                <div class="fs-6 fw-normal text-muted mt-4">يرجى الحاولة لاحقا, الموقع غير متاح حاليا</div>
                            </div>
                        </div>      
                    </div>
                </div>
            </div>
        </div>

    <script src="{{ env('LOTTIE_PATH') }}"></script>
    <script>
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('lottie'),
            path: "{{ URL::to('/') }}{{ env('APP_PUBLIC') }}/lottie/repairing.json",
            renderer: 'svg',
            loop: false,
            autoplay: true, 
        });
    </script>
    </body>
</html>