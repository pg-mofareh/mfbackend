<div class="container mt-5 bg-white shadow-sm rounded" >
  <div class="row p-4" >
    <div class="col-md-3">
      <div class="nav nav-pills flex-md-column">
        @foreach($categories as $index => $category)
          <a class="nav-link  @if($index === 0){{'active text-white'}}@endif mt-2 text-center hand btn btn-sm text-dark">{{ $category->name }}</a>
        @endforeach
      </div>
    </div>
    <div class="col-md-9 mt-4 m-md-0">
      <div class="tab-content">
        @foreach($categories as $index => $category)
          <div class="tab-pane fade @if($index === 0){{'show active'}}@endif">
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
        @endforeach
      </div>
    </div>
  </div>
</div>

<script>

        $(document).ready(function() {
            $('.nav-pills a.nav-link').on('click', function() {
                $('.nav-pills a.nav-link').removeClass('active text-white');
                $(this).addClass('active text-white');

                var index = $(this).index();
                $('.tab-content .tab-pane').removeClass('show active');
                $('.tab-content .tab-pane').eq(index).addClass('show active');
            });

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