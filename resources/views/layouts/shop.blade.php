@extends('layouts.master')

@section('modals')
    @parent
    @include('partials.modals._shop_logo', ['shop' => $shop])
@stop

@push('footer-js')
    <script src="{{ elixir('js/sneefr.billing.js') }}"></script>
    <script src="{{ elixir('js/sneefr.shops.js') }}"></script>
@endpush

@section('content')
    <style>
        .profile-details__title, .profile-details__tagline {
            color: #FFF;
            text-shadow: 0px 0px 3px rgba(0, 0, 0, 0.8);
            font-size: 3rem;
        }

        .profile--place {
            background-image: url('{{ $shop->getCover('360x120') }}');
            background-color: {{ $shop->getBackgroundColor() }};
            color: {{ $shop->getFontColor() }};
            min-height: 20rem;
            position: relative;
        }

        .profile-nav {
            margin-top: auto;
            position: absolute;
            bottom: 0;
        }

        .profile-nav__item {color: #000000;}

        .summary__item--header {

        }
        .js-summary__item--hidden {
            display: none;
        }
        .js-summary__item--expandable {
            overflow: hidden;
        }
        .js-summary__item--collapsed {
            max-height: 4rem;
        }

        @media screen and (max-width: 768px) {
            .profile--place .profile-details {
                margin-top: 0;
            }
            .summary__item--header {
                display: none;
            }
        }

        /* 1.5 dpr */
        @media (-webkit-min-device-pixel-ratio: 1.5), (min-resolution: 144dpi) {
            .profile--place {
                background-image: url('{{ $shop->getCover('720x240') }}');
            }
        }

        @media screen and (min-width: 768px) {
            .profile--place {
                background-image: url('{{ $shop->getCover('1400x450') }}');
                min-height: 40rem;
            }
            .profile-details__title, .profile-details__tagline {
                font-size: 6rem;
            }
        }
    </style>

    <header class="cover">
        <div class="profile--place">
            <div class="profile-details">
                <h1 class="profile-details__title">{{ $shop->getName() }}</h1>
            </div>

            <div class="profile-details__tagline">
            </div>

            <ul class="profile-nav">
                <li>
            <span class="profile-nav__item">
                <i class="fa fa-map-marker"></i> {{ $shop->getLocation() }}

                @if (auth()->check() && $shop->isOwner() && App::environment('local', 'staging'))
                    <form class="form-inline" style="display: inline-block;" action="{{ route('shops.destroy', $shop) }}" method="post">
                        {!! method_field('DELETE') !!}
                        {!! csrf_field() !!}
                        <button class="btn btn-xs btn-danger" type="submit">delete</button>
                    </form>
                @endif
            </span>
                </li>
            </ul>
        </div>
    </header>

    <div class="timeline">
        <div class="row">
            
            <div class="col-md-4">
                <ul class="summary">
                    <li class="summary__item{{ setActive('xxx', '--selected') }} text-center summary__item--header"
                        style="background-color: {{ $shop->getBackgroundColor() }};padding-top: 1rem;">

                        <a href="#" data-toggle="modal" data-target="#profilePicture">
                            <img class="profile__image" alt="{{ $shop->getName() }}"
                                 src="{{ $shop->getLogo('80x80') }}" width="40" height="40">
                        </a>

                        <h2 class="summary__head" style="color:{{ $shop->getFontColor() }}; padding-left: 0;padding-top: 1rem;">
                            {{ $shop->getName() }}
                        </h2>
                    </li>
                    <li class="summary__item{{ setActive('xxx', '--selected') }}">
                        <p class="text-muted js-summary__item--expandable" style="font-size:1.3rem; margin-bottom: 0;">
                            {{ $shop->getDescription() }}
                        </p>
                        <a class="summary__toggle js-summary__toggle" href="#"><i class="fa fa-chevron-down"></i></a>
                    </li>
                    {{--<li class="summary__item js-summary__item--hidden">
                        <p class="summary__content">
                            <i class="fa fa-phone summary__icon"></i>
                            PHONE
                        </p>
                    </li>
                    <li class="summary__item js-summary__item--hidden">
                        <p class="summary__content">
                            <i class="fa fa-home summary__icon"></i>
                            ADDRESS
                        </p>
                    </li>
                    <li class="summary__item js-summary__item--hidden">
                        <i class="fa fa-calendar summary__icon"></i>
                        <dl class="summary__content">
                            <dt>Lundi - Jeudi</dt>
                            <dd>8:00 - 13:00 —14:00 - 19:00</dd>
                            <dt>Vendredi</dt>
                            <dd>8:00 - 13:00</dd>
                            <dt>Samedi - Dimanche</dt>
                            <dd>Fermé</dd>
                        </dl>
                    </li>--}}
                    <li class="summary__item{{ setActive('shops.show', '--selected') }}">
                        <h2 class="summary__head">
                            <i class="fa fa-globe summary__icon"></i>
                            <a href="{{ route('shops.show', $shop) }}">
                                {{ $shop->ads->count() }} ads
                            </a>
                        </h2>
                        <p class="summary__content summary__content--extra">
                            Details
                        </p>
                    </li>
                    <!-- evaluation section -->
                    <li class="summary__item{{ setActive('profiles.evaluations.index', '--selected') }}">
                        <h2 class="summary__head">
                            <i class="fa fa-trophy summary__icon"></i>
                            <a href="{{ route('shops.evaluations', $shop) }}"
                               title="@choice('shop.sidebar.evaluations_title', $shop->evaluations->ratio()), [
                                'ratio' => $shop->evaluations->ratio()),
                                'name' => $shop->getName()])">
                                @choice('profile.sidebar.evaluations', $shop->evaluations->ratio(), ['ratio' => $shop->evaluations->ratio()])
                            </a>
                        </h2>
                    </li>

                    <li class="summary__item{{ setActive('xxx', '--selected') }}">
                        <h2 class="summary__head">
                            <i class="fa fa-smile-o summary__icon"></i>
                            {{--<a href="#"
                               title="">--}}
                            {{ $shop->employees->count() }} people in team
                            {{--</a>--}}
                        </h2>
                        <p class="summary__content summary__content--extra">
                            @foreach ($shop->employees as $employee)
                                <a class="user__picture" data-toggle="tooltip"
                                   title="@lang('ad.show_profile_title', ['name' => $employee->present()->givenName()])">
                                    {!! HTML::profilePicture($employee->getSocialNetworkId(), $employee->present()->fullName(), 20) !!}
                                </a>
                            @endforeach
                        </p>
                    </li>
                    <li>
                        @if ($shop->isOwner())
                            <a class="btn btn-block btn-primary btn-primary2"
                               href="{{ route('shops.edit', $shop) }}" title="___">
                                <i class="fa fa-cog"></i>
                                Edit
                            </a>
                        @endif
                    </li>
                </ul>
            </div>

            <div class="col-md-8">

                @if (! $shop->owner->subscribed('shop') && $shop->isOwner())
                    @include('shops._subscription')
                @elseif (! $shop->owner->payment()->hasOne() && $shop->isOwner())
                    <p class="text-warning bg-warning">
                        @lang('shops.show.link_payment', [
                            'link' => link_to_route('profiles.settings.edit',
                                trans('shops.show.link_payment_action'),
                                auth()->user(),
                                ['title' => trans('shops.show.link_payment_action_title')]
                            )
                        ])
                    </p>
                @endif

                @yield('shop_content')

            </div>
        </div>
    </div>
@stop
