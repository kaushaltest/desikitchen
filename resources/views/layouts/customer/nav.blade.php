    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0 justify-content-end">
        <!-- <a href="" class="navbar-brand p-0 d-flex align-items-center gap-2">
            <img src="{{asset('logo3.png')}}" alt="Logo">
            <h1 class="text-primary m-0">Desi Kitchen</h1>

        </a> -->
        <!-- <a href="{{ route('customer.cart') }}"
            class="position-relative d-inline-flex align-items-center py-2 px-4 d-md-none"
            id="cartBtn">
            <i class="fa fa-shopping-cart fa-lg"></i>
            <span id="cartCountMobile"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                0
            </span>
        </a> -->
        <button class="navbar-toggler position-relative " type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
            <span id="cartCountMobile"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                
            </span>
        </button>
        <div class="collapse navbar-collapse {{session('user_id')?'':''}}" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0 pe-4">
                <a href="{{route('customer.dashboard')}} " class="nav-item nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">Home</a>
                <a href="{{route('customer.about')}}" class="nav-item nav-link {{ request()->routeIs('customer.about') ? 'active' : '' }}">About</a>

                <!-- <a href="{{route('customer.service')}}" class="nav-item nav-link">Service</a> -->
                <a href="{{route('customer.menu')}}" class="nav-item nav-link {{ request()->routeIs('customer.menu') ? 'active' : '' }}">Menu</a>
                <!-- <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu m-0">
                        <a href="booking.html" class="dropdown-item">Booking</a>
                        <a href="team.html" class="dropdown-item">Our Team</a>
                        <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                    </div>
                </div> -->
                <a href="{{route('customer.contact')}}" class="nav-item nav-link {{ request()->routeIs('customer.contact') ? 'active' : '' }}">Contact</a>
                <!-- <a href="{{route('customer.subscription')}}" class="nav-item nav-link ">Plans</a> -->
                @if(session('user_id'))
                <div class="nav-item dropdown ">
                    <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs(['customer.profile','customer.order']) ? 'active' : '' }}" data-bs-toggle="dropdown"><i class="fa fa-user"></i></a>
                    <div class="dropdown-menu m-0">
                        <a href="{{route('customer.profile')}}" class="dropdown-item">My Plan</a>
                        <a href="{{route('customer.order')}}" class="dropdown-item">My Order</a>
                        <!-- <a href="{{route('customer.profile')}}" class="dropdown-item">My Profile</a> -->
                        <a href="{{route('customer.logout')}}" class="dropdown-item">Logout</a>

                    </div>
                </div>
                @else
                <div class="nav-item dropdown nav-auth" style="display: none;">
                    <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs(['customer.profile','customer.order']) ? 'active' : '' }}" data-bs-toggle="dropdown"><i class="fa fa-user"></i></a>
                    <div class="dropdown-menu m-0">
                        <a href="{{route('customer.profile')}}" class="dropdown-item">My Plan</a>
                        <a href="{{route('customer.order')}}" class="dropdown-item">My Order</a>
                        <!-- <a href="{{route('customer.profile')}}" class="dropdown-item">My Profile</a> -->
                        <a href="{{route('customer.logout')}}" class="dropdown-item">Logout</a>
                    </div>
                </div>
                <a id="login-user" href="javascript:void(0)" class="nav-item nav-link">Sign In</a>
                <!-- <button id="login-user" class="btn btn-primary ">Sign In</button> -->
                @endif

            </div>
            <a href="{{route('customer.cart')}}" class="btn btn-primary py-2 px-4 " id="cartBtn">
                <i class="fa fa-shopping-cart"></i>
                <span id="cartCount">Cart</span>
                <span class="text-white fw-bold" id="cartTotal"></span>
            </a>
            <!-- <a href="" class="btn btn-primary py-2 px-4">Book A Table</a> -->



        </div>
    </nav>