<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Panel')</title>
    <link rel="stylesheet" href="{{ asset('customer-assets/css/style.css') }}">
    @include('layouts.customer.header')

</head>
<style>
    .hero-header {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../../../logo3.png') no-repeat center center/cover !important;
        /* background-size: cover;
        background-position: center;
        background-repeat: no-repeat !important;
        background-attachment: fixed; */

        /* make it fill the container */
        /* keep it centered when cropping */
        /* avoid tiling */
        /* optional: parallax effect */
    }

    .modal {
        background-color: rgba(0, 0, 0, 0.8) !important;
        /* darker than default 0.5 */
    }

    .toast.fade:not(.show) {
        display: none !important;
        pointer-events: none;
    }

    .menu-img {
        width: 120px;
        height: 80px;
        object-fit: cover;
        /* keeps aspect ratio, crops if needed */
    }

    .custom-map-control-button {
        background-color: #fff;
        border: 0;
        border-radius: 2px;
        box-shadow: 0 1px 4px -1px rgba(0, 0, 0, .3);
        cursor: pointer;
        margin: 10px;
        padding: 0 12px;
        font-family: Roboto, Arial, sans-serif;
        font-size: 14px;
        color: #333;
        line-height: 38px;
        text-align: center;
    }

    /* phones < 768px */
    @media (max-width: 767.98px) {
        .menu-img {
            width: 90px;
            height: 60px;
        }
    }
</style>

<body>
    <div class="toast-container position-fixed top-0 end-0 toast-index toast-rtl" style="z-index: 10000;">
        <div id="toast-success" class="toast fade m-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex justify-content-between bg-success text-white">
                <div class="toast-body"></div>
            </div>
        </div>
    </div>

    <!-- FAIL TOAST -->
    <div class="toast-container position-fixed top-0 end-0 toast-index toast-rtl" style="z-index: 10000;">
        <div id="toast-fail" class="toast fade m-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex justify-content-between bg-danger text-white">
                <div class="toast-body"></div>
            </div>
        </div>
    </div>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
       
        <div class="container-xxl position-relative p-0">
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

        @yield('content')

    </div>
  
    @include('layouts.customer.footer2')
    <script src="{{ asset('customer-assets/customtoast.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="{{asset('customer-assets/validation/auth.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWYgnYkmhhENengt8Gv1qPHnyc5KxMuFk&libraries=places"></script>

</body>
<script>
   
</script>
@yield('script_contact')

</html>