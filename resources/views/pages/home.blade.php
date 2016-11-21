@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">

            {{-- Trending shops --}}
            <div class="col-sm-12">

                <div class="home__header">
                    <div class="home__header__content">
                        <h1 class="home__header__title">@lang('home.hero.heading')</h1>
                        <p class="home__header__dscription">@lang('home.hero.text')</p>
                        <span class="home__header__locale"><i class="fa fa-map-marker"></i> @lang('home.hero.footer')</span>
                    </div>
                </div>

                <div class="row">

                    @include('pages.home.trending-shops', ['highlights' => $highlights])

                </div>
            </div>

        </div>

    </div>

    <div class="shop__pub text-center color-white">

        @include('pages.home.shop-advertising')

    </div>
@endsection
