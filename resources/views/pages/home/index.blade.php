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

            @include('pages.home.search')

        </div>

        <div class="featured">

            @include('pages.home.highlighted-ad', ['ad' => $randomAd])

        </div>
    </header>

    <nav class="trending-categories">

        @include('pages.home.featured-categories', ['categories' => $highlighted])

    </nav>

    <main>

        @include('pages.home.featured-shops', ['shops' => $topShops])

        @include('pages.home.featured-ads', ['ads' => $topAds])

        @include('pages.home.featured-users', ['users' => $topUsers])

    </main>

    <aside class="keypoints">

        @include('pages.home.keypoints')

    </aside>

    <footer class="last-call">
        <a href="{{ url('terms') }}" title="@lang('login.terms_title')'">@lang('login.terms')</a>
        <a href="{{ url('privacy') }}" title="@lang('login.privacy_title')'">@lang('login.privacy')</a>
        <a href="{{ url('help') }}" title="@lang('login.help_title')">@lang('login.help')</a>
    </footer>

@stop
