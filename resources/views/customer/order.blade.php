@extends('layouts.customer.app')

@section('title', 'About Us')

@section('content')
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container text-center my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Orders</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Order</li>
            </ol>
        </nav>
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

        <!-- Current Order Section -->
        <div class="">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h1 class="section-title ff-secondary text-center text-primary fw-normal mb-5">Current Order</h1>
            </div>
            @if(!empty($orders) && count($orders)>0)
            @foreach($orders as $order)
            @php
            $statusFlow = [
            'confirmed' => ['title' => 'Order Confirmed', 'icon' => 'bi-check-circle'],
            'outfordelivery' => ['title' => 'Out for Delivery', 'icon' => 'bi-truck'],
            'delivered' => ['title' => 'Delivered', 'icon' => 'bi-check-circle'],
            ];

            $current = strtolower($order['status']);
            $keys = array_keys($statusFlow);
            $currentIndex = array_search($current, $keys);
            if ($currentIndex === false) {
            $currentIndex = 0; // fallback if unknown
            }
            @endphp
            @if($current=='confirmed' || $current=='outfordelivery')
            <div class="card current-order-card mb-3">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="bi bi-fire text-primary me-2"></i>
                                Order #{{$order['order_id']}}
                                @if($order['current_date'] == $order['order_date'])
                                <span class="badge bg-primary ms-2">Current Order</span>
                                @endif
                            </h5>
                            <p class="card-text text-muted">Ordered at {{$order['order_date']}}</p>
                        </div>
                        @if($current == 'rejected')
                        <div class="text-end">
                            <span class="badge bg-danger rounded text-light status-badge">Rejected</span>
                        </div>
                        @elseif($current === 'outfordelivery')
                        <div class="text-end">
                            <span class="badge bg-warning rounded text-dark status-badge">Out for Delivery</span>
                        </div>
                        @elseif($current === 'confirmed')
                        <div class="text-end">
                            <span class="badge bg-info rounded text-dark status-badge">Confirmed</span>
                        </div>
                        @elseif($current === 'delivered')
                        <div class="text-end">
                            <span class="badge bg-success rounded text-light status-badge">Delivered</span>
                        </div>
                        @endif

                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <!-- Order Status Column -->
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Order Status</h6>

                            <!-- Current Status Banner -->
                            <!-- @if(!empty($order['status']=='dispatched'))
                            <div class="current-order-status mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-truck fs-3 me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Out for Delivery</h6>
                                        <small class="opacity-75">Estimated delivery: 10-15 mins</small>
                                    </div>
                                </div>
                            </div>
                            @endif -->
                            @if($current === 'rejected')
                            <!-- Show Rejected Only -->
                            <div class="timeline-item">
                                <div class="timeline-icon completed">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-dark">Rejected</h6>
                                    <small class="text-muted">{{$order['note']}}</small>
                                </div>
                            </div>
                            @else
                            <!-- Timeline Flow -->
                            @php
                            $keys = array_keys($statusFlow);
                            $currentIndex = array_search($current, $keys);
                            if ($currentIndex === false) {
                            $currentIndex = 0; // fallback
                            }
                            @endphp

                            @foreach($keys as $idx => $key)
                            @php
                            $step = $statusFlow[$key];
                            $state = $idx <= $currentIndex ? 'completed' : 'pending' ;
                                $time=$state==='completed' ? \Carbon\Carbon::parse($order['created_at'])->format('g:i A') : '';
                                @endphp

                                <div class="timeline-item">
                                    <div class="timeline-icon {{ $state }}">
                                        <i class="bi {{ $step['icon'] }}"></i>
                                    </div>

                                    @if($idx < count($keys) - 1)
                                        <div class="timeline-line {{ $idx < $currentIndex ? 'completed' : '' }}">
                                </div>
                                @endif

                                <div>
                                    <h6 class="pt-2 {{ $state === 'pending' ? 'text-muted' : 'text-dark' }}">
                                        {{ $step['title'] }}
                                    </h6>
                                </div>
                        </div>
                        @endforeach
                        @endif
                    </div>

                    <!-- Order Details Column -->
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Order Details</h6>

                        <!-- Restaurant Info -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-dark">Desi Kitchen</h6>
                            <small class="text-muted">+1 (555) 987-6543</small>
                        </div>

                        <!-- Items Ordered -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Items Ordered</h6>
                            {!!$order['items']!!}

                            <div class="d-flex justify-content-between fw-semibold">
                                <span>Total</span>
                                <span>${{$order['total_amount']}}</span>
                            </div>
                        </div>

                        <!-- Delivery Address -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-geo-alt brand-color me-2"></i>
                                Delivery Address
                            </h6>
                            <small class="text-muted">{!!$order['address']!!}</small>
                        </div>

                        <!-- Contact -->
                        <!-- <div>
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-telephone brand-color me-2"></i>
                                Contact
                            </h6>
                            <small class="text-muted">+1 (555) 123-4567</small>
                        </div> -->
                    </div>
                </div>
            </div>
            @endif

        </div>
        @endforeach
        @else
        <div class="d-flex justify-content-center align-items-start">
            <p>No Current orders found.</p>
        </div>
        @endif
    </div>

    <!-- Past Orders Section -->
    <div class="mb-5">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="section-title ff-secondary text-center text-primary fw-normal mb-5">Past Order</h1>
        </div>
        <!-- Past Order 1 -->
        @if(!empty($orders) && count($orders)>0)
        @foreach($orders as $order)
        @php
        $statusFlow = [
        'confirmed' => ['title' => 'Order Confirmed', 'icon' => 'bi-check-circle'],
        'outfordelivery' => ['title' => 'Out for Delivery', 'icon' => 'bi-truck'],
        'delivered' => ['title' => 'Delivered', 'icon' => 'bi-check-circle'],
        ];

        $current = strtolower($order['status']);
        $keys = array_keys($statusFlow);
        $currentIndex = array_search($current, $keys);
        if ($currentIndex === false) {
        $currentIndex = 0; // fallback if unknown
        }
        @endphp
        @if($current=='delivered' || $current=='rejected')
        <div class="card mb-3 order-summary-card">
            <div class="card-header collapse-toggle" data-bs-toggle="collapse" data-bs-target="#{{$order['order_id']}}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Order #{{$order['order_id']}}</h6>
                        <small class="text-muted">Ordered at, {{$order['order_date']}} • Delivered at {{$order['delivered_at']}}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        @if($current == 'rejected')
                        <div class="text-end">
                            <span class="badge bg-danger rounded text-light status-badge me-3">Rejected</span>
                        </div>
                        @elseif($current === 'delivered')
                        <div class="text-end">
                            <span class="badge bg-success rounded text-light status-badge me-3">Delivered</span>
                        </div>
                        @endif
                        <span class="fw-semibold me-3">${{$order['total_amount']}}</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </div>
            </div>

            <div class="collapse" id="{{$order['order_id']}}">
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Order Status Column -->
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Order Status</h6>

                            @if($current === 'rejected')
                            <!-- Show Rejected Only -->
                            <div class="timeline-item">
                                <div class="timeline-icon completed">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-dark">Rejected</h6>
                                    <small class="text-muted">{{$order['note']}}</small>
                                </div>
                            </div>
                            @else
                            <!-- Timeline Flow -->
                            @php
                            $keys = array_keys($statusFlow);
                            $currentIndex = array_search($current, $keys);
                            if ($currentIndex === false) {
                            $currentIndex = 0; // fallback
                            }
                            @endphp

                            @foreach($keys as $idx => $key)
                            @php
                            $step = $statusFlow[$key];
                            $state = $idx <= $currentIndex ? 'completed' : 'pending' ;
                                $time=$state==='completed' ? \Carbon\Carbon::parse($order['created_at'])->format('g:i A') : '';
                                @endphp

                                <div class="timeline-item">
                                    <div class="timeline-icon {{ $state }}">
                                        <i class="bi {{ $step['icon'] }}"></i>
                                    </div>

                                    @if($idx < count($keys) - 1)
                                        <div class="timeline-line {{ $idx < $currentIndex ? 'completed' : '' }}">
                                </div>
                                @endif

                                <div>
                                    <h6 class="pt-2 {{ $state === 'pending' ? 'text-muted' : 'text-dark' }}">
                                        {{ $step['title'] }}
                                    </h6>
                                </div>
                        </div>
                        @endforeach
                        @endif
                    </div>

                    <!-- Order Details Column -->
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Order Details</h6>

                        <!-- Restaurant Info -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-dark">Desi Kitchen</h6>
                            <small class="text-muted">+1 (555) 987-6543</small>
                        </div>

                        <!-- Items Ordered -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Items Ordered</h6>
                            {!!$order['items']!!}

                            <div class="d-flex justify-content-between fw-semibold">
                                <span>Total</span>
                                <span>${{$order['total_amount']}}</span>
                            </div>
                        </div>

                        <!-- Delivery Address -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-geo-alt brand-color me-2"></i>
                                Delivery Address
                            </h6>
                            <small class="text-muted">{!!$order['address']!!}</small>
                        </div>

                        <!-- Contact -->
                        <!-- <div>
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-telephone brand-color me-2"></i>
                                Contact
                            </h6>
                            <small class="text-muted">+1 (555) 123-4567</small>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach
    @else
    <div class="d-flex justify-content-center align-items-start">
        <p>No Previous orders found.</p>
    </div>
    @endif

</div>


</div>
</div>
</div>
@endsection