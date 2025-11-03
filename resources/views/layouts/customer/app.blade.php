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
    <div class="toast-container position-fixed top-0 end-0 toast-index toast-rtl" style="z-index: 999999999999999999999;">
        <div id="toast-success" class="toast fade m-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex justify-content-between bg-success text-white">
                <div class="toast-body"></div>
            </div>
        </div>
    </div>

    <!-- FAIL TOAST -->
    <div class="toast-container position-fixed top-0 end-0 toast-index toast-rtl" style="z-index: 999999999999999999999;">
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
                    <div class="w-100 text-right d-flex justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="text-center mb-3">
                        <!-- Replace src with your logo -->
                        <img src="{{asset('logo3.png')}}" width="100" alt="Logo" class="logo">
                    </div>
                    <div id="step-mobile">
                        <!-- <h5 class="mb-3 text-center">Login</h5> -->
                        <div id="mobile-alert" class="alert d-none" role="alert"></div>
                        <form id="login_mobile_form" method="post">
                            <!-- <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" class="form-control" id="txt_mobile" name="txt_mobile" placeholder="Enter 10-digit mobile">
                                </div>
                                <div class="form-text">We'll send a one-time password (OTP) to this number.</div>
                            </div> -->
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Email</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="txt_login_email" name="txt_login_email" placeholder="Enter your email">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="txt_login_password" name="txt_login_password" placeholder="Enter your password">
                                </div>
                            </div>
                            <div style="text-align: right;" class="text-right mt-1 mb-3">
                                <a href="javascript:void(0)" class="forgot_user_password">Forgot your password ?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="send-otp-btn">Sign In</button>

                            <div class="text-center mt-3">
                                <a href="javascript:void(0)" class="register_new_user">Register as New user</a>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-light border w-100" id="guest_login">Continue as Guest</button>
                            </div>
                        </form>
                    </div>

                    <!-- <div id="step-otp" style="display: none;">
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
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="model_forgotpassword" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="w-100 text-right d-flex justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="text-center mb-3">
                        <!-- Replace src with your logo -->
                        <img src="{{asset('logo3.png')}}" width="100" alt="Logo" class="logo">
                    </div>
                    <div id="step-mobile">
                        <h5 class="mb-3 text-center">Forgot your password</h5>
                        <div id="mobile-alert" class="alert d-none" role="alert"></div>
                        <form id="form_forgot_password" method="post">
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Email</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="txt_forgot_email" name="txt_forgot_email" placeholder="Enter your email">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Submit</button>
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
                    <div class="w-100 text-right d-flex justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
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
                                    <div class="input-group-prepend">
                                        <select class="form-control" name="txt_guest_countrycode" id="txt_guest_countrycode">
                                            <option value="+91">+91</option>
                                            <option value="+1" selected="">+1</option>
                                        </select>
                                    </div>
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
                                    <span id="guest_timer" class="ms-1"></span>
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
                    <div class="w-100 text-right d-flex justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
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
                                <label for="mobile" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="txt_new_password" name="txt_new_password" placeholder="Enter your password">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="txt_new_confirm_password" name="txt_new_confirm_password" placeholder="Enter your confirm password">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <div class="input-group">
                                    <!-- <span class="input-group-text">+1</span> -->
                                    <div class="input-group-prepend">
                                        <select class="form-control" name="txt_new_countrycode" id="txt_new_countrycode">
                                            <option value="+91">+91</option>
                                            <option value="+1" selected>+1</option>
                                        </select>
                                    </div>
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


    <div class="modal fade overflow-y-auto" id="checkoutModal" tabindex="-1">
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

                            <h6 class="mt-4 mb-3" id="delivertAddress">Delivery Address</h6>
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
                    <button class="btn btn-success" id="placeOrderBtn">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        Place Order</button>
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
                        <span class="addAddressModal_model"></span> New Address
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="form_addnewaddress" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="newAddressId" id="newAddressId" class="form-control">
                            <input type="hidden" name="newLat" id="newLat" class="form-control">
                            <input type="hidden" name="newLng" id="newLng" class="form-control">
                            <div class="col-md-6">
                                <div class="position-relative">
                                    <div class="map-search-box">
                                        <input type="text" class="form-control" id="mapSearchInput" style="width:100%;max-width:400px" placeholder="Search for a location...">
                                    </div>
                                    <div id="map" class="w-100" style="height:400px;"></div>
                                </div>
                                <div class="mt-3">
                                    <div class="alert alert-info">
                                        <small><i data-lucide="info"></i> Click on the map to select your delivery location</small>
                                    </div>
                                    <!-- <div id="selectedLocationInfo" style="display: none;">
                                        <h6>Selected Location:</h6>
                                        <p id="selectedLocationText" class="text-muted"></p>
                                    </div> -->
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
                        <button class="btn btn-primary" id="saveAddressBtn">Save Address</button>
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

</body>
<script src="{{ asset('customer-assets/customtoast.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="{{asset('customer-assets/validation/auth.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWYgnYkmhhENengt8Gv1qPHnyc5KxMuFk&libraries=places"></script>

<script>
    let map, marker, geocoder, autocomplete;

    function initGoogleMap() {
        try {
            const mapEl = document.getElementById("map");
            map = new google.maps.Map(mapEl, {
                center: {
                    lat: 20,
                    lng: 0
                },
                zoom: 2
            });
            geocoder = new google.maps.Geocoder();
            marker = new google.maps.Marker({
                map,
                draggable: true
            });

            // ---- Autocomplete ----
            const input = document.getElementById("mapSearchInput");
            const autocomplete = new google.maps.places.Autocomplete(input, {
                fields: ["address_components", "geometry", "name", "formatted_address"],
                types: ["geocode"], // or leave out to allow all types
            });

            // Keep the search biased to the current map bounds (optional)
            autocomplete.bindTo("bounds", map);

            // Stop Enter from submitting a form
            input.addEventListener("keydown", (e) => {
                if (e.key === "Enter") e.preventDefault();
            });

            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                if (place.geometry && place.geometry.location) {
                    const newLocation = results[0].geometry.location;
                    const pos = {
                        lat: newLocation.lat(),
                        lng: newLocation.lng(),
                    };
                    map.setCenter(place.geometry.location);
                    map.setZoom(15);
                    marker.setPosition(place.geometry.location);
                    console.log("dsds")
                    fillAddressFields(place);
                    reverseGeocode(pos)
                } else {
                    // Case 2: The place is a general area with no geometry.
                    // Use geocoder to find its coordinates by name.
                    if (place.name) {
                        geocoder.geocode({
                            'address': place.name
                        }, (results, status) => {
                            if (status === 'OK' && results[0] && results[0].geometry) {
                                const newLocation = results[0].geometry.location;
                                const pos = {
                                    lat: newLocation.lat(),
                                    lng: newLocation.lng(),
                                };
                                map.setCenter(newLocation);
                                map.setZoom(15);
                                marker.setPosition(newLocation);

                                // You might want to call a different function to fill fields
                                // as results[0] may have different components.
                                fillAddressFields(results[0]);
                                reverseGeocode(pos)
                            } else {
                                console.log('Geocode was not successful for the following reason: ' + status);
                                alert("Could not find a location for this place.");
                            }
                        });
                    }
                }
            });

            // --- 3. Add Current Location Button ---
            const locationButton = document.createElement("button");
            locationButton.textContent = "My Location";
            locationButton.classList.add("custom-map-control-button");
            locationButton.type = "button";
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);

            locationButton.addEventListener("click", () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            map.setCenter(pos);
                            map.setZoom(15);
                            marker.setPosition(pos);
                            reverseGeocode(pos);
                        },
                        () => {
                            alert("Error: The Geolocation service failed.");
                        }
                    );
                } else {
                    alert("Error: Your browser doesn't support geolocation.");
                }
            });

            // Click on map → set marker & reverse geocode
            map.addListener("click", e => {
                marker.setPosition(e.latLng);
                reverseGeocode(e.latLng);
            });

            // Marker drag → reverse geocode
            marker.addListener("dragend", () => reverseGeocode(marker.getPosition()));

            // Try current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
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
        } catch (error) {
            console.log(error)
        }
    }

    function reverseGeocode(latLng) {
        geocoder.geocode({
            location: latLng
        }, (results, status) => {
            if (status === "OK" && results[0]) {
                // document.getElementById("selectedLocationInfo").style.display = "block";
                // document.getElementById("selectedLocationText").textContent = results[0].formatted_address;
                (results[0]);
                fillAddressFromPlace(results[0])
            }
        });
    }

    function fillAddressFromPlace(place) {
        // document.getElementById("selectedLocationInfo").style.display = "block";
        // document.getElementById("selectedLocationText").textContent = place.formatted_address || "";
        fillAddressFields(place);
    }

    function fillAddressFields(place) {
        const c = {};
        console.log(place);
        if (place.address_components) {
            (place.address_components || []).forEach(comp => {
                comp.types.forEach(t => {
                    c[t] = comp.long_name;
                });
            });
        }
        // Case 2: simplified object (direct keys)
        else {
            c = place;
        }

        // --- Line 1: detailed local address ---
        const line1Parts = [];
        if (c.premise) line1Parts.push(c.premise);
        if (c.route) line1Parts.push(c.route);
        if (c.sublocality_level_2) line1Parts.push(c.sublocality_level_2);
        if (c.sublocality_level_1 && !line1Parts.includes(c.sublocality_level_1))
            line1Parts.push(c.sublocality_level_1);
        else if (c.sublocality) line1Parts.push(c.sublocality);

        // --- Line 2: broader region info ---
        const line2Parts = [];
        if (c.locality) line2Parts.push(c.locality);
        if (c.administrative_area_level_1) line2Parts.push(c.administrative_area_level_1);
        if (c.country) line2Parts.push(c.country);

        // --- Fill input fields ---
        let lat = null,
            lng = null;
        if (place.geometry && place.geometry.location) {
            lat = place.geometry.location.lat();
            lng = place.geometry.location.lng();
        } else if (place.lat && place.lng) {
            lat = place.lat;
            lng = place.lng;
        }

        if (lat && lng) {
            document.getElementById("newLat").value = lat;
            document.getElementById("newLng").value = lng;
        }
        document.getElementById("newAddress1").value = line1Parts.join(", ");
        document.getElementById("newAddress2").value = line2Parts.join(", ");
        document.getElementById("newPincode").value = c.postal_code || "";
    }

    function addCurrentLocationButton(map) {
        const locationButton = document.createElement("button");
        locationButton.textContent = "Pan to Current Location";
        locationButton.classList.add("custom-map-control-button");
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);

        locationButton.addEventListener("click", () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        map.setCenter(pos);
                        map.setZoom(15);
                        marker.setPosition(pos);
                        reverseGeocode(pos);
                    },
                    () => {
                        // Handle errors if geolocation fails
                        alert("Error: The Geolocation service failed.");
                    }
                );
            } else {
                // Browser doesn't support Geolocation
                alert("Error: Your browser doesn't support geolocation.");
            }
        });
    }
    // Initialize map only when modal is fully visible
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("addAddressModal");
        modal.addEventListener("shown.bs.modal", () => {
            if (!map) {
                initGoogleMap();
            } else {
                google.maps.event.trigger(map, "resize");
                map.setCenter(marker.getPosition() || {
                    lat: 20,
                    lng: 0
                });
            }
        });
    });
    let cart = (localStorage.getItem('cart_items')) ? JSON.parse(localStorage.getItem('cart_items')) : [];
    let activeTab = 'menu_daywise';
    let allMenuList = [];
    let searchTerm = '';
    let alacarteorder_date = '';
    let currentUser = '';
    let mealRemaining = 0;
    let selectedAddress = null;
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
        } else if (activeTab === 'menu_dining') {
            renderDiningMenu();
        }
    }

    function addToCart(item, type) {

        const cartId = `${type}-${item.id}`;
        const existing = cart.find(cartItem => cartItem.id === cartId);
        if (existing) {
            if (type != 'subscription') {
                existing.quantity += 1;
            }
        } else {
            if (type == 'subscription') {
                cart = cart.filter(cartItem => cartItem.type !== "subscription");
                cart.push({
                    id: cartId,
                    db_id: item.id,
                    name: (item?.name) ? item?.name : '',
                    days: (item?.days) ? item?.days : '',
                    total_meals: (item?.total_meals) ? item?.total_meals : '',
                    description: (item?.items) ? item?.items : item?.description,
                    price: item?.price,
                    quantity: 1,
                    type: type,
                    image: item.image
                });
            } else {
                // Suppose item.menu_date = "2025-09-23"
                const menuDateStr = item?.menu_date; // e.g. "2025-09-23"

                // 1. Create a Date object for the menu date (ignore time, use local midnight)
                const menuDate = new Date(menuDateStr + "T00:00:00");

                // 2. Get today's date and tomorrow's date (also at midnight for comparison)
                const now = new Date();
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                const tomorrow = new Date(today);
                tomorrow.setDate(today.getDate() + 1);

                // 3. Check if menuDate is tomorrow
                const isTomorrow = menuDate.getTime() === tomorrow.getTime();
                // 4. If it’s tomorrow, check if current time is after 9 PM
                if (isTomorrow) {
                    const currentHour = now.getHours(); // 0–23
                    const currentMinute = now.getMinutes(); // optional if you need minute precision
                    if (currentHour >= 21) {
                        // After 9 PM
                        toastFail("You cannot buy meal after 9 PM for tomorrow.");
                        return;
                    }
                }


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
                    is_deductamount: (type == 'daywise') ? true : false,
                    image: item.image
                });
            }
            toastSuccess("Item added to cart")

        }
        localStorage.setItem('cart_items', JSON.stringify(cart));
        updateCartDisplay();
        renderMenu();
    }

    function isDeductAmountFromPlan(type, itemId, ischecked) {
        const cartId = `${itemId}`;
        console.log("cartId", cartId)
        const existing = cart.find(cartItem => cartItem.id === cartId);
        console.log("existing", existing)
        if (existing) {
            existing.is_deductamount = ischecked;
        }
        localStorage.setItem('cart_items', JSON.stringify(cart));

    }

    function getCartQuantity(cartId) {
        const item = cart.find(cartItem => cartItem.id === cartId);
        return item ? item.quantity : 0;
    }



    function updateCartDisplay() {
        let itemCount = 0;
        let total = 0;

        cart.forEach(item => {
            const itemQty = parseFloat(item.quantity) || 0;
            const itemPrice = parseFloat(item.price) || 0;

            // Add main item
            itemCount += itemQty;
            total += itemPrice * itemQty;

            // Add additional items if present
            if (item.additional_items && Array.isArray(item.additional_items)) {
                item.additional_items.forEach(add => {
                    const addQty = parseFloat(add.quantity) || 0;
                    const addPrice = parseFloat(add.price) || 0;

                    itemCount += addQty;
                    total += addPrice * addQty;
                });
            }
        });

        // Update display
        if (itemCount == 0) {
            $('#cartCountMobile').hide();
        } else {
            $('#cartCountMobile').show();
        }

        $('#cartCountMobile').text(`${itemCount > 0 ? itemCount + '' : ''}`);
        $('#cartCount').text(`${itemCount > 0 ? itemCount + ' items' : 'Cart'}`);
        $('#cartTotal').text(`${total != 0 ? '$' + total.toFixed(2) : ''}`);
    }

    function removeFromCart(cartId) {

        const existing = cart.find(item => item.id === cartId);
        if (existing && existing.quantity > 1) {
            existing.quantity = existing.quantity - 1;
        } else {
            toastSuccess("Item removed from cart");
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
            const date = new Date(menu.menu_date);
            const formatted = date.toLocaleString("en-GB", {
                day: "2-digit",
                month: "short"
            });
            html += ` <div class="col-lg-12 border  p-3 mb-3" style="border-radius: 10px;">
                            <div class="d-flex align-items-center">
                                <div class="w-100 d-flex flex-column text-start ">
                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                        <span>${menu.title}
                                        <span class="badge bg-primary fs-6" style="text-wrap: auto;">
                                            ${formatted} - ${menu.day_name}
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
                //alacarte menu image
                // <img class="flex-shrink-0 img-fluid rounded menu-img" src="${menu.image_path ? `${assetBase}/${menu.image_path}` : defaultImage}" alt=""  onerror="this.onerror=null;this.src='{{ asset("default.png") }}">

                html += ` <div class="col-lg-12 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="w-100 d-flex flex-column text-start ">
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

    function renderDiningMenu() {

        const filteredMenu = allMenuList?.dining.map(category => ({
            ...category,
            items: category.diningmenus.filter(item =>
                item.name.toLowerCase().includes(searchTerm) ||
                item.description.toLowerCase().includes(searchTerm)
            )
        })).filter(category => category.items.length > 0);
        console.log(filteredMenu)
        let html = '';
        filteredMenu.forEach(category => {
            html += `<h5 class="mb-3">${category.category}</h5><div class="row mb-3 border p-3" style="border-radius: 10px;">`;
            category?.diningmenus?.forEach(menu => {
                //alacarte menu image
                // <img class="flex-shrink-0 img-fluid rounded menu-img" src="${menu.image_path ? `${assetBase}/${menu.image_path}` : defaultImage}" alt=""  onerror="this.onerror=null;this.src='{{ asset("default.png") }}">

                html += ` <div class="col-lg-12 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="w-100 d-flex flex-column text-start ">
                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                        <span>${menu.name}</span>
                                        <span class="text-primary">$${menu.price}</span>
                                    </h5>
                                    <div class="d-flex justify-content-between  pb-2">
                                            <small class="fst-italic">${menu.description}</small>
                                          
                                    </div>
                                </div>
                            </div>
                            <hr />
                        </div>`;
            })
            html += `</div>`;
        });
        $('.menu_dining').html(html);
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
                    name: 'Catering Platters',
                    icon: 'fas fa-utensils',
                    color: 'success',
                    bgColor: 'bg-success-subtle'
                },
                'party': {
                    name: 'Party Menu',
                    icon: 'fas fa-birthday-cake',
                    color: 'warning',
                    bgColor: 'bg-warning-subtle'
                },
                'subscription': {
                    name: 'Subscription',
                    icon: 'fas fa-id-card',
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
                    const alacarteOrderDate = localStorage.getItem('alacarte_orderdate') || "";
                    html += `
                            <div class=" p-2 mt-3">
                                <label class="form-label fw-medium">Order Date</label>
                                <input type="date" class="form-control w-auto" name="order_date_${type}" id="order_date_${type}" data-id="${type}" data-type="${type}" min="${minDate}" value="${alacarteOrderDate}">
                            </div>
                        `;
                }

                // Handle Set Menu items differently (item-wise additional menu)

                //daatwise menu image
                // <div class="me-3">
                //                                     <img 
                //                                         src="${item.image_path ?? '{{asset("default.png")}}'}"
                //                                         alt="${item.name}"
                //                                         class="rounded"
                //                                         style="width: 50px; height: 50px; object-fit: cover;"
                //                                         onerror="this.onerror=null;this.src='{{asset("default.png")}}';"
                //                                     >
                //                                 </div>

                // Handle other categories (A La Carte, Party Menu) - category-wise additional items
                html += `<div class="row p-2 ${(type === 'daywise' || type === 'subscription')?'mt-3':''}">`;
                items.forEach((item, index) => {
                    const formatted = new Date(item?.order_date).toLocaleString("en-GB", {
                        day: "2-digit",
                        month: "short"
                    });

                    html += `
                                <div class="col-lg-12 pb-2">
                                    <div class="card shadow-sm ${(type === 'daywise')?'mb-3':''}">
                                        <div class="card-body p-2">
                                            <div class="d-flex">
                                               
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between">
                                                            <div className="flex-1">
                                                                <p class="fw-semibold mb-0">${item.name} ${item.type === 'daywise' && formatted ? `<span class="badge bg-primary">${formatted} (${item.day_name || ''})</span>` : ''}
                                                                </p>
                                                                <small className="text-sm text-orange-700">${(type=="subscription")?item?.description:'$'+item.price}</small>
                                                            </div>
                                                        
                                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">`;
                    if (type == "subscription") {
                        html += `
                                                             <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-primary" onclick="removeFromCart('${item.id}')">
                                                                Remove
                                                            </button>
                                                        </div>
                                                            `;
                    } else {
                        html += `
                                                              <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-primary" onclick="removeFromCart('${item.id}')">
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                            <span class="btn btn-sm btn-white disabled">${item.quantity}</span>
                                                            <button class="btn btn-sm btn-primary" onclick="addToCartFromModal('${item.id}')">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                            `;
                    }

                    html += ` <span class="fw-bold text-primary">$${(item.price * item.quantity).toFixed(2)}</span>
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
                                // additional menu image 
                                // <div class="me-3">
                                //                                 <img 
                                //                                     src="${additem.image_path ?? '{{asset("default.png")}}'}"
                                //                                     alt="${additem.name}"
                                //                                     class="rounded"
                                //                                     style="width: 40px; height: 40px; object-fit: cover;"
                                //                                     onerror="this.onerror=null;this.src='{{asset("default.png")}}';"
                                //                                 >
                                //                             </div>
                                html += `
                                            <div class="col-lg-12">
                                                <div class="card bg-warning-subtle border-warning shadow-sm mb-3">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex">
                                                       
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between">
                                                                    <div className="flex-1">
                                                                        <p class="fw-semibold mb-0">${additem.name}</p>
                                                                        <small className="text-sm text-orange-700">$${additem.price}</small>
                                                                    </div>
                                                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
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
                    if ((index === items.length - 1) && (type === 'party')) {
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
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between">
                                                                    <div className="flex-1">
                                                                        <p class="fw-semibold mb-0">${additem.name}</p>
                                                                        <small className="text-sm text-orange-700">$${additem.price}</small>
                                                                    </div>
                                                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
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

    $(document).on('change', `#order_date_alacarte`, function() {
        if ($(this).val()) {
            localStorage.setItem('alacarte_orderdate', $(this).val());

        } else {
            localStorage.remove('alacarte_orderdate');

        }
    });

    function addToCartFromModal(cartId) {
        const existing = cart.find(item => item.id === cartId);
        if (existing) {
            existing.quantity += 1;
            localStorage.setItem('cart_items', JSON.stringify(cart));
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
            localStorage.setItem('cart_items', JSON.stringify(cart));
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
                localStorage.setItem('cart_items', JSON.stringify(cart));
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
                                toastFail("Please select the order date");
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
                    url: "{{ route('customer.sign-in') }}", // Change this to your server endpoint
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
                            $('#model_login').modal('toggle');
                            toastSuccess(response.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                            // toastSuccess("OTP sent to your mobile successfully! mobile number");
                            // $('#display-mobile').text('+91 ' + data.txt_mobile);
                            // $('#step-mobile').hide();
                            // $("#step-otp").show();
                            // startResendCountdown();
                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please contact our team or try after some time.");
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
                            toastFail((response.message) ? response.message : "Something went wrong. Please contact our team or try after some time.");
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

        //forgot password
        $('#form_forgot_password').validate({
            rules: validationRules.forgotForm.rules,
            messages: validationRules.forgotForm.messages,
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
                event.preventDefault();
                $.ajax({
                    url: "{{ route('customer.forgot-password') }}", // Change this to your server endpoint
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
                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please contact our team or try after some time.");
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

                formData.append('mobile', $("#txt_guest_mobile").val())
                formData.append('code', $("#txt_guest_countrycode").val())
                $.ajax({
                    url: "{{ route('customer.guest-login-otp') }}", // Change this to your server endpoint
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
                        if (response.success) {
                            toastSuccess("OTP sent to your mobile successfully! mobile number");
                            $('#display-guest-mobile').text('+91 ' + data.txt_guest_mobile);
                            $('#guest-step-mobile').hide();
                            $("#guest-step-otp").show();
                            startResendCountdown();

                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please contact our team or try after some time.");
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

        $('#guest_login_otp_form').validate({
            rules: {},
            messages: {},
            submitHandler: function(form, event) {
                event.preventDefault();
                let otp = '';
                $('.guest-otp-input').each(function() {
                    otp += $(this).val();
                });
                if (otp.length !== 6 || !/^\d+$/.test(otp)) {
                    toastFail('Please enter 4 digit OTP.');
                    return;
                }
                console.log(otp)
                const formData = new FormData();
                formData.append('otp', otp);
                formData.append('mobile', $("#txt_guest_mobile").val())
                formData.append('code', $("#txt_guest_countrycode").val())
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
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000); // 1000 milliseconds = 1 second

                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please contact our team or try after some time.");
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

        $(document).on('input', '.register-otp-input', function() {
            const $this = $(this);
            const val = $this.val();
            if (val.length === 1) {
                $this.next('.register-otp-input').focus();
            }
        });
        $(document).on('keydown', '.register-otp-input', function(e) {
            if (e.key === 'Backspace' && !$(this).val()) {
                $(this).prev('.register-otp-input').focus();
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
                    url: "{{ route('customer.register-user-mobile') }}", // Change this to your server endpoint
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
                            toastSuccess("OTP sent to your mobile successfully! mobile number");
                            $('#display-register-mobile').text('+91 ' + data.txt_new_mobile);
                            $('#step-register-mobile').hide();
                            $("#step-register-otp").show();
                            startResendCountdown();
                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please contact our team or try after some time.");
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
                if (otp.length !== 6 || !/^\d+$/.test(otp)) {
                    toastFail('Enter 6 digit OTP.');
                    return;
                }
                const formData = new FormData();
                formData.append('otp', otp);
                formData.append('name', $("#txt_new_name").val())
                formData.append('email', $("#txt_new_email").val())
                formData.append('mobile', $("#txt_new_mobile").val())
                formData.append('password', $("#txt_new_password").val())
                formData.append('country_code', $("#txt_new_countrycode").val())

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
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000); // 1000 milliseconds = 1 second
                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please contact our team or try after some time.");
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
            $(".addAddressModal_model").text("Add");
            // $("#checkoutModal").modal('toggle');
            $('#addAddressModal').modal('toggle');
            setTimeout(() => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(pos => {
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
            }, 2000);
        });
        $(document).on('click', '.editAddressBtn', function() {
            const addrData = JSON.parse($(this).attr('data-addr'));
            $('#addAddressModal').modal('toggle');

            $(".addAddressModal_model").text("Edit");

            setTimeout(() => {
                const fullAddress = [
                    addrData?.address_line1,
                    addrData?.address_line2,
                    addrData?.pincode
                ].filter(Boolean).join(', ');
                if (addrData?.lat && addrData?.long) {
                    // If coordinates exist, set directly
                    const pos = {
                        lat: parseFloat(addrData.lat),
                        lng: parseFloat(addrData.long)
                    };
                    map.setCenter(pos);
                    map.setZoom(15);
                    marker.setPosition(pos);
                } else {
                    geocoder.geocode({
                        address: fullAddress
                    }, (results, status) => {
                        if (status === 'OK' && results[0] && results[0].geometry) {
                            const pos = results[0].geometry.location;
                            map.setCenter(pos);
                            map.setZoom(15);
                            marker.setPosition(pos);
                            fillAddressFields(results[0]); // optional: fill form again from geocode
                        } else {
                            console.warn('Could not find coordinates for this address');
                        }
                    });
                }


                $("#newAddressId").val(addrData?.id || "");
                $("#newAddressType").val(addrData?.address_type || "");
                $("#newAddress1").val(addrData?.address_line1 || "");
                $("#newAddress2").val(addrData?.address_line2 || "");
                $("#newPincode").val(addrData?.pincode || "");

            }, 2000); // 1000ms = 1 second

            // $("#form_addnewaddress")[0].reset();
        });
        $(document).on('click', '.deleteAddressBtn', function() {
            $("#confirm_delete_address_message").modal('toggle');
            $("#hid_delete_addressid").val($(this).attr('data-id'));
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
                            toastFail((response.message) ? response.message : "Something went wrong. Please contact our team or try after some time.");
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
                        mealRemaining = (response?.meal_remaining) ? response.meal_remaining : 0;
                        if (response?.is_subscription) {
                            resolve(true);
                        } else {
                            resolve(false);
                        }
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
        $("#addAddressBtn").hide();
        $("#delivertAddress").hide();
        let is_subscription_exist = await isSubscriptionExist();
        const hasOnlySubscription = cart.length > 0 && cart.find(item => item.type == "subscription");
        const cartOnlySubscriptionPlan = cart.length > 0 && cart.every(item => item.type == "subscription");

        if (hasOnlySubscription) {
            if (is_subscription_exist) {
                toastFail("You already have an active subscription. Please remove the subscription from the cart to proceed further.");
                return;
            } else {
                mealRemaining = hasOnlySubscription?.total_meals;
            }
        }
        const onlySubscriptionData = cart.find(item => item.type === "subscription");
        let freshSubscriptionMeal = 0;
        if (onlySubscriptionData) {
            freshSubscriptionMeal = Number(onlySubscriptionData?.total_meals)
        }
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
                    if (cartOnlySubscriptionPlan) {
                        $("#addAddressBtn").hide();
                        $("#delivertAddress").hide();
                    } else {
                        $("#addAddressBtn").show();
                        $("#delivertAddress").show();
                        response?.data?.address.forEach(addr => {
                            const fullAddress = `${addr.address_line1}${addr.address_line2 ? ', ' + addr.address_line2 : ''} - ${addr.pincode}`;
                            if (addr.is_default) {
                                selectedAddress = addr
                            }
                            addressHtml += `
                        <div class="address-card card mb-2" onclick="selectAddress(${addr.id})">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <!-- Left: Radio + Address -->
                            
                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                            <input type="radio" name="address" ${addr.is_default ? 'checked' : ''} value="${addr.id}" class="form-check-input" style="padding:7px;">
                          
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <strong class="text-primary">${addr.address_type || "Default"}</strong>
                                ${addr.is_default ? '<span class="badge bg-success">Default</span>' : ''}
                                </div>
                                <p class="text-muted mb-0 small">${fullAddress}</p>
                            </div>
                              <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary editAddressBtn"   data-addr='${JSON.stringify(addr)}'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger deleteAddressBtn" data-id='${addr.id}'>
                                    <i class="bi bi-trash"></i>
                                </button>
                                </div>
                            </div>

                        </div>
                        </div>
                `;
                        });
                    }

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
                name: 'Catering Platters',
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
        let isDailyTiffinAvailable = false;
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
                if (category?.name == 'Daywise') {
                    let sub_category_total = 0;
                    // if (is_subscription_exist) {
                    //     if (mealRemaining > 0) {
                    //         if (ifDeductAnountFromPlan) {
                    //             let total_meal = mealRemaining - item.quantity;
                    //             if (total_meal < 0) {
                    //                 let finalQty = Math.abs(total_meal);
                    //                 let caalculateQtyForTotal = finalQty - item.quantity;
                    //                 const finalPrice = item.price * Math.abs(caalculateQtyForTotal);
                    //                 quantityAfterPlan = finalQty
                    //                 mealRemaining = 0;
                    //                 sub_category_total = sub_category_total - finalPrice;
                    //             } else {
                    //                 quantityAfterPlan = 0
                    //                 mealRemaining -= item.quantity;
                    //                 const finalPrice = item.price * mealRemaining;
                    //                 sub_category_total = sub_category_total - finalPrice
                    //             }
                    //         }

                    //     }
                    // } else {
                    //     if (onlySubscriptionData) {
                    //         let total_meal = freshSubscriptionMeal - item.quantity;
                    //         console.log("total_meal", total_meal)
                    //         if (total_meal < 0) {
                    //             console.log("call this total_meal")
                    //             const usedFromPlan = freshSubscriptionMeal; // use all remaining plan meals
                    //             const remainingQty = item.quantity - usedFromPlan; // qty not covered by plan
                    //             const finalPrice = item.price * remainingQty; // charge only remaining
                    //             quantityAfterPlan = remainingQty;
                    //             freshSubscriptionMeal = 0; // plan exhausted
                    //             sub_category_total = sub_category_total - finalPrice;
                    //         } else {
                    //             console.log("not call this total_meal")

                    //             quantityAfterPlan = 0;
                    //             freshSubscriptionMeal -= item.quantity; // reduce available meals
                    //             const finalPrice = item.price * 0;
                    //             sub_category_total = sub_category_total - finalPrice;
                    //             console.log("freshSubscriptionMeal", freshSubscriptionMeal)

                    //         }
                    //     }
                    // }
                    const itemTotal = sub_category_total;
                    console.log("itemTotal", itemTotal)
                    const additionalTotal = Array.isArray(item.additional_items) ?
                        item.additional_items.reduce((aSum, addItem) => aSum + (addItem.price * addItem.quantity), 0) :
                        0;
                    return sum + itemTotal + additionalTotal;
                } else {
                    const itemTotal = item.price * item.quantity;

                    const additionalTotal = Array.isArray(item.additional_items) ?
                        item.additional_items.reduce((aSum, addItem) => aSum + (addItem.price * addItem.quantity), 0) :
                        0;
                    return sum + itemTotal + additionalTotal;
                }

            }, 0);
            summaryHtml += `<div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex"><h6><b>${category.name}</b></h6>`;
            if (category.name == "Alacarte") {
                summaryHtml += ` <span class="badge bg-primary" style="height:19px">${alacarteorder_date}</span>`;
            }

            summaryHtml += ` </div>
                                    </div>

            `;
            // <div class="text-muted fw-medium" >$${category_wisetotal.toFixed(2)}</div>

            items.forEach(item => {
                summaryHtml += `
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <div class="d-flex">
                            <h6>${item.name} ${(category.name == "Subscription")?'':'x' +item.quantity}</h6>`;

                let quantityAfterPlan = item.quantity;
                if (category.name == "Daywise") {
                    isDailyTiffinAvailable = true;
                    if (is_subscription_exist) {
                        if (mealRemaining > 0) {

                            if (item.is_deductamount) {
                                let total_meal = mealRemaining - item.quantity;
                                if (total_meal < 0) {
                                    let finalQty = Math.abs(total_meal);
                                    let caalculateQtyForTotal = finalQty - item.quantity;
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
                    } else {
                        if (onlySubscriptionData) {
                            let total_meal = freshSubscriptionMeal - item.quantity;
                            if (total_meal < 0) {
                                let finalQty = Math.abs(total_meal);
                                let caalculateQtyForTotal = finalQty - item.quantity;
                                const finalPrice = item.price * Math.abs(caalculateQtyForTotal);
                                quantityAfterPlan = finalQty
                                freshSubscriptionMeal = 0;
                                total = total - finalPrice;
                            } else {
                                quantityAfterPlan = 0
                                freshSubscriptionMeal -= item.quantity;
                                const finalPrice = item.price * item.quantity;
                                total = total - finalPrice
                            }
                        }
                    }
                    const date = new Date(item.order_date);
                    const daywise_order_summary_date = date.toLocaleString("en-GB", {
                        day: "2-digit",
                        month: "short"
                    });
                    summaryHtml += `<span class="badge bg-primary" style="height:22px">${daywise_order_summary_date} (${item.day_name})</span>`;
                }
                summaryHtml += `</div>
                        <small class="fw-medium">$${(item.price * quantityAfterPlan).toFixed(2)}</small>
                    </div>
                `;
                if (category.name == 'Daywise' && is_subscription_exist) {
                    summaryHtml += `<div class="form-check ml-2">
                                    <input class="form-check-input chk_isdeductamount" data-id="${item.id}" data-type="${item.type}" type="checkbox" id="chk_ded_${item.id}" ${(item.is_deductamount)?'checked':''}>
                                    <label class="form-check-label" id="lbl_ded_${item.id}" for="chk_ded_${item.id}">Deduct meal from plan
                                    </label>
                                </div>`;
                }
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
        // summaryHtml += `
        //         <p>Remaining meal : ${mealRemaining}</p>`;
        summaryHtml += `
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Total:</strong>
                    <strong class="text-gray">$${total.toFixed(2)}</strong>

                </div>
            `;
        summaryHtml += `
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <strong>USD 0.8</strong>
                    <strong class="text-success">$${(total * 1.25).toFixed(2)}</strong>

                </div>
            `;

        $('#orderSummary').html(summaryHtml);
        $('#checkoutModal').modal('show');
    }

    $(document).on("change", ".chk_isdeductamount", async function() {
        if ($(this).is(":checked")) {
            isDeductAmountFromPlan($(this).attr('data-type'), $(this).attr('data-id'), true)
        } else {
            isDeductAmountFromPlan($(this).attr('data-type'), $(this).attr('data-id'), false)
        }
        showCheckout()
    })

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

    //forgot user password
    $(document).on("click", '.forgot_user_password', function() {
        $("#model_login").modal('toggle');
        $("#form_forgot_password").validate().resetForm()
        $("#form_forgot_password")[0].reset();
        $("#model_forgotpassword").modal('toggle');
    });


    function selectAddress(addressId) {
        selectedAddress = currentUser.address.find(addr => addr.id === addressId);
        // Update visual selection
        $('.address-card').removeClass('selected');
        $(`.address-card input[value="${addressId}"]`).closest('.address-card').addClass('selected');
        $(`.address-card input[value="${addressId}"]`).prop('checked', true);
    }

    function completeOrder() {
        const $btn = $('#placeOrderBtn');
        const $spinner = $btn.find('.spinner-border');
        if (!selectedAddress?.id) {
            toastFail("Please select a delivery address");
            return;
        }
        const grouped = cart.reduce((acc, item) => {
            if (!acc[item.type]) acc[item.type] = [];
            acc[item.type].push(item);
            return acc;
        }, {});
        console.log(grouped.hasOwnProperty('subscription'))
        if (!grouped.hasOwnProperty('subscription')) {
            grouped.subscription = [];
        }


        $.ajax({
            url: "{{ route('customer.add-order') }}", // Change this to your server endpoint
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                cart: grouped,
                address_id: selectedAddress?.id || '',
                alacarteorder_date: alacarteorder_date,
            },
            beforeSend: function() {
                $spinner.removeClass('d-none');
                $btn.prop('disabled', true);
            },
            success: function(response) {
                // Handle success response
                if (response.success) {
                    toastSuccess(response.message);
                    cart = [];
                    localStorage.removeItem('cart_items')
                    localStorage.removeItem('alacarte_orderdate')
                    currentUser = null;
                    selectedAddress = null;
                    updateCartDisplay();
                    $('#checkoutModal').modal('hide');
                    setTimeout(function() {
                        window.location.href = "order";
                    }, 1000);

                } else {
                    toastFail((response.message) ? response.message : "Something went wrong. Please contact our team or try after some time.");
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                var errors = xhr.responseJSON.errors;
                toastFail(errors)
            },
            complete: function() {
                $spinner.addClass('d-none');
                $btn.prop('disabled', false);
            },
        });





    }
</script>
@yield('script_contact')

</html>