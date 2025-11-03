@extends('layouts.customer.app')

@section('title', 'My Order')

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

    .btn-cancel-order {
        font-weight: 700;
    }

    @media (max-width: 900px) {
        .cancel_btn_box {
            display: flex;
            justify-content: space-between;
            width: 100%;
            align-items: center;
        }

        .btn-cancel-order {
            font-size: 13px;
            padding: 5px 15px;
            font-weight: 700;
        }

        .prev_status_box {
            justify-content: space-between;
            width: 100%;
        }
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
<div class="modal fade" id="confirm_delete_order_message" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    Message
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <input type="hidden" id="hid_cancel_orderid" name="hid_cancel_orderid">
            <div class="modal-body">
                <p>Are you sure want to cancel this Order ?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="btn_cancel_order">
                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                    Submit</button>
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
            pending: {
                title: 'Pending Confirmation',
                icon: 'bi bi-arrow-clockwise'
            },
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
            if (current === 'pending' || current === 'confirmed' || current === 'outfordelivery') {
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
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                <div>
                    <h5 class="card-title mb-1">Order #${order.order_id}
                        ${order.current_date === order.order_date ? '<span class="badge bg-primary ms-2">Current Order</span>' : ''}
                    </h5>
                    <p class="card-text text-muted">Ordered at ${order.order_date}</p>
                </div>
                <div class="cancel_btn_box">
                    ${statusBadge}
                    <button class="btn btn-danger rounded btn-cancel-order" data-id="${order.id}" data-orderdate="${order.without_format_order_date}">
                                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                    Cancel Order</button>
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
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                <div>
                    <h6 class="card-title mb-1">Order #${order.order_id}</h6>
                    <small class="text-muted">Ordered at ${order.order_date} • Delivered at ${order.delivered_at ?? ''}</small>
                </div>
                <div class="d-flex align-items-center prev_status_box">
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
        if (s === 'pending') return `<span class="badge bg-warning rounded text-dark status-badge" style="height:20px">Pending Confirmation</span>`;
        if (s === 'rejected') return `<span class="badge bg-danger rounded text-light status-badge" style="height:20px">Rejected</span>`;
        if (s === 'outfordelivery') return `<span class="badge bg-warning rounded text-dark status-badge" style="height:20px">Out for Delivery</span>`;
        if (s === 'confirmed') return `<span class="badge bg-info rounded text-dark status-badge" style="height:20px">Confirmed</span>`;
        if (s === 'delivered') return `<span class="badge bg-success rounded text-light status-badge" style="height:20px">Delivered</span>`;
        return '';
    }

    $(document).ready(function() {

        $(document).on("click", "#btn_cancel_order", function() {
            const $btn = $('#btn_cancel_order');
            const $spinner = $btn.find('.spinner-border');

            let orderId = $("#hid_cancel_orderid").val();
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
                    $spinner.removeClass('d-none');
                    $btn.prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        toastSuccess((response.message) ? response.message : "Something went wrong. Please try again later.");
                        $("#confirm_delete_order_message").modal('toggle');
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
                    $spinner.addClass('d-none');
                    $btn.prop('disabled', false);
                },
            });
        })

        $(document).on('click', '.btn-cancel-order', function() {

            const menuDateStr = $(this).attr('data-orderdate'); // e.g. "2025-09-23"

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
           
            $("#hid_cancel_orderid").val($(this).attr('data-id'));
            $("#confirm_delete_order_message").modal('toggle');
        })
    });
</script>

@endsection