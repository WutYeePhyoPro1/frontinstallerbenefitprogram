@include('layouts.front.customerheader')
<div id="app">
    <div class="d-flex vh-100 justify-content-center align-items-center">
        <div class="col-md-4 p-4">
               <h5>@yield('caption')</h5>

               @yield('content')

               <!-- bootstrap loader -->
               <div class="d-flex justify-content-center mt-3">
                    <div id="loader" class="spinner-border spinner-border-sm d-none" role="status"></div>
               </div>

            <!-- social login -->
             <div class="row">
                <small class="text-center text-muted mt-3">Our Social Applications</small>
                <div class="col-12 mt-2 text-center">
                    <a href="javascript:void(0);" class="btn" title="Login with Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="javascript:void(0);" class="btn" title="Login with Google"><i class="fab fa-google"></i></a>
                    <a href="javascript:void(0);" class="btn" title="Login with Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="javascript:void(0);" class="btn" title="Login with Github"><i class="fab fa-github"></i></a>
                </div>
             </div>

             <!-- register -->
             <div class="row">
                <div class="col-12 mt-2 text-center">
                    {{-- <small>Already have an account?  <a href="{{ route('login') }}" class="text-primary ms-1">Sign In</a></small> --}}
                </div>
             </div>

             <!-- data policy -->
             <div class="row">
                <div class="col-12 mt-2 text-center text-muted">
                    <small>By clicking Sign In, you agree to our  <a href="javascript:void(0);" class="text-primary fw-bold">Terms</a>, <a href="javascript:void(0);" class="text-primary fw-bold">Data Policy</a> and <a href="javascript:void(0);" class="text-primary fw-bold">Cookie Policy</a>. </small>
                </div>
             </div>
        </div>
    </div>
</div>
@include('layouts.front.customerfooter')
