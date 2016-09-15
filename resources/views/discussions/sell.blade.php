@extends('layouts.master')

@section('title', trans('ad.sell.page_title', ['title' => $ad->getTitle()]))

@section('content')
    <div class="container">

        <header class="hero hero--centered">
            <img src="{{ asset('img/pig.svg') }}" width="100" alt="sneefR" class="hero__img">
            <h1 class="hero__title">@lang('ad.sell.heading')</h1>
            <p class="hero__tagline">
                @lang('ad.sell.tagline', ['name' => $buyer->present()->givenName()])
                <a class="hero__link" title="@lang('ad.sell.change_buyer')"
                   href="{{ route('ads.chooseBuyer', $ad->slug()) }}">
                    @lang('ad.sell.change_buyer')
                </a>
            </p>
        </header>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <main class="box">
                    <h1 class="box__title">@lang('ad.sell.box_heading')</h1>

                    <form action="{{ route('discussions.ads.update', [$buyer->id(), $ad->slug()]) }}" method="POST">
                        {!! csrf_field() !!}
                        {!! method_field('patch') !!}

                    <article class="preview">
                        <div class="preview__figure">
                            <a class="preview__link" title="{{ $ad->getTitle() }}"
                               href="{{ route('ad.show', $ad->slug()) }}">
                                <img class="preview__image"
                                     src="{{ $ad->firstImageUrl(145) }}"
                                     alt="{{ $ad->getTitle() }}">
                            </a>
                        </div>
                        <div class="preview__body">
                            <h4 class="preview__heading">
                                <a class="preview__link" title="{{ $ad->getTitle() }}"
                                   href="{{ route('ad.show', $ad->slug()) }}">
                                    {{ $ad->getTitle() }}
                                </a>
                            </h4>

                            <div class="preview__edit">
                                <div class="js-edit-price hidden input-group">
                                    <input class="form-control" type="number"
                                           name="final_amount" id="final_amount"
                                           value="{{ $ad->price()->readable() }}"
                                           pattern="\d+(,\d{2})?" autocomplete="off"
                                           required>
                                    <span class="input-group-addon">@lang('common.currency_symbol')</span>
                                </div>

                                <div class="js-original-price">
                                    {!! $ad->present()->price() !!}
                                    <a class="js-change-price" href="#"
                                       title="@lang('ad.sell.edit_price')">
                                        @lang('ad.sell.edit_price')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <hr class="box__separator">

                    <h1 class="box__title">@lang('ad.sell.choose_pay_method')</h1>

                    <div class="row">
                        @if (! auth()->user()->payment()->hasOne())
                            <div class="col-md-12">
                                <p class="bg-warning text-warning">
                                    @lang('ad.sell.link_payment_account', [
                                        'url' => route('profiles.settings.edit', auth()->user())."#payment"
                                    ])</p>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="submit" name="secure" value="true"
                                        title="@lang('ad.sell.pay_secure_title')"
                                        class="btn btn-primary btn-primary2 btn-lg btn-block"
                                        {{ auth()->user()->payment()->hasOne() ? '' : 'disabled' }}>
                                    <i class="fa fa-lock"></i> @lang('ad.sell.pay_secure')
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="submit" name="secure" value="false"
                                        title="@lang('ad.sell.pay_unsecure_title')"
                                        class="btn btn-default btn-default2 btn-lg btn-block">
                                    @lang('ad.sell.pay_unsecure')
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a class="box__link" title="@lang('ad.sell.pay_cancel_title')"
                           href="{{ route('discussions.show', $discussion) }}#latest">
                            @lang('ad.sell.pay_cancel')
                        </a>
                    </div>

                </form>

                </main>

                <footer class="tips">
                    <h1 class="tips__heading">@lang('ad.sell.tips.heading')</h1>
                    <ul class="tips__items">
                        <li class="tips_item">
                            <div class="tip__image">
                                <img src="{{ asset('img/b64/tracking.svg') }}" height="30"
                                     alt="@lang('ad.sell.tips.first_title')"
                                     title="@lang('ad.sell.tips.first_title')">
                            </div>
                            <h2 class="tip__heading">@lang('ad.sell.tips.first')</h2>
                        </li>
                        <li class="tips__item">
                            <div class="tip__image">
                                <img src="{{ asset('img/b64/picture.svg') }}" height="30"
                                     alt="@lang('ad.sell.tips.second_title')"
                                     title="@lang('ad.sell.tips.second_title')">
                            </div>
                            <h2 class="tip__heading">@lang('ad.sell.tips.second')</h2>
                        </li>
                        <li class="tips_item">
                            <div class="tip__image">
                                <img src="{{ asset('img/b64/smiley.svg') }}" height="30"
                                     alt="@lang('ad.sell.tips.third_title')"
                                     title="@lang('ad.sell.tips.third_title')">
                            </div>
                            <h2 class="tip__heading">@lang('ad.sell.tips.third')</h2>
                        </li>
                    </ul>
                </footer>

            </div>
        </div>
    </div>
@stop
