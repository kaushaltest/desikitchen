const validationRules = {
    editUser: {
        rules: {
            txt_edit_name: {
                required: true,
            },
            txt_edit_email: {
                required: true,
                email: true,
            },
        },
        messages: {
            txt_edit_name: {
                required: "Name is required",
            },
            txt_edit_email: {
                required: "Email is required",
                email: "Please enter valid email",
            },
        },
    },
};
