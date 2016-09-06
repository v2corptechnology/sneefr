(function () {
    var StripeBilling = {

        init: function () {
            // Get the stripe key
            this.stripeKey = $('meta[name="_stripe_key"]').attr('content');

            // Get the payments buttons in the page
            this.buttons = $('.js-add-stripe');

            // Load Stripe's required javascript
            this.injectStripeJs();

            // Finally bind the events
            this.bindEvents();
        },

        bindEvents: function () {
            this.buttons.on('click', $.proxy(this.handleButtonClick, this));
        },

        injectStripeJs: function () {
            //http://stackoverflow.com/questions/8578617/inject-a-script-tag-with-remote-src-and-wait-for-it-to-execute
            var js, sjs = document.getElementsByTagName('script')[0], id = 'stripe-checkout';

            // If the script is already loaded, exit
            if (document.getElementById(id)) {return;}

            js = document.createElement('script');
            js.id = id;
            js.onload = $.proxy(this.buildHandler, this);
            js.src = "//checkout.stripe.com/checkout.js";
            sjs.parentNode.insertBefore(js, sjs);
        },

        buildHandler: function () {
            // Basic config for handler
            this.handler = StripeCheckout.configure({key: this.stripeKey});
        },

        handleButtonClick: function (event) {
            // Avoid form submission
            event.preventDefault();

            // Simplifies our lives with .data()
            var $target = $(event.target);

            // Open Checkout with further options:
            this.handler.open({
                image: $target.data('image'),
                locale: $target.data('locale'),
                billingAddress: $target.data('billing-address'),
                shippingAddress: $target.data('shipping-address'),
                email: $target.data('email'),
                name: $target.data('name'),
                description: $target.data('description'),
                currency: $target.data('currency'),
                amount: $target.data('amount'),
                // You can access the token ID with `token.id`.
                // Get the token ID to your server-side code for use.
                token: function (token, addresses) {
                    // The form to be sent
                    var $form = $target.closest('form');

                    // Append the address pieces to request
                    for (var part in addresses) {
                        $form.append('<input type="hidden" name="' + part + '" value="' + addresses[part] + '" />');
                    }

                    $target
                        .prop('disabled', true)
                        .after('<input type="hidden" name="stripeToken" value="'+ token.id +'" />');

                    // Finally submit the payment
                    $form.submit();
                },
            });

            // Close Checkout on page navigation:
            $(window).on('popstate', function () {
                this.handler.close();
            });
        },
    };

    StripeBilling.init();
})();
