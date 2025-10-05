@extends('layouts.customer.app')

@section('title', 'About Us')

@section('content')
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container text-center my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Menu</h1>
        <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Menu</li>
            </ol>
        </nav> -->
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="section-title ff-secondary text-center text-primary fw-normal mb-5">Food Menu</h1>
        </div>
        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
            <ul class="nav nav-pills d-flex justify-content-center flex-column flex-md-row  border-bottom mb-5">
                <li class="nav-item menu-items mb-3" data-tab="menu_daywise">
                    <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active" data-bs-toggle="pill" href="#menu_daywise">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16">
                            <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5zm13-3H1v2h14zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                        </svg>
                        <div class="ps-3">
                            <!-- <small class="text-body">Popular</small> -->
                            <h5 class="mt-n1 mb-0">Daily Tiffin</h5>
                        </div>
                    </a>
                </li>
                <li class="nav-item menu-items mb-3" data-tab="menu_alacarte">
                    <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#menu_alacarte">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-fork-knife" viewBox="0 0 16 16">
                            <path d="M13 .5c0-.276-.226-.506-.498-.465-1.703.257-2.94 2.012-3 8.462a.5.5 0 0 0 .498.5c.56.01 1 .13 1 1.003v5.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5zM4.25 0a.25.25 0 0 1 .25.25v5.122a.128.128 0 0 0 .256.006l.233-5.14A.25.25 0 0 1 5.24 0h.522a.25.25 0 0 1 .25.238l.233 5.14a.128.128 0 0 0 .256-.006V.25A.25.25 0 0 1 6.75 0h.29a.5.5 0 0 1 .498.458l.423 5.07a1.69 1.69 0 0 1-1.059 1.711l-.053.022a.92.92 0 0 0-.58.884L6.47 15a.971.971 0 1 1-1.942 0l.202-6.855a.92.92 0 0 0-.58-.884l-.053-.022a1.69 1.69 0 0 1-1.059-1.712L3.462.458A.5.5 0 0 1 3.96 0z" />
                        </svg>
                        <div class="ps-3">
                            <!-- <small class="text-body">Special</small> -->
                            <h5 class="mt-n1 mb-0">Catering Platters</h5>
                        </div>
                    </a>
                </li>
                <li class="nav-item menu-items mb-3" data-tab="menu_dining">
                    <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#menu_dining">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-dining-table" viewBox="0 0 16 16">
                            <!-- tabletop -->
                            <rect x="1" y="2" width="14" height="2" rx="0.3" />
                            <!-- table apron / skirt -->
                            <rect x="1" y="5.2" width="14" height="1" rx="0.2" />
                            <!-- left leg -->
                            <rect x="2" y="7" width="1.5" height="6" rx="0.2" />
                            <!-- right leg -->
                            <rect x="12.5" y="7" width="1.5" height="6" rx="0.2" />
                            <!-- left chair (back) -->
                            <rect x="0.5" y="3.5" width="1.2" height="3" rx="0.2" />
                            <!-- right chair (back) -->
                            <rect x="14.3" y="3.5" width="1.2" height="3" rx="0.2" />
                        </svg>

                        <div class="ps-3">
                            <!-- <small class="text-body">Lovely</small> -->
                            <h5 class="mt-n1 mb-0">Dining</h5>
                        </div>
                    </a>
                </li>
                <li class="nav-item menu-items mb-3" data-tab="menu_party">
                    <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#menu_party">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-cake" viewBox="0 0 16 16">
                            <path d="m7.994.013-.595.79a.747.747 0 0 0 .101 1.01V4H5a2 2 0 0 0-2 2v3H2a2 2 0 0 0-2 2v4a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4a2 2 0 0 0-2-2h-1V6a2 2 0 0 0-2-2H8.5V1.806A.747.747 0 0 0 8.592.802zM4 6a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v.414a.9.9 0 0 1-.646-.268 1.914 1.914 0 0 0-2.708 0 .914.914 0 0 1-1.292 0 1.914 1.914 0 0 0-2.708 0A.9.9 0 0 1 4 6.414zm0 1.414c.49 0 .98-.187 1.354-.56a.914.914 0 0 1 1.292 0c.748.747 1.96.747 2.708 0a.914.914 0 0 1 1.292 0c.374.373.864.56 1.354.56V9H4zM1 11a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.793l-.354.354a.914.914 0 0 1-1.293 0 1.914 1.914 0 0 0-2.707 0 .914.914 0 0 1-1.292 0 1.914 1.914 0 0 0-2.708 0 .914.914 0 0 1-1.292 0 1.914 1.914 0 0 0-2.708 0 .914.914 0 0 1-1.292 0L1 11.793zm11.646 1.854a1.915 1.915 0 0 0 2.354.279V15H1v-1.867c.737.452 1.715.36 2.354-.28a.914.914 0 0 1 1.292 0c.748.748 1.96.748 2.708 0a.914.914 0 0 1 1.292 0c.748.748 1.96.748 2.707 0a.914.914 0 0 1 1.293 0Z" />
                        </svg>
                        <div class="ps-3">
                            <!-- <small class="text-body">Lovely</small> -->
                            <h5 class="mt-n1 mb-0">Party</h5>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="menu_daywise" class="tab-pane fade show p-0 active menu_daywise">

                </div>
                <div id="menu_alacarte" class="tab-pane fade show p-0 menu_alacarte">

                </div>
                <div id="menu_dining" class="tab-pane fade show p-0 menu_dining">

                </div>
                <div id="menu_party" class="tab-pane fade show p-0">
                    <div class="container mt-5">
                        <div class="card border-0 shadow-sm" style="max-width: 600px; margin: auto;">
                            <div class="card-body d-flex align-items-start gap-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-2">Party Order Assistance</h5>
                                    <p class="card-text mb-2 text-muted">For bulk or party orders, kindly reach out to our representative:</p>
                                    <p class="mb-0">
                                        <!-- <strong>Mr. Rajesh Kumar</strong><br> -->
                                        <a href="tel:+13455461580" class="text-decoration-none text-primary fw-semibold">
                                            +1 345-546-1580
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection