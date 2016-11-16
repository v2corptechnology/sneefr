@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">

            {{-- Trending shops --}}
            <div class="col-sm-12">

                @include('pages.home.quote')

                <div class="row">

                    @include('pages.home.trending-shops', ['shops' => $topShops])

                </div>
            </div>

        </div>

    </div>

    <div class="shop__pub text-center color-white">

        @include('pages.home.shop-advertising')

    </div>
@endsection
