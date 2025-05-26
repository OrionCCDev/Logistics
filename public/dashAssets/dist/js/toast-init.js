// Toast notification initialization
$(document).ready(function() {
    // Clear any existing toasts
    $('.jq-toast-wrap').remove();

    // Initialize toast with custom settings
    $.toast({
        heading: 'Success',
        text: 'Operation completed successfully',
        showHideTransition: 'slide',
        icon: 'success',
        position: 'bottom-right',
        stack: false,
        hideAfter: 3000,
        allowToastClose: true,
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    });
});

// Function to show success toast
function showSuccessToast(message) {
    $.toast({
        heading: 'Success',
        text: message,
        showHideTransition: 'slide',
        icon: 'success',
        position: 'bottom-right',
        stack: false,
        hideAfter: 3000,
        allowToastClose: true
    });
}

// Function to show error toast
function showErrorToast(message) {
    $.toast({
        heading: 'Error',
        text: message,
        showHideTransition: 'slide',
        icon: 'error',
        position: 'bottom-right',
        stack: false,
        hideAfter: 3000,
        allowToastClose: true
    });
}

// Function to show warning toast
function showWarningToast(message) {
    $.toast({
        heading: 'Warning',
        text: message,
        showHideTransition: 'slide',
        icon: 'warning',
        position: 'bottom-right',
        stack: false,
        hideAfter: 3000,
        allowToastClose: true
    });
}

// Function to show info toast
function showInfoToast(message) {
    $.toast({
        heading: 'Information',
        text: message,
        showHideTransition: 'slide',
        icon: 'info',
        position: 'bottom-right',
        stack: false,
        hideAfter: 3000,
        allowToastClose: true
    });
}
