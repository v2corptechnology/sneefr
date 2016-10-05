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
            <div class="box__panel box__panel--padding">
                <div class="home__section">
                    <h4 class="home__section__title home__section__title--small">Best Seller</h4>
                </div>
                <div class="row">
                    @foreach($bestSellers as $item)
                        <div class="card__box__footer">
                            <div class="card__box__avatar">
                                <a href="{{ route('ad.show', $item) }}">
                                    <img class="card__box__avatar__img" src="{{ $item->images('60x60', true)[0] }}" alt="">
                                </a>
                            </div>
                            <div class="card__box__description">
                                <span class="text-indent">{{ $item->present()->title() }}</span>
                                <div>
                                    <span class="text-primary">{!! $item->present()->price() !!}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center">
                    <a class="btn btn-default-o" href="{{ route('search.index', ['type' => 'ad']) }}">@lang('button.see_more')</a>
                </div>
            </div>
        </div>
        {{-- end best seller --}}
    </div>

    <div class="row" id="shop-categories">

        <div class="col-sm-12">
            <div class="home__section home__section--padding">
                <h4 class="home__section__title">@lang('common.browse_by_category')</h4>
            </div>
        </div>

        <div class="row">
            {{-- categpries --}}
            <div class="col-sm-2">
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="home__categories">
                            <li>
                                <a href="{{ route('home') }}#shop-categories">All</a>
                            </li>
                            @foreach($categories as $category)
                                <li class="home__categories__item{{ $categories->parent == $category->id ? '--active' : '' }}">
                                    <a href="{{ route('home', "category={$category->id}#shop-categories") }}">{{ trans("category.{$category->id}") }}</a>
                                </li>
                                @if( $categories->parent == $category->id && $category->childrens)
                                    <ul class="home__categories home__categories--child">
                                        @foreach($category->childrens as $child)
                                            <li class="home__categories__item{{ $categories->child == $child->id ? '--active' : '' }}">
                                                @if($categories->child == $child->id)
                                                    <i class="fa fa-check color-pink home__categories__item--checked"></i>
                                                @endif
                                                <a href="{{ route('home', "category={$child->id}") }}#shop-categories">{{ trans("category.{$child->id}") }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            {{-- ads by categorie  --}}
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-12">
                        @foreach($shopsByCategory as $ad)
                            <div class="col-sm-4">
                                @include('partials.card', ['item' => $ad, 'multiple' => false])
                            </div>
                        @endforeach
                    </div>
                    <div class="col-sm-12" style="padding-right: 30px;">
                        <a href="{{ route('search.index', ['type' => 'shop']) }}" class="btn btn-default-o pull-right">@lang('button.see_more')</a>
                    </div>
                </div>

            </div>
            {{-- end ads by categorie  --}}
        </div>
    </div>

</div>

<div class="space"></div>

<div class="shop__pub text-center color-white">
    <div class="container">
        <div class="col-sm-6 col-sm-offset-3">
            <h1 class="shop__pub__title">@lang('common.pub.title')</h1>
            <p class="shop__pub__description">
                @lang('common.pub.description')
            </p>
            <a href="{{ route('pricing') }}" class="btn btn-blue text-uppcase">@lang('button.learn_more')</a>
        </div>
    </div>
</div>
@endsection
