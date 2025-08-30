// validation-rules.js
const validationRules = {
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

    addNewCustomer: {
        rules: {
            mobileInput:{
                required: true,
            },
            userNameInput:{
                required: true,
            },
            userEmailInput:{
                required: true,
                email:true
            },
            addressTypeInput: {
                required: true,
            },
            address1Input: {
                required: true,
            },
            address2Input: {
                required: true,
            },
            cityInput: {
                required: true,
            },
            stateInput: {
                required: true,
            },
            countryInput: {
                required: true,
            },
            pincodeInput: {
                required: true,
            },
        },
        messages: {
            mobileInput:{
                required: "Phone is required",
            },
            userNameInput:{
                required: "Name is required",
            },
            userEmailInput:{
                required: "Email is required",
                email:"Please enter valid email"
            },
            addressTypeInput: {
                required: "Select address type",
            },
            address1Input: {
                required: "Address1 is required",
            },
            address2Input: {
                required: "Address2 is required",
            },
            cityInput: {
                required: "City is required",
            },
            stateInput: {
                required: "State is required",
            },
            countryInput: {
                required: "Country is required",
            },
            pincodeInput: {
                required: "Pincode is required",
            },
        },
    },
};
