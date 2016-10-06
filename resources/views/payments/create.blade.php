@extends('layouts.master')

@section('title', trans('payments.create.page_title'))

@push('footer-js')
    <script src="{{ elixir('js/sneefr.billing.js') }}"></script>
    <script src="{{ elixir('js/sneefr.delivery.js') }}"></script>
@endpush

@section('content')
    <div class="container">

        <header class="hero hero--centered">
            <img src="{{ asset('img/pig.svg') }}" width="100" alt="sneefR" class="hero__img">
            <h1 class="hero__title">@lang('payments.create.heading')</h1>
            <p class="hero__tagline">
                @lang('payments.create.tagline', ['name' => $ad->seller->present()->givenName()])
            </p>
        </header>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <main class="box text-center">
                    <h1 class="box__title">@lang('payments.create.box_heading')</h1>

                    <form action="{{ route('payments.store') }}" method="POST" class="js-payment-form">
                        {!! csrf_field() !!}

                        <input type="hidden" name="ad" value="{{ $ad->id }}">

                        <div class="box--jumbo">
                            <span class="js-final-price" data-amount-with-delivery="{{ $ad->negotiatedPrice() }}">
                                {!! $ad->present()->negotiatedPrice() !!}
                            </span>
                            @foreach ($ad->delivery->getFees() as $name => $fee)
                                <span class="js-final-price js-price-delivery-{{ $name }} hidden"
                                      data-amount-with-delivery="{{ $ad->negotiatedPrice()->withFee($name) }}">
                                    {!! $ad->present()->price($ad->negotiatedPrice()->withFee($name)->readable()) !!}
                                </span>
                            @endforeach
                        </div>

                        @if ($ad->isInShop())
                            <hr class="box__separator">
                            <h1 class="box__title">@lang('payments.create.delivery_heading')</h1>
                            <div class="form-group">

                                @foreach ($ad->present()->getFees() as $name => $fee)
                                    <label class="radio-inline delivery__option" for="delivery-{{ $name }}">
                                        <input class="js-delivery-option" type="radio"
                                               name="delivery" value="{{ $name }}" id="delivery-{{ $name }}" required autocomplete="off">
                                        @lang('payments.create.delivery_'.$name.'_label', ['price' => $fee . $ad->delivery->getCurrency() ])
                                    </label>
                                @endforeach
                                <input type="hidden" name="pick-address" value="{{ $ad->shop->getLocation() }}">

                            </div>

                            <p class="bg-info text-info text-left js-delivery-info js-delivery-info-pick hidden">
                                <strong>{{ $ad->shop->getName() }}</strong><br>
                                {{ $ad->shop->getLocation() }}
                            </p>
                        @endif

                        <hr class="box__separator">

                        <div class="form-group text-left js-extra-info hidden">
                            <label for="extra">@lang('payments.create.extra_label')</label>
                            <textarea class="form-control  js-comment" name="extra" id="extra"
                                      cols="10" rows="3" placeholder="@lang('payments.create.extra_placeholder')"></textarea>
                        </div>

                        <input type="hidden" name="payment_token" class="js-payment-token">

                        <div class="form-group">

                            @if ($ad->canMakeSecurePayement())

                                <input type="hidden" name="secure" value="true">

                                <button class="btn btn-primary btn-primary2 btn-lg btn-block js-add-stripe"
                                        type="submit"
                                        data-image="{{ ($ad->isInShop()) ? $ad->shop->getLogo('150x150') : \Img::avatar($ad->seller->facebook_id, [150, 150]) }}"
                                        data-locale="{{ auth()->user()->getLanguage() }}"
                                        data-shipping-address="true"
                                        data-billing-address="true"
                                        data-email="{{ auth()->user()->getEmail() }}"
                                        data-currency="USD" data-name="sneefR"
                                        data-description="{{ $ad->present()->title() }}"
                                        data-amount="{{ $ad->negotiatedPrice() }}"
                                        @if ($ad->isInShop()) disabled @endif
                                        title="@lang('payments.create.btn_secure_title')">
                                    <i class="fa fa-lock"></i> @lang('payments.create.btn_secure')
                                </button>

                            @else

                                <input type="hidden" name="secure" value="false">

                                <button type="submit"
                                        title="@lang('payments.create.btn_unsecure_title')"
                                        class="btn btn-default btn-default2 btn-lg btn-block">
                                    @lang('payments.create.btn_unsecure')
                                </button>
                            @endif

                        </div>

                        <div>
                            <a class="box__link" title="@lang('payments.create.pay_cancel_title')"
                               href="{{ url()->previous() }}">
                                @lang('payments.create.pay_cancel')
                            </a>
                        </div>

                    </form>

                </main>

                <footer class="tips">
                    <h1 class="tips__heading">@lang('payments.create.tips.heading')</h1>
                    <ul class="tips__items">
                        <li class="tips_item">
                            <div class="tip__image">
                                <img src="{{ asset('img/b64/tracking.svg') }}" height="30"
                                     alt="@lang('payments.create.tips.first_title')"
                                     title="@lang('payments.create.tips.first_title')">
                            </div>
                            <h2 class="tip__heading">@lang('payments.create.tips.first')</h2>
                        </li>
                        <li class="tips__item">
                            <div class="tip__image">
                                <img src="{{ asset('img/b64/picture.svg') }}" height="30"
                                     alt="@lang('payments.create.tips.second_title')"
                                     title="@lang('payments.create.tips.second_title')">
                            </div>
                            <h2 class="tip__heading">@lang('payments.create.tips.second')</h2>
                        </li>
                        <li class="tips_item">
                            <div class="tip__image">
                                <img src="{{ asset('img/b64/smiley.svg') }}" height="30"
                                     alt="@lang('payments.create.tips.third_title')"
                                     title="@lang('payments.create.tips.third_title')">
                            </div>
                            <h2 class="tip__heading">@lang('payments.create.tips.third')</h2>
                        </li>
                    </ul>
                </footer>

            </div>
        </div>
    </div>
@stop
