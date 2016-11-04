@extends('layouts.master')

@section('title', trans('shop.create.page_title'))

@section('scripts')
    <script src="//maps.googleapis.com/maps/api/js?libraries=places&key={{ config('sneefr.keys.GOOGLE_API_KEY') }}"></script>
    @parent
@stop

@push('footer-js')
    <script src="{{ elixir('js/sneefr.autovalidate.js') }}"></script>
    <script src="{{ elixir('js/sneefr.shops.js') }}"></script>
@endpush

@section('content')
    <style>
        body {
            background-color: transparent;}
        html {
            background: url('/img/demo-bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .box {
            box-shadow: 0 0 8px rgba(0,0,0,0.8);
        }
        .hero__tagline, .hero__title {
            color: #FFF;
            text-shadow: 0 0 3px rgba(0, 0, 0, 1);
        }
    </style>
<div class="container">

    <header class="hero hero--centered">
        <img src="{{ asset('img/pig.svg') }}" width="100" alt="Sidewalks" class="hero__img">
        <h1 class="hero__title">@lang('shop.create.heading')</h1>
        <p class="hero__tagline">@lang('shop.create.tagline')</p>
    </header>

    <main class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            <div class="box">
                <form class="form2 clearfix js-auto-validate" action="{{ route('shops.store') }}"
                      method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    @include('shops._form', [
                        'name' => old('name'),
                        'slug' => old('slug'),
                        'description' => old('description'),
                        'location' => old('location', auth()->user()->getLocation()),
                        'latitude' => old('latitude', auth()->user()->getLatitude()),
                        'longitude' => old('longitude', auth()->user()->getLongitude()),
                        'selectedTags' => [],
                        'isEditMode' => false,
                    ])

                    <div class="form-group {{ $errors->has('terms') ? ' has-error' : '' }}">
                        <label class="checkbox-inline" for="terms">
                            <input name="terms" id="terms" value="1" type="checkbox" required>
                            @lang('shop.create.terms_label', ['link' => url('terms')])
                        </label>
                        {!! $errors->first('terms', '<p class="help-block">:message</p>') !!}
                    </div>

                    <div class="form-group">
                        <button class="btn btn-lg btn-primary btn-primary2 pull-right">@lang('shop.create.save_label')</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@stop
