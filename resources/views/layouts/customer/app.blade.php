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
    .modal {
        background-color: rgba(0, 0, 0, 0.8) !important;
        /* darker than default 0.5 */
    }

    .toast.fade:not(.show) {
        display: none !important;
        pointer-events: none;
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
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="container-xxl position-relative p-0">
            @include('layouts.customer.nav')
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

        @yield('content')

        @include('layouts.customer.footer')
    </div>
    <div class="modal fade" id="model_userModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i data-lucide="user"></i>
                        Customer Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="form_addnewuser" action="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Mobile Number</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" name="mobileInput" id="mobileInput" placeholder="Enter mobile number">
                                <button class="btn btn-outline-primary" id="checkMobileBtn" type="button">Check</button>
                            </div>
                        </div>
                        <div id="addressSelectionContainer" class="mt-3"></div>

                        <div id="newUserForm" style="display: none;">
                            <div class="alert alert-info">
                                <small>New customer! Please fill in your details.</small>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="userNameInput" id="userNameInput" placeholder="Enter your name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="userEmailInput" id="userEmailInput" placeholder="Enter your email">
                                </div>
                            </div>

                            <div class="address-form-section">
                                <h6 class="mb-3">Address Details</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Address Type</label>
                                        <select class="form-select" name="addressTypeInput" id="addressTypeInput">
                                            <option value="Home">Home</option>
                                            <option value="Office">Office</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Address Line 1</label>
                                        <input type="text" class="form-control" name="address1Input" id="address1Input" placeholder="House/Flat No, Building">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Address Line 2</label>
                                        <input type="text" class="form-control" name="address2Input" id="address2Input" placeholder="Street, Area, Landmark">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" name="cityInput" id="cityInput" placeholder="City">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">State</label>
                                        <input type="text" class="form-control" name="stateInput" id="stateInput" placeholder="State">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" class="form-control" name="countryInput" id="countryInput" placeholder="Country">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Pin Code</label>
                                        <input type="text" class="form-control" name="pincodeInput" id="pincodeInput" placeholder="Pin Code">
                                    </div>
                                </div>
                                <!-- <button class="btn btn-outline-secondary" id="selectFromMapBtn">
                                    <i data-lucide="map-pin"></i>
                                    Select from Map
                                </button> -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="saveUserBtn" style="display: none;">Save & Continue</button>
                        <button class="btn btn-primary" type="button" id="continueCheckoutBtn" style="display: none;">Continue to Checkout</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="model_login" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <!-- Replace src with your logo -->
                        <img src="{{asset('logo3.png')}}" width="100" alt="Logo" class="logo">
                    </div>
                    <div id="step-mobile">
                        <h5 class="mb-3 text-center">Login with Mobile</h5>
                        <div id="mobile-alert" class="alert d-none" role="alert"></div>
                        <form id="login_mobile_form" method="post">
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" class="form-control" id="txt_mobile" name="txt_mobile" placeholder="Enter 10-digit mobile">
                                </div>
                                <div class="form-text">We'll send a one-time password (OTP) to this number.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="send-otp-btn">Send OTP</button>

                            <div class="text-center mt-3">
                                <a href="javascript:void(0)" class="register_new_user">Register a new user ?</a>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-light border w-100" id="guest_login">Continue as Guest ?</button>
                            </div>
                        </form>
                    </div>

                    <div id="step-otp" style="display: none;">
                        <h5 class="mb-3 text-center">Enter OTP</h5>
                        <div id="otp-alert" class="alert d-none" role="alert"></div>
                        <p class="small-muted text-center mb-2">OTP sent to <span id="display-mobile"></span></p>
                        <form id="login_otp_form" method="post">
                            <div class="d-flex justify-content-center mb-3 gap-2" id="otp-inputs">
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control otp-input" autocomplete="one-time-code" />
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control otp-input" autocomplete="one-time-code" />
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control otp-input" autocomplete="one-time-code" />
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control otp-input" autocomplete="one-time-code" />
                            </div>
                            <div class="mb-2 text-center">
                                <button type="submit" class="btn btn-success w-100" id="verify-otp-btn">Verify OTP</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small-muted">
                                    <span id="resend-label">Didn't get it?</span>
                                    <span id="guest-resend-action" class="text-primary cursor-pointer">Resend</span>
                                    <span id="guest_timer" class="ms-1"></span>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-link p-0" id="change-number">Change Number</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="model_guest_login" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <!-- Replace src with your logo -->
                        <img src="{{asset('logo3.png')}}" width="100" alt="Logo" class="logo">
                    </div>
                    <div id="guest-step-mobile">
                        <h5 class="mb-3 text-center">Continue as Guest</h5>
                        <div id="mobile-alert" class="alert d-none" role="alert"></div>
                        <form id="guest_login_mobile_form" method="post">
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" class="form-control" id="txt_guest_mobile" name="txt_guest_mobile" placeholder="Enter 10-digit mobile">
                                </div>
                                <div class="form-text">We'll send a one-time password (OTP) to this number.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="send-otp-btn">Send OTP</button>
                        </form>
                    </div>

                    <div id="guest-step-otp" style="display: none;">
                        <h5 class="mb-3 text-center">Enter OTP</h5>
                        <p class="small-muted text-center mb-2">OTP sent to <span id="display-guest-mobile"></span></p>
                        <form id="guest_login_otp_form" method="post">
                            <div class="d-flex justify-content-center mb-3 gap-2" id="otp-inputs">
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control guest-otp-input" autocomplete="one-time-code" />
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control guest-otp-input" autocomplete="one-time-code" />
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control guest-otp-input" autocomplete="one-time-code" />
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control guest-otp-input" autocomplete="one-time-code" />
                            </div>
                            <div class="mb-2 text-center">
                                <button type="submit" class="btn btn-success w-100">Verify OTP</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small-muted">
                                    <span id="resend-label">Didn't get it?</span>
                                    <span id="resend-guest-action" class="text-primary cursor-pointer">Resend</span>
                                    <span id="timer" class="ms-1"></span>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-link p-0" id="guest-change-number">Change Number</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- //signup new user -->
    <div class="modal fade" id="model_register" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <!-- Replace src with your logo -->
                        <img src="{{asset('logo3.png')}}" width="100" alt="Logo" class="logo">
                    </div>
                    <div id="step-register-mobile">
                        <h5 class="mb-3 text-center">Register new User</h5>
                        <div id="mobile-alert" class="alert d-none" role="alert"></div>
                        <form id="register_mobile_form" method="post">
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="txt_new_name" name="txt_new_name" placeholder="Enter your name">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Email</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="txt_new_email" name="txt_new_email" placeholder="Enter your email">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" class="form-control" id="txt_new_mobile" name="txt_new_mobile" placeholder="Enter 10-digit mobile">
                                </div>
                                <div class="form-text">We'll send a one-time password (OTP) to this number.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="send-otp-btn">Send OTP</button>
                        </form>
                    </div>

                    <div id="step-register-otp" style="display: none;">
                        <h5 class="mb-3 text-center">Enter OTP</h5>
                        <div id="otp-alert" class="alert d-none" role="alert"></div>
                        <p class="small-muted text-center mb-2">OTP sent to <span id="display-register-mobile"></span></p>
                        <form id="register_otp_form" method="post">
                            <div class="d-flex justify-content-center mb-3 gap-2" id="register-otp-inputs">
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control register-otp-input" autocomplete="one-time-code" />
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control register-otp-input" autocomplete="one-time-code" />
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control register-otp-input" autocomplete="one-time-code" />
                                <input type="text" style="width: 35px;" inputmode="numeric" maxlength="1" class="form-control register-otp-input" autocomplete="one-time-code" />
                            </div>
                            <div class="mb-2 text-center">
                                <button type="submit" class="btn btn-success w-100" id="verify-otp-btn">Verify OTP</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small-muted">
                                    <span id="resend-label">Didn't get it?</span>
                                    <span id="resend-action" class="text-primary cursor-pointer">Resend</span>
                                    <span id="timer" class="ms-1"></span>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-link p-0" id="change-register-number">Change Number</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Checkout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Customer Details</h6>
                            <div class="card">
                                <div class="card-body" id="customerDetails">
                                    <!-- Customer details will be populated here -->
                                </div>
                            </div>

                            <h6 class="mt-4 mb-3">Delivery Address</h6>
                            <div class="mb-3" id="addressList">
                                <!-- Address list will be populated here -->
                            </div>
                            <button class="btn btn-outline-primary btn-sm addAddressBtn" id="addAddressBtn">
                                <i data-lucide="plus"></i>
                                Add New Address
                            </button>
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3">Order Summary</h6>
                            <div class="card">
                                <div class="card-body" id="orderSummary">
                                    <!-- Order summary will be populated here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="placeOrderBtn">Place Order</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addAddressModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i data-lucide="map-pin"></i>
                        Add New Address
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="form_addnewaddress" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative">
                                    <div class="map-search-box">
                                        <input type="text" class="form-control" id="mapSearchInput" placeholder="Search for a location...">
                                    </div>
                                    <div id="map"></div>
                                </div>
                                <div class="mt-3">
                                    <div class="alert alert-info">
                                        <small><i data-lucide="info"></i> Click on the map to select your delivery location</small>
                                    </div>
                                    <div id="selectedLocationInfo" style="display: none;">
                                        <h6>Selected Location:</h6>
                                        <p id="selectedLocationText" class="text-muted"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="address-form-section">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Address Type</label>
                                            <select class="form-select" name="newAddressType" id="newAddressType">
                                                <option value="Home">Home</option>
                                                <option value="Office">Office</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Address Line 1</label>
                                            <input type="text" class="form-control" name="newAddress1" id="newAddress1" placeholder="House/Flat No, Building">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Address Line 2</label>
                                            <input type="text" class="form-control" name="newAddress2" id="newAddress2" placeholder="Street, Area, Landmark">
                                        </div>
                                        <!-- <div class="col-md-6 mb-3">
                                            <label class="form-label">City</label>
                                            <input type="text" class="form-control" name="newCity" id="newCity" placeholder="City">
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <!-- <div class="col-md-4 mb-3">
                                            <label class="form-label">State</label>
                                            <input type="text" class="form-control" name="newState" id="newState" placeholder="State">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Country</label>
                                            <input type="text" class="form-control" name="newCountry" id="newCountry" placeholder="Country" value="USA">
                                        </div> -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Pin Code</label>
                                            <input type="text" class="form-control" name="newPincode" id="newPincode" placeholder="Pin Code">
                                        </div>
                                    </div>
                                    <!-- <button type="button" class="btn btn-outline-secondary" id="selectFromMapNewBtn">
                                        <i data-lucide="map-pin"></i>
                                        Select from Map
                                    </button> -->
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="saveAddressBtn">Add Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Map Selection Modal -->
    <!-- <div class="modal fade" id="mapModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i data-lucide="map"></i>
                        Select Address from Map
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="position-relative">
                        <div class="map-search-box">
                            <input type="text" class="form-control" id="mapSearchInput" placeholder="Search for a location...">
                        </div>
                        <div id="map"></div>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <small><i data-lucide="info"></i> Click on the map to select your delivery location</small>
                        </div>
                        <div id="selectedLocationInfo" style="display: none;">
                            <h6>Selected Location:</h6>
                            <p id="selectedLocationText" class="text-muted"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="confirmLocationBtn" disabled>Confirm Location</button>
                </div>
            </div>
        </div>
    </div> -->

    @include('layouts.customer.footer2')
    <script src="{{ asset('customer-assets/customtoast.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="{{asset('customer-assets/validation/auth.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4sigxLQ9_i9Nfb4ZWLgl8sU2imWO89qM&libraries=places"></script>

</body>
<script>
    let map, marker, geocoder, autocomplete;

    function initGoogleMap() {
        const mapEl = document.getElementById("map");

        // Initialize Map
        map = new google.maps.Map(mapEl, {
            center: {
                lat: 20,
                lng: 0
            }, // default
            zoom: 2
        });

        geocoder = new google.maps.Geocoder();
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
        });

        // Autocomplete setup
        const input = document.getElementById("mapSearchInput");
        autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo("bounds", map);

        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) return;

            // Center map
            map.setCenter(place.geometry.location);
            map.setZoom(15);

            // Place marker
            marker.setPosition(place.geometry.location);

            // Fill form
            fillAddressFromPlace(place);
        });

        // Click on map → set marker & reverse geocode
        map.addListener("click", (e) => {
            marker.setPosition(e.latLng);
            reverseGeocode(e.latLng);
        });

        // Marker drag → reverse geocode
        marker.addListener("dragend", () => {
            reverseGeocode(marker.getPosition());
        });

        // Try current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((pos) => {
                const latLng = {
                    lat: pos.coords.latitude,
                    lng: pos.coords.longitude
                };
                map.setCenter(latLng);
                map.setZoom(15);
                marker.setPosition(latLng);
                reverseGeocode(latLng);
            });
        }
    }

    // Reverse geocode to fill form fields
    function reverseGeocode(latLng) {
        geocoder.geocode({
            location: latLng
        }, (results, status) => {
            if (status === "OK" && results[0]) {
                document.getElementById("selectedLocationInfo").style.display = "block";
                document.getElementById("selectedLocationText").textContent = results[0].formatted_address;
                fillAddressFields(results[0]);
            }
        });
    }

    // Fill form from Autocomplete place object
    function fillAddressFromPlace(place) {
        document.getElementById("selectedLocationInfo").style.display = "block";
        document.getElementById("selectedLocationText").textContent = place.formatted_address || "";

        fillAddressFields(place);
    }

    // Extract components into fields
    function fillAddressFields(place) {
        let components = {};

        (place.address_components || []).forEach(comp => {
            const type = comp.types[0];
            components[type] = comp.long_name;
        });

        document.getElementById("newAddress1").value = components.street_number ? `${components.street_number} ${components.route || ''}` : (components.route || '');
        document.getElementById("newAddress2").value = components.sublocality || '';
        document.getElementById("newCity").value = components.locality || '';
        document.getElementById("newState").value = components.administrative_area_level_1 || '';
        document.getElementById("newCountry").value = components.country || '';
        document.getElementById("newPincode").value = components.postal_code || '';
    }

    // Bootstrap modal event → init map
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("addAddressModal");
        // console.log("map")
        // modal.addEventListener("shown.bs.modal", () => {

        if (!map) {
            initGoogleMap();
        } else {
            google.maps.event.trigger(map, "resize");
        }
        // });
    });
</script>
<script>
    let cart = (localStorage.getItem('cart_items')) ? JSON.parse(localStorage.getItem('cart_items')) : [];
    let activeTab = 'menu_daywise';
    let allMenuList = [];
    let searchTerm = '';
    let alacarteorder_date = '';
    let currentUser = '';
    let mealRemaining = 0;
    let ifDeductAnountFromPlan = true;
    const assetBase = "{{ asset('storage') }}";
    const defaultImage = "{{ asset('default.png') }}";

    function renderMenu() {
        if (activeTab === 'menu_daywise') {
            renderDayWiseMenu();
        } else if (activeTab === 'menu_alacarte') {
            renderAlaCarteMenu();
        } else if (activeTab === 'menu_party') {
            renderPartyMenu();
        }
    }

    function addToCart(item, type) {

        const cartId = `${type}-${item.id}`;
        const existing = cart.find(cartItem => cartItem.id === cartId);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({
                id: cartId,
                db_id: item.id,
                order_date: (item?.menu_date) ? item?.menu_date : "",
                day_name: (item?.day_name) ? item?.day_name : "",
                name: (item?.name) ? item?.name : item?.title,
                description: (item?.items) ? item?.items : item?.description,
                price: (item?.price) ? item?.price : (item?.price_per_kg) ? item?.price_per_kg : item?.price_per_qty,
                price_type: (item?.price_per_kg) ? item?.price_per_kg : (item?.price_per_qty) ? item?.price_per_qty : "",
                quantity: 1,
                type: type,
                image: item.image
            });
        }
        localStorage.setItem('cart_items', JSON.stringify(cart));
        updateCartDisplay();
        renderMenu();
    }

    function getCartQuantity(cartId) {
        const item = cart.find(cartItem => cartItem.id === cartId);
        return item ? item.quantity : 0;
    }



    function updateCartDisplay() {
        const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
        const total = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        $('#cartCount').text(`${itemCount>0?itemCount+' items':'Cart'}`);
        $('#cartTotal').text(`${(total!=0)?'$'+total.toFixed(2):''}`);
    }

    function removeFromCart(cartId) {

        const existing = cart.find(item => item.id === cartId);
        if (existing && existing.quantity > 1) {
            existing.quantity = existing.quantity - 1;
        } else {
            cart = cart.filter(item => item.id !== cartId);
        }
        localStorage.setItem('cart_items', JSON.stringify(cart));
        updateCartDisplay();
        renderMenu();
        showCart();
    }

    function renderDayWiseMenu() {
        const filteredMenu = allMenuList?.daywise.filter(menu =>
            menu.title?.toLowerCase().includes(searchTerm) ||
            menu.items?.toLowerCase().includes(searchTerm)
        );
        const today = new Date().toISOString().split("T")[0];
        let html = '<div class="row">';
        filteredMenu.forEach(menu => {
            menu.type = "daywise";
            const isToday = menu.menu_date === today;
            html += ` <div class="col-lg-12 border  p-3 mb-3" style="border-radius: 10px;">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 img-fluid rounded" src="${menu.image_path ? `${assetBase}/${menu.image_path}` : defaultImage}" alt="" style="width: 120px; height:80px" onerror="this.onerror=null;this.src='{{ asset("default.png") }}">
                                <div class="w-100 d-flex flex-column text-start ps-4">
                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                        <span>${menu.title}
                                        <span class="badge bg-primary fs-6">
                                            ${menu.menu_date} - ${menu.day_name}
                                        </span>
                                        </span>
                                        
                                        <span class="text-primary">$${menu.price}</span>
                                    </h5>
                                    <div class="d-flex justify-content-between  pb-2">
                                            <small class="fst-italic">${menu.items}</small>
                                          <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-primary btn-sm" onclick="removeFromCart('daywise-${menu.id}')" ${getCartQuantity('daywise-' + menu.id) === 0 ? 'disabled' : ''}>
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <span class="fw-medium quantity-control">${getCartQuantity('daywise-' + menu.id)}</span>
                                            <button class="btn btn-primary btn-sm" onclick="addToCart(${JSON.stringify(menu).replace(/"/g, '&quot;')}, 'daywise')">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
        })
        html += `</div>`;
        $('.menu_daywise').html(html);
        updateCartDisplay();
    }

    function renderAlaCarteMenu() {
        const filteredMenu = allMenuList?.alacarte.map(category => ({
            ...category,
            items: category.alacartemenus.filter(item =>
                item.name.toLowerCase().includes(searchTerm) ||
                item.description.toLowerCase().includes(searchTerm)
            )
        })).filter(category => category.items.length > 0);
        let html = '';
        filteredMenu.forEach(category => {
            html += `<h5 class="mb-3">${category.category}</h5><div class="row mb-3 border p-3" style="border-radius: 10px;">`;
            category?.alacartemenus?.forEach(menu => {

                html += ` <div class="col-lg-12 mb-3">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 img-fluid rounded" src="${menu.image_path ? `${assetBase}/${menu.image_path}` : defaultImage}" alt="" style="width: 120px; height:80px" onerror="this.onerror=null;this.src='{{ asset("default.png") }}">
                                <div class="w-100 d-flex flex-column text-start ps-4">
                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                        <span>${menu.name}</span>
                                        <span class="text-primary">$${menu.price}</span>
                                    </h5>
                                    <div class="d-flex justify-content-between  pb-2">
                                            <small class="fst-italic">${menu.description}</small>
                                          <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-primary btn-sm" onclick="removeFromCart('alacarte-${menu.id}')" ${getCartQuantity('alacarte-' + menu.id) === 0 ? 'disabled' : ''}>
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <span class="fw-medium quantity-control">${getCartQuantity('alacarte-' + menu.id)}</span>
                                            <button class="btn btn-primary btn-sm" onclick="addToCart(${JSON.stringify(menu).replace(/"/g, '&quot;')}, 'alacarte')">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                        </div>`;
            })
            html += `</div>`;
        });
        $('.menu_alacarte').html(html);
        updateCartDisplay();

    }

    function showCart() {
        const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
        const total = cart.reduce((sum, item) => {
            // Main item total
            let itemTotal = item.price * item.quantity;
            // Additional items total
            if (Array.isArray(item.additional_items)) {
                itemTotal += item.additional_items.reduce((addSum, addItem) => {
                    return addSum + (addItem.price * addItem.quantity);
                }, 0);
            }
            return sum + itemTotal;
        }, 0);

        $('#cartModalTitle').text(`Your Cart (${itemCount} items)`);
        $('#cartModalTotal').text(`$${total.toFixed(2)}`);

        if (cart.length === 0) {
            $('#cartModalBody').html(`
            <div class="text-center py-4">
                <p class="text-muted">Your cart is empty</p>
            </div>
        `);
            $('#cartModalFooter').hide();
        } else {
            const grouped = cart.reduce((acc, item) => {
                if (!acc[item.type]) acc[item.type] = [];
                acc[item.type].push(item);
                return acc;
            }, {});

            let html = '';

            // Define menu categories with icons and colors
            const menuCategories = {
                'set': {
                    name: 'Set Menu',
                    icon: 'fas fa-utensils',
                    color: 'primary',
                    bgColor: 'bg-primary-subtle'
                },
                'alacarte': {
                    name: 'Alacarte',
                    icon: 'fas fa-utensils',
                    color: 'success',
                    bgColor: 'bg-success-subtle'
                },
                'party': {
                    name: 'Party Menu',
                    icon: 'fas fa-birthday-cake',
                    color: 'warning',
                    bgColor: 'bg-warning-subtle'
                }
            };
            // Render each group
            for (const [type, items] of Object.entries(grouped)) {
                const category = menuCategories[type] || {
                    name: type.charAt(0).toUpperCase() + type.slice(1),
                    icon: 'fas fa-utensils',
                    color: 'secondary',
                    bgColor: 'bg-secondary-subtle'
                };

                const categoryTotal = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const additionalItemTotal = items.reduce((sum, item) => {
                    if (Array.isArray(item.additional_items)) {
                        return sum + item.additional_items.reduce((aSum, addItem) => aSum + (addItem.price * addItem.quantity), 0);
                    }
                    return sum;
                }, 0);
                html += `
                <div class="cart-section mb-4 border rounded" style="position:relative;">
                    <!-- Category Header -->
                    <div style="position: absolute; top: -20px; left: 0; right: 0; margin-bottom: 12px; width: 100%;">
                        <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                            <div class="d-flex align-items-center">
                            <h5 class=" bg-white text-dark px-2 py-2 me-1">
                                <i class="${category.icon} me-1"></i>
                                ${category.name}
                            </h5>
                            </div>
                            <div class="fw-medium mb-2 bg-white">
                            $${(categoryTotal + additionalItemTotal).toFixed(2)}
                            </div>
                        </div>
                        </div>
                             `;

                // Show order date input for certain types
                if (type === 'alacarte' || type === 'party') {
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    const minDate = tomorrow.toISOString().split('T')[0];

                    html += `
                            <div class=" p-2 mt-3">
                                <label class="form-label fw-medium">Order Date</label>
                                <input type="date" class="form-control w-auto" name="order_date_${type}" id="order_date_${type}" min="${minDate}">
                            </div>
                        `;
                }

                // Handle Set Menu items differently (item-wise additional menu)

                // Handle other categories (A La Carte, Party Menu) - category-wise additional items
                html += `<div class="row p-2 ${(type === 'daywise')?'mt-3':''}">`;
                items.forEach((item, index) => {
                    html += `
                                <div class="col-lg-12 pb-2">
                                    <div class="card shadow-sm ${(type === 'daywise')?'mb-3':''}">
                                        <div class="card-body p-2">
                                            <div class="d-flex">
                                                <div class="me-3">
                                                    <img 
                                                        src="${item.image_path ?? '{{asset("default.png")}}'}"
                                                        alt="${item.name}"
                                                        class="rounded"
                                                        style="width: 50px; height: 50px; object-fit: cover;"
                                                        onerror="this.onerror=null;this.src='{{asset("default.png")}}';"
                                                    >
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between">
                                                            <div className="flex-1">
                                                                <p class="fw-semibold mb-0">${item.name} ${item.type === 'daywise' && item.order_date ? `<span class="badge bg-primary">${item.order_date} (${item.day_name || ''})</span>` : ''}
                                                                </p>
                                                                <small className="text-sm text-orange-700">$${item.price}</small>
                                                            </div>
                                                        
                                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-primary" onclick="removeFromCart('${item.id}')">
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                            <span class="btn btn-sm btn-white disabled">${item.quantity}</span>
                                                            <button class="btn btn-sm btn-primary" onclick="addToCartFromModal('${item.id}')">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <span class="fw-bold text-primary">$${(item.price * item.quantity).toFixed(2)}</span>
                                                    </div>
                                                    </div>                                        
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                    if (type === 'daywise') {
                        if (allMenuList?.additional_menu.length > 0) {
                            html += `
                                        <div style="margin-left:22px;">
                                            <div class="d-flex align-items-center text-muted fw-medium mb-2">
                                                <i class="fa fa-plus-circle me-2"></i>
                                                <small>Additional Items for ${item.name}</small>
                                            </div>
                                            <div class="row">
                                        `;

                            allMenuList?.additional_menu.forEach(additem => {
                                const cartItem = cart.find(c => c.id == item.id);
                                const matchedAdditionalItem = cartItem?.additional_items?.find(ai => ai.id == additem.id);

                                const quantity = matchedAdditionalItem?.quantity || 0;
                                const totalPrice = (additem.price * quantity).toFixed(2);
                                html += `
                                            <div class="col-lg-12">
                                                <div class="card bg-warning-subtle border-warning shadow-sm mb-3">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex">
                                                        <div class="me-3">
                                                                <img 
                                                                    src="${additem.image_path ?? '{{asset("default.png")}}'}"
                                                                    alt="${additem.name}"
                                                                    class="rounded"
                                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                                    onerror="this.onerror=null;this.src='{{asset("default.png")}}';"
                                                                >
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between">
                                                                    <div className="flex-1">
                                                                        <p class="fw-semibold mb-0">${additem.name}</p>
                                                                        <small className="text-sm text-orange-700">$${additem.price}</small>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                                                    <div class="btn-group" role="group">
                                                                        <button class="btn btn-sm btn-outline-warning" onclick="removeFromCartAdditionalItem('${item.id}','${additem.id}')">
                                                                            <i class="fa fa-minus"></i>
                                                                        </button>
                                                                        <span class="btn btn-sm btn-warning-subtle disabled border-0">${quantity}</span>
                                                                        <button class="btn btn-sm btn-outline-warning" onclick="addToCartAdditionalItem('${item.id}','${additem.id}','${additem.price}','${additem.name}')">
                                                                            <i class="fa fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                    <span class="fw-bold text-warning-emphasis">$${totalPrice}</span>
                                                                </div>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                            });

                            html += `</div></div>`;
                        } else {
                            html += `</div>`;
                        }
                    }
                    if ((index === items.length - 1) && (type === 'alacarte' || type === 'party')) {
                        if (allMenuList?.additional_menu.length > 0) {
                            html += `
                                        <div style="margin-left:22px;">
                                            <div class="d-flex align-items-center text-muted fw-medium mb-2 mt-2">
                                                <i class="fa fa-plus-circle me-2"></i>
                                                <small>Additional Items</small>
                                            </div>
                                            <div class="row">
                                        `;

                            allMenuList?.additional_menu.forEach(additem => {
                                const cartItem = cart.find(c => c.id == item.id);
                                const matchedAdditionalItem = cartItem?.additional_items?.find(ai => ai.id == additem.id);

                                const quantity = matchedAdditionalItem?.quantity || 0;
                                const totalPrice = (additem.price * quantity).toFixed(2);
                                html += `
                                            <div class="col-lg-12">
                                                <div class="card bg-warning-subtle border-warning shadow-sm mb-3">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex">
                                                        <div class="me-3">
                                                                <img 
                                                                    src="${additem.image_path ?? '{{asset("default.png")}}'}"
                                                                    alt="${additem.name}"
                                                                    class="rounded"
                                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                                    onerror="this.onerror=null;this.src='{{asset("default.png")}}';"
                                                                >
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between">
                                                                    <div className="flex-1">
                                                                        <p class="fw-semibold mb-0">${additem.name}</p>
                                                                        <small className="text-sm text-orange-700">$${additem.price}</small>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                                                    <div class="btn-group" role="group">
                                                                        <button class="btn btn-sm btn-outline-warning" onclick="removeFromCartAdditionalItem('${item.id}','${additem.id}')">
                                                                            <i class="fa fa-minus"></i>
                                                                        </button>
                                                                        <span class="btn btn-sm btn-warning-subtle disabled border-0">${quantity}</span>
                                                                        <button class="btn btn-sm btn-outline-warning" onclick="addToCartAdditionalItem('${item.id}','${additem.id}','${additem.price}','${additem.name}')">
                                                                            <i class="fa fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                    <span class="fw-bold text-warning-emphasis">$${totalPrice}</span>
                                                                </div>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                            });

                            html += `</div></div>`;
                        } else {
                            html += `</div>`;
                        }
                    }
                    html += `</div>`;
                });
                html += `</div></div>`;
            }
            $('#cartModalBody').html(html);
            $('#cartModalFooter').show();
        }
    }

    function addToCartFromModal(cartId) {
        const existing = cart.find(item => item.id === cartId);
        if (existing) {
            existing.quantity += 1;
            updateCartDisplay();
            showCart();
        }
    }

    function addToCartAdditionalItem(cartId, additionalItemId, price, name) {
        const existing = cart.find(item => item.id === cartId);

        if (existing) {
            // Ensure additional_items array exists
            if (!existing.additional_items) {
                existing.additional_items = [];
            }

            // Check if the additional item already exists
            const additionalItem = existing.additional_items.find(add => add.id === additionalItemId);

            if (additionalItem) {
                // If exists, increment quantity
                additionalItem.quantity += 1;
            } else {
                // If not, add with initial quantity
                console.log("dsd")
                console.log()
                existing.additional_items.push({
                    id: additionalItemId,
                    quantity: 1,
                    price: price,
                    name: name,
                    type: 'additional'
                });
            }

            // Optional: update main quantity if needed (depends on logic)
            // existing.quantity += 1;

            updateCartDisplay();
            showCart();
        }
    }

    function removeFromCartAdditionalItem(cartId, additionalItemId) {
        const existing = cart.find(item => item.id === cartId);

        if (existing && Array.isArray(existing.additional_items)) {
            const index = existing.additional_items.findIndex(add => add.id === additionalItemId);

            if (index !== -1) {
                const additionalItem = existing.additional_items[index];

                if (additionalItem.quantity > 1) {
                    additionalItem.quantity -= 1;
                } else {
                    // Remove item if quantity is 1 and being decreased
                    existing.additional_items.splice(index, 1);
                }

                updateCartDisplay();
                showCart();
            }
        }
    }
    $(document).ready(function() {

        $.ajax({
            url: '{{route("customer.get-menu")}}', // Replace with your actual GET URL
            type: 'GET',
            dataType: 'json', // or 'html' depending on what the server returns
            success: function(response) {
                var daywise_menu = '';
                if (response.success) {
                    allMenuList = response?.data;
                    renderMenu();
                    showCart()
                }
            },
            error: function(xhr, status, error) {
                $('#result').html('Failed to load data.');
            }
        });
        $(document).on('click', '.add-to-cart', function() {
            $(this).hide();

        })

        $('.menu-items[data-tab]').click(function() {
            const tab = $(this).data('tab');
            activeTab = tab;
            renderMenu(tab);
        });
        $("#login-user").click(function() {
            $('#step-mobile').show();
            $('#step-otp').hide();
            $("#guest_login").show();
            $('#model_login').modal('toggle');
        })
        $('#checkoutBtn').click(function() {
            $.ajax({
                url: "{{ route('customer.verify-user') }}", // Change this to your server endpoint
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                method: 'post',
                beforeSend: function() {
                    $(".loader-wrapper").css("display", "flex")

                },
                success: function(response) {
                    if (response) {
                        alacarteorder_date = $("#order_date_alacarte").val();

                        if ($('#order_date_alacarte').is(':visible')) {
                            const value = $('#order_date_alacarte').val();
                            if (!value) {
                                toastFail("Alacarte Order Date is required!");
                                return;
                            }
                        }
                        ifDeductAnountFromPlan = true;
                        showCheckout();
                    } else {
                        $('#step-mobile').show();
                        $('#step-otp').hide();
                        $("#guest_login").show();
                        $('#model_login').modal('toggle');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    var errors = xhr.responseJSON.errors;
                    toastFail(errors)
                },
                complete: function() {
                    $(".loader-wrapper").css("display", "none")
                },
            });

        });

        $('#login_mobile_form').validate({
            rules: validationRules.loginForm.rules,
            messages: validationRules.loginForm.messages,
            errorElement: "div",
            errorClass: "invalid-feedback",
            highlight: function(element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
            },
            errorPlacement: function(error, element) {
                const $group = element.closest(".input-group");
                if ($group.length) {
                    error.insertAfter($group);
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, event) {
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                console.log(data)
                event.preventDefault();
                $.ajax({
                    url: "{{ route('customer.check-mobile-exist') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".loader-wrapper").css("display", "flex")

                    },
                    success: function(response) {
                        console.log(response)
                        // Handle success response
                        if (response.success) {
                            toastSuccess("OPT send in your mobile number");
                            $('#display-mobile').text('+91 ' + data.txt_mobile);
                            $('#step-mobile').hide();
                            $("#step-otp").show();
                            startResendCountdown();
                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please try again later.");
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        var errors = xhr.responseJSON.errors;
                        toastFail(errors)
                    },
                    complete: function() {
                        $(".loader-wrapper").css("display", "none")
                    },
                });

            }
        });

        $('#login_otp_form').validate({
            rules: {},
            messages: {},
            submitHandler: function(form, event) {
                event.preventDefault();
                let otp = '';
                $('.otp-input').each(function() {
                    otp += $(this).val();
                });
                if (otp.length !== 4 || !/^\d+$/.test(otp)) {
                    toastFail('Enter 4 digit OTP.');
                    return;
                }
                const formData = new FormData();
                formData.append('otp', otp);
                formData.append('mobile', $("#txt_mobile").val())
                $.ajax({
                    url: "{{ route('customer.verify-otp') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".loader-wrapper").css("display", "flex")

                    },
                    success: function(response) {
                        console.log(response)
                        // Handle success response
                        if (response.success) {
                            $(".nav-auth").show();
                            $("#login-user").hide();
                            $("#step-otp").hide();
                            $('#model_login').modal('toggle');
                            toastSuccess(response.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please try again later.");
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        var errors = xhr.responseJSON.errors;
                        toastFail(errors)
                    },
                    complete: function() {
                        $(".loader-wrapper").css("display", "none")
                    },
                });

            }
        });

        //guest_login
        $('#guest_login_mobile_form').validate({
            rules: validationRules.guestLoginForm.rules,
            messages: validationRules.guestLoginForm.messages,
            errorElement: "div",
            errorClass: "invalid-feedback",
            highlight: function(element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
            },
            errorPlacement: function(error, element) {
                const $group = element.closest(".input-group");
                if ($group.length) {
                    error.insertAfter($group);
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, event) {
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                toastSuccess("OPT send in your mobile number");
                $('#display-guest-mobile').text('+91 ' + data.txt_guest_mobile);
                $('#guest-step-mobile').hide();
                $("#guest-step-otp").show();
                startResendCountdown();
            }
        });

        $('#guest_login_otp_form').validate({
            rules: {},
            messages: {},
            submitHandler: function(form, event) {
                event.preventDefault();
                let otp = '';
                $('.guest-otp-input').each(function() {
                    otp += $(this).val();
                });
                if (otp.length !== 4 || !/^\d+$/.test(otp)) {
                    toastFail('Enter 4 digit OTP.');
                    return;
                }
                console.log(otp)
                const formData = new FormData();
                formData.append('otp', otp);
                formData.append('mobile', $("#txt_guest_mobile").val())
                $.ajax({
                    url: "{{ route('customer.guest-verify-otp') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".loader-wrapper").css("display", "flex")

                    },
                    success: function(response) {
                        console.log(response)
                        // Handle success response
                        if (response.success) {
                            $(".nav-auth").show();
                            $("#login-user").hide();
                            $("#guest-step-otp").hide();
                            $('#model_guest_login').modal('toggle');
                            toastSuccess(response.message);

                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please try again later.");
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        var errors = xhr.responseJSON.errors;
                        toastFail(errors)
                    },
                    complete: function() {
                        $(".loader-wrapper").css("display", "none")
                    },
                });

            }
        });
        $(document).on('input', '.guest-otp-input', function() {
            const $this = $(this);
            const val = $this.val();
            if (val.length === 1) {
                $this.next('.guest-otp-input').focus();
            }
        });
        $(document).on('keydown', '.guest-otp-input', function(e) {
            if (e.key === 'Backspace' && !$(this).val()) {
                $(this).prev('.guest-otp-input').focus();
            }
        });

        $(document).on('input', '.otp-input', function() {
            const $this = $(this);
            const val = $this.val();
            if (val.length === 1) {
                $this.next('.otp-input').focus();
            }
        });
        $(document).on('keydown', '.otp-input', function(e) {
            if (e.key === 'Backspace' && !$(this).val()) {
                $(this).prev('.otp-input').focus();
            }
        });



        //register
        $('#register_mobile_form').validate({
            rules: validationRules.registerForm.rules,
            messages: validationRules.registerForm.messages,
            errorElement: "div",
            errorClass: "invalid-feedback",
            highlight: function(element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
            },
            errorPlacement: function(error, element) {
                const $group = element.closest(".input-group");
                if ($group.length) {
                    error.insertAfter($group);
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, event) {
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                event.preventDefault();
                $.ajax({
                    url: "{{ route('customer.check-register-mobile-exist') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".loader-wrapper").css("display", "flex")

                    },
                    success: function(response) {
                        console.log(response)
                        // Handle success response
                        if (response.success) {
                            toastSuccess("OPT send in your mobile number");
                            $('#display-register-mobile').text('+91 ' + data.txt_new_mobile);
                            $('#step-register-mobile').hide();
                            $("#step-register-otp").show();
                            startResendCountdown();
                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please try again later.");
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        var errors = xhr.responseJSON.errors;
                        toastFail(errors)
                    },
                    complete: function() {
                        $(".loader-wrapper").css("display", "none")
                    },
                });

            }
        });

        $('#register_otp_form').validate({
            rules: {},
            messages: {},
            submitHandler: function(form, event) {
                event.preventDefault();
                let otp = '';
                $('.register-otp-input').each(function() {
                    otp += $(this).val();
                });
                if (otp.length !== 4 || !/^\d+$/.test(otp)) {
                    toastFail('Enter 4 digit OTP.');
                    return;
                }
                const formData = new FormData();
                formData.append('otp', otp);
                formData.append('name', $("#txt_new_name").val())
                formData.append('email', $("#txt_new_email").val())
                formData.append('mobile', $("#txt_new_mobile").val())

                $.ajax({
                    url: "{{ route('customer.verify-register-otp') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".loader-wrapper").css("display", "flex")

                    },
                    success: function(response) {
                        console.log(response)
                        // Handle success response
                        if (response.success) {
                            $('#step-register-mobile').hide();
                            $("#step-register-otp").hide();
                            $('#model_register').modal('toggle');
                            toastSuccess(response.message);

                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please try again later.");
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        var errors = xhr.responseJSON.errors;
                        toastFail(errors)
                    },
                    complete: function() {
                        $(".loader-wrapper").css("display", "none")
                    },
                });

            }
        });


        $('#resend-action').on('click', function() {
            if ($(this).hasClass('text-muted')) return;
            startResendCountdown();
        });

        $("#resend-guest-action").on('click', function() {
            if ($(this).hasClass('text-muted')) return;
            guestStartResendCountdown();
        });

        // Change number
        $('#change-number').on('click', function() {
            $('#step-otp').hide();
            $('#step-mobile').show();
        });
        $('#guest-change-number').on('click', function() {
            $('#guest-step-otp').hide();
            $('#guest-step-mobile').show();
        });
        $("#change-register-number").on('click', function() {
            $('#step-register-otp').hide();
            $('#step-register-mobile').show();
        });

        $('#placeOrderBtn').click(function() {
            completeOrder();
        });

        $(document).on('click', '.addAddressBtn', function() {
            $("#form_addnewaddress")[0].reset();
            // $("#checkoutModal").modal('toggle');
            $('#addAddressModal').modal('toggle');
        });

        $('#form_addnewaddress').validate({
            rules: validationRules.addressValidationForm.rules,
            messages: validationRules.addressValidationForm.messages,
            errorElement: "div",
            errorClass: "invalid-feedback",
            highlight: function(element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
            },
            errorPlacement: function(error, element) {
                const $group = element.closest(".input-group");
                if ($group.length) {
                    error.insertAfter($group);
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, event) {
                const formData = new FormData(form);
                // event.preventDefault();
                $.ajax({
                    url: "{{ route('customer.add-new-address') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".loader-wrapper").css("display", "flex")

                    },
                    success: function(response) {
                        // Handle success response
                        if (response.success) {
                            toastSuccess(response.message);
                            $('#addAddressModal').modal('toggle');
                            showCheckout();
                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please try again later.");
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        var errors = xhr.responseJSON.errors;
                        toastFail(errors)
                    },
                    complete: function() {
                        $(".loader-wrapper").css("display", "none")
                    },
                });

            }
        });
        $('#selectFromMapNewBtn').click(function() {
            isNewAddress = true;
            $('#mapModal').modal('show');
            setTimeout(() => {
                if (map) {
                    google.maps.event.trigger(map, 'resize');
                    map.setCenter({
                        lat: 40.7128,
                        lng: -74.0060
                    });
                }
            }, 300);
        });

        $(document).on("click", ".register_new_user", function() {
            $("#register_mobile_form")[0].reset();
            $("#register_otp_form")[0].reset();
            $('#step-register-mobile').show();
            $('#step-register-otp').hide();
            $("#model_login").modal('toggle');
            $("#model_register").modal('toggle');
        })
        $(document).on("click", "#guest_login", function() {
            $("#guest_login_mobile_form")[0].reset();
            $("#guest_login_otp_form")[0].reset();
            $('#guest-step-mobile').show();
            $("#guest-step-otp").hide();
            $("#model_login").modal('toggle');
            $("#model_guest_login").modal('toggle');
        })

    });

    function startResendCountdown() {
        countdown = 30;
        $('#resend-action').addClass('text-muted').removeClass('text-primary').css('pointer-events', 'none');
        $('#timer').text(`(${countdown}s)`);
        resendTimer = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(resendTimer);
                $('#timer').text('');
                $('#resend-action').removeClass('text-muted').addClass('text-primary').css('pointer-events', 'auto');
            } else {
                $('#timer').text(`(${countdown}s)`);
            }
        }, 1000);
    }

    function guestStartResendCountdown() {
        countdown = 30;
        $('#guest-resend-action').addClass('text-muted').removeClass('text-primary').css('pointer-events', 'none');
        $('#guest_timer').text(`(${countdown}s)`);
        resendTimer = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(resendTimer);
                $('#guest_timer').text('');
                $('#guest-resend-action').removeClass('text-muted').addClass('text-primary').css('pointer-events', 'auto');
            } else {
                $('#guest_timer').text(`(${countdown}s)`);
            }
        }, 1000);
    }

    async function isSubscriptionExist() {
        return new Promise((resolve) => {
            $.ajax({
                url: "{{ route('customer.check-subscription') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                method: 'get',
                beforeSend: function() {
                    $(".loader-wrapper").css("display", "flex");
                },
                success: function(response) {
                    if (response.success) {
                        mealRemaining = (response?.meal_remaining) ? response.meal_remaining : 0
                        resolve(true);
                    } else {
                        resolve(false);
                    }
                },
                error: function() {
                    resolve(false);
                },
                complete: function() {
                    $(".loader-wrapper").css("display", "none");
                },
            });
        });
    }


    async function showCheckout() {
        let is_subscription_exist = await isSubscriptionExist()
        $.ajax({
            url: "{{ route('customer.get-user-address') }}", // Change this to your server endpoint
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            method: 'post',
            beforeSend: function() {
                $(".loader-wrapper").css("display", "flex")

            },
            success: function(response) {
                if (response) {
                    currentUser = response?.data;
                    $('#customerDetails').html(`
                <p class="mb-1"><strong>Name:</strong>${response?.data?.name || ""}</p>
                <p class="mb-1"><strong>Mobile:</strong>${response?.data?.phone || ""}</p>
                <p class="mb-0"><strong>Email:</strong>${response?.data?.email || ""}</p>
            `);
                    // Address list
                    let addressHtml = '';
                    response?.data?.address.forEach(addr => {
                        const fullAddress = `${addr.address_line1}${addr.address_line2 ? ', ' + addr.address_line2 : ''} - ${addr.pincode}`;
                        if (addr.is_default) {
                            selectedAddress = addr
                        }
                        addressHtml += `
                    <div class="address-card card mb-2" onclick="selectAddress(${addr.id})">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start gap-3">
                                <input type="radio" name="address" ${addr.is_default ? 'checked' : ''}  value="${addr.id}" class="form-check-input mt-1">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <strong class="text-primary">${addr.address_type || "Default"}</strong>
                                        ${addr.is_default ? '<span class="badge bg-success">Default</span>' : ''}
                                    </div>
                                    <p class="text-muted mb-0 small">${fullAddress}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    });
                    $('#addressList').html(addressHtml);

                } else {
                    $('#model_login').modal('toggle');
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                var errors = xhr.responseJSON.errors;
                toastFail(errors)
            },
            complete: function() {
                $(".loader-wrapper").css("display", "none")
            },
        });
        // Customer details


        // Order summary
        let total = cart.reduce((sum, item) => {
            // Main item total
            let itemTotal = item.price * item.quantity;
            console.log(item)
            console.log("item.additional_items", item.additional_items)
            // Additional items total
            if (Array.isArray(item.additional_items)) {
                itemTotal += item.additional_items.reduce((addSum, addItem) => {
                    return addSum + (addItem.price * addItem.quantity);
                }, 0);
            }
            return sum + itemTotal;
        }, 0);

        const menuCategories = {
            'set': {
                name: 'Set Menu',
                icon: 'fas fa-utensils',
                color: 'primary',
                bgColor: 'bg-primary-subtle'
            },
            'alacarte': {
                name: 'Alacarte',
                icon: 'fas fa-plate-wheat',
                color: 'success',
                bgColor: 'bg-success-subtle'
            },
            'party': {
                name: 'Party Menu',
                icon: 'fas fa-birthday-cake',
                color: 'warning',
                bgColor: 'bg-warning-subtle'
            }
        };
        let summaryHtml = '';
        let isDailyTiffinAvailable=false;
        const grouped = cart.reduce((acc, item) => {
            if (!acc[item.type]) acc[item.type] = [];
            acc[item.type].push(item);
            return acc;
        }, {});
        for (const [type, items] of Object.entries(grouped)) {
            const category = menuCategories[type] || {
                name: type.charAt(0).toUpperCase() + type.slice(1),
                icon: 'fas fa-utensils',
                color: 'secondary',
                bgColor: 'bg-secondary-subtle'
            };
            const category_wisetotal = items.reduce((sum, item) => {
                const itemTotal = item.price * item.quantity;
                const additionalTotal = Array.isArray(item.additional_items) ?
                    item.additional_items.reduce((aSum, addItem) => aSum + (addItem.price * addItem.quantity), 0) :
                    0;
                return sum + itemTotal + additionalTotal;
            }, 0);
            summaryHtml += `<div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex"><h6><b>${category.name}</b></h6>`;
            if (category.name == "Alacarte") {
                summaryHtml += ` <span class="badge bg-primary" style="height:19px">${alacarteorder_date}</span>`;
            }

            summaryHtml += ` </div>
                                    <div class="text-muted fw-medium" >$${category_wisetotal.toFixed(2)}</div>
                                    </div>

            `;


            items.forEach(item => {
                summaryHtml += `
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <div class="d-flex">
                            <h6>${item.name} x ${item.quantity}</h6>`;
                let quantityAfterPlan = item.quantity;
                if (category.name == "Daywise") {
                    isDailyTiffinAvailable=true;
                    if (mealRemaining > 0) {
                        if (ifDeductAnountFromPlan) {
                            let total_meal = mealRemaining - item.quantity;
                            if (total_meal < 0) {
                                let finalQty =  Math.abs(total_meal);
                                let caalculateQtyForTotal =finalQty -item.quantity;
                                const finalPrice = item.price * Math.abs(caalculateQtyForTotal);
                                quantityAfterPlan = finalQty
                                mealRemaining = 0;
                                total = total - finalPrice;
                            } else {
                                quantityAfterPlan = 0
                                mealRemaining -= item.quantity;
                                const finalPrice = item.price * item.quantity;
                                total = total - finalPrice
                            }
                        }

                    }
                    summaryHtml += `<span class="badge bg-primary" style="height:22px">${item.order_date} (${item.day_name})</span>`;
                }
                summaryHtml += `</div>
                        <small class="fw-medium">$${(item.price * quantityAfterPlan).toFixed(2)}</small>
                    </div>
                `;
                if (item?.additional_items?.length > 0) {
                    summaryHtml += '<small style="margin-left:10px"><b>Additional Items:</b></small>'
                    item?.additional_items?.forEach(additem => {
                        summaryHtml += `
                            <div class="d-flex justify-content-between align-items-center mb-2" style="margin-left:10px">
                                <div>
                                    <small>${additem.name} x ${additem.quantity}</small>
                                </div>
                                <small class="fw-medium">$${(additem.price * additem.quantity).toFixed(2)}</small>
                            </div>
                        `;
                    });
                }
            });
            summaryHtml += ' <hr>';
        }
        summaryHtml += `<hr>`;
        console.log("is_subscription_exist " + is_subscription_exist)
        if (is_subscription_exist && isDailyTiffinAvailable) {
            summaryHtml += `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="deductPlanCheckbox" ${(ifDeductAnountFromPlan)?'checked':''}>
                    <label class="form-check-label" id="chk_deductplantext" for="deductPlanCheckbox">
                    Deduct amount from plan (Remaining meal : ${mealRemaining})
                    </label>
                </div>
                `;
        }
        summaryHtml += `
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Total:</strong>
                    <strong class="text-success">$${total.toFixed(2)}</strong>

                </div>
            `;
        $('#orderSummary').html(summaryHtml);

        $('#checkoutModal').modal('show');
    }
    $(document).on("change", "#deductPlanCheckbox", async function() {
        if ($(this).is(":checked")) {
            ifDeductAnountFromPlan = true;
            showCheckout()
            // Do something when checked
        } else {
            ifDeductAnountFromPlan = false;
            showCheckout()
            $("#chk_deductplantext").text(`Deduct amount from plan (Remaining meal : ${mealRemaining})`);
            // Do something when unchecked
        }
    });

    function selectAddress(addressId) {
        selectedAddress = currentUser.address.find(addr => addr.id === addressId);
        // Update visual selection
        $('.address-card').removeClass('selected');
        $(`.address-card input[value="${addressId}"]`).closest('.address-card').addClass('selected');
        $(`.address-card input[value="${addressId}"]`).prop('checked', true);
    }

    function completeOrder() {
        let isDeductChecked = $("#deductPlanCheckbox").is(":checked");

        const grouped = cart.reduce((acc, item) => {
            if (!acc[item.type]) acc[item.type] = [];
            acc[item.type].push(item);
            return acc;
        }, {});
        $.ajax({
            url: "{{ route('customer.add-order') }}", // Change this to your server endpoint
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                cart: grouped,
                address_id: selectedAddress.id,
                alacarteorder_date: alacarteorder_date,
                is_deduct_amount: isDeductChecked
            },
            beforeSend: function() {
                $(".loader-wrapper").css("display", "flex")

            },
            success: function(response) {
                // Handle success response
                if (response.success) {
                    toastSuccess(response.message);
                    cart = [];
                    localStorage.removeItem('cart_items')
                    currentUser = null;
                    selectedAddress = null;
                    updateCartDisplay();
                    $('#checkoutModal').modal('hide');
                    setTimeout(function() {
                        window.location.href = "cart";
                    }, 1000);

                } else {
                    toastFail((response.message) ? response.message : "Something went wrong. Please try again later.");
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                var errors = xhr.responseJSON.errors;
                toastFail(errors)
            },
            complete: function() {
                $(".loader-wrapper").css("display", "none")
            },
        });


    }
</script>
@yield('script_contact')

</html>