<div class="panel panel-default" id="info">
    <div class="panel-heading">
        <i class="fa fa-info-circle"></i> @lang('profile.parameters.your_info')
    </div>
    <div class="panel-body">
        <form action="{{ route('profiles.settings.update', auth()->user()) }}"
              method="POST">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}

            <input type="hidden" name="settings_category" value="info">

            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label" for="given_name">
                        @lang('profile.parameters.given_name_label')
                    </label>
                    <input type="text"
                           name="given_name" class="form-control"
                           placeholder="{{ auth()->user()->present()->givenName() }}"
                           value="{{ old('given_name') }}"
                           autocomplete="off">

                    {{-- Potential error messages following a form submit --}}
                    @if ($errors->has('given_name'))
                        <p class="help-block">{{ $errors->first('given_name') }}</p>
                    @endif
                </div>
                <div class="col-sm-6">
                    <label class="control-label" for="surname">
                        @lang('profile.parameters.surname_name_label')
                    </label>
                    <input type="text"
                           name="surname" class="form-control"
                           placeholder="{{ auth()->user()->present()->surName() }}"
                           value="{{ old('surname') }}"
                           autocomplete="off">

                    {{-- Potential error messages following a form submit --}}
                    @if ($errors->has('surname'))
                        <p class="help-block">{{ $errors->first('surname') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                <div class="col-md-12">
                    <label class="control-label" for="email">
                        @lang('profile.parameters.email_label')
                    </label>
                    <small class="text-muted">
                        @lang('profile.parameters.email_text')
                    </small>
                    <input type="text"
                        id="email" name="email" class="form-control"
                        @if (auth()->user()->hasVerifiedEmail())
                            placeholder="{{ auth()->user()->present()->protectedEmail() }}"
                        @else
                            value="{{ old('email') }}"
                            placeholder="@lang('profile.parameters.email_placeholder')"
                        @endif
                        autocomplete="off">

                    @if (! auth()->user()->hasVerifiedEmail())
                        <p class="help-block">
                            <small class="text-warning">
                                @lang('profile.parameters.email_not_yet_validated', ['email' => auth()->user()->getEmail()])
                            </small>
                        </p>
                    @endif

                    {{-- Potential error messages following a form submit --}}
                    @if ($errors->has('email'))
                        <p class="help-block">{{ $errors->first('email') }}</p>
                    @endif
                </div>
            </div>

            <?php $hasOneError = $errors->has('location') || $errors->has('latitude') || $errors->has('longitude');?>

            <div class="form-group row {{ $hasOneError ? 'has-error' : '' }}">
                <div class="col-md-12 location">
                    <label class="control-label"
                           for="location">@lang('profile.parameters.location_label')</label>
                    <div class="js-location-field-group">

                        @include('partials._geolocation_field', [
                            'location'      => old('location', auth()->user()->getLocation()),
                            'latitude'      => old('latitude', auth()->user()->getLatitude()),
                            'longitude'     => old('longitude', auth()->user()->getLongitude()),
                            'extraClasses'  => 'js-add-geocomplete js-add-geolocation',
                        ])

                    </div>

                    {{-- Potential error messages following a form submit --}}
                    @if ($errors->has('location') || $errors->has('latitude') || $errors->has('longitude'))
                        <p class="help-block">{{ $errors->first('location') }}</p>
                    @endif
                </div>
            </div>

            <button class="btn btn-success" type="submit">
                @lang('profile.parameters.button_save_parameters')
            </button>
        </form>
    </div>
</div>
