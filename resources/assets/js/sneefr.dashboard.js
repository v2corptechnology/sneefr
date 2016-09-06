$(function () {
    var slidersFlow = document.querySelectorAll('.dashboard .slider');
    if (slidersFlow) {
        for (var i = slidersFlow.length - 1; i >= 0; i--) {
            new IdealImageSlider.Slider({
                transitionDuration: 400,
                selector: '.' + slidersFlow[i].getAttribute('data-target'),
                keyboardNav: false
            });
        }
        ;
    }
});
