function toastSuccess(message) {
    var $toast = $("#toast-success");

    var $toastBody = $toast.find(".toast-body");
    $toastBody.css("z-index", 1000001); // Set toast z-index higher than the modal
    let html = `<h6 class="text-white">Success</h6><div>${message}</div>`;
    $toastBody.html(html);
    $toast
        .addClass("show") // Add the 'show' class to make the toast visible
        .delay(4000) // Wait for 4000ms (4 seconds)
        .queue(function (next) {
            $toast.removeClass("show"); // Remove the 'show' class
            next(); // Continue with the next item in the queue
        })
        // .removeClass("show"); // Fade out the toast
}
function toastFail(message) {
    var $toast = $("#toast-fail");
    var $toastBody = $toast.find(".toast-body");
    let html = `<h6 class="text-white">Error</h6><div>${message}</div>`;
    $toastBody.html(html);
    $toast
        .addClass("show") // Add the 'show' class to make the toast visible
        .delay(4000) // Wait for 4000ms (4 seconds)
        .queue(function (next) {
            $toast.removeClass("show"); // Remove the 'show' class
            next(); // Continue with the next item in the queue
        })
        // .fadeOut(); // Fade out the toast
}
