(function () {

    var AutoNavigate = {
        init: function () {
            this.autoNavNodes = $('.js-auto-navigate');
            this.bindEvents();
        },

        bindEvents: function () {
            this.autoNavNodes.on('change', $.proxy(this.navigate));
        },

        navigate: function (event) {
            var target = event.target.options[event.target.selectedIndex].value;

            if (target) {
                window.location.href = target;
            }
            event.preventDefault();
        }
    };

    AutoNavigate.init();
})();
