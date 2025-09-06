// validation-rules.js
const validationRules = {
    validationForm: {
        rules: {
            drp_category: {
                required: true,
            },
            file_menu_image: {
                extension: "jpg|jpeg|png|gif",
            },
            txt_title: {
                required: true,
            },
            text_description: {
                required: true,
            },
            txt_price: {
                required: true,
            },
        },
        messages: {
            drp_category: {
                required: "Required",
            },
            file_menu_image: {
                extension:
                    "Only image files (jpg, jpeg, png, gif) are allowed.",
            },
            txt_title: {
                required: "Required",
            },
            text_description: {
                required: "Required",
            },
            txt_price: {
                required: "Required",
            },
        },
    },
    categoryForm: {
        rules: {
            txt_category: {
                required: true,
            },
        },
        messages: {
            txt_category: {
                required: "Required",
            },
        },
    },
};
