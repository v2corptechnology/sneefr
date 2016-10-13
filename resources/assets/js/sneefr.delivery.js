(function () {
    var Delivery = {

        init: function () {
            // Options available
            this.options = $('.js-delivery-option');

            // Quantity
            this.quantity = $('.js-quantity');

            // Price
            this.price = $('.js-price');

            // Finally bind the events
            this.bindEvents();
        },

        bindEvents: function () {
            this.options.add(this.quantity).on('change', $.proxy(this.makePrice, this));
        },

        makePrice: function (event) {
            // Be safe my friend !
            event.preventDefault();

            // Total amount (*100)
            var $checkedDelivery = $('.js-delivery-option:checked'),
                fee = $checkedDelivery.data('delivery-fee') || 0,
                amount = parseInt(this.quantity.val()) * parseInt(this.price.text()) + fee,
                textAmount = "" + amount;

            // Update the payment box to bill the correct price
            if ($checkedDelivery.length) {
                $('.js-add-stripe').attr('data-amount', amount)
                    // Enable pay button
                    .attr('disabled', false)
            } else {
                $('.js-add-stripe').attr('data-amount', amount)
                    // Disable pay button
                    .attr('disabled', true)
            }

            // Update displayed price
            $(".js-final-price").html( textAmount.slice(0, -2) + '.' + textAmount.slice(-2)+ '$');

            // Display targeted delivery info
            $('.js-delivery-info').addClass('hidden');
            if ($checkedDelivery.length) {

                // Display extra info
                $('.js-extra-info').removeClass('hidden');

                // Display info specific to this delivery
                $('.js-delivery-info-' + $checkedDelivery.val()).removeClass('hidden');
            }
        },
    };

    Delivery.init();
})();
