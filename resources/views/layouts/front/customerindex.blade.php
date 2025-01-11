@include('layouts.front.customerheader')
<div id="app">
    <div class="container-fluid d-flex vh-100 justify-content-center align-items-center">
        <div class="col-md-12 col-lg-4">
            @yield('content')

        </div>
    </div>

</div>
@include('layouts.front.customerfooter')
