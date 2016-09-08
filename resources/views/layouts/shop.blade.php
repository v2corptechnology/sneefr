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
        .cover__img {
            background-image: url('{{ $shop->getCover('360x120') }}');
            background-color: {{ $shop->getBackgroundColor() }};
            color: {{ $shop->getFontColor() }};
            background-repeat: no-repeat;
            background-position: center;
            height: 200px;
            position: relative;
            text-align: center;
        }

        .cover__avatar{
            border-radius: 50%;
            margin-top: 60px;
        }

        /* 1.5 dpr */
        @media (-webkit-min-device-pixel-ratio: 1.5), (min-resolution: 144dpi) {
            .cover__img {
                background-image: url('{{ $shop->getCover('720x240') }}');
            }
        }

        @media screen and (min-width: 768px) {
            .cover__img {
                background-image: url('{{ $shop->getCover('1400x450') }}');
            }
        }
    </style>

    <header class="header">

        <div class="cover">
            <div class="cover__img">
                <a href="#" data-toggle="modal" data-target="#profilePicture">
                    <img class="cover__avatar" src="{{ $shop->getLogo('80x80') }}" alt="{{ $shop->getName() }}">
                </a>
            </div>
        </div>

        <div class="container">

            <div class="summary col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3">
                <h1 class="summary__name">{{ $shop->getName() }}</h1>
                <span class="summary__address"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $shop->getLocation() }}</span>
                <ul class="summary__keypoints">
                    <li class="summary__keypoints__item">
                        <div class="summary__icon"><i class="fa fa-thumbs-up" aria-hidden="true"></i></div> 25
                    </li>
                    <li class="summary__keypoints__item">
                        <div class="summary__icon"><i class="fa fa-thumbs-down" aria-hidden="true"></i></div> 11
                    </li>
                </ul>
                <hr>
                <p class="summary__description summary__description--narrow text-muted js-summary__item--expandable js-summary__item--collapsed">
                    {{ $shop->getDescription() }}
                </p>
                <a class="summary__toggle js-summary__toggle" href="#"><i class="fa fa-chevron-down"></i></a>
            </div>
            <div class="hidden-xs col-sm-3 col-md-3">
                <div class="actions pull-right">

                    @if ($shop->isOwner())
                        <a class="btn btn-block btn-primary btn-primary2"
                           href="{{ route('shops.edit', $shop) }}" title="___">
                            <i class="fa fa-cog"></i>
                            Edit
                        </a>
                    @else
                        @if ($shop->isFollowed())
                            <form action="{{ route('follows.destroy', [0, 'type' => 'shop', 'item' => $shop]) }}" method="POST" style="display: inline-block;">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button class="btn btn-info actions__item actions__btn" type="submit">@lang('shop.unfollow')</button>
                            </form>
                        @else
                            <form action="{{ route('follows.store', ['type' => 'shop', 'item' => $shop]) }}" method="POST" style="display: inline-block;">
                                {!! csrf_field() !!}
                                <button class="btn btn-info actions__item actions__btn" type="submit">@lang('shop.follow')</button>
                            </form>
                        @endif
                    @endif

                    <a href="#" class="btn btn-default actions__item">Share</a>

                    <div class="dropdown" style="display: inline-block;">
                        <a class="btn dropdown-toggle actions__item" type="button" id="actions-menu" data-toggle="dropdown">
                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="actions-menu">
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Report this profile</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <nav class="navbar sections__nav">
            <ul class="nav navbar-nav sections__nav__items">
                <li class="sections__nav__item sections__nav__item--selected">
                    <a href="#">
                        <span class="sections__nav__item--emphasis">{{ $shop->ads->count() }}</span>
                        <span>ads</span>
                    </a>
                </li>
                <li class="sections__nav__item">
                    <a href="#">
                        <span class="sections__nav__item--emphasis">{{ $shop->followers->count() }}</span>
                        <span>Followers</span>
                    </a>
                </li>
                <li class="sections__nav__item">
                    <a href="#">
                        <span class="sections__nav__item--emphasis">0</span>
                        <span>PLACES</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="actions visible-xs">

            @if ($shop->isOwner())
                <a class="btn btn-block btn-primary btn-primary2"
                   href="{{ route('shops.edit', $shop) }}" title="___">
                    <i class="fa fa-cog"></i>
                    Edit
                </a>
            @else
                @if ($shop->isFollowed())
                    <form action="{{ route('follows.destroy', [0, 'type' => 'shop', 'item' => $shop]) }}" method="POST" style="display: inline-block;">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}
                        <button class="btn btn-info actions__item actions__btn" type="submit">@lang('shop.unfollow')</button>
                    </form>
                @else
                    <form action="{{ route('follows.store', ['type' => 'shop', 'item' => $shop]) }}" method="POST" style="display: inline-block;">
                        {!! csrf_field() !!}
                        <button class="btn btn-info actions__item actions__btn" type="submit">@lang('shop.follow')</button>
                    </form>
                @endif
            @endif

            <a href="#" class="btn actions__item"><i class="actions__icon fa fa-comment-o" aria-hidden="true"></i></a>
            <a href="#" class="btn actions__item"><i class="actions__icon fa fa-share-alt" aria-hidden="true"></i></a>
            <a href="#" class="btn actions__item"><i class="actions__icon fa fa-info-circle" aria-hidden="true"></i></a>
        </div>
    </header>


    <div class="timeline">
        <div class="row">

            <div class="col-md-12">

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
