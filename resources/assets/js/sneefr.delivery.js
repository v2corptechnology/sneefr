(function () {
    var Delivery = {

        init: function () {
            // Options available
            this.options = $('.js-delivery-option');

            // Finally bind the events
            this.bindEvents();
        },

        bindEvents: function () {
            this.options.on('change', $.proxy(this.handleDeliveryChange, this));
        },

        handleDeliveryChange: function (event) {
            // Be safe my friend !
            event.preventDefault();

            // Delivery method details options
            var $this = $(event.target),
                $displayedDelivery = $('.js-price-delivery-' + $this.val()),
                $displayedInfo = $('.js-delivery-info-' + $this.val());

            // Display targeted price
            $displayedDelivery.siblings().addClass('hidden').end().removeClass('hidden');

            // Display targeted delivery info
            $('.js-delivery-info').addClass('hidden');
            $displayedInfo.removeClass('hidden');

            $('.js-add-stripe')
                // Update the payment box to bill the correct price
                .attr('data-amount', parseFloat($displayedDelivery.data('amount-with-delivery')))
                // Enable pay button
                .attr('disabled', false);

            // Display extra info
            $('.js-extra-info').removeClass('hidden');
        },
    };

    Delivery.init();
})();
