@extends('layouts.master')

@section('title', trans('ad_form.edit.page_title'))

@section('body', 'ad-create')

@section('scripts')
    <script>
        var adImages = [ "{!! implode('", "', $ad->images(80)) !!}" ];
        var deleteUrls = ["{!! implode('", "', array_map(function($name) use($ad) { return route('ads.images.destroy', [$ad->getId(), $name]);}, $ad->imageNames())) !!}"];
    </script>
    @parent
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">

            <header class="hero form2">
                <h1 class="hero__title">@lang('ad_form.edit.images_header')</h1>
                <p class="hero__tagline">You can add up to 10 photos of your product.</p>
                <div class="has-error js-no-image-error {{ ($errors->has('images')) ?: 'hidden' }}">
                    <p class="help-block">@lang('ad_form.create.no_enough_image_error')</p>
                </div>
            </header>

            <div class="box">
                <form class="dropzone" action="{{ route('ads.images.store', $ad) }}"
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
            <form class="form2 js-auto-validate" action="{{ route('ad.update', $ad->getId()) }}" method="POST">
                {!! method_field('patch') !!}
                {!! csrf_field() !!}

                <header class="hero">

                    <h1 class="hero__title">
                        @lang('ad_form.create.details_header')
                    </h1>

                    <p class="hero__tagline">******</p>

                </header>

                <div class="box" style="margin-bottom: 1rem;">
                    @include('items.form', [
                        'buttonText'    => trans('ad_form.edit.apply_button'),
                        'name'          => auth()->user()->present()->fullName(),
                        'title'         => old('title', $ad->getTitle()),
                        'amount'        => old('amount', $ad->price()->readable()),
                        'description'   => old('description', $ad->rawDescription()),
                        'condition_id'  => old('condition_id', $ad->getConditionId()),
                        'location'      => old('location', $ad->location()),
                        'latitude'      => old('latitude', $ad->latitude()),
                        'longitude'     => old('longitude', $ad->longitude()),
                        'shop_id'       => old('shop_id', $ad->getShopId()),
                        'selectedTags' => $ad->tags->pluck('id')->toArray(),
                        'lock_quantity' => true,
                        'quantity' => $ad->remaining_quantity,
                        'hide_share'    => true,
                        // Todo: refactor please
                        'is_pickable'   => $ad->delivery->isPickable(),
                        'us_delivery'   => $ad->delivery->isDeliverable('us') ? $ad->delivery->amountFor('us')/100 : false,
                        'worldwide_delivery' => $ad->delivery->isDeliverable('worldwide') ? $ad->delivery->amountFor('worldwide')/100 : false,
                    ])
                </div>
            </form>
        </div>
    </div>
</div>
@stop
