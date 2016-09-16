@extends('layouts.place', ['place' => $place])

@section('title', trans('place.networks.page_title', ['name' => $place->getName() ]))

@section('place_content')
    <div class="row">
        <div class="col-md-8">
            <h1 class="content-head" id="followed">@lang('place.networks.head', ['name' => $place->getName()])</h1>
            @if ($place->followers->isEmpty())
                <p class="text-muted">@lang('place.networks.following_empty_text', ['name' => $place->getName()])</p>
            @else
                <ol class="profile-list row">
                    @foreach ($place->followers as $follower)
                        <li class="profile-list__item  col-md-6">
                            <div class="content">
                                <a href="{{ route('profiles.show', $follower) }}" class="person--small"
                                   title="@lang('place.networks.profile_title', ['name' => $follower->present()->fullName()])">
                                    {!! HTML::profilePicture($follower->socialNetworkId(), $follower->present()->givenName(), 30, ['person__image']) !!}
                                    {{ $follower->present()->fullName() }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
@stop
