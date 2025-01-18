
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>{{ config('app.name', 'Laravel') }}</title>

      <!-- Favicon -->
      <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" />
      {{-- <link rel="stylesheet" href="{{ asset('css/backend-plugin.min.css') }}"> --}}
      <link rel="stylesheet" href="{{ asset('css/backend.css?v=1.0.0') }}">
      {{-- <link rel="stylesheet" href="{{ asset('css/select2.css')}}"/> --}}
      {{-- <link rel="stylesheet" href="{{ asset('css/select2.min.css')}}"/> --}}
      <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
      <link rel="stylesheet" href="{{ asset('css/app.css') }}">

      <link rel="stylesheet" href="{{ asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
      <link rel="stylesheet" href="{{ asset('vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
      <link rel="stylesheet" href="{{ asset('vendor/remixicon/fonts/remixicon.css') }}">

      {{-- @include('sweetalert::alert') --}}
    </head>
  <body class="  ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">

        <div class="iq-sidebar  sidebar-default ">
            <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
                <a href="{{ route('installercards.detail') }}" class="header-logo">
                    <img src="{{ asset('images/logo.png') }}" class="img-fluid rounded-normal light-logo" alt="logo"><h4 class="logo-title light-logo ml-3 pt-2">Installer Benefit Program</Label></h4>
                </a>
                <div class="iq-menu-bt-sidebar ml-0">
                    <i class="las la-bars wrapper-menu" ></i>
                </div>
            </div>

            @includeIf('layouts.admin.nav')
        </div>
         <!-- header -->
         @includeIf('layouts.admin.header')
         <!-- header -->
         @yield('content')

    </div>



    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('js/backend-bundle.min.js') }}"></script>

    <!-- Table Treeview JavaScript -->
    <script src="{{ asset('js/table-treeview.js') }}"></script>

    <!-- Chart Custom JavaScript -->
    <script src="{{ asset('js/customizer.js') }}"></script>

    <!-- select 2 -->
    <script src="{{ asset('js/select2.min.js') }}"></script>

    <!-- Chart Custom JavaScript -->
    <script async src="{{ asset('js/chart-custom.js') }}"></script>

    <!-- app JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jQuery.print.js') }}"></script>
    <script type="text/javascript">
      // $(document).ready(function() {
      //   function sendRequest(){
      //     $.ajax({
      //         url: "/notifications",
      //         success:
      //         function(result){
      //            if(result == 0){
      //              $('#notification_count').hide();
      //             }else{
      //               $('#notification_count').show();
      //               $('#notification_count').text(result);
      //             }
      //             setTimeout(function(){
      //                 sendRequest(); //this will send request again and again in every 10s;
      //             }, 10000);
      //         }
      //     });
      //   }
      //   sendRequest();
      // });
    </script>
     <script>
        function test_a5_print_printing()
        {
            var pdfFrame = window.frames["test_a5_print"];
            pdfFrame.focus();
            pdfFrame.print();
        }
        function test_a6_print_printing()
        {
            var pdfFrame = window.frames["test_a6_print"];
            pdfFrame.focus();
            pdfFrame.print();
        }

        function openCloseFullscreen() {
            var isInFullScreen = (document.fullscreenElement && document.fullscreenElement !== null) ||
                (document.webkitFullscreenElement && document.webkitFullscreenElement !== null) ||
                (document.mozFullScreenElement && document.mozFullScreenElement !== null) ||
                (document.msFullscreenElement && document.msFullscreenElement !== null);

            var docElm = document.documentElement;
            if (!isInFullScreen) {
                if (docElm.requestFullscreen) {
                    docElm.requestFullscreen();
                } else if (docElm.mozRequestFullScreen) {
                    docElm.mozRequestFullScreen();
                } else if (docElm.webkitRequestFullScreen) {
                    docElm.webkitRequestFullScreen();
                } else if (docElm.msRequestFullscreen) {
                    docElm.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        }
    </script>

    <script type="text/javascript">
        // Start Back Btn
        {{-- const getbtnback = document.getElementById("back-btn");
        getbtnback.addEventListener("click",function(){
            window.history.back();
        }); --}}
        // End Back Btn
    </script>
    @yield('js')
  </body>
</html>
