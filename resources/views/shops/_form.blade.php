@section('body', 'body--no-bg')

<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    <label class="control-label" for="name">@lang('shop.create.name_label')</label>
    <input class="form-control input-md js-name" id="name"
           name="name" value="{{ $name }}"
           placeholder="@lang('shop.create.name_placeholder')" type="text"
           minlength="3" maxlength="100" autocomplete="off" required>
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>

@if (!$isEditMode)
    <div class="form-group {{ $errors->has('slug') ? ' has-error' : '' }}">
        <label class="control-label" for="slug">@lang('shop.create.slug_label')</label>
        <div class="input-group">
            <span class="input-group-addon">@lang('shop.create.slug_prepend')</span>
            <input class="form-control js-slug" id="slug"
                   name="slug" value="{{ $slug }}" type="text"
                   placeholder="@lang('shop.create.slug_placeholder')"
                   minlength="3" maxlength="120" pattern="^[A-Za-z0-9\-]+$" autocomplete="off" required>
            <span class="input-group-addon">@lang('shop.create.slug_append')</span>
        </div>
        {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
    </div>
@endif

<div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="control-label" for="description">@lang('shop.create.description_label')</label>
    <textarea class="form-control" id="description" rows="4" cols="20"
              name="description" placeholder="@lang('shop.create.description_placeholder')"
              required>{{ $description }}</textarea>
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group location js-location-panel {{ $errors->has('location') ? ' has-error' : '' }}">
    <label class="control-label" for="location">@lang('shop.create.location_label')</label>
    <div class="js-location-field-group">
        @include('partials._geolocation_field', [
            'location'  => $location,
            'latitude'  => $latitude,
            'longitude' => $longitude,
            'extraClasses' => 'js-add-geocomplete',
        ])
    </div>

    {{-- Potential error message following a form submit --}}
    {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    <label for="tags" class="control-label">Tags</label>
    {!! Form::select('tags[]', $tags, $selectedTags, [
        'class' => 'form-control',
        'id' => 'js-tags',
        'size' => 5,
        'autocomplete' => 'off',
        'multiple',
        'required',
    ]) !!}
    {!! $errors->first('tags', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('logo') ? ' has-error' : '' }}">
    <label class="control-label" for="logo">@lang('shop.create.logo_label')</label>
    <input class="input-file" id="logo" name="logo" type="file" accept="image/*"
           autocomplete="off" @if(!$isEditMode) required @endif>
    {!! $errors->first('logo', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('cover') ? ' has-error' : '' }}">
    <label class="control-label" for="cover">@lang('shop.create.cover_label')</label>
    <input class="input-file" id="cover" name="cover" type="file"
           accept="image/*" autocomplete="off" @if(!$isEditMode) required @endif>
    {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
</div>

@push('footer-js')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
    $('#js-tags').select2({
        maximumSelectionLength: 4,
        placeholder: 'Choose tags',
    });
</script>
@endpush
