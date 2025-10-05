@extends('layouts.customer.app')

@section('title', 'Subscription')

@section('content')
<style>
    /* Card polish */
    .plan-card {
        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 1rem;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .plan-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, .08);
    }

    .plan-header {
        background: linear-gradient(180deg, rgba(254, 161, 22, .12), rgba(254, 161, 22, .03));
        border-bottom: 1px solid rgba(0, 0, 0, .06);
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .price {
        font-size: 2.25rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .badge-soft {
        background-color: rgba(254, 161, 22, .12);
        color: #b66b00;
        border: 1px solid rgba(254, 161, 22, .3);
    }
</style>
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container text-center my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Subscription</h1>
        <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Subscription</li>
            </ol>
        </nav> -->
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="section-title ff-secondary text-center text-primary fw-normal mb-5">Subscription for tiffin service</h1>
        </div>

    </div>
    <div class="row g-4">

        @foreach($subscription as $sub)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="plan-card h-100 d-flex flex-column border-2 {{ $sub['name'] == 'Pro plan' ? 'border-primary' : '' }}">
                <div class="plan-header p-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h5 class="mb-1">{{$sub['name']}}</h5>
                        </div>
                        <span class="badge badge-soft rounded-pill">{{($sub['days'])?$sub['days'] : 'Unlimited'}} days</span>
                    </div>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex align-items-baseline gap-2 mb-3">
                        <div class="price">${{$sub['price']}}</div>
                        <span class="text-secondary">/ plan</span>
                    </div>

                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="bi bi-check-circle me-2 text-primary"></i>
                            <strong>Days:</strong> {{($sub['days'])?$sub['days'] : 'Unlimited'}}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle me-2 text-primary"></i>
                            <strong>Total meals:</strong> {{$sub['total_meals']}}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle me-2 text-primary"></i>
                            <strong>Description:</strong> {{$sub['description']}}
                        </li>
                    </ul>

                    <!-- button pushed to bottom -->
                    <button
                        class="btn btn-primary w-100 mt-auto choose-plan-btn"
                        data-menu='@json($sub)'
                        data-id="{{ $sub['id'] }}"
                        data-name="{{ $sub['name'] }}"
                        data-price="{{ $sub['price'] }}"
                        data-days="{{ $sub['days'] }}"
                        data-total-meals="{{ $sub['total_meals'] }}"
                        data-description="{{ $sub['description'] }}">
                        Choose {{ $sub['name'] }}
                    </button>

                </div>
            </div>

        </div>
        @endforeach
    </div>

</div>
<script>
    $(document).ready(function() {
        // $(".choose-plan-btn").on("click", function() {
            
        // })
        $(".choose-plan-btn").on("click", function() {
            let planId =  $(this).attr("data-id");
            let meal = $(this).attr("data-total-meals");
            let days = $(this).attr("data-days");
            const item = {
                id: $(this).attr("data-id"),
                name: $(this).attr("data-name"),
                price: $(this).attr("data-price"),
                days : $(this).attr("data-days"),
                total_meals : $(this).attr("data-total-meals"),
                description: $(this).attr("data-description"),
                image: ""
            };

            $.ajax({
                url: "{{route('customer.buy-subscription')}}", // Laravel route
                type: "POST",
                data: {
                    plan_id: planId,
                    meal: meal,
                    days: days,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    // optional: disable button / show loader
                },
                success: function(response) {
                    // handle success
                    if (response.success) {
                        toastSuccess(response.message);
                    } else {
                        if (!response.loggedin) {
                            addToCart(item, 'subscription');
                        } else {
                            toastFail(response.message);
                        }
                    }
                },
                error: function(xhr) {
                    // handle error
                    toastFail(xhr.responseText);
                }
            });
        });
    });
</script>

@endsection