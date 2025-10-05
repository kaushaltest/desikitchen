$.validator.addMethod(
    "mobileIND",
    function (value, element) {
        return this.optional(element) || /^\d{10}$/.test(value);
    },
    "Please enter a valid 10-digit mobile number."
);

const validationRulesContact = {
    contactForm: {
        rules: {
            txt_contactname: {
                required: true,
            },
            txt_contactemail: {
                required: true,
                email: true,
            },
            txt_contactsubject: {
                required: true,
            },
            txt_contactphone: {
                required: true,
                mobileIND: true, // uses the custom 10-digit rule
            },
            txt_contactmessage: {
                required: true,
            },
        },
        messages: {
            txt_contactname: {
                required: "Name is required",
            },
            txt_contactemail: {
                required: "Email is required",
                email: "Please enter valid email",
            },
            txt_contactsubject: {
                required: "Subject is required",
            },
            txt_contactphone: {
                required: "Mobile number is required.",
                mobileIND: "Enter a valid 10-digit mobile number.",
            },
            txt_contactmessage: {
                required: "Message is required",
            },
        },
    },
};
