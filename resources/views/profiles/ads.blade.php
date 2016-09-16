@extends('layouts.master')

@section('title', trans('profile.ads.page_title', ['name' => $person->present()->givenName() ]))

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

                @include('profiles._ads_content', [
                    'person'         => $person,
                    'filter'         => $filter,
                    'ads'            => $ads,
                    'displayedAds'   => $displayedAds,
                    'type'           => 'ads',
                    'resetUrl'       => route('profiles.ads.index', $person->getRouteKey()),
                ])

            </div>
        </div>
    </div>
@stop
