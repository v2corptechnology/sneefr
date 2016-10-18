@extends('layouts.master')

@section('title', trans('profile.places.page_title', ['name' => $person->present()->fullName() ]))

@section('content')
    <header class="cover">
        @include('profiles._header', ['person' => $person])
    </header>

    <div class="timeline">
        <div class="row">
            <div class="col-md-4">
                @include('profiles._sidebar', [
                    'person'           => $person,
                    'ads'              => $ads,
                    'isMine'           => $isMine,
                    'followedPlaces'   => $followedPlaces,
                    'searches'         => $searches,
                    'followingPersons' => $followingPersons,
                    'followedPersons'  => $followedPersons,
                    'commonPersons'    => $commonPersons,
                    'isFollowed'       => $isFollowed,
                    'evaluationRatio'  => $evaluationRatio,
                    'soldAds'          => $soldAds,
                ])
            </div>

            <div class="col-md-8">

                <h1 class="content-head">
                    @choice(
                        'profile.places.head',
                        count($followedPlaces), ['nb' => count($followedPlaces), 'name' => $person->present()->givenName()])
                </h1>

                @if ($followedPlaces->isEmpty())
                    @if ($followedPlaces->isEmpty() && !$isMine && auth()->id())
                    @else
                        <p class="places-empty">@lang('profile.places.empty_text', ['name' => $person->present()->givenName()])</p>
                    @endif
                @else
                    <ul class="places">
                        @foreach ($followedPlaces as $place)
                            <li class="places__item">
                                <div class="dashboard-aside-block">

                                    {{--
                                        Allow to delete the point of interest only if I am on my profile
                                    --}}
                                    @if ($isMine)

                                        <form class="remove-block" action="{{ route('follows.destroy', [0, 'type' => 'place', 'item' => $place]) }}" method="POST">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="close" type="submit" title="@lang('place.sidebar.unfollow_title')">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">@lang('place.sidebar.unfollow')</span>

                                            </button>
                                        </form>

                                    @endif

                                    <h1 class="place__head">
                                        <a href="{{ route('places.show', $place) }}" title="{{ $place->getName() }}">{{ $place->getName() }}</a>
                                    </h1>
                                    <a href="{{ route('places.show', $place) }}" title="{{ $place->getLongName() }}">
                                        <img class="place__img" width="350" alt="{{ $place->getLongName() }}"
                                             srcset="{!! $place->getMapUrl(350, 150, true) !!} 2x"
                                             src="{!! $place->getMapUrl(350, 150) !!}">
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>
    </div>
@stop
