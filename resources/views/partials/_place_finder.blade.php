@push('footer-js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('sneefr.keys.GOOGLE_API_KEY') }}&libraries=places&language={{ config('app.locale') }}&callback=initPlaceFinder"
            async defer></script>

    <script>
        function initPlaceFinder() {
            unlockControls();
            bindUiEvents();
        }

        function unlockControls() {
            var finder = document.querySelector('.js-place-finder'),
                save = document.querySelector('.js-place-save');

            save.disabled = false;
            finder.disabled = false;
            finder.placeholder = finder.getAttribute('data-active-placeholder');
        }

        function bindUiEvents() {
            var input = document.querySelector('.js-place-finder'),
                searchBox = new google.maps.places.SearchBox(input),
                placeId = document.querySelector('.js-place-id'),
                placeName = document.querySelector('.js-place-name');

            // Prevent parent form from being submitted if user hit enter.
            input.addEventListener('keypress', function(event) {
                if (! placeId.value || (input.value != placeName.value)) {
                    if (event.keyCode === 13 || event.keyCode === 169) {
                        event.stopPropagation();
                        event.preventDefault();
                    }
                }
            });

            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    placeName.value = null;
                    placeId.value = null;
                    return;
                }

                // Fill the place_id in hidden field
                placeId.value = places[0].place_id;
                placeName.value = input.value;
            });
        }
    </script>
@endpush

<form action="{{ route('places.store') }}" method="POST">
    {!! csrf_field() !!}
    <div class="input-group">
        <input type="text" class="form-control js-place-finder" name="location"
               data-active-placeholder="@lang('profile.places.add_place_of_interest_placeholder')"
               placeholder="@lang('profile.places.add_place_of_interest_disabled_placeholder')"
               autocomplete="off" value="" disabled>
        <span class="input-group-btn">
            <button class="btn btn-success js-place-save" type="submit" disabled>
                @lang('profile.places.button_save_place_of_interest')
            </button>
        </span>
    </div>

    <input type="hidden" name="place_id" class="js-place-id" value="">
    <input type="hidden" name="place_name" class="js-place-name" value="">
</form>
