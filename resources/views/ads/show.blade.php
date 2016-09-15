@extends('layouts.master')

@section('title', trans('ad.show.page_title', ['title' => $ad->getTitle()]))

@section('social_media')
    @include('ads._social_media', ['ad' => $ad, 'seller' => $ad->seller])
@stop

@section('modals')
    @parent
    @include('partials.modals._report_ad', ['title' => $ad->getTitle(), 'id' => $ad->getId()])
    
    @include('partials.modals._login')

    @if ($ad->isInShop())
        @include('partials.modals._write', ['recipient' => $ad->shop, 'adId' => $ad->getId()])
    @else
        @include('partials.modals._write', ['recipient' => $ad->seller, 'adId' => $ad->getId()])
    @endif
@stop

@push('footer-js')
    <script src="{{ elixir('js/sneefr.ad.js') }}"></script>
    <script src="{{ elixir('js/sneefr.like.js') }}"></script>
@endpush

@section('styles')
    @parent
    <link rel="stylesheet" href="//cdn.rawgit.com/Pixabay/JavaScript-flexImages/27a5bf14f892bdc545218b7e9f99858d055c47b8/flex-images.css">
    <link rel="stylesheet" href="//cdn.rawgit.com/feimosi/baguetteBox.js/v1.5.0/dist/baguetteBox.min.css">
@stop

@section('content')
    <style>
        .cover {
            background-color: #FFF;
            background-position: center center;
            background-size: cover;
            cursor: pointer;
            height: 250px;
        }

        .ad__header {
            padding: 2.5rem 0;
        }

        .ad__summary {
            text-align: center;
        }

        .ad__title {
            color: #445868;
            font-size: 1.6rem;
            margin: 1rem 0 1rem 0;
        }

        .ad__condition {
            color: #F5A623;
            display: inline-block;
            font-style: normal;
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .ad__stock {
            display: inline-block;
        }

        /* TODO: change opacity to rgba */
        .ad__location {
            color: #445868;
            display: block;
            font-size: 1.3rem;
            opacity: 0.45;
        }

        .ad__price {
            display: inline-block;
            color: #1968A6;
            font-size: 2rem;
            font-weight: normal;
            margin: 1rem 0 3rem;
        }

        /* TODO: merge it in sub selector and change opacity to rgba */
        .ad__price sup {padding: 0 0.25rem; font-size: 0.7em; opacity: 0.5;}

        .ad__contact {
            border-radius: 0;
            margin: -2rem 0 2rem;
        }

        .social-interactions {
            margin-top: 2rem;
        }

        .ad__actions {
            text-align: center;
            font-size: 1.3rem;
            font-weight: normal;
        }

        .like-form {
            display: inline-block;
        }

        .action__like, .action__share {
            font-weight: normal;
        }

        .description__title {
            color: #445868;
            font-size: 1.5rem;
            margin-top: 0;
            text-transform: uppercase;
        }

        .description__body {
            margin-bottom: 3rem;
        }

        .picture {
            float: left;
            margin: 1rem 1rem 0 0;
        }

        .aside:after {
            border-bottom: 1px solid #E0E8EF;
            content: '';
            clear:  both;
            display: block;
            margin: 1.5rem 0 2rem;
        }

        .aside:last-child:after {
            display: none;
         }

        .aside__title {
            color: #445868;
            font-size: 1.2rem;
            margin: 0 0 2rem;
            text-transform: uppercase;
        }

        .aside__title a {
            color: #445868;
        }

        .aside__title-info {
            float: right;
            font-size: 1.3rem;
            margin-top: -2px;
        }

        .evaluations2 {
            margin-bottom: 3rem;
        }
        .evaluation2 {
            clear: left;
        }
        .evaluation2

        .evaluation2__user {
            float: left;
        }
        .evaluation2__content {
            margin-left: 40px;
            padding-left: 1rem;
        }

        .evaluation2__content:after {
            border-bottom: 1px solid #E0E8EF;
            content: '';
            clear:  both;
            display: block;
            margin: 0 0 1.5rem;
        }

        .evaluation2:last-child .evaluation2__content:after {
            display: none;
        }

        .evaluation2__body {
            color: #5C6A77;
            font-size: 1.3rem;
            padding-top: 0.5rem;
        }

        /* TODO: change breakpoint to bootstrap */
        @media screen and (min-width: 640px) {

            .cover {
                height: 400px;
            }

            .ad__summary {
                text-align: left;
            }

            .ad__price {
                float: right;
                font-size: 3rem;
                margin: 0.5rem 0 0;
            }

            .ad__summary:before,
            .ad__summary:after {
                border-left: 1px solid #E0E8EF;
                content: '';
                display: inline-block;
                height: 70%;
                left: 0;
                position: absolute;
                top: 15%;
            }

            .ad__summary:after {
                left: auto;
                right: 0;
            }

            .ad__title {
                font-size: 2rem;
            }

            .ad__actions {
                font-size: 1.4rem;
            }

            .like-form {
                float: left;
                margin-left: 2rem;
            }

            .action__share {
                float: right;
                margin-right: 2rem;
            }

            .ad__contact {
                border-radius: 2px;
                margin: 0;
            }
        }
    </style>

<article itemscope itemtype="http://schema.org/Product">
    {{-- Ad's main cover picture --}}
    <div class="cover" style="background-image: url('{{ $ad->firstImageUrl('1200x450') }}')"></div>

    <div class="box box--no-space">
        <div class="container">
            <div class="row ad__header">
                <div class="col-md-9">
                    <div class="row">
                        {{-- Seller profile--}}
                        <div class="col-md-2">
                            @if ($ad->isInShop())
                                <div class="user " itemprop="author">
                                    <a class="user__name" href="{{ route('shops.show', $ad->shop->getRouteKey()) }}" title="{{ $ad->shop->getName() }}">
                                        {{ $ad->shop->getName() }}
                                    </a>

                                    <a class="user__picture" href="{{ route('shops.show', $ad->shop->getRouteKey()) }}" title="{{ $ad->shop->getName() }}">
                                        <img class="user__image" src="{{ $ad->shop->getLogo('60x60') }}" srcset="{{ $ad->shop->getLogo('120x120') }} 2x" alt="{{ $ad->shop->getName() }}" height="60" width="60">
                                    </a>

                                    <div class="user__rank user__rank--first" title="@lang('rank.pro_title')">
                                        <span class="user__rank-label">@lang('rank.pro')</span>
                                    </div>
                                </div>
                            @else
                                @include('partials._link_to_user', ['user' => $ad->seller])
                            @endif
                        </div>{{-- .col-md-2 --}}

                        <div class="col-md-10 ad__summary">
                            <strong class="ad__price hidden-xs">
                                {!! $ad->present()->price() !!}
                            </strong>

                            <h1 class="ad__title" itemprop="name">{{ $ad->getTitle() }}</h1>

                            {{-- Ad condition --}}
                            <em class="ad__condition">
                                {{ $ad->present()->condition() }}
                            </em>

                            <span class="ad__stock text-muted">
                                &bull; @choice('ad.show.stock', $ad->stock->remaining, ['nb' => $ad->stock->remaining])
                            </span>

                            {{-- Ad geolocation--}}
                            <span class="ad__location">
                                <i class="fa fa-map-marker"></i>
                                {{ $ad->location() }}
                                {{ $ad->present()->distance('&bull; %s') }}
                            </span>

                            {{-- Ad price for mobile --}}
                            <strong class="ad__price visible-xs" itemprop="offers">
                                {!! $ad->present()->price() !!}
                            </strong>
                        </div>{{-- .col-md-10 --}}
                    </div>
                </div>
                {{-- Ad buttons --}}
                <div class="col-md-3">
                    <div class="ad__actions">

                        {{-- Show contact button --}}
                        @if (! $ad->isMine() || ! auth()->check())
                            <a class="btn {{ $ad->isInShop() ? 'btn-default btn-default2' : 'btn-primary btn-primary2'  }}" href="#writeTo" data-toggle="modal"
                               data-target="#writeTo" title="@lang('ad.show.btn_contact_title', ['name' => $ad->seller->present()->givenName()])">
                                <i class="fa {{ $ad->isInShop() ? 'fa-comments' : 'fa-shopping-cart' }}"></i>
                                @lang('ad.show.btn_contact')
                            </a>
                        @endif

                        {{-- Show pay button --}}
                        @if (! $ad->isMine() && $ad->isInShop())
                            <a class="btn btn-primary btn-primary2 hidden-xs ad__contact"
                                @if(auth()->check())
                                    href="{{ route('payments.create', ['ad' => $ad]) }}"
                                @else
                                    data-toggle="modal"
                                    data-target="#LoginBefore"
                                @endif
                                title="">
                                <i class="fa fa-shopping-cart"></i>
                                @lang('ad.show.btn_pay')
                            </a>
                        @endif

                        {{-- Show administration buttons --}}
                        @if ($ad->isMine() || (auth()->check() && auth()->user()->isAdmin()))
                            <a class="btn btn-default btn-default2" href="{{ route('ads.chooseBuyer', $ad->getSlug()) }}"
                               title="@lang('ad.show.btn_remove_title')">
                                @lang('ad.show.btn_remove')
                            </a>
                            <a class="btn btn-default btn-default2" href="{{ route('ad.edit', $ad->getId()) }}"
                               title="@lang('ad.show.btn_edit_title')">
                                @lang('ad.show.btn_edit')
                            </a>
                        @endif

                        <div class="social-interactions js-activity">
                            {{-- Include ‘Like’ button --}}
                            @include('widgets.like', ['item' => $ad])

                            @include('partials._share_button', ['ad' => $ad])
                        </div>{{-- .social-interactions --}}
                    </div>{{-- .ad__actions --}}
                </div>{{-- .col-md-3 --}}
            </div>
        </div>
    </div>


    {{-- Show contact or pay block button --}}
    @if ($ad->isInShop() && ! $ad->isMine())
        <a class="btn btn-lg btn-block btn-primary btn-primary2 visible-xs ad__contact"
            @if(auth()->check())
                href="{{ route('payments.create', ['ad' => $ad]) }}"
            @else
                data-toggle="modal"
                data-target="#LoginBefore"
            @endif 
           title="">
            <i class="fa fa-shopping-cart"></i>
            @lang('ad.show.btn_pay')
        </a>
    @elseif (! $ad->isMine())
        <a class="btn btn-lg btn-block btn-primary btn-primary2 visible-xs ad__contact"
           href="#writeTo" data-toggle="modal" data-target="#writeTo"
           title="@lang('ad.show.btn_contact_title', ['name' => $ad->seller->present()->givenName()])">
            <i class="fa fa-shopping-cart"></i> @lang('ad.show.btn_contact')
        </a>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="box">

                    {{-- If there is one description, display it --}}
                    @if ($ad->present()->description())
                        <h1 class="description__title">{{ $ad->getTitle() }}</h1>
                        <div class="description__body" itemprop="description">
                            {!! $ad->present()->description() !!}
                        </div>
                    @endif

                    {{-- Display ad pictures --}}
                    <h1 class="description__title">@lang('ad.show.pictures')</h1>
                    <div class="ad_pictures clearfix flex-images gallery">
                        @foreach ($ad->imageNames() as $k => $image)
                            <?php $info = getimagesize(Img::thumbnail($ad, $image, '155x155'));?>
                            <a class="item"
                               href="{{ Img::thumbnail($ad, $image, '1366x1366') }}"
                               data-w="{{ $info[0] }}" data-h="{{ $info[1] }}"
                               data-at-450="{{ Img::thumbnail($ad, $image, '450x450') }}"
                               data-at-800="{{ Img::thumbnail($ad, $image, '800x800') }}">
                                <img src="{{ Img::thumbnail($ad, $image, '155x155') }}"
                                     data-target="{{ $image }}" alt="{{ $ad->getTitle() }}"
                                     width="155" height="155">
                            </a>
                        @endforeach
                    </div>
                </div>{{-- .box --}}
            </div>{{-- .col-md-8 --}}

            <div class="col-md-4">
                <div class="box">
                    <div class="aside">
                        {{-- Count the common relationships --}}
                        @unless ($relationships['common']->isEmpty())
                            <a class="aside__title-info"
                               href="{{ route('profiles.networks.index', $ad->seller) }}"
                               title="@lang('ad.show.common_relationships_title')">
                                @choice('ad.show.common_relationships',
                                    $relationships['common']->count(),
                                    ['nb' => $relationships['common']->count()])
                            </a>
                        @endunless

                        <h1 class="aside__title">
                            <a href="{{ route('profiles.networks.index', $ad->seller) }}"
                               title="@lang('ad.show.relationships_title', ['name' => $ad->seller->present()->givenName()])">
                                @choice('ad.show.relationships',
                                    $relationships['all']->count(),
                                    ['nb' => $relationships['all']->count()])
                            </a>
                        </h1>

                        @forelse ($relationships['all']->take(9) as $relation)
                            <a title="{{ $relation->present()->fullName() }}" data-toggle="tooltip"
                               href="{{ route('profiles.show', $relation) }}">
                            {!! HTML::profilePicture($relation->socialNetworkId(), $relation->present()->givenName(), 30, ['img-rounded']) !!}
                            </a>
                        @empty
                            @lang('ad.show.relationships_empty')
                        @endforelse
                    </div>

                    <div class="aside">
                        <style>
                            .evaluation-ratio--negative {
                                color: #e74c3c;
                            }
                            .evaluation-ratio--mixed {
                                color: #f39c12;
                            }
                            .evaluation-ratio--positive {
                                color: #27ae60;
                            }
                        </style>
                        <?php
                            $evaluationModifier = getEvaluationModifier($ad->seller->evaluations->ratio());
                            function getEvaluationModifier($ratio) {
                                if ($ratio > 65) {
                                    return 'positive';
                                } elseif ($ratio > 50) {
                                    return 'mixed';
                                }
                                return 'negative';
                            }
                        ?>
                        @unless(! $ad->seller->evaluations->count())
                            <a class="aside__title-info evaluation-ratio--{{ $evaluationModifier }}"
                               href="{{ route('profiles.evaluations.index', $ad->seller) }}"
                               title="@lang('ad.show.btn_evaluations_title', ['name' => ($ad->isInShop() ) ? $ad->shop->getName() : $ad->seller->present()->givenName()])">
                                @lang('ad.show.evaluations_positive', ['percentage' => $ad->seller->evaluations->ratio()])
                            </a>
                        @endunless
                        <h1 class="aside__title">
                            <a href="{{ route('profiles.evaluations.index', $ad->seller) }}"
                               title="@lang('ad.show.btn_evaluations_title', ['name' => ($ad->isInShop() ) ? $ad->shop->getName() : $ad->seller->present()->givenName() ])">
                                @lang('ad.show.evaluations', ['name' => ($ad->isInShop() ) ? $ad->shop->getName() : $ad->seller->present()->givenName()])
                            </a>
                        </h1>

                        @unless(! $ad->seller->evaluations->count())
                            <ol class="evaluations2 list-unstyled">
                                @foreach ($ad->seller->evaluations->take(3) as $evaluation)
                                    <li class="evaluation2" itemprop="review"
                                        itemscope
                                        itemtype="http://schema.org/Review">
                                    <div class="evaluation2__user">
                                            @include('partials._link_to_user', [
                                                'user' => $evaluation->user,
                                                'showRank' => false,
                                                'showName' => false,
                                                'size' => 40
                                            ])
                                        </div>

                                        <div class="evaluation2__content">
                                            @include('partials._link_to_user', [
                                                'user' => $evaluation->user,
                                                'showRank' => false,
                                                'showImage' => false,
                                                'modifiers' => 'user--inline'
                                            ])

                                            <i class="fa fa-thumbs-{{ $evaluation->value ? 'up' : 'down' }}"></i>

                                            @if ( $evaluation->body )
                                                <p class="evaluation2__body" itemprop="description">
                                                    {{ $evaluation->body }}
                                                </p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                            @if ($ad->seller->evaluations->count() > 3)
                                <a class="btn btn-block btn-default btn-default2"
                                   href="{{ route('profiles.evaluations.index', $ad->seller) }}"
                                   title="@lang('ad.show.btn_evaluations_title', ['name' => ($ad->isInShop() ) ? $ad->shop->getName() : $ad->seller->present()->givenName()])">
                                    @lang('ad.show.btn_evaluations')
                                </a>
                            @endif
                        @endunless
                        @if (! $ad->seller->evaluations->count())
                            @lang('ad.show.evaluations_empty', ['name' => ($ad->isInShop() ) ? $ad->shop->getName() : $ad->seller->present()->givenName() ])
                        @endif
                    </div>
                </div>{{-- .box --}}
                {{-- Report an ad --}}
                @if ($ad->isReported())
                    <p class="bg-danger text-center">@lang('ad.show.reported')</p>
                @else
                    <p class="text-center">
                        <a href="#reportAd" data-toggle="modal"
                           class="text-danger"
                           title="@lang('ad.show.btn_report_title')">
                        <small>@lang('ad.show.btn_report')</small>
                        </a>
                    </p>
                @endif

            </div>{{-- .col-md-4 --}}
        </div>{{-- .row --}}
    </div>{{-- .container --}}
</article>
@stop
