@extends('layouts.admin.app')

@section('title', 'Table Order')

@section('content')
<style>
    /* Card polish */
    .plan-card {
        border: 1px solid #FEA116;
        border-radius: 1rem;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .text-primary {
        color: #FEA116 !important;
    }

    .plan-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, .08);
    }

    .plan-header {
        background: linear-gradient(180deg, rgba(254, 161, 22, .12), rgba(254, 161, 22, .03));
        border-bottom: 1px solid #FEA116;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .price {
        font-size: 2.25rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .price i {
        padding: 12px 18px;
        background: #FEA116;
        color: #FFFFFF;
        border-radius: 100px
    }

    .btn-primary {
        background-color: #FEA116;
        border: 1px solid #FEA116;

    }

    .border-primary {
        border-color: #FEA116 !important;
    }

    .btn-primary:hover {
        background-color: #FEA116;
        border: 1px solid #FEA116;
    }

    .badge-soft {
        background-color: rgba(254, 161, 22, .12);
        color: #b66b00;
        border: 1px solid rgba(254, 161, 22, .3);
    }

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
                    <h3 class="mb-0">Table Order</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Table Order</li>
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
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
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
                                        <span id="cartCount">Cart</span>
                                        <span class="text-dark fw-bold" id="cartTotal"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
                                <ul class="nav nav-pills nav-fill mb-4" id="menu_type_tab" role="tablist">
                                    <!-- <li class="nav-item" role="presentation">
                                        <button class="nav-link active d-flex align-items-center justify-content-center gap-2"
                                            id="daywise-tab" data-tab="daywise">
                                            <i data-lucide="calendar"></i>
                                            <span class="d-none d-sm-inline">Day-wise</span>
                                            <span class="d-sm-none">Daily</span>
                                        </button>
                                    </li> -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active d-flex align-items-center justify-content-center gap-2"
                                            id="alacarte-tab" data-tab="alacarte">
                                            <i data-lucide="utensils"></i>
                                            <span class="d-none d-sm-inline">Dining</span>
                                            <span class="d-sm-none">Menu</span>
                                        </button>
                                    </li>

                                </ul>
                                <div id="menuContent">
                                    <!-- Day-wise Menu -->
                                    <!-- <div id="daywise-content" class="tab-content active"></div> -->

                                    <!-- A-la-carte Menu -->
                                    <div id="dining-content" class="tab-content active"></div>

                                    <!-- Party Menu -->
                                    <!-- <div id="party-content" class="tab-content" style="display: none;"></div> -->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Container-->
            </div>
        </div>
    </div>
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

    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Checkout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="form_addnewtablecustomer" method='post'>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Customer Details</h6>
                                <div class="card">
                                    <div class="card-body" id="customerDetails">
                                    </div>
                                </div>
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
                        <button class="btn btn-success">Place Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end::App Content-->
</main>
<script src="{{asset('admin-assets/validation/users.js')}}"></script>
<script>
    // Sample data with detailed addresses


    // Sample user database with detailed addresses

    // Global variables
    let order_name = 'tbl_order_' + (
        localStorage.getItem('table_id') ?
        localStorage.getItem('table_id') :
        ''
    );
    let cart = (localStorage.getItem(order_name)) ? JSON.parse(localStorage.getItem(order_name)) : [];

    let currentUser = null;
    let selectedAddress = null;
    let activeTab = 'alacarte';
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
            $('#cartModal').modal('toggle');
            showCheckout();
        });

        // Continue to checkout
        $('#continueCheckoutBtn').click(function() {
            $('#userModal').modal('hide');
            showCheckout();
        });

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


    function renderAlaCarteMenu() {
        const filteredMenu = allMenuList?.dining.map(category => ({
            ...category,
            items: category.diningmenus.filter(item =>
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
                                                        <button class="btn btn-outline-secondary btn-sm" onclick="removeFromCart('dining-${item.id}')" ${getCartQuantity('dining-' + item.id) === 0 ? 'disabled' : ''}>
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                        <span class="fw-medium quantity-control">${getCartQuantity('dining-' + item.id)}</span>
                                                        <button class="btn btn-outline-secondary btn-sm" onclick="addToCart(${JSON.stringify(item).replace(/"/g, '&quot;')}, 'dining')">
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
        updateCartDisplay()
        $('#dining-content').html(html);
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
        localStorage.setItem(order_name, JSON.stringify(cart));
        updateCartDisplay();
        renderMenu();
    }

    function removeFromCart(cartId, isRemoveFromCart = false) {

        const existing = cart.find(item => item.id === cartId);
        console.log("existing", existing)

        if (existing && existing.quantity > 1) {
            existing.quantity = existing.quantity - 1;
        } else {
            cart = cart.filter(item => item.id !== cartId);
        }
        localStorage.setItem(order_name, JSON.stringify(cart));
        updateCartDisplay();
        renderMenu();
        if (isRemoveFromCart) {
            showCart();
        }
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
        let itemCount = 0;
        let total = 0;

        cart.forEach(item => {
            // main item
            itemCount += item.quantity;
            total += parseFloat(item.price) * item.quantity;

            // additional items (if exist)
            if (item.additional_items && Array.isArray(item.additional_items)) {
                item.additional_items.forEach(addItem => {
                    itemCount += addItem.quantity;
                    total += parseFloat(addItem.price) * addItem.quantity;
                });
            }
        });
        if (itemCount != 0) {
            $('#cartCount').text(`${itemCount} items`);
            $('#cartTotal').text(`$${total.toFixed(2)}`);
        } else {
            $('#cartCount').text(`Cart`);
            $('#cartTotal').text(``);
        }

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
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="removeFromCart('${item.id}',true)">
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
                    if ((index === items.length - 1) && (type === 'alacarte' || type === 'dining' || type === 'party')) {
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
        localStorage.setItem(order_name, JSON.stringify(cart));
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
        localStorage.setItem(order_name, JSON.stringify(cart));
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


    function showCheckout() {
        let userDetails = JSON.parse(localStorage.getItem(`tbl_order_${localStorage.getItem('table_id')}_user`));
        console.log(userDetails?.name)
        // Address list
        let customerHtml = `
       <p class="mb-1"><strong>Name: </strong>${userDetails?.name || "-"}</p>
                <p class="mb-1"><strong>Mobile: </strong>${userDetails?.phone || "-"}</p>
                <p class="mb-0"><strong>Email: </strong>${userDetails?.email || "-"}</p>
        `;

        $('#customerDetails').html(customerHtml);

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
            'dining': {
                name: 'Dining',
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
            console.log("category", category)
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
                    <strong class="text-dark">$${total.toFixed(2)}</strong>

                </div>
                 <div class="d-flex justify-content-between align-items-center mt-2">
                    <strong>USD 0.8:</strong>
                    <strong class="text-success">$${total.toFixed(2)*1.25}</strong>

                </div>
            `;
        $('#orderSummary').html(summaryHtml);

        $('#checkoutModal').modal('show');
    }

    //complete order
    $('#form_addnewtablecustomer').validate({
        rules: {},
        messages: {},
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
            const grouped = cart.reduce((acc, item) => {
                if (!acc[item.type]) acc[item.type] = [];
                acc[item.type].push(item);
                return acc;
            }, {});
            let userDetails = JSON.parse(localStorage.getItem(`tbl_order_${localStorage.getItem('table_id')}_user`));

            formData.append('cart', grouped);
            formData.append('table_id', localStorage.getItem('table_id'))
            event.preventDefault();
            $.ajax({
                url: "{{ route('admin.add-table-order') }}", // Change this to your server endpoint
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    cart: grouped,
                    table_id: localStorage.getItem('table_id'),
                    txt_name: userDetails?.name,
                    txt_email: userDetails?.email,
                    txt_phone: userDetails?.phone
                },
                beforeSend: function() {
                    $(".loader-wrapper").css("display", "flex")

                },
                success: function(response) {
                    // Handle success response
                    if (response.success) {
                        toastSuccess(response.message);
                        localStorage.removeItem(order_name);
                        localStorage.removeItem('table_id')
                        localStorage.removeItem(`tbl_order_${localStorage.getItem('table_id')}_user`)
                        cart = [];
                        updateCartDisplay();
                        $('#checkoutModal').modal('hide');
                        showCheckout();
                        setTimeout(function() {
                            window.location.href = "dashboard";
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
    });
</script>

@endsection