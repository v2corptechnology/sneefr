@extends('layouts.master')

@section('title', trans('payments.create.page_title'))

@push('footer-js')
    <script src="{{ elixir('js/sneefr.billing.js') }}"></script>
    <script src="{{ elixir('js/sneefr.delivery.js') }}"></script>
@endpush

@section('content')
    <div class="container">

        <header class="hero hero--centered">
            <h1 class="hero__title">@lang('payments.create.heading')</h1>
            <p class="hero__tagline">
                @lang('payments.create.tagline', ['name' => $ad->seller->present()->givenName()])
            </p>
        </header>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <main class="box text-center">

                    <form action="{{ route('payments.store') }}" method="POST" class="js-payment-form">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="box__title">I want <select name="quantity" id="quantity" class="js-quantity" required>
                                @for($i = 1; $i <= $ad->remaining_quantity; $i++)
                                    <option value="{{ $i }}" {{ $i == 1 ? 'selected' : null }}
                                            @foreach($ad->delivery->getFees() as $name => $fee)
                                            data-{{ $name }}-tax="{{ $ad->price()->for($i)->taxOnly()->formatted() }}"
                                            data-{{ $name }}-total="{{ $ad->price()->for($i)->fee($fee)->formatted() }}"
                                            data-{{ $name }}-cents="{{ $ad->price()->for($i)->fee($fee)->cents() }}"
                                            @endforeach
                                    >{{ $i }}</option>
                                @endfor
                            </select> of these,</label>
                        </div>

                        <div class="form-group">
                            <label class="box__title">Delivered to</label><br>
                            @foreach ($ad->delivery->getFees() as $name => $fee)
                                <label class="radio-inline delivery__option">
                                    <input class="js-delivery-option" type="radio"
                                           name="delivery" value="{{ $name }}" required autocomplete="off">
                                    @lang('payments.create.delivery_'.$name.'_label', ['price' => \Sneefr\Price::fromCents($fee)->formatted() ])
                                </label>
                            @endforeach
                            <input type="hidden" name="pick-address" value="{{ $ad->shop->getLocation() }}">
                        </div>

                        <p class="bg-info text-info text-left js-delivery-info js-delivery-info-pick hidden">
                            <strong>{{ $ad->shop->getName() }}</strong><br>
                            {{ $ad->shop->getLocation() }}
                        </p>

                        <div class="js-extra-info hidden">
                            <label for="extra" class="box__title">@lang('payments.create.extra_label')</label>
                            <textarea class="form-control  js-comment" name="extra" id="extra"
                                      cols="10" rows="3" placeholder="@lang('payments.create.extra_placeholder')"></textarea>
                        </div>

                        <hr class="box__separator">

                        <div class="form-group">
                            <div class="box--jumbo">
                                <span class="js-price">
                                    {!! $ad->price()->formatted() !!}
                                </span>
                            </div>
                            <small>(incl. 9% taxes <span class="js-tax">{{ $ad->price()->taxOnly()->formatted() }}</span>)</small>
                            </div>
                        
                        <input type="hidden" name="payment_token" class="js-payment-token">

                        <div class="form-group">

                            <input type="hidden" name="ad" value="{{ $ad->id }}">

                            @if ($ad->canMakeSecurePayement())

                                <input type="hidden" name="secure" value="true">

                                <button class="btn btn-primary btn-primary2 btn-lg btn-block js-add-stripe"
                                        type="submit"
                                        data-image="{{ ($ad->isInShop()) ? $ad->shop->getLogo('150x150') : \Img::avatar($ad->seller->facebook_id, [150, 150]) }}"
                                        data-locale="{{ auth()->user()->getLanguage() }}"
                                        data-shipping-address="true"
                                        data-billing-address="true"
                                        data-email="{{ auth()->user()->getEmail() }}"
                                        data-currency="USD" data-name="Sidewalks"
                                        data-description="{{ $ad->present()->title() }}"
                                        data-amount="{{ $ad->price()->cents() }}"
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
