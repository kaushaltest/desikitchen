@extends('layouts.customer.app')

@section('title', 'About Us')

@section('content')
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container text-center my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Orders</h1>
        <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Order</li>
            </ol>
        </nav> -->
    </div>
</div>
<style>
    :root {
        --brand-color: #FEA116;
        --brand-color-light: #FEA11620;
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .brand-color {
        color: var(--brand-color) !important;
    }

    .bg-brand {
        background-color: var(--brand-color) !important;
    }

    .border-brand {
        border-color: var(--brand-color) !important;
    }

    .current-order-card {
        border-left: 4px solid var(--brand-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .status-timeline {
        position: relative;
    }

    .timeline-item {
        position: relative;
        padding-left: 3rem;
        padding-bottom: 0.70rem;
    }

    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .timeline-icon.completed {
        background-color: var(--brand-color);
        color: white;
    }

    .timeline-icon.pending {
        background-color: #e9ecef;
        color: #6c757d;
    }

    .timeline-line {
        position: absolute;
        left: 0.95rem;
        top: 2rem;
        width: 2px;
        height: calc(100% - 2rem);
        background-color: #e9ecef;
    }

    .timeline-line.completed {
        background-color: var(--brand-color);
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .order-summary-card {
        transition: all 0.3s ease;
        border-left: 4px solid var(--brand-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .order-summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .current-order-status {
        background: linear-gradient(135deg, var(--brand-color), #ff8c00);
        color: white;
        border-radius: 0.5rem;
        padding: 1rem;
    }

    .collapse-toggle {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .collapse-toggle:hover {
        background-color: #f8f9fa;
    }

    .order-item {
        border-bottom: 1px solid #e9ecef;
        padding: 0.5rem 0;
    }

    .order-item:last-child {
        border-bottom: none;
    }
</style>
<div class="container-xxl py-5">
    <div class="container">
        <div id="currentOrders"></div>
        <div id="pastOrders" class="mb-5"></div>
    </div>
</div>


</div>
</div>
</div>
<script>
    const orderUrl = "{{ route('customer.get-order') }}";

    document.addEventListener('DOMContentLoaded', function() {
        loadOrders();
    });

    function loadOrders() {
        fetch(orderUrl)
            .then(res => res.json())
            .then(data => renderOrders(data))
            .catch(err => console.error('Order fetch failed', err));
    }

    function renderOrders(orders) {
        const currentContainer = document.getElementById('currentOrders');
        const pastContainer = document.getElementById('pastOrders');

        currentContainer.innerHTML = `
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="section-title ff-secondary text-primary fw-normal mb-5">Current Order</h1>
        </div>`;
        pastContainer.innerHTML = `
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="section-title ff-secondary text-primary fw-normal mb-5">Past Order</h1>
        </div>`;

        if (!orders || orders.length === 0) {
            currentContainer.innerHTML += `<div class="d-flex justify-content-center"><p>No active order currently exist.</p></div>`;
            pastContainer.innerHTML += `<div class="d-flex justify-content-center"><p>No past orders available at this time.</p></div>`;
            return;
        }

        const statusFlow = {
            confirmed: {
                title: 'Order Confirmed',
                icon: 'bi-check-circle'
            },
            outfordelivery: {
                title: 'Out for Delivery',
                icon: 'bi-truck'
            },
            delivered: {
                title: 'Delivered',
                icon: 'bi-check-circle'
            }
        };
        let hasCurrent = false;
        let hasPast = false;

        orders.forEach(order => {
            const current = (order.status || '').toLowerCase();
            const keys = Object.keys(statusFlow);
            const idx = Math.max(keys.indexOf(current), 0);

            // ==== Current Orders ====
            if (current === 'confirmed' || current === 'outfordelivery') {
                currentContainer.innerHTML += buildCurrentOrder(order, statusFlow, keys, idx);
                hasCurrent = true;
            }

            // ==== Past Orders ====
            if (current === 'delivered' || current === 'rejected') {
                pastContainer.innerHTML += buildPastOrder(order, statusFlow, keys, idx);
                hasPast = true;
            }
        });

        if (!hasCurrent) {
            currentContainer.innerHTML +=
                `<div class="d-flex justify-content-center">
            <p>No active order currently exist.</p>
         </div>`;
        }

        if (!hasPast) {
            pastContainer.innerHTML +=
                `<div class="d-flex justify-content-center">
            <p>No past orders available at this time.</p>
         </div>`;
        }
    }

    function buildCurrentOrder(order, flow, keys, currentIdx) {
        const paymentBadge = badgeForPayment(order.payment_status);
        const statusBadge = badgeForStatus(order.status);
        const timeline = buildTimeline(keys, flow, currentIdx);

        // simple tax calc like Blade
        const rate = 0.25;
        const final = order.total_amount;
        const base = (final / (1 + rate)).toFixed(2);

        return `
    <div class="card current-order-card mb-3">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="bi bi-fire text-primary me-2"></i>
                        Order #${order.order_id}
                        ${order.current_date === order.order_date ? '<span class="badge bg-primary ms-2">Current Order</span>' : ''}
                    </h5>
                    <p class="card-text text-muted">Ordered at ${order.order_date}</p>
                </div>
                <div class="text-end">
                    ${statusBadge}
                    <button class="btn btn-danger rounded btn-cancel-order" data-id="${order.id}">Cancel Order</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3">Order Status</h6>
                    ${currentIdx === -1 ? '' : timeline}
                </div>
                <div class="col-md-6">
                    <div class="d-flex w-100 justify-content-between align-item-center mb-3">
                        <h6 class="fw-semibold ">Order Details</h6>
                        ${paymentBadge}
                    </div>
                    ${order.items}
                    <div class="d-flex justify-content-between fw-bold mt-1">
                        <span>Total</span><span>$${base}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold mt-1">
                        <span>USD 0.8</span><span>$${final}</span>
                    </div>
                    <div class="mb-4 mt-3">
                        <h6 class="fw-semibold mb-2"><i class="bi bi-geo-alt brand-color me-2"></i>Delivery Address</h6>
                        <small class="text-muted">${order.address}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
    }

    function buildPastOrder(order, flow, keys, currentIdx) {
        const statusBadge = badgeForStatus(order.status);
        const timeline = buildTimeline(keys, flow, currentIdx);

        return `
    <div class="card mb-3 order-summary-card">
        <div class="card-header collapse-toggle" data-bs-toggle="collapse" data-bs-target="#order${order.order_id}">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title mb-1">Order #${order.order_id}</h6>
                    <small class="text-muted">Ordered at ${order.order_date} • Delivered at ${order.delivered_at ?? ''}</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="text-end me-3">${statusBadge}</div>
                    <span class="fw-semibold me-3">$${order.total_amount}</span>
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>
        </div>
        <div class="collapse" id="order${order.order_id}">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Order Status</h6>
                        ${timeline}
                    </div>
                    <div class="col-md-6">
                        ${order.items}
                        <div class="d-flex justify-content-between fw-semibold mt-2">
                            <span>Total</span><span>$${order.total_amount}</span>
                        </div>
                        <div class="mb-4 mt-3">
                            <h6 class="fw-semibold mb-2"><i class="bi bi-geo-alt brand-color me-2"></i>Delivery Address</h6>
                            <small class="text-muted">${order.address}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
    }

    function buildTimeline(keys, flow, currentIdx) {
        return keys.map((key, idx) => {
            const step = flow[key];
            const state = idx <= currentIdx ? 'completed' : 'pending';
            const line = idx < keys.length - 1 ?
                `<div class="timeline-line ${idx < currentIdx ? 'completed' : ''}"></div>` : '';
            return `
        <div class="timeline-item">
            <div class="timeline-icon ${state}">
                <i class="bi ${step.icon}"></i>
            </div>
            ${line}
            <div><h6 class="pt-2 ${state === 'pending' ? 'text-muted' : 'text-dark'}">${step.title}</h6></div>
        </div>`;
        }).join('');
    }

    function badgeForPayment(status) {
        if (status === 'Unpaid') return `<span class="badge bg-warning rounded text-dark status-badge" style="height:20px">Unpaid</span>`;
        if (status === 'Partially Paid') return `<span class="badge bg-info rounded text-dark status-badge" style="height:20px">Partially Paid</span>`;
        if (status === 'Paid') return `<span class="badge bg-success rounded text-light status-badge" style="height:20px">Paid</span>`;
        return '';
    }

    function badgeForStatus(status) {
        const s = (status || '').toLowerCase();
        if (s === 'rejected') return `<span class="badge bg-danger rounded text-light status-badge">Rejected</span>`;
        if (s === 'outfordelivery') return `<span class="badge bg-warning rounded text-dark status-badge">Out for Delivery</span>`;
        if (s === 'confirmed') return `<span class="badge bg-info rounded text-dark status-badge">Confirmed</span>`;
        if (s === 'delivered') return `<span class="badge bg-success rounded text-light status-badge">Delivered</span>`;
        return '';
    }

    $(document).ready(function() {
        $(document).on('click', '.btn-cancel-order', function() {
            let orderId = $(this).attr('data-id');
            const formData = new FormData();
            formData.append('order_id', orderId)

            $.ajax({
                url: "{{ route('customer.cancel-order') }}", // Change this to your server endpoint
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
                        toastSuccess((response.message) ? response.message : "Something went wrong. Please try again later.");
                        loadOrders()
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
        })
    });
</script>

@endsection