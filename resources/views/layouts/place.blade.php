@extends('layouts.master')

@section('content')
    <header class="cover">
        <div class="profile--place" style="background-image: url('{!! $place->getMapUrl(1200, 240, true) !!}')">
            <div class="profile-details">
                <h1 class="profile-details__title">
                    {{ $place->getName() }}
                </h1>

                <div class="profile-details__tagline">
                </div>
            </div>
            <ul class="profile-nav">
                <li>
            <span class="profile-nav__item">
                <i class="fa fa-map-marker"></i> {{ $place->getLongName() }}
            </span>
                </li>
            </ul>
        </div>
    </header>

    <div class="timeline">
        <div class="row">
            <div class="col-md-4">
                <div class="dashboard-aside-block">
                    @if ($place->isFollowed())
                        <form action="{{ route('follows.destroy', [0, 'type' => 'place', 'item' => $place->getRouteKey()]) }}" method="POST">
                            {!! csrf_field() !!}
                            {!! method_field('DELETE') !!}
                            <button class="btn btn-block btn-link" type="submit" title="@lang('place.sidebar.unfollow_title')">
                                <i class="fa fa-times-circle text-danger"></i>
                                @lang('place.sidebar.unfollow')
                            </button>
                        </form>

                    @else

                        <form action="{{ route('follows.store', ['type' => 'place', 'item' => $place->getRouteKey()]) }}" method="POST">
                            {!! csrf_field() !!}
                            <button class="btn btn-block btn-link" type="submit" title="@lang('place.sidebar.follow_title')">
                                <i class="fa fa-plus-circle text-success"></i>
                                @lang('place.sidebar.follow')
                            </button>
                        </form>

                    @endif
                </div>

                <ul class="summary">
                    <li class="summary__item{{ setActive(['places.show', 'places.search'], '--selected') }}">
                        <h2 class="summary__head">
                            <i class="fa fa-globe summary__icon"></i>
                            <a href="{{ route('places.show', $place) }}"
                               title="@choice('place.sidebar.ads_title', count($followersAds), [
                                'nb' => count($followersAds),
                                'name' => $place->getName()])">
                                @choice('place.sidebar.ads', count($followersAds), ['nb' => count($followersAds)])
                            </a>
                        </h2>
                        <p class="summary__content summary__content--extra">
                            @choice('place.sidebar.ads_text', count($followersAds), ['nb' => count($followersAds)])
                        </p>
                    </li>
                    <li class="summary__item{{ setActive('places.followers', '--selected') }}">
                        <h2 class="summary__head">
                            <i class="fa fa-users summary__icon"></i>
                            <a href="{{ route('places.followers', $place) }}"
                               title="@lang('place.sidebar.followers_title')">
                                @choice('place.sidebar.followers', $place->followers->count(), ['nb' => $place->followers->count()])
                            </a>
                        </h2>
                        <p class="summary__content summary__content--extra">
                            @choice('place.sidebar.followers_text', $place->followers->count(), ['nb' => $place->followers->count()])
                        </p>
                    </li>
                    <li class="summary__item{{ setActive(['places.nearby', 'places.searchAround'], '--selected') }}">
                        <h2 class="summary__head">
                            <i class="fa fa-map-marker summary__icon"></i>
                            <a href="{{ route('places.nearby', $place) }}"
                               title="@lang('place.sidebar.nearby_title')">
                                @choice('place.sidebar.nearby', count($nearbyAds), ['nb' => count($nearbyAds)])
                            </a>
                        </h2>
                        <p class="summary__content summary__content--extra">
                            @lang('place.sidebar.nearby_text')
                        </p>
                    </li>
                </ul>
            </div>

            <div class="col-md-8">

                @yield('place_content')

            </div>
        </div>
    </div>
@stop
