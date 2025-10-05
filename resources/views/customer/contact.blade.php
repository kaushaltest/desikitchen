@extends('layouts.customer.app')

@section('title', 'About Us')

@section('content')
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container text-center my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Contact Us</h1>
        <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Contact</li>
            </ol>
        </nav> -->
    </div>
</div>
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="section-title ff-secondary text-center text-primary fw-normal mb-5">Contact Us</h1>
        </div>
        <div class="row g-4">
            <!-- <div class="col-12">
                <div class="row gy-4">
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Booking</h5>
                        <p><i class="fa fa-envelope-open text-primary me-2"></i>book@example.com</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">General</h5>
                        <p><i class="fa fa-envelope-open text-primary me-2"></i>info@example.com</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Technical</h5>
                        <p><i class="fa fa-envelope-open text-primary me-2"></i>tech@example.com</p>
                    </div>
                </div>
            </div> -->
            <div class="col-md-6 wow fadeIn" data-wow-delay="0.1s">

                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3765.5755030188807!2d-81.38489012417901!3d19.300817881950692!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f258658c1c746ab%3A0x56feb451fee07728!2s144%20N%20Church%20St%2C%20George%20Town%2C%20Cayman%20Islands!5e0!3m2!1sen!2sin!4v1753813363292!5m2!1sen!2sin"  height="380" style="border:0; width:100%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-md-6">
                <div class="wow fadeInUp" data-wow-delay="0.2s">
                    <form id="form_contact" method="post">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="txt_contactname" name="txt_contactname" placeholder="Your Name">
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="txt_contactemail" name="txt_contactemail" placeholder="Your Email">
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="txt_contactsubject" name="txt_contactsubject" aria-label="Subject">
                                        <option selected disabled value="">Choose a subject</option>
                                        <option value="Party Order">Party Order</option>
                                        <option value="Tiffin Service">Tiffin Service</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="txt_contactphone" name="txt_contactphone" placeholder="phone">
                                    <label for="subject">Phone Number</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="txt_contactmessage" name="txt_contactmessage" style="height: 150px"></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button id="btnContactSend" class="btn btn-primary w-100 py-3" type="submit">
                                    <span class="spinner-border spinner-border-sm me-2 d-none"
                                        role="status"
                                        aria-hidden="true"></span>
                                    Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('customer-assets/validation/contact.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#form_contact').validate({
            rules: validationRulesContact.contactForm.rules,
            messages: validationRulesContact.contactForm.messages,
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
                const $btn = $("#btnContactSend");
                const $spinner = $btn.find(".spinner-border");
                const data = Object.fromEntries(formData.entries());
                event.preventDefault();
                $.ajax({
                    url: "{{ route('customer.contact-mail') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".loader-wrapper").css("display", "flex")
                        $btn.prop("disabled", true);
                        $spinner.removeClass("d-none");

                    },
                    success: function(response) {
                        if (response.success) {
                            toastSuccess(response.message);
                            $('#form_contact')[0].reset();
                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please try again later.");
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        var errors = xhr.responseJSON.errors;
                        toastFail("Something went wrong. Please try again later.")
                    },
                    complete: function() {
                        $(".loader-wrapper").css("display", "none")
                        $btn.prop("disabled", false);
                        $spinner.addClass("d-none");
                    },
                });

            }
        });
    });
</script>
@endsection