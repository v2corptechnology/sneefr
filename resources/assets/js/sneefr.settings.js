$(document).ready(function () {
    /**
     * Trigger click avatar input file
     */
    $('.js-avatar-button').on('click', function () {
        $('.js-avatar-file').click();
    });

    /**
     * Submit a form when selected file
     */
    $('.js-avatar-file').on('change', function () {
        $('.js-avatar-form').submit();
    });
});