$(function(){
    if (document.getElementsByClassName('js-auto-validate').length) {

        $('.js-auto-validate').validate({
            highlight: function(element, errorClass) {
                $(element).parents('.form-group').addClass('has-error');
            },
            unhighlight: function(element, errorClass) {
                $(element).parents('.form-group').removeClass('has-error');
            },
            errorPlacement: function(error, element) {
                error.appendTo( element.parents(".form-group") );
            },
            errorClass: 'help-block'
        });
    }
});
