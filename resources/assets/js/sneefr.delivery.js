(function () {
    var Delivery = {

        init: function () {
            // Options available
            this.fees = $('.js-delivery-option');

            // Quantity
            this.quantities = $('.js-quantity');

            // Price
            this.price = $('.js-price');

            // Taxes
            this.tax = $('.js-tax')

            // Finally bind the events
            this.bindEvents();
        },

        bindEvents: function () {
            this.fees.add(this.quantities).on('change', $.proxy(this.updatePrice, this));

            this.fees.first().trigger('click');
        },

        updatePrice: function (event) {
            // Be safe my friend !
            event.preventDefault();

            var priceDetails = this.quantities.find(':selected').data(),
                selectedFee = $("input[name=delivery]:checked").val();

            // Update displayed tax amount
            this.tax.text(priceDetails[selectedFee + 'Tax']);
            // Update displayed price
            this.price.text(priceDetails[selectedFee + 'Total']);
            // Update Stripe's charged amount
            $('.js-add-stripe').attr('data-amount', priceDetails[selectedFee + 'Cents']);

            // Display info specific to this delivery
            $('.js-delivery-info').addClass('hidden').filter('.js-delivery-info-' + selectedFee).removeClass('hidden');
        },
    };

    Delivery.init();
})();
