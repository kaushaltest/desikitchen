@extends('layouts.customer.app')

@section('title', 'Profile')

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
        <h1 class="display-3 text-white mb-3 animated slideInDown">Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="section-title ff-secondary text-center text-primary fw-normal mb-5">Profile Details</h1>
        </div>

    </div>
    <div class="row g-4">

        <!-- Profile Section -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="plan-card h-100 d-flex flex-column border-2">
                            <div class="plan-header p-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-1">User Information</h5>
                                    </div>
                                    <button type="button" id="btn_edit_user" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                </div>
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2 text-primary"></i>
                                        <strong>Name:</strong> <span id="sp_edit_name">{{ $profile['name'] ?? '' }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2 text-primary"></i>
                                        <strong>Email:</strong> <span id="sp_edit_email">{{ $profile['email'] ?? '' }}</span> 
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2 text-primary"></i>
                                        <strong>Phone:</strong> {{ $profile['phone'] ?? '' }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2 text-primary"></i>
                                        <strong>Status:</strong>
                                        @if($profile['is_active'])
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-danger">Deactive</span>
                                        @endif

                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2 text-primary"></i>
                                        <strong>Joined:</strong>
                                        {{ $profile['created_at'] ? \Carbon\Carbon::parse($profile['created_at'])->format('d-m-Y') : '' }}

                                    </li>
                                </ul>

                            </div>
                        </div>

                    </div>

                    <!-- Subscription Info -->
                    @foreach($profile['subscription'] as $sub)
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="plan-card h-100 d-flex flex-column border-2">
                            <div class="plan-header p-4">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <h5 class="mb-1">Subscription Information</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2 text-primary"></i>
                                        <strong>Plan Name:</strong> {{$sub['plan']['name']}}
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2 text-primary"></i>
                                        <strong>Meals Remaining:</strong> {{$sub['meals_remaining']}}
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2 text-primary"></i>
                                        <strong>Start Date:</strong>
                                        {{ $sub['start_date'] ? \Carbon\Carbon::parse($sub['start_date'])->format('d-m-Y') : '' }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2 text-primary"></i>
                                        <strong>End Date:</strong>
                                        {{ $sub['end_date'] ? \Carbon\Carbon::parse($sub['end_date'])->format('d-m-Y') : 'Unlimited' }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2 text-primary"></i>
                                        <strong>Status:</strong>
                                        @if($sub['status'])
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-danger">Deactive</span>
                                        @endif
                                    </li>
                                </ul>

                            </div>
                        </div>

                    </div>
                    @endforeach

                </div>

            </div>
        </div>

    </div>
    <div class="modal fade" id="model_edit_user" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <!-- Replace src with your logo -->
                        <img src="{{asset('logo3.png')}}" width="100" alt="Logo" class="logo">
                    </div>
                    <h5 class="mb-3 text-center">Edit User</h5>
                    <div id="mobile-alert" class="alert d-none" role="alert"></div>
                    <form id="form_edit_user" method="post">
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="txt_edit_name" name="txt_edit_name" placeholder="Enter your name" value="{{ $profile['name'] ?? '' }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Email</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="txt_edit_email" name="txt_edit_email" placeholder="Enter your email" value="{{ $profile['email'] ?? '' }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="send-otp-btn">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('customer-assets/validation/profile.js')}}"></script>
<script>
    $(document).ready(function() {
        $(".choose-plan-btn").on("click", function() {
            let planId = $(this).data("plan");
            let meal = $(this).data("meal");
            let days = $(this).data("day");

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
                    console.log("Subscribing to plan " + planId + "...");
                },
                success: function(response) {
                    // handle success
                    toastSuccess(response.message);
                },
                error: function(xhr) {
                    // handle error
                    toastError(xhr.responseText);
                }
            });
        });

        $("#btn_edit_user").click(function() {
            $("#model_edit_user").modal('toggle');
        });
        $('#form_edit_user').validate({
            rules: validationRules.editUser.rules,
            messages: validationRules.editUser.messages,
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
                const data = Object.fromEntries(formData.entries());
                event.preventDefault();
                $.ajax({
                    url: "{{ route('customer.update-user') }}", // Change this to your server endpoint
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
                            $("#sp_edit_name").text(data.txt_edit_name);
                            $("#sp_edit_email").text(data.txt_edit_email);
                            $("#model_edit_user").modal('toggle')
                            toastSuccess("User update successfully");
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
    });
</script>

@endsection