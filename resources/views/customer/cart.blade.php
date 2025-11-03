@extends('layouts.customer.app')

@section('title', 'Cart')

@section('content')
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container text-center my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Cart</h1>
        <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Cart</li>
            </ol>
        </nav> -->
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
<div class="modal fade" id="confirm_delete_address_message" tabindex="-1" style="z-index:1000000000000000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    Message
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="form_delete_address" method="post">
                <input type="hidden" id="hid_delete_addressid" name="hid_delete_addressid">
                <div class="modal-body">
                    <p>Are you sure want to delete this address ?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#form_delete_address').validate({
            rules: {},
            messages: {},
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
                // event.preventDefault();
                $.ajax({
                    url: "{{ route('customer.delete-address') }}", // Change this to your server endpoint
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
                        // Handle success response
                        if (response.success) {
                            toastSuccess(response.message);
                            $('#confirm_delete_address_message').modal('toggle');
                            selectedAddress=null;
                            showCheckout();
                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please contact our team or try after some time.");
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
    })
</script>
@endsection