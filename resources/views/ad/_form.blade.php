@section('scripts')
    <script src="//maps.googleapis.com/maps/api/js?libraries=places&key={{ config('sneefr.keys.GOOGLE_API_KEY') }}"></script>
    @parent
@stop

@push('footer-js')
    <script src="{{ elixir('js/sneefr.autovalidate.js') }}"></script>
    <script src="{{ elixir('js/sneefr.ad_edition.js') }}"></script>
@endpush

@unless ($shops->isEmpty())
    <div class="form-group hidden">
        <label class="control-label" for="shop_id">
            @lang('ad_form.create.as_label')
        </label>
        <select class="form-control js-publish-as" name="shop_id" id="shop_id"
                autocomplete="off">
            {{--<option value=""
                    data-location="{{ $location }}"
                    data-latitude="{{ $latitude }}"
                    data-longitude="{{ $longitude }}">
                @lang('ad_form.create.as_default', ['name' => $name])
            </option>--}}
            @foreach ($shops as $shop)
                <option value="{{ $shop->getId() }}" selected
                        data-location="{{ $shop->getLocation() }}"
                        data-latitude="{{ $shop->getLatitude() }}"
                        data-longitude="{{ $shop->getLongitude() }}"
                        @if($shop_id == $shop->getRouteKey()) selected @endif>
                    {{ $shop->getName() }}
                </option>
            @endforeach
        </select>
        {!! $errors->first('shop_id', '<p class="help-block">:message</p>') !!}
    </div>
@endunless

<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    <label class="control-label" for="title">
        @lang('ad_form.create.title_label')
    </label>
    <input class="form-control" type="text" value="{{ $title }}"
           name="title" id="title"
           placeholder="@lang('ad_form.create.title_placeholder')"
           title="@lang('ad_form.create.title_title')" minlength="3"
           pattern=".{3,}" required>
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('amount') ? ' has-error' : '' }}">
            <label class="control-label" for="amount">
                @lang('ad_form.create.price_label')
            </label>
            <div class="input-group">
                <span class="input-group-addon">@lang('ad_form.create.price_currency')</span>
                <input class="form-control" type="number" value="{{ $amount }}"
                       name="amount" id="amount"
                       placeholder="@lang('ad_form.create.price_placeholder')"
                       title="@lang('ad_form.create.price_title')"
                       pattern="\d+(,\d{2})?" required>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">
            <label class="control-label" for="category_id">
                @lang('ad_form.create.category_label')
            </label>
            {!! Form::select('category_id', $categories, $category_id, [
                'class' => 'form-control',
                'autocomplete' => 'off',
                'required'
            ]) !!}
            {!! $errors->first('category_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="control-label" for="description">
        @lang('ad_form.create.description_label')
    </label>
    <textarea class="form-control"
              placeholder="{{ $title ? trans('ad_form.create.description_placeholder_filled', ['item' => $title]) : trans('ad_form.create.description_placeholder_empty') }}"
              title="@lang('ad_form.create.description_title')"
              name="description" id="description" cols="30"
              rows="5">{!! $description !!}</textarea>
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('condition') ? ' has-error' : '' }}">
            <label class="control-label" for="condition_id">
                @lang('ad_form.create.condition_label')
            </label>
            {!! Form::select('condition_id', trans('condition.names'), $condition_id, [
                'class' => 'form-control',
                'autocomplete' => 'off',
                'required'
            ]) !!}
            {!! $errors->first('condition_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group location js-location-panel js-inject-geoloc {{ $errors->has('location') ? ' has-error' : '' }}">
            <label class="control-label" for="location">
                @lang('ad_form.create.geolocation_title')
            </label>
            <div class="js-location-field-group">
                @include('partials._geolocation_field', [
                    'location'      => $location,
                    'latitude'      => $latitude,
                    'longitude'     => $longitude,
                    'extraClasses'  => 'js-add-geocomplete js-add-geolocation',
                ])
            </div>

            {{-- Potential error message following a form submit --}}
            {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>


@unless ($shops->isEmpty())

    <div class="form-group js-delivery-options">
        <label class="control-label" for="delivery">
            @lang('ad_form.create.delivery_label')
            <small class="text-muted">@lang('ad_form.create.delivery_label_tip')</small>
        </label>

        <div class="form-inline">
            <div class="checkbox">
                <label for="delivery_pick">
                    <input name="delivery[]" id="delivery_pick" value="pick" type="checkbox" {{ $is_pickable === false ? null : 'checked' }} required aria-required="true" minlength="1">
                    @lang('ad_form.create.delivery_at_shop')
                </label>
            </div>
        </div>

        <div class="form-inline">
            <div class="checkbox">
                <label for="delivery_us">
                    <input name="delivery[]" id="delivery_us" value="us" type="checkbox" {{ $us_delivery ? 'checked' : null }} autocomplete="off">
                    @lang('ad_form.create.delivery_us')
                </label>
                <input class="form-control input-sm" name="delivery_us_value" type="number" style="width:6rem" value="{{ $us_delivery ?? null }}" autocomplete="off">
                @lang('ad_form.create.price_currency')
            </div>
        </div>

        <div class="form-inline">
            <div class="checkbox">
                <label for="delivery_worldwide">
                    <input name="delivery[]" id="delivery_worldwide" value="worldwide" type="checkbox" {{ $worldwide_delivery ? 'checked' : null }} autocomplete="off">
                    @lang('ad_form.create.delivery_worldwide')
                </label>
                <input class="form-control input-sm" name="delivery_worldwide_value" type="number" style="width:6rem" value="{{ $worldwide_delivery ?? null }}" autocomplete="off">
                @lang('ad_form.create.price_currency')
            </div>
        </div>

        <input type="hidden" name="delivery_currency" value="@lang('ad_form.create.price_currency')">

    </div>

@endunless

@if(!isset($hide_share))
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="auto_share">
                        @lang('ad_form.create.auto_share')
                    </label>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="form-group" style="margin-bottom: 0;">
            <p style="margin-bottom: 0;">
                <button class="btn btn-primary btn-primary2 btn-lg pull-right" id="save"
                        name="save" type="submit"
                        data-save="{{ $buttonText }}"
                        data-upload-in-progress="@lang('ad_form.create.upload_in_progress')">
                    <i class="fa fa-spin fa-spinner hidden"></i>
                    {{ $buttonText }}
                </button>
            </p>
        </div>
    </div>
</div>

<div class="hide" id="uploaded-images-list"></div>
