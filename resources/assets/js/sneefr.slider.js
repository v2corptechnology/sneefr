(function () {
    var Slider = {

        init: function () {
            var sliders = document.querySelectorAll('.js-slider');

            for (var i = sliders.length - 1; i >= 0; i--) {
                new IdealImageSlider.Slider({
                    selector: sliders[i].getAttribute('data-slider-target'),
                    height: parseInt(sliders[i].getAttribute('data-slider-h')),
                    transitionDuration: 400,
                    keyboardNav: false
                });
            }
        },
    };

    Slider.init();

})();
