@extends('layouts.admin.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .gradient-header {
        background: linear-gradient(135deg, #e97444 0%, #dc2626 100%);
    }

    .gradient-bg {
        background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
        min-height: 100vh;
    }

    .menu-item-image {
        width: 64px;
        height: 64px;
        object-fit: cover;
    }

    .cart-item-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }

    .party-menu-image {
        height: 200px;
        object-fit: cover;
    }

    .quantity-control {
        min-width: 20px;
        text-align: center;
    }

    .nav-pills .nav-link {
        color: #000;
    }

    .nav-pills .nav-link.active {
        background-color: #FF8C00;
    }

    .badge-dot {
        width: 6px;
        height: 6px;
    }

    .modal-backdrop-custom {
        background-color: rgba(0, 0, 0, 0.5);
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1040;
    }

    #map {
        height: 300px;
        width: 100%;
        border-radius: 8px;
    }

    .address-card {
        border: 2px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .address-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .address-card.selected {
        border-color: #0d6efd;
        background-color: #f8f9ff;
    }

    .map-search-box {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        z-index: 1000;
    }

    .address-form-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    #menu_type_tab {
        border-bottom: 1px solid #000;
    }
</style>

<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Orders</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <div class="card">


                    <div class="card-body py-4">
                        <!-- Search and Cart -->
                        <div class="row mb-4">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search menu items...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center gap-2" id="cartBtn">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span id="cartCount">0 items</span>
                                    <span class="text-dark fw-bold" id="cartTotal">$0.00</span>
                                </button>
                            </div>
                        </div>

                        <!-- Menu Tabs -->
                        <ul class="nav nav-pills nav-fill mb-4" id="menu_type_tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active d-flex align-items-center justify-content-center gap-2"
                                    id="daywise-tab" data-tab="daywise">
                                    <i data-lucide="calendar"></i>
                                    <span class="d-none d-sm-inline">Day-wise</span>
                                    <span class="d-sm-none">Daily</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center justify-content-center gap-2"
                                    id="alacarte-tab" data-tab="alacarte">
                                    <i data-lucide="utensils"></i>
                                    <span class="d-none d-sm-inline">Alacarte</span>
                                    <span class="d-sm-none">Menu</span>
                                </button>
                            </li>

                        </ul>

                        <!-- Menu Content -->
                        <div id="menuContent">
                            <!-- Day-wise Menu -->
                            <div id="daywise-content" class="tab-content active"></div>

                            <!-- A-la-carte Menu -->
                            <div id="alacarte-content" class="tab-content" style="display: none;"></div>

                            <!-- Party Menu -->
                            <!-- <div id="party-content" class="tab-content" style="display: none;"></div> -->
                        </div>
                    </div>
                </div>

                <!-- Cart Modal -->
                <div class="modal fade" id="cartModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-flex align-items-center gap-2">
                                    <i data-lucide="shopping-cart"></i>
                                    <span id="cartModalTitle">Your Cart (0 items)</span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" id="cartModalBody">
                                <div class="text-center py-4">
                                    <p class="text-muted">Your cart is empty</p>
                                </div>
                            </div>
                            <div class="modal-footer" id="cartModalFooter" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <div class="fs-5 fw-bold">
                                        Total: <span class="text-success" id="cartModalTotal">$0.00</span>
                                    </div>
                                    <button class="btn btn-success" id="checkoutBtn">Proceed to Checkout</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Form Modal -->
                <div class="modal fade" id="userModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
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

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="modal-body" id="mapSelectForm" style="display: none;">
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
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile Number</label>
                                                <div class="input-group">
                                                    <input type="tel" class="form-control" name="mobileInput" id="mobileInput" placeholder="Enter mobile number">
                                                    <button class="btn btn-outline-primary" id="checkMobileBtn">Check</button>
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
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Address Type</label>
                                                            <select class="form-select" name="addressTypeInput" id="addressTypeInput">
                                                                <option value="Home">Home</option>
                                                                <option value="Office">Office</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Address Line 1</label>
                                                            <input type="text" class="form-control" name="address1Input" id="address1Input" placeholder="House/Flat No, Building">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Address Line 2</label>
                                                            <input type="text" class="form-control" name="address2Input" id="address2Input" placeholder="Street, Area, Landmark">
                                                        </div>
                                                        <!-- <div class="col-md-6 mb-3">
                                                    <label class="form-label">City</label>
                                                    <input type="text" class="form-control" name="cityInput" id="cityInput" placeholder="City">
                                                </div> -->
                                                    </div>
                                                    <div class="row">
                                                        <!-- <div class="col-md-4 mb-3">
                                                    <label class="form-label">State</label>
                                                    <input type="text" class="form-control" name="stateInput" id="stateInput" placeholder="State">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Country</label>
                                                    <input type="text" class="form-control" name="countryInput" id="countryInput" placeholder="Country">
                                                </div> -->
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Pin Code</label>
                                                            <input type="text" class="form-control" name="pincodeInput" id="pincodeInput" placeholder="Pin Code">
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-outline-secondary" type="button" id="selectFromMapBtn">
                                                        <i data-lucide="map-pin"></i>
                                                        Select from Map
                                                    </button>
                                                </div>
                                            </div>
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

                <!-- Map Selection Modal -->
                <div class="modal fade" id="mapModal" tabindex="-1">
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
                </div>

                <!-- Checkout Modal -->
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
                                        <button type="button" class="btn btn-outline-primary btn-sm addAddressBtn" id="addAddressBtn">
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

                <!-- Add Address Modal -->
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

                                    <div class="address-form-section">
                                        <div class="row">
                                            <div class="col-md-6">
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
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
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
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Pin Code</label>
                                                        <input type="text" class="form-control" name="newPincode" id="newPincode" placeholder="Pin Code">
                                                    </div>
                                                </div>

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


                <!-- /.card -->
            </div>
        </div>
    </div>
    <!--end::Container-->
    </div>

    <!--end::App Content-->
</main>
<script src="{{asset('admin-assets/validation/neworder.js')}}"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initMap"></script>
<script>
    // Sample data with detailed addresses


    // Sample user database with detailed addresses

    // Global variables
    let cart = [];
    let currentUser = null;
    let selectedAddress = null;
    let activeTab = 'daywise';
    let searchTerm = '';
    let map = null;
    let marker = null;
    let geocoder = null;
    let selectedLocation = null;
    let isNewAddress = false;
    let allMenuList = [];
    let alacarteorder_date = "",
        partyorder_date = "";
    // Initialize the application
    $(document).ready(function() {
        $.ajax({
            url: '{{route("admin.getallmenulist")}}', // Replace with your actual GET URL
            type: 'GET',
            dataType: 'json', // or 'html' depending on what the server returns
            success: function(response) {
                allMenuList = response.data;
                renderMenu();
                bindEvents();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                $('#result').html('Failed to load data.');
            }
        });

    });

    // Initialize Google Maps
    function initMap() {
        // Default location (New York City)
        const defaultLocation = {
            lat: 40.7128,
            lng: -74.0060
        };

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: defaultLocation,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false
        });

        geocoder = new google.maps.Geocoder();

        // Create marker
        marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true,
            title: 'Selected Location'
        });

        // Map click event
        map.addListener('click', function(event) {
            const location = event.latLng;
            marker.setPosition(location);
            selectedLocation = {
                lat: location.lat(),
                lng: location.lng()
            };
            reverseGeocode(location);
            $('#confirmLocationBtn').prop('disabled', false);
        });

        // Marker drag event
        marker.addListener('dragend', function(event) {
            const location = event.latLng;
            selectedLocation = {
                lat: location.lat(),
                lng: location.lng()
            };
            reverseGeocode(location);
            $('#confirmLocationBtn').prop('disabled', false);
        });

        // Search box
        const searchBox = new google.maps.places.SearchBox(document.getElementById('mapSearchInput'));

        searchBox.addListener('places_changed', function() {
            const places = searchBox.getPlaces();
            if (places.length === 0) return;

            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            map.setCenter(place.geometry.location);
            map.setZoom(15);
            marker.setPosition(place.geometry.location);

            selectedLocation = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng()
            };

            displayLocationInfo(place);
            $('#confirmLocationBtn').prop('disabled', false);
        });
    }

    function reverseGeocode(location) {
        geocoder.geocode({
            location: location
        }, function(results, status) {
            if (status === 'OK' && results[0]) {
                displayLocationInfo(results[0]);
            }
        });
    }

    function displayLocationInfo(place) {
        $('#selectedLocationInfo').show();
        $('#selectedLocationText').text(place.formatted_address || place.name);

        // Parse address components
        if (place.address_components) {
            const addressComponents = parseAddressComponents(place.address_components);
            selectedLocation.addressComponents = addressComponents;
            selectedLocation.formattedAddress = place.formatted_address;
        }
    }

    function parseAddressComponents(components) {
        const addressData = {
            address1: '',
            address2: '',
            city: '',
            state: '',
            country: '',
            pincode: ''
        };

        components.forEach(component => {
            const types = component.types;

            if (types.includes('street_number')) {
                addressData.address1 = component.long_name + ' ';
            }
            if (types.includes('route')) {
                addressData.address1 += component.long_name;
            }
            if (types.includes('sublocality') || types.includes('neighborhood')) {
                addressData.address2 = component.long_name;
            }
            if (types.includes('locality') || types.includes('administrative_area_level_2')) {
                addressData.city = component.long_name;
            }
            if (types.includes('administrative_area_level_1')) {
                addressData.state = component.short_name;
            }
            if (types.includes('country')) {
                addressData.country = component.long_name;
            }
            if (types.includes('postal_code')) {
                addressData.pincode = component.long_name;
            }
        });

        return addressData;
    }

    function bindEvents() {
        // Tab switching
        $('.nav-link[data-tab]').click(function() {
            const tab = $(this).data('tab');
            switchTab(tab);
        });

        // Search functionality
        $('#searchInput').on('input', function() {
            searchTerm = $(this).val().toLowerCase();
            renderMenu();
        });

        // Cart button
        $('#cartBtn').click(function() {
            showCart();
        });

        // Checkout button
        $('#checkoutBtn').click(function() {
            currentUser = null;
            $("#mobileInput").val('');
            $('#addressSelectionContainer').html('');
            alacarteorder_date = $("#order_date_alacarte").val();
            partyorder_date = $("#order_date_party").val();
            if ($('#order_date_party').is(':visible')) {
                const value = $('#order_date_party').val();
                if (!value) {
                    toastFail("Party Order Date is required!");
                    return;
                }
            }
            if ($('#order_date_alacarte').is(':visible')) {
                const value = $('#order_date_alacarte').val();
                if (!value) {
                    // Value is empty
                    toastFail("Alacarte Order Date is required!");
                    return;
                }
            }

            if (!currentUser) {
                $('#userModal .modal-dialog').removeClass('model-lg');
                $('#userModal .modal-dialog').addClass('model-xl');

                $('#cartModal').modal('hide');
                $('#userModal').modal('show');
            } else {
                $('#cartModal').modal('hide');
                showCheckout();
            }
        });

        // Mobile check
        $('#checkMobileBtn').click(function() {
            checkMobileNumber();
        });

        // Save user
        $('#saveUserBtn').click(function() {
            addNewUser();
        });

        // Continue to checkout
        $('#continueCheckoutBtn').click(function() {
            $('#userModal').modal('hide');
            showCheckout();
        });

        // Place order
        $('#placeOrderBtn').click(function() {
            completeOrder();
        });

        // Add address
        $(document).on('click', '.addAddressBtn', function() {
            isNewAddress = true;
            clearAddressForm();
            $('#addAddressModal').modal('show');
        });
        let clickedBtn = null;
        $('#form_addnewuser button[type="submit"]').on('click', function() {
            clickedBtn = $(this); // remember which was clicked
        });
        // Save address
        $('#form_addnewuser').validate({
            rules: validationRules.addNewCustomer.rules,
            messages: validationRules.addNewCustomer.messages,
            submitHandler: function(form, event) {
                const formData = new FormData(form);
                if (clickedBtn) {
                    // event.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.add-new-user') }}", // Change this to your server endpoint
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
                                const newUser = {
                                    id: response.user_id,
                                    phone: $('#mobileInput').val(),
                                    name: $('#userNameInput').val(),
                                    email: $('#userEmailInput').val(),
                                    address: [{
                                        id: response.address_id,
                                        address_type: $('#addressTypeInput').val(),
                                        address_line1: $('#address1Input').val(),
                                        address_line2: $('#address2Input').val(),
                                        city: $('#cityInput').val(),
                                        state: $('#stateInput').val(),
                                        country: $('#countryInput').val(),
                                        pincode: $('#pincodeInput').val(),
                                        is_default: true
                                    }]
                                };
                                currentUser = newUser;
                                selectedAddress = newUser.address[0];

                                $('#userModal').modal('hide');
                                showCheckout();

                            } else {
                                toastFail((response.message) ? response.message : "Application cant register try again");
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
            }
        });


        // Select from map buttons
        $('#selectFromMapBtn').click(function() {
            isNewAddress = false;
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

        // Confirm location
        $('#confirmLocationBtn').click(function() {
            if (selectedLocation && selectedLocation.addressComponents) {
                fillAddressFromMap(selectedLocation.addressComponents);
                $('#mapModal').modal('hide');
            }
        });
    }

    function fillAddressFromMap(addressData) {
        if (isNewAddress) {
            $('#newAddress1').val(addressData.address1);
            $('#newAddress2').val(addressData.address2);
            $('#newCity').val(addressData.city);
            $('#newState').val(addressData.state);
            $('#newCountry').val(addressData.country);
            $('#newPincode').val(addressData.pincode);
        } else {
            $('#address1Input').val(addressData.address1);
            $('#address2Input').val(addressData.address2);
            $('#cityInput').val(addressData.city);
            $('#stateInput').val(addressData.state);
            $('#countryInput').val(addressData.country);
            $('#pincodeInput').val(addressData.pincode);
        }
    }

    function clearAddressForm() {
        $('#newAddress1').val('');
        $('#newAddress2').val('');
        $('#newCity').val('');
        $('#newState').val('');
        $('#newCountry').val('USA');
        $('#newPincode').val('');
    }

    function switchTab(tab) {
        activeTab = tab;
        $('.nav-link').removeClass('active');
        $(`#${tab}-tab`).addClass('active');
        $('.tab-content').hide();
        $(`#${tab}-content`).show();
        renderMenu();
    }

    function renderMenu() {
        if (activeTab === 'daywise') {
            renderDayWiseMenu();
        } else if (activeTab === 'alacarte') {
            renderAlaCarteMenu();
        } else if (activeTab === 'party') {
            renderPartyMenu();
        }
    }

    function renderDayWiseMenu() {
        const filteredMenu = allMenuList?.daywise.filter(menu =>
            menu.title?.toLowerCase().includes(searchTerm) ||
            menu.items?.toLowerCase().includes(searchTerm)
        );
        console.log("filteredMenu", filteredMenu)
        const today = new Date().toISOString().split("T")[0];
        let html = ' <div class="row g-3">';
        filteredMenu.forEach(menu => {
            menu.type = "daywise";
            const isToday = menu.menu_date === today;
            html += `

               
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex gap-3">
                                  <img 
                        src="${menu.image_path ?? '{{asset("default.png")}}'}" 
                        alt="${menu.title}" 
                        class="rounded menu-item-image"
                        onerror="this.onerror=null;this.src='{{asset('default.png')}}';"
                    >
                                <div class="">
                                    <h6 class="card-title mb-1">${menu.title}
                                    ${isToday ? '<span class="badge bg-warning text-dark ms-2">Today</span>' : ''}
                                    </h6>
                                                                    <p class="card-text small text-muted mb-1"><i class="fa fa-calendar"></i> ${menu.menu_date}</p>

                                    <p class="card-text small text-muted mb-2">${menu.items}</p>
                                     <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-success"><i class="fa fa-usd"></i>${menu.price}</span>
                              </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-outline-secondary btn-sm" onclick="removeFromCart('daywise-${menu.id}')" ${getCartQuantity('daywise-' + menu.id) === 0 ? 'disabled' : ''}>
                                            <i class="fa fa-minus"></i>
                                          </button>
                                        <span class="fw-medium quantity-control">${getCartQuantity('daywise-' + menu.id)}</span>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="addToCart(${JSON.stringify(menu).replace(/"/g, '&quot;')}, 'daywise')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                

    `;
        });
        html += '</div>';
        $('#daywise-content').html(html);

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
            html += `
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-success bg-opacity-10">
                            <h5 class="card-title text-success mb-0">${category.category}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                ${category.items.map(item => `
                                    <div class="col-md-6">
                                        <div class="card h-100 shadow-sm">
                                            <div class="card-body d-flex gap-3">
                                               <img 
                                                src="${item.image_path ?? '{{asset("default.png")}}'}" 
                                                alt="${item.title}" 
                                                class="rounded menu-item-image"
                                                onerror="this.onerror=null;this.src='{{asset('default.png')}}';"
                                            >
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title mb-0">${item.name}</h6>
                                                        <span class="fs-5 fw-bold text-success">$${item.price}</span>
                                                    </div>
                                                    <p class="card-text small text-muted mb-3">${item.description}</p>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <button class="btn btn-outline-secondary btn-sm" onclick="removeFromCart('alacarte-${item.id}')" ${getCartQuantity('alacarte-' + item.id) === 0 ? 'disabled' : ''}>
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                        <span class="fw-medium quantity-control">${getCartQuantity('alacarte-' + item.id)}</span>
                                                        <button class="btn btn-outline-secondary btn-sm" onclick="addToCart(${JSON.stringify(item).replace(/"/g, '&quot;')}, 'alacarte')">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
        });

        $('#alacarte-content').html(html);

    }

    function renderPartyMenu() {
        const filteredMenu = allMenuList?.party.filter(menu =>
            menu.name.toLowerCase().includes(searchTerm) ||
            menu.description.toLowerCase().includes(searchTerm)
        );

        let html = ' <div class="row g-3">';
        filteredMenu.forEach(menu => {
            menu.type = "party";

            html += `

               
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex gap-3">
                            <img 
                    src="${menu.image_path ?? '{{asset("default.png")}}'}" 
                    alt="${menu.name}" 
                    class="rounded menu-item-image"
                    onerror="this.onerror=null;this.src='{{asset('default.png')}}';"
                >
                            <div class="d-flex flex-column">
                                <h6 class="card-title mb-1">${menu.name}</h6>
                                 <p class="card-text small text-muted mb-2">${menu.description}</p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-success"><i class="fa fa-usd"></i>${(menu.price_per_kg)?menu.price_per_kg+"/<small>Per kg</small>":menu.price_per_qty+"/<small>Per qty</small>"}</span>
                        </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="removeFromCart('party-${menu.id}')" ${getCartQuantity('party-' + menu.id) === 0 ? 'disabled' : ''}>
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <span class="fw-medium quantity-control">${getCartQuantity('party-' + menu.id)}</span>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="addToCart(${JSON.stringify(menu).replace(/"/g, '&quot;')}, 'party')">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                `;
        });
        html += '</div>';

        $('#party-content').html(html);

    }


    function additionalMenu(type) {
        let html = '<div class="row g-3 mt-2">';
        html += `<h6 class="text-muted">Additional Items for ${type.charAt(0).toUpperCase() + type.slice(1)}</h6>`;

        allMenuList?.additional_menu.forEach(menu => {
            const cartId = `${type}-additional-${menu.id}`;
            html += `
            <div class="col-md-6 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex gap-3">
                        <img 
                            src="${menu.image_path ?? '{{asset("default.png")}}'}" 
                            alt="${menu.name}" 
                            class="rounded menu-item-image"
                            height="45"
                            onerror="this.onerror=null;this.src='{{asset("default.png")}}';"
                        >
                        <div class="d-flex flex-column w-100">
                            <h6 class="card-title mb-1">${menu.name}</h6>
                            <p class="card-text small text-muted mb-2">${menu.description}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success"><i class="fa fa-usd"></i>${menu.price}</span>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="removeFromCart('${cartId}')" ${getCartQuantity(cartId) === 0 ? 'disabled' : ''}>
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <span class="fw-medium">${getCartQuantity(cartId)}</span>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="addToCart(${JSON.stringify(menu).replace(/"/g, '&quot;')}, '${type}-additional')">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        });

        html += '</div>';
        return html;
    }

    function addToCart(item, type) {

        const cartId = `${type}-${item.id}`;
        const existing = cart.find(cartItem => cartItem.id === cartId);
        console.log("existing", cartId)
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({
                id: cartId,
                db_id: item.id,
                order_date: (item?.menu_date) ? item?.menu_date : "",
                name: (item?.name) ? item?.name : item?.title,
                description: (item?.items) ? item?.items : item?.description,
                price: (item?.price) ? item?.price : (item?.price_per_kg) ? item?.price_per_kg : item?.price_per_qty,
                price_type: (item?.price_per_kg) ? item?.price_per_kg : (item?.price_per_qty) ? item?.price_per_qty : "",
                quantity: 1,
                type: type,
                image: item.image
            });
        }

        updateCartDisplay();
        renderMenu();
    }

    function removeFromCart(cartId) {

        const existing = cart.find(item => item.id === cartId);
        console.log("existing", existing)

        if (existing && existing.quantity > 1) {
            existing.quantity = existing.quantity - 1;
        } else {
            cart = cart.filter(item => item.id !== cartId);
        }

        updateCartDisplay();
        renderMenu();
        showCart();
    }

    function removeItemCompletely(cartId) {
        cart = cart.filter(item => item.id !== cartId);
        updateCartDisplay();
        showCart();
    }

    function getCartQuantity(cartId) {
        const item = cart.find(cartItem => cartItem.id === cartId);
        return item ? item.quantity : 0;
    }

    function updateCartDisplay() {
        const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
        const total = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        $('#cartCount').text(`${itemCount} items`);
        $('#cartTotal').text(`$${total.toFixed(2)}`);
    }

    function showCart() {
        const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
        const total = cart.reduce((sum, item) => {
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

            console.log(grouped);
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
                    name: 'A La Carte',
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
                console.log("additionalItemTotal", additionalItemTotal)
                html += `
                <div class="cart-section mb-4">
                    <!-- Category Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <span class="badge ${category.bgColor} text-${category.color} border border-${category.color} px-3 py-2 me-2">
                                <i class="${category.icon} me-1"></i>
                                ${category.name}
                            </span>
                        </div>
                        <div class="text-muted fw-medium">$${(categoryTotal+additionalItemTotal).toFixed(2)}</div>
                    </div>
            `;

                // Show order date input for certain types
                if (type === 'alacarte' || type === 'party') {
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    const minDate = tomorrow.toISOString().split('T')[0];

                    html += `
                    <div class="mb-3">
                        <label class="form-label fw-medium">Order Date</label>
                        <input type="date" class="form-control" name="order_date_${type}" id="order_date_${type}" min="${minDate}">
                    </div>
                `;
                }

                // Handle Set Menu items differently (item-wise additional menu)

                // Handle other categories (A La Carte, Party Menu) - category-wise additional items
                html += `<div class="row ${(type === 'alacarte' || type === 'party')?'border rounded':''}">`;
                console.log("items", items)
                items.forEach((item, index) => {
                    html += `
                        <div class="col-lg-12 ${(type === 'daywise')?'border rounded p-3 mb-3':'pt-3'}">
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
                                                        <p class="fw-semibold mb-0">${item.name}</p>
                                                        <small className="text-sm text-orange-700">$${item.price}</small>
                                                    </div>
                                                
                                                  <div class="d-flex justify-content-between align-items-center gap-3">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="removeFromCart('${item.id}')">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                    <span class="btn btn-sm btn-light disabled">${item.quantity}</span>
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="addToCartFromModal('${item.id}')">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                                <span class="fw-bold text-success">$${(item.price * item.quantity).toFixed(2)}</span>
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



                html += `
                    
                </div>
                <hr class="my-4">
            `;
            }

            $('#cartModalBody').html(html);
            $('#cartModalFooter').show();
        }

        $('#cartModal').modal('show');
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

    function addToCartFromModal(cartId) {
        const existing = cart.find(item => item.id === cartId);
        if (existing) {
            existing.quantity += 1;
            updateCartDisplay();
            showCart();
            renderMenu();

        }
    }

    function checkMobileNumber() {
        const mobile = $('#mobileInput').val();
        checkWithDBMobileExist(mobile)
    }

    function checkWithDBMobileExist(mobile) {
        $.ajax({
            url: '{{route("admin.checkmobileexist")}}', // Replace with your actual GET URL
            type: 'get',
            data: {
                mobile: mobile
            },
            dataType: 'json', // or 'html' depending on what the server returns
            success: function(response) {

                if (response?.data) {
                    currentUser = response.data;
                    const addressList = currentUser.address || [];
                    let html = '';

                    if (addressList.length > 0) {
                        html += `<h5>Select Address</h5><div id="addressOptions" class="mb-3">
                          <button type="button" class="btn btn-outline-primary btn-sm addAddressBtn" id="addAddressBtn">
                                    <i data-lucide="plus"></i>
                                    Add New Address
                                </button>
                        `;

                        addressList.forEach((addr, index) => {
                            if (addr?.is_default == 1) {
                                selectedAddress = addr;
                            }
                            const isChecked = addr?.is_default == 1 || (addr?.is_default == 0 && index === 0);
                            const addressText = `
                                ${addr.label ? `<strong>${addr.label}:</strong> ` : ''}
                                ${addr.address_line1}, ${addr.address_line2}, ${addr.city} - ${addr.pincode}
                            `;
                            html += `
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="selectedAddress" 
                                    id="address_${addr.id}" value="${addr.id}" 
                                    ${isChecked ? 'checked' : ''}>
                                <label class="form-check-label" for="address_${addr.id}">
                                    ${addressText}
                                </label>
                            </div>
                        `;
                        });

                        html += `</div>`;
                    } else {
                        html += `<h5>Select Address</h5><div id="addressOptions" class="mb-3">
                          <button type="button" class="btn btn-outline-primary btn-sm addAddressBtn" id="addAddressBtn">
                                    <i data-lucide="plus"></i>
                                    Add New Address
                                </button>
                        `;
                    }
                    $('#addressSelectionContainer').html(html).show();
                    $('#newUserForm').hide();
                    $('#mapSelectForm').hide();
                    $('#saveUserBtn').hide();
                    $('#continueCheckoutBtn').show();
                } else {
                    currentUser = null;
                    $('#addressSelectionContainer').hide().empty();

                    $('#newUserForm').show();
                    $('#mapSelectForm').show();
                    $('#saveUserBtn').show();
                    $('#continueCheckoutBtn').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                $('#result').html('Failed to load data.');
            }
        });
    }



    function showCheckout() {
        if (!currentUser) return;
        // Customer details
        $('#customerDetails').html(`
                <p class="mb-1"><strong>Name:</strong> ${currentUser.name}</p>
                <p class="mb-1"><strong>Mobile:</strong> ${currentUser.phone}</p>
                <p class="mb-0"><strong>Email:</strong> ${currentUser.email}</p>
            `);

        // Address list
        let addressHtml = '';
        currentUser?.address.forEach(addr => {
            const fullAddress = `${addr.address_line1}${addr.address_line2 ? ', ' + addr.address_line2 : ''} - ${addr.pincode}`;
            addressHtml += `
                    <div class="address-card card mb-2 ${selectedAddress && selectedAddress.id === addr.id ? 'selected' : ''}" onclick="selectAddress(${addr.id})">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start gap-3">
                                <input type="radio" name="address" value="${addr.id}" ${selectedAddress && selectedAddress.id === addr.id ? 'checked' : ''} class="form-check-input mt-1">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <strong class="text-primary">${addr.address_type}</strong>
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

        // Order summary
        const total = cart.reduce((sum, item) => {
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
                name: 'A La Carte',
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

            summaryHtml += `<div class="d-flex justify-content-between align-items-center mb-3"><h6><b>${category.name}</b></h6>
                                    <div class="text-muted fw-medium">$${category_wisetotal.toFixed(2)}</div>
                                    </div>

            `;


            items.forEach(item => {
                summaryHtml += `
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <div>
                            <h6>${item.name} x ${item.quantity}</h6>
                        </div>
                        <small class="fw-medium">$${(item.price * item.quantity).toFixed(2)}</small>
                    </div>
                `;
                if (item?.additional_items?.length > 0) {
                    summaryHtml += '<small style="margin-left:10px"><b>Additional Items:</b></small>'
                    item?.additional_items?.forEach(additem => {
                        console.log(additem)
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
        summaryHtml += `
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Total:</strong>
                    <strong class="text-success">$${total.toFixed(2)}</strong>

                </div>
            `;
        $('#orderSummary').html(summaryHtml);

        $('#checkoutModal').modal('show');
    }

    function selectAddress(addressId) {
        selectedAddress = currentUser.address.find(addr => addr.id === addressId);
        // Update visual selection
        $('.address-card').removeClass('selected');
        $(`.address-card input[value="${addressId}"]`).closest('.address-card').addClass('selected');
        $(`.address-card input[value="${addressId}"]`).prop('checked', true);
    }

    function addNewAddress() {
        const addressType = $('#newAddressType').val();
        const address1 = $('#newAddress1').val();
        const address2 = $('#newAddress2').val();
        const city = $('#newCity').val();
        const state = $('#newState').val();
        const country = $('#newCountry').val();
        const pincode = $('#newPincode').val();

        if (!address1 || !city || !state || !country || !pincode) {
            alert('Please fill in all required fields');
            return;
        }

        const newAddr = {
            id: currentUser.addresses.length + 1,
            type: addressType,
            address1: address1,
            address2: address2,
            city: city,
            state: state,
            country: country,
            pincode: pincode,
            lat: selectedLocation ? selectedLocation.lat : null,
            lng: selectedLocation ? selectedLocation.lng : null,
            isDefault: false
        };

        currentUser.address.push(newAddr);
        selectedAddress = newAddr;

        $('#addAddressModal').modal('hide');
        clearAddressForm();
        showCheckout();
    }

    function completeOrder() {
        const grouped = cart.reduce((acc, item) => {
            if (!acc[item.type]) acc[item.type] = [];
            acc[item.type].push(item);
            return acc;
        }, {});
        console.log(grouped)
        $.ajax({
            url: "{{ route('admin.add-order') }}", // Change this to your server endpoint
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                cart: grouped,
                user_id: currentUser.id,
                address_id: selectedAddress.id,
                alacarteorder_date: alacarteorder_date,
                partyorder_date: alacarteorder_date
            },
            beforeSend: function() {
                $(".loader-wrapper").css("display", "flex")

            },
            success: function(response) {
                // Handle success response
                if (response.success) {
                    toastSuccess(response.message);
                    cart = [];
                    currentUser = null;
                    selectedAddress = null;
                    updateCartDisplay();
                    $('#checkoutModal').modal('hide');
                    setTimeout(function() {
                        window.location.href = "/admin/dashboard";
                    }, 1000);

                } else {
                    toastFail((response.message) ? response.message : "Application cant register try again");
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


        // alert('Order placed successfully! Your food will be delivered to the selected address.');
    }

    $('#form_addnewaddress').validate({
        rules: validationRules.addressValidationForm.rules,
        messages: validationRules.addressValidationForm.messages,
        submitHandler: function(form, event) {
            const formData = new FormData(form);
            formData.append('customer_id', currentUser.id);
            // event.preventDefault();
            $.ajax({
                url: "{{ route('admin.add-new-address') }}", // Change this to your server endpoint
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
                        checkWithDBMobileExist($('#mobileInput').val())

                    } else {
                        toastFail((response.message) ? response.message : "Application cant register try again");
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
</script>

@endsection