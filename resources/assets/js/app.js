
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

/*
Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: 'body'
});
*/


var TimeagoWidget = {
    settings: {
        // miliseconds : 30 days
        oldestDate: 1000*60*60*24*30,
    },

    init: function () {
        jQuery.timeago.settings.cutoff= this.settings.oldestDate;
        $('time.timeago').timeago();
    },
};

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });

    TimeagoWidget.init();

    // Enable tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // remove FB ugly hash
    if (window.location.hash == '#_=_') {
        history.replaceState
            ? history.replaceState(null, null, window.location.href.split('#')[0])
            : window.location.hash = '';
    }

    $('.ad-create .form-vertical, .ad-edit .form-vertical').each(function (event) {
        var $btn = $('.btn-success', this);
        $btn.removeAttr('disabled');
        $btn.find('.fa-spinner').addClass('hidden');
    });

    $('.ad-create .form-vertical, .ad-edit .form-vertical').on('submit', function (event) {
        var $btn = $('.btn-success', this);
        $btn.attr('disabled', 'disabled');
        $btn.find('.fa-spinner').removeClass('hidden');
    });

    $('.pop').popover({
        trigger: 'hover',
        delay: {"show": 500, "hide": 100},
        html: true
    });

    // Allow ad's final amount edition
    var $changePrice = $('.js-change-price');
    if ($changePrice.length) {
        $changePrice.on('click', function (event) {
            event.preventDefault();
            $('.js-original-price').addClass('hidden');
            $('.js-edit-price').removeClass('hidden');
            $('#final_amount').focus();
            $('#final_amount').select();
        });
    }

    $('.ajax').on('submit', function (event) {
        var trigger = this.querySelector('button[type="submit"]');
        var inputs = this.querySelectorAll('input, button');
        var options = {};
        var count = trigger.parentNode.parentNode.parentNode.parentNode.parentNode.querySelector('.count'),
            amount = count.querySelector('.amount'),
            heart = count.querySelector('.fa'),
            nb = parseInt(amount.innerHTML),
            originalText = trigger.innerHTML,
            originalClass = trigger.classList;

        for (i = 0; i < inputs.length; i++) {
            var name;
            if (name = inputs[i].getAttribute('name')) {
                options[name] = inputs[i].value;
            }
        }

        //TODO: clean this up by returning the html fragment
        $.ajax({
            url: this.getAttribute('action'),
            data: options,
            dataType: 'json',
            method: 'post',
            beforeSend: function () {
                var heartClass = heart.classList.contains('fa-heart') ? 'fa-heart-o' : 'fa-heart';
                var nbBis = heart.classList.contains('fa-heart') ? --nb : ++nb;
                var textBis = heart.classList.contains('fa-heart') ? 'J\'aime' : 'Je n\'aime plus';
                trigger.innerHTML = textBis;
                heart.classList.remove('fa-heart', 'fa-heart-o');
                heart.classList.add(heartClass);
                amount.innerHTML = nbBis;
                trigger.disabled = true;
                trigger.blur();
            },
            error: function () {
                trigger.classList = originalClass;
                trigger.innerHTML = originalText;
                amount.innerHTML = nb;

            },
            complete: function () {
                trigger.disabled = false;
            }
        });

        event.preventDefault();
    });

    $('.ajax-message').on('submit', function (event) {
        var $form = $(this);
        var inputs = this.querySelectorAll('input, button, textarea');
        var options = {};
        var sendable = true;

        if (sendable) {
            for (i = 0; i < inputs.length; i++) {
                var name;
                if (name = inputs[i].getAttribute('name')) {
                    options[name] = inputs[i].value;
                }
            }

            $.ajax({
                url: this.getAttribute('action'),
                data: options,
                dataType: 'html',
                method: 'post',
                beforeSend: function () {
                    $(inputs).add($form).attr('disabled', true).attr('readonly', true);
                    $('.autosubmit, .saving', $form).toggleClass('hidden');
                    sendable = false;
                },
                complete: function () {
                    $(inputs).add($form).attr('disabled', false).attr('readonly', false);
                    $('textarea', $form).val('');
                    $('.saving, .autosubmit', $form).toggleClass('hidden')
                    sendable = true;
                }
            });
        }

        event.preventDefault();
    });

    $('button.autosend').hide();

    $('textarea.autosend').on('keypress', function (event) {
        if (event.keyCode == 13 && !(event.metaKey || event.ctrlKey || event.shiftKey || event.altKey)) {
            var $this = $(this);
            if ($this.val().trim() != '') {
                $this.parents('form').submit();
            }
        }
    });

    // Fill modal with content from link href
    var modalContent = null;
    $("#shareModal").on("show.bs.modal", function(event) {
        modalContent = $('#shareModalContent').html();
        $('#shareModal #shareModalContent').load(event.relatedTarget.href, function() {
            $('[data-toggle="tooltip"]').tooltip();
            var clipboard = new Clipboard('.copy__btn');
            clipboard.on('success', function(event) {
                $('.copy__btn').attr('data-original-title', $(event.trigger).data('success-message')).tooltip('show');
                event.clearSelection();
            });
            clipboard.on('error', function(event) {
                $('.copy__btn').attr('data-original-title', $(event.trigger).data('error-message')).tooltip('show');
            });
        });
    });
    $("#shareModal").on("hide.bs.modal", function(event) {
        $('#shareModalContent').html(modalContent);
    });

    $("#writeTo").on("shown.bs.modal", function(event) {
        $('#body').focus();
    });
});


/*--------------------------------------------------*\
 Location finder/autocomplete widget
 \*--------------------------------------------------*/
// Only execute this code on pages where people can choose a normalized location.
if ($('.js-add-geocomplete').length) {

    // Use the ‘jQuery Geocoding and Places Autocomplete’ jQuery
    // plugin (‘geocomplete’ on Bower) to attach behavior to
    // the field where people can add their location.
    // This plugin will create an autocomplete
    // allowing people to select a location.
    $('.js-add-geocomplete')
        .geocomplete()
        // Define a callback to execute once geocoding is done. We
        // will simply add the coordinates to hidden form fields.
        .on('geocode:result', function (event, result) {

            console.log(this);

            var $parent = $(this).parents('.js-location-field-group'),
                coordinates = result.geometry.location;

            console.log($parent);

            // Add the coordinates of the location to the form.
            $('.js-latitude', $parent).val(coordinates.lat());
            $('.js-longitude', $parent).val(coordinates.lng());
        });
}

// Only execute this code on pages where people can geolocate themselves.
if ($('.js-add-geolocation').length) {
    injectFindMyLocationComponent($('.js-add-geolocation'));
}


/**
 * Add a small component that allows the person to geolacate herself.
 *
 * @param  {string}  container  A CSS selector targeting the element to
 *                              use as the container of the component.
 *
 * @return {void}
 */
function injectFindMyLocationComponent($input) {

    // End execution if the navigator doesn’t have geolocation abilities.
    if (!('geolocation' in navigator)) {
        return false;
    }

    // Set an event listener to detect clicks on the trigger element
    $($input.parent()).on('click', '.js-find-my-location', guessLocation);

    // Prepare the HTML of the component.
    var componentHtml = '\
        <span class="input-group-btn">\
            <button type="button" class="btn btn-default js-find-my-location">\
                <i class="fa fa-map-marker"></i>\
                <i class="fa fa-spinner fa-spin hidden" aria-hidden="true"></i>\
            </button>\
        </span>';

    // Inject the component after the input.
    $input.after(componentHtml);

    // This class will change the existing layout in order
    // to play nicely with the new element we’re creating.
    $input.parent().addClass('input-group');
}

/**
 * Try to use browser geolocation followed by reverse
 * geocoding in order to find the coordinates and
 * the name of the person’s current location.
 *
 * @param  {UIEvent}  event
 *
 * @return {void}
 */
function guessLocation(event) {

    event.preventDefault();

    // Start by initializing some variables.
    var
    // The DOM element that triggered the call to this method.
        trigger = this,

    // The container of the widget.
        $widget = $(trigger).closest('.input-group'),

    // The field where location can be entered.
        location = $('.js-add-geolocation', $widget).get(0),
        locationValue = location.value,

    // An element containing an error message.
        $geolocationError = $widget.next('.js-geolocation-error').hide().removeClass('hidden');

    this.blur();

    // Temporarily disable location input.
    $widget.addClass('has-feedback-left');
    location.disabled = true;
    location.value = location.getAttribute('data-waiting-text');
    $('.fa-spinner', $widget).removeClass('hidden');
    $('.fa-map-marker', $widget).addClass('hidden');


    // Launch a timer that, if not canceled before
    // its end, will re-enable the location input.
    var browserGeolocationTimer = setTimeout(function () {

        location.value = locationValue;

        $geolocationError.fadeIn('fast');

        // Re-enable the location input.
        $widget.removeClass('has-feedback-left');
        location.disabled = false;

        $('.fa-spinner', $widget).addClass('hidden');
        $('.fa-map-marker', $widget).removeClass('hidden');

    }, 10000);

    // Define a callback to execute if geolocation succeeds.
    // This function uses the geolocation data obtained by
    // the browser and tries to do a reverse geocoding of
    // the coordinates in order to find a named location.
    var successCallback = function (position) {

        clearTimeout(browserGeolocationTimer);

        // The address types we want to request from the Google Maps Geocoding API.
        // https://developers.google.com/maps/documentation/geocoding/intro#Types
        var addressTypes = [
            'sublocality',
            'postal_code',
            'locality',
            'administrative_area_level_2',
            'administrative_area_level_1'
        ];


        // Do a reverse geocoding operation from the coordinates we got from the browser.
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            'latLng': new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
            'language': 'fr'
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK && results.length) {
                // Loop through all the results to find the more accurate
                for (i = 0; i < results.length; i++) {
                    // Is any of this addressType fullfiled by the current result
                    for (j = 0; j < addressTypes.length; j++) {
                        if (results[i].types.indexOf(addressTypes[j]) != -1) {
                            // We update the location input with the data we got.
                            location.value = results[i].formatted_address;

                            // We also update the hidden input fiels with the coordinates.
                            $('.js-latitude').val(position.coords.latitude);
                            $('.js-longitude').val(position.coords.longitude);

                            // Re-enable the location input.
                            $widget.removeClass('has-feedback-left');
                            location.disabled = false;

                            $('.fa-spinner', $widget).addClass('hidden');
                            $('.fa-map-marker', $widget).removeClass('hidden');
                            return;
                        }
                    }
                }
                // If something went wrong, return.
                location.value = location.getAttribute('data-error-text');;

                // Re-enable the location input.
                $widget.removeClass('has-feedback-left');
                location.disabled = false;
            } else {
                // If something went wrong, return.
                location.value = location.getAttribute('data-error-text');;

                // Re-enable the location input.
                $widget.removeClass('has-feedback-left');
                location.disabled = false;
            }
        });
    };


    // Define a callback to execute in case the browser
    // geolocation fails. This function just displays a
    // warning and then re-enables the location input.
    var errorCallback = function (error) {

        clearTimeout(browserGeolocationTimer);

        location.value = locationValue;

        alert(
            'Une erreur est survenue pendant que nous vous géolocalisions, ' +
            'veuillez réessayer ou la remplir à la main.'
        );

        $geolocationError.fadeIn('fast');

        // Re-enable the location input.
        $widget.removeClass('has-feedback-left');
        location.disabled = false;

        $('.fa-spinner', $widget).addClass('hidden');
        $('.fa-map-marker', $widget).removeClass('hidden');
    };


    // Ask the browser to geolocate the position of the person.
    navigator.geolocation.getCurrentPosition(
        successCallback,
        errorCallback,
        {timeout: 10000}
    );
}
