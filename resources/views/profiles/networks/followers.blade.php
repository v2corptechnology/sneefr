@extends('layouts.master')

@section('title', trans('profile.networks.page_title', ['name' => $person->present()->fullName() ]))

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

                <div class="row">
                    
                    @include('profiles.networks._followers_complete_list', [
                                'type'      => "followers",
                                'person'    => $person,
                                'follows'   => $followingPersons ])
                    
                </div>

            </div>
        </div>
    </div>
@stop
