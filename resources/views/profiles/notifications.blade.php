@extends('layouts.master')

@section('title', trans('profile.notifications.page_title'))

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

                @if ($notifications->isEmpty() && $specialNotifications->isEmpty())
                    <p class="text-muted">@lang('profile.notifications.empty_text')</p>
                @endif

                @if (! $specialNotifications->isEmpty())
                    <ol class="list-unstyled dashboard-aside-block--list">
                        @each('profiles._notification', $specialNotifications, 'notification')
                    </ol>
                @endif

                @if (! $notifications->isEmpty())
                    <ol class="list-unstyled dashboard-aside-block--list">
                        @each('profiles._notification', $notifications, 'notification')
                    </ol>
                @endif
            </div>
        </div>
    </div>
@stop
