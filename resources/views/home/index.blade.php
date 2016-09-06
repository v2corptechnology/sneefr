@extends('layouts.master')

@section('title', trans('login.page_title'))

@push('footer-js')
    <script src="{{ elixir('js/sneefr.dashboard.js') }}"></script>
@endpush

@if (! auth()->check())
    @section('nav')
        <nav class="navbar">
            <div class="container">
                <a class="navbar__brand" href="{{ route('home') }}">
                    <img class="visible-xs" src="{{ asset('img/demo-logo.png') }}" alt="@lang('login.img_logo')" height="20">
                    <img class="hidden-xs" src="{{ asset('img/pig_extended@2x.png') }}" alt="@lang('login.img_logo')" height="20">
                </a>
                <div class="navbar__links hidden-xs">
                    <a class="btn btn-link navbar__cta"
                       href="{{ route('pricing') }}"
                       title="@lang('login.btn_pro_title')">@lang('login.btn_pro')</a>
                    <a class="btn btn-default" style="border-radius:2px; text-transform: lowercase; font-variant: small-caps" href="{{ route('login') }}"
                       title="@lang('login.btn_login_title')">@lang('login.btn_login')</a>
                    <a class="btn btn-primary btn-primary2"
                       href="{{ route('ad.create') }}"
                       title="@lang('login.btn_add_title')">@lang('login.btn_add')</a>
                </div>
                <div class="navbar__links visible-xs-inline-block">
                    <a class="btn btn-primary btn-primary2"
                       href="{{ route('login') }}"
                       title="@lang('login.btn_login_title')">@lang('login.btn_login')</a>
                </div>
            </div>
        </nav>
    @stop
@endif

@section('content')
    <header class="welcome"
            style="background-image: url('{{ $randomAd->firstImageUrl('1600x800', true) }}'), url('{{ $randomAd->firstImageUrl('160x80', true) }}')">
    <div class="welcome__search container">
        <img class="search__image" src="{{ asset('img/demo-logo.png') }}"
             height="70" alt="@lang('login.img_logo')">
        <h1 class="search__heading">@lang('login.heading')</h1>
            <div class="hidden-xs">
                <form action="{{ route('search.index') }}" method="GET" role="search">
                    <div class="input-group input-group-lg">
                        <input type="search" name="q" class="form-control js-add-autocompletion home-search"
                               placeholder="@lang('login.btn_search_placeholder')" autofocus>
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-primary2" type="submit">@lang('login.btn_search')</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="visible-xs">
                <form action="{{ route('search.index') }}" method="GET" role="search">
                    <div class="input-group">
                        <input type="search" name="q" class="form-control js-add-autocompletion"
                               placeholder="@lang('login.btn_search_placeholder')">
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-primary2" type="submit">@lang('login.btn_search')</button>
                        </span>
                    </div>
                </form>
            </div>
    </div>
    <div class="featured">
        @if($randomAd->isInShop())
            @include('partials._link_to_shop', ['shop' => $randomAd->shop, 'size' => 40])
            
        @else
            @include('partials._link_to_user', ['user' => $randomAd->seller, 'showRank' => false, 'showName' => false, 'size' => 40])
        @endif
        <div class="featured__content">
            <a class="featured__heading"
               href="{{ route('ad.show', $randomAd->getSlug()) }}"
               title="{{ $randomAd->present()->title() }}">{{ $randomAd->present()->title() }}</a>
            <div class="featured__description">
                @lang('login.sold_by')
                @if($randomAd->isInShop())
                    <a class="featured__link"
                        href="{{ route('shops.show', $randomAd->shop->getRouteKey())  }}"
                        title="{{ $randomAd->shop->getName() }}">{{ $randomAd->shop->getName() }}
                    </a>
                @else
                    <a class="featured__link"
                        href="{{ route('profiles.ads.index', $randomAd->seller) }}"
                        title="{{ $randomAd->seller->present()->fullName() }}">{{ $randomAd->seller->present()->fullName() }}
                    </a>      
                @endif
            </div>
            <div class="featured__price">{!! $randomAd->present()->price() !!}</div>
        </div>
    </div>
</header>

<nav class="trending-categories">
    <div class="container">
        <div class="row">
            <ul class="categories">
                @foreach ($highlighted as $highlight)
                    <li class="categories__item">
                        <a class="category category--{{ $highlight['class'] }}"
                           href="{{ route('search.index') }}?categories={{ json_encode($highlight['ids']) }}">
                            <strong class="category__heading">@lang('category.'.$highlight['parentId'])</strong>
                            <span class="category__details">
                                @choice('login.articles_in_category',
                                    $highlight['ads']->count(),
                                    ['nb' => $highlight['ads']->count()])
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>

<main>
    <section class="container best-of">
        <header class="best-of__header">
            <img class="best-of__image"
                 src="{{ base64Image('img/b64/best-of__shops.svg') }}"
                 height="60" alt="@lang('login.img_shops')">
            <h1 class="best-of__heading">
                {!! link_to_route('search.index', trans('login.shops_heading'), ['type' => 'shop'], ['title' => trans('login.shops_heading_title')]) !!}
            </h1>
        </header>
        <ol class="best-of__list">
            @foreach ($topShops as $shop)
                <li class="best-of__list-item">
                    @include('partials._card', [
                        'item' => $shop,
                        'gallerySize' => '360x120',
                        'modifiers' => 'card--center'
                    ])
                </li>
            @endforeach
        </ol>
        <p class="cta__container--center">
            @lang('login.shops_text')<br>
            <a class="cta__btn btn"
               href="{{ route('pricing') }}"
               title="@lang('login.btn_shops_title')">@lang('login.btn_shops')</a>
        </p>
    </section>

    @include('home.partials.topads', ['ads' => $topAds])

    <section class="container best-of">
        <header class="best-of__header">
            <img class="best-of__image" src="{{ base64Image('img/b64/best-of__places.svg') }}" height="60" @lang('login.img_places')>
            <h1 class="best-of__heading">
                {!! link_to_route('search.index', trans('login.places_heading'), ['type' => 'place'], ['title' => trans('login.places_heading_title')]) !!}
            </h1>
        </header>
        <ol class="best-of__list">
            @foreach ($topPlaces as $place)
                <li class="best-of__list-item">
                    @include('partials._card', [
                        'item' => $place,
                        'gallerySize' => '360x120',
                        'modifiers' => 'card--center'
                    ])
                </li>
            @endforeach
        </ol>
    </section>
    <section class="container best-of">
        <header class="best-of__header">
            <img class="best-of__image" src="{{ base64Image('img/b64/best-of__sellers.svg') }}" height="60" @lang('login.img_sellers')>
            <h1 class="best-of__heading">
                {!! link_to_route('search.index', trans('login.sellers_heading'), ['type' => 'person'], ['title' => trans('login.sellers_heading_title')]) !!}
            </h1>
        </header>
        <ol class="best-of__list">
            @foreach ($topUsers as $user)
                <li class="best-of__list-item best-of__list-item--small">
                    @include('partials._card', [
                        'item' => $user,
                        'modifiers' => 'card--no-gallery card--columns card--no-delete'
                    ])
                </li>
            @endforeach
        </ol>
    </section>
</main>

<aside class="keypoints">
    <div class="keypoints__container container">
        <div class="keypoint">
            <img src="{{ asset('img/particular_pig.png') }}" alt="@lang('login.img_professional')" class="keypoint__image">
            <h1 class="keypoint__heading">@lang('login.particular_heading')</h1>
            <p class="keypoint__text">@lang('login.particular_text')</p>
            <div class="keypoint__cta">
                <a class="btn btn-primary btn-primary2"
                   href="{{ route('ad.create') }}"
                   title="@lang('login.btn_particular_title')">@lang('login.btn_particular')</a>
            </div>
        </div>
        <div class="keypoint">
            <img src="{{ asset('img/pro_pig.png') }}" alt="@lang('login.img_professional')" class="keypoint__image">
            <h1 class="keypoint__heading">@lang('login.professional_heading')</h1>
            <p class="keypoint__text">@lang('login.professional_text')</p>
            <div class="keypoint__cta">
                <a class="btn btn-primary btn-primary2 cta__btn"
                   href="{{ route('pricing') }}"
                   title="@lang('login.btn_professional_title')">@lang('login.btn_professional')</a>
            </div>
        </div>
    </div>
</aside>

<footer class="last-call">
    <a href="{{ url('terms') }}" title="@lang('login.terms_title')'">@lang('login.terms')</a>
    <a href="{{ url('privacy') }}" title="@lang('login.privacy_title')'">@lang('login.privacy')</a>
    <a href="{{ url('help') }}" title="@lang('login.help_title')">@lang('login.help')</a>
</footer>

@stop
