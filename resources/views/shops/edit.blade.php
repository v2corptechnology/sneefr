@extends('layouts.master')

@section('title', trans('shop.create.page_title'))

@section('scripts')
    <script src="//maps.googleapis.com/maps/api/js?libraries=places&key={{ config('sneefr.keys.GOOGLE_API_KEY') }}"></script>
    @parent
    <script>
        function convertToSlug (text)
        {
            return text.toLowerCase().trim()
                    .replace(/[^\w\s-]/g, '') // remove non-word [a-z0-9_], non-whitespace, non-hyphen characters
                    .replace(/[\s_-]+/g, '-') // swap any length of whitespace, underscore, hyphen characters with a single -
                    .replace(/^-+|-+$/g, ''); // remove leading, trailing -
        }

        $(".js-name").on('keyup', function () {
            $('.js-slug').val(convertToSlug(this.value));
        });

        $('.js-slug').on('keydown', function(event) {
            this.value = convertToSlug(this.value);
        });
    </script>
@stop

@push('footer-js')
    <script src="{{ elixir('js/sneefr.autovalidate.js') }}"></script>
@endpush

@section('content')
<div class="container">

    <header class="hero hero--centered">
        <img src="{{ asset('img/pig.svg') }}" width="100" alt="sneefR" class="hero__img">
        <h1 class="hero__title">@lang('shop.create.heading')</h1>
        <p class="hero__tagline">@lang('shop.create.tagline')</p>
    </header>

    <main class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            <div class="box">
                <form class="form2 clearfix js-auto-validate"
                      action="{{ route('shops.update', $shop) }}"
                      method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    {!! method_field('patch') !!}

                    @include('shops._form', [
                        'name' => $shop->getName(),
                        'description' => $shop->getDescription(),
                        'location' => $shop->getLocation(),
                        'latitude' => $shop->getLatitude(),
                        'longitude' => $shop->getLongitude(),
                        'isEditMode' => true,
                    ])

                    <div class="form-group">
                        <button class="btn btn-lg btn-primary btn-primary2 pull-right">@lang('shop.create.edit_label')</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@stop
