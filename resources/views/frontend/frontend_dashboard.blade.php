<!DOCTYPE html>
<html lang="th">
<head>

    <!-- Basic Page Needs
================================================== -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <link rel="icon" href="{{ asset('frontend/images/B-8-removebg.png') }}">


    <!-- CSS
================================================== -->
    <link  rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="preload" href="{{ asset('frontend/css/main-color.css') }}" as="style" onload="this.rel='stylesheet'" id="colors">
    <noscript><link rel="stylesheet" href="{{ asset('frontend/css/main-color.css') }}" id="colors"></noscript>

    <!-- Defer less critical CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/toastify.css') }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ asset('frontend/css/toastify.css') }}"></noscript>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


      
   

    @yield('meta')
   

</head>

<body>

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Main Content
================================================== -->
        @yield('main')

    </div>
    <!-- Wrapper / End -->


    <!-- Scripts
================================================== -->
    <script type="text/javascript" src="{{ asset('frontend/scripts/jquery-3.6.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/jquery-migrate-3.3.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/mmenu.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/chosen.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/slick.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/rangeslider.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/magnific-popup.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/waypoints.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/counterup.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/jquery-ui.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/tooltips.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/custom.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/toastify.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('frontend/scripts/sweetalert.js') }}" defer></script>




    <script src="{{ asset('frontend/scripts/leaflet.min.js') }}"></script>
    <!-- Leaflet Maps Scripts -->
    <script src="{{ asset('frontend/scripts/leaflet-markercluster.min.js') }}"></script>
    <script src="{{ asset('frontend/scripts/leaflet-gesture-handling.min.js') }}"></script>
    <script src="{{ asset('frontend/scripts/leaflet-control-geocoder.js') }}"></script>

  
    <script src="{{ asset('frontend/scripts/MapSearch.js') }}"></script>

   



    <script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    window.isAuthenticated = @json(auth()->check());
    </script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&language=th&loading=async&callback=initAutocomplete">
</script>

    @yield('scripts')
    
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Toastify({
            text: @json($errors->first()),
            duration: 3000,
            gravity: "top",
            position: "right",
            style: {
                background: "#f44336"
            },
        }).showToast();
    });
</script>
@endif

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-N9TDZD78VB"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){ dataLayer.push(arguments); }
  gtag('js', new Date());
  gtag('config', 'G-N9TDZD78VB');
</script>
</body>

</html>