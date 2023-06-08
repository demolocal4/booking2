toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}
function success_toast(message) {
    toastr.success(message);
}
function error_toast(message) {
    toastr.error(message);
}
function warning_toast(message) {
    toastr.warning(message);
}
function info_toast(message) {
    toastr.info(message);
}
