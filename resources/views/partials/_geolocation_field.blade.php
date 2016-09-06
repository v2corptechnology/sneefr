<div>
    {{-- Field allowing the person to enter her location --}}
    <input type="text" id="location" class="form-control js-location {{ $extraClasses ?? null }}"
           name="location" autocomplete="off" required="required"
           value="{{ $location }}"
           placeholder="{{ trans('profile.parameters.location_placeholder') }}"
           data-waiting-text="@lang('profile.parameters.location_waiting')"
           data-error-text="@lang('profile.parameters.location_error')">
</div>

{{-- Error message that is always included but is hidden by default --}}
<p class="js-geolocation-error help-block hidden">
    <span class="text-danger">@lang('profile.parameters.location_timeout_warning')</span>
</p>

{{-- Hidden fields storing geographic coordinates --}}
<input type="hidden" id="latitude" class="js-latitude" name="latitude" value="{{ $latitude }}">
<input type="hidden" id="longitude" class="js-longitude" name="longitude" value="{{ $longitude }}">
