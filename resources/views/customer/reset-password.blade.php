@extends('layouts.customer.auth')

@section('title', 'Reset Password')

@section('content')
<style>
    /* Card polish */
    .plan-card {
        width: 100%;
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

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="section-title ff-secondary text-center text-primary fw-normal mb-5">Reset password</h1>
        </div>

    </div>

    <!-- Profile Section -->
    <div class="row g-4">

        <!-- Reset Password Section -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-12 col-md-12 col-lg-12 d-flex justify-content-center">
                        <div class="plan-card h-100 d-flex flex-column border-2">
                            <div class="plan-header p-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-1">Reset Password</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <input type="hidden" name="email" value="{{ request()->email }}">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Enter new password">
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                                        @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-danger mt-3"><i class="fa fa-key me-2"></i>Reset Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
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
    });
</script>

@endsection