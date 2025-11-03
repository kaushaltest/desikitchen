@extends('layouts.customer.app')

@section('title', 'Service')

@section('content')
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container text-center my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Service</h1>
        <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Service</li>
            </ol>
        </nav> -->
    </div>
</div>
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="section-title ff-secondary text-center text-primary fw-normal mb-5">Our Services</h1>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-user-tie text-primary mb-4"></i>
                        <h5>Master Chefs</h5>
                        <p>Our chefs bring decades of culinary expertise to every plate, blending tradition with creativity.</p>

                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-utensils text-primary mb-4"></i>
                        <h5>Quality Food</h5>
                        <p>We source fresh, local ingredients to ensure every dish is flavorful, nutritious, and memorable.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-cart-plus text-primary mb-4"></i>
                        <h5>Online Order</h5>
                        <p>Skip the wait! Order your favorite meals online with just a few taps and enjoy fast delivery.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-shipping-fast text-primary mb-4"></i>
                        <h5>Fast Delivery</h5>
                        <p>Get your food fresh and hot — delivered right to your door in record time with zero compromise.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.9s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-seedling text-primary mb-4"></i>
                        <h5>Fresh Ingredients</h5>
                        <p>We use handpicked, seasonal ingredients to ensure every dish bursts with freshness and flavor.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="1.1s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-concierge-bell text-primary mb-4"></i>
                        <h5>Exceptional Service</h5>
                        <p>Our staff is trained to deliver a warm, attentive, and seamless dining experience every time.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="1.3s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-mug-hot text-primary mb-4"></i>
                        <h5>Cozy Ambience</h5>
                        <p>Enjoy your meal in a clean, comfortable, and inviting environment perfect for any occasion.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="1.5s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-star text-primary mb-4"></i>
                        <h5>Customer Satisfaction</h5>
                        <p>We go the extra mile to ensure every guest leaves with a smile and a satisfied appetite.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection