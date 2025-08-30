function toastSuccess(message) {
    console.log("toastSuccess");
    var $toast = $("#toast-success");

    var $toastBody = $toast.find(".toast-body");
    $toastBody.css('z-index', 1000001); // Set toast z-index higher than the modal

    $toastBody.text(message);
    $toast
        .addClass("show") // Add the 'show' class to make the toast visible
        .delay(4000) // Wait for 4000ms (4 seconds)
        .queue(function (next) {
            $toast.removeClass("show"); // Remove the 'show' class
            next(); // Continue with the next item in the queue
        })
        .fadeOut(); // Fade out the toast
}
function toastFail(message) {
    var $toast = $("#toast-fail");
    var $toastBody = $toast.find(".toast-body");
    $toastBody.html(message);
    $toast
        .addClass("show") // Add the 'show' class to make the toast visible
        .delay(4000) // Wait for 4000ms (4 seconds)
        .queue(function (next) {
            $toast.removeClass("show"); // Remove the 'show' class
            next(); // Continue with the next item in the queue
        })
        .fadeOut(); // Fade out the toast
}
