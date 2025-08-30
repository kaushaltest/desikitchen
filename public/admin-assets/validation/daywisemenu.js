// validation-rules.js
const validationRules = {
    userManagementForm: {
        rules: {
            file_menu_image: {
                extension: "jpg|jpeg|png|gif",
            },
            dt_date: {
                required: true,
            },
            txt_title: {
                required: true,
            },
            txt_item: {
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
            dt_date: {
                required: "Required",
            },
            txt_title: {
                required: "Required",
            },
            txt_item: {
                required: "Required",
            },
            txt_price: {
                required: "Required",
            },
        },
    },
};
