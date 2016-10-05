@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">

        {{-- Trending shops --}}
        <div class="col-sm-9">

            @include('pages.home.quote')

            <div class="row">

                @include('pages.home.trending-shops', ['shops' => $topShops])

            </div>
        </div>

        {{-- Best selling --}}
        <div class="col-sm-3 ">

            @include('pages.home.best-selling', ['items' => $bestSellers])

        </div>
    </div>

    <div class="row" id="shop-categories">

        @include('pages.home.shops-by-category', ['categories' => $categories, 'shops' => $shopsByCategory])

    </div>

</div>

<div class="shop__pub text-center color-white">

    @include('pages.home.shop-advertising')

</div>

@endsection
