@extends('layouts.master')

@section('title', trans('profile.write.page_title', ['name' => $person->present()->fullName() ]))

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
        </div>
    </div>
@stop
