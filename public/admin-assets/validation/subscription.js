// validation-rules.js
const validationRules = {
    validationForm: {
        rules: {
            txt_name: {
                required: true,
            },
            txt_description: {
                required: true,
            },
            txt_price: {
                required: true,
                number: true
            },
            txt_meals: {
                required: true,
                digits: true
            },
            txt_days: {
                digits: true
            },
        },
        messages: {
            txt_name: {
                required: "Required",
            },
            txt_description: {
                required: "Required",
            },
            txt_price: {
                required: "Required",
                number: "Please enter digits only"
            },
            txt_meals: {
                required: "Required",
                digits: "Please enter digits only"
            },
            txt_days: {
                digits: "Please enter digits only"
            },
        },
    },
};
