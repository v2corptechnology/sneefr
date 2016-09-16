@extends('layouts.master')

@section('title', trans('ad_form.create.page_title'))

@section('body', 'ad-create')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">

            <header class="hero form2">
                <h1 class="hero__title">@lang('ad_form.create.images_header')</h1>
                <p class="hero__tagline">@lang('ad_form.create.images_sub_heading')</p>
                <div class="has-error js-no-image-error {{ ($errors->has('images')) ?: 'hidden' }}">
                    <p class="help-block">@lang('ad_form.create.no_enough_image_error')</p>
                </div>
            </header>
            
            <div class="box">
                <form class="dropzone" action="{{ route('temporaryImages.store') }}"
                      method="POST" enctype="multipart/form-data" id="dropzone"
                      data-error-uploading="@lang('ad_form.create.error_while_uploading')">
                    {!! csrf_field() !!}
                    <div class="dz-default dz-message">
                        <img class="dropzone__illustration" width="100" height="100"
                             src="{{ asset('img/upload.png') }}"
                             srcset="{{ asset('img/upload@2x.png') }} 2x">

                        <div class="dropzone__upload">
                            <span class="dropzone__upload-button js-dropzone-file btn btn-default btn-default2">
                                @lang('ad_form.create.browse')
                            </span>
                            <p>@lang('ad_form.create.drop_or_click')</p>
                        </div>
                    </div>
                </form>

                <p class="dropzone__tip">
                    @lang('ad_form.create.random_images')
                </p>
            </div>
        </div>
        <div class="col-md-6">

            <form class="form2 js-auto-validate" action="{{ route('items.store') }}" method="POST">
                {!! csrf_field() !!}
                <header class="hero">

                    <h1 class="hero__title">
                        @lang('ad_form.create.details_header')
                    </h1>

                    <p class="hero__tagline">@lang('ad_form.create.details_sub_heading')</p>

                </header>

                <div class="box" style="margin-bottom: 1rem;">
                    @include('items.form', [
                        'buttonText'    => trans('ad_form.create.button_save'),
                        'shops'         => auth()->user()->shops,
                        'name'          => auth()->user()->present()->fullName(),
                        'title'         => old('title', request()->get('title')),
                        'amount'        => old('amount'),
                        'description'   => old('description'),
                        'category_id'   => old('category_id'),
                        'condition_id'  => old('condition_id', 3),
                        'location'      => old('location', auth()->user()->getLocation()),
                        'latitude'      => old('latitude', auth()->user()->getLatitude()),
                        'longitude'     => old('longitude', auth()->user()->getLongitude()),
                        'lock_quantity' => false,
                        'quantity' => old('quantity', 1),
                        'shop_id'     => old('shop_id', null),
                        // Todo: refactor please
                        'is_pickable'   => true,
                        'us_delivery'   => false,
                        'worldwide_delivery' => false,
                    ])
                </div>
            </form>
        </div>
    </div>
</div>
@stop
