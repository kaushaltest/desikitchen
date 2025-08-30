// validation-rules.js
$.validator.addMethod(
    "mobileIND",
    function (value, element) {
        return this.optional(element) || /^\d{10}$/.test(value);
    },
    "Please enter a valid 10-digit mobile number."
);

const validationRules = {
    loginForm: {
        rules: {
            txt_mobile: {
                required: true,
                mobileIND: true, // uses the custom 10-digit rule
            },
        },
        messages: {
            txt_mobile: {
                required: "Mobile number is required.",
                mobileIND: "Enter a valid 10-digit mobile number.",
            },
        },
    },

    guestLoginForm: {
        rules: {
            txt_guest_mobile: {
                required: true,
                mobileIND: true, // uses the custom 10-digit rule
            },
        },
        messages: {
            txt_guest_mobile: {
                required: "Mobile number is required.",
                mobileIND: "Enter a valid 10-digit mobile number.",
            },
        },
    },

    registerForm: {
        rules: {
            txt_new_name: {
                required: true,
            },
            txt_new_email: {
                required: true,
                email: true,
            },
            txt_new_mobile: {
                required: true,
                mobileIND: true, // uses the custom 10-digit rule
            },
        },
        messages: {
            txt_new_name: {
                required: "Name is required",
            },
            txt_new_email: {
                required: "Email is required",
                email: "Please enter valid email",
            },
            txt_new_mobile: {
                required: "Mobile number is required.",
                mobileIND: "Enter a valid 10-digit mobile number.",
            },
        },
    },
    addressValidationForm: {
        rules: {
            newAddressType: {
                required: true,
            },
            newAddress1: {
                required: true,
            },
            newAddress2: {
                required: true,
            },
            newCity: {
                required: true,
            },
            newState: {
                required: true,
            },
            newCountry: {
                required: true,
            },
            newPincode: {
                required: true,
            },
        },
        messages: {
            newAddressType: {
                required: "Select address type",
            },
            newAddress1: {
                required: "Address1 is required",
            },
            newAddress2: {
                required: "Address2 is required",
            },
            newCity: {
                required: "City is required",
            },
            newState: {
                required: "State is required",
            },
            newCountry: {
                required: "Country is required",
            },
            newPincode: {
                required: "Pincode is required",
            },
        },
    },
};
