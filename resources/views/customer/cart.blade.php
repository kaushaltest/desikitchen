@extends('layouts.customer.app')

@section('title', 'About Us')

@section('content')
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container text-center my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Cart</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Cart</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="section-title ff-secondary text-center text-primary fw-normal mb-5">Cart</h1>
        </div>
        <div id="cartModalBody"></div>
        <div id="cartModalFooter" style="display:none;">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="fs-5 fw-bold">
                    Grand Total: <span class="text-primary" id="cartModalTotal">$0.00</span>
                </div>
                <button class="btn btn-primary" id="checkoutBtn">Proceed to Checkout</button>
            </div>
        </div>
    </div>
</div>
@endsection