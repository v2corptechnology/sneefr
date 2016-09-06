(function () {

    var Like = {
        init: function () {
            this.bindEvents();
        },

        bindEvents: function () {
            $(document.body).on('submit', '.js-like-form', $.proxy(this.sendLikeForm));
        },

        sendLikeForm: function (event) {
            event.preventDefault();

            var $form = $(this),
                $btn = $('.action__like', $form);

            // Toggle button state
            Like.toggleButtonState($btn);

            $.post($form.attr('action'), $form.serialize()).fail(function () {
                // Revert button state
                Like.toggleButtonState($btn);
            });
        },

        toggleButtonState: function ($btn) {
            var $buttonText = $('.js-like-text', $btn),
                isLiked = $btn.hasClass('action__like--active');

            // Add the class to parent activity
            $btn.toggleClass('action__like--active');

            // Update the button's text
            if (!isLiked) {
                $buttonText.text($buttonText.data('unlike-text'));
            } else {
                $buttonText.text($buttonText.data('like-text'));
            }
        }
    };

    Like.init();
})();
