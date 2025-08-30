// validation-rules.js
const validationRules = {
    userValidationForm: {
        rules: {
            txt_name: {
                required: true,
            },
            txt_email: {
                required: true,
                email: true,
            },
            txt_phone: {
                required: true,
            },
        },
        messages: {
            txt_name: {
                required: "Name is required",
            },
            txt_email: {
                required: "Email is required",
                email: "Please enter valid email",
            },
            txt_phone: {
                required: "Phone is required",
            },
        },
    },
    tableValidationForm: {
        rules: {
            txt_name: {
                required: true,
            },
            txt_capicity: {
                required: true,
            }
        },
        messages: {
            txt_name: {
                required: "Name is required",
            },
            txt_capicity: {
                required: "Capicity is required",
            }
        },
    },
    tableUserValidationForm: {
        rules: {
            txt_name: {
                required: true,
            },
            txt_email: {
                email: true,
            },
            txt_phone: {
                required: true,
            },
        },
        messages: {
            txt_name: {
                required: "Name is required",
            },
            txt_email: {
                email: "Please enter valid email",
            },
            txt_phone: {
                required: "Phone is required",
            },
        },
    },
};
