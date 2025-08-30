// validation-rules.js
const validationRules = {
    validationForm: {
        rules: {
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
};
