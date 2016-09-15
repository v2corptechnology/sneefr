{{--
    A small form allowing to quickly start the creation of an ad.

    It only contains a text field in order to choose the title
    of the ad. Once submitted, the form will redirect to the
    creation page of an ad and the title field in that new
    page will then be prepopulated with the given title.
--}}
<form action="{{ route('items.create') }}" method="get">
    <div class="form-group form-prepare">

        <label class="control-label" for="title">
            @lang('ad_form.prepare.create_title')
        </label>

        <div class="input-group">

            {{-- Text field to set the title of the new ad --}}
            <input id="title" name="title" type="text"
                   placeholder="@lang('ad_form.prepare.title_placeholder')"
                   class="form-control input-md"
                   value="{{ old('title') }}"
                   required>

            {{-- Submit button to send the form --}}
            <span class="input-group-btn">
                <button type="submit" class="btn btn-success">
                    @lang('ad_form.prepare.create_button')
                </button>
            </span>

        </div>
    </div>
</form>
