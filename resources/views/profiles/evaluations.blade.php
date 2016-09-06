@extends('layouts.master')

@section('title', trans('profile.evaluations.page_title', ['name' => $person->present()->fullName() ]))

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
                        'profile.evaluations.head',
                        count($evaluations), ['nb' => count($evaluations), 'name' => $person->present()->givenName()])
                </h1>

                @if ($evaluations->isEmpty())
                    <p class="text-muted">@lang('profile.evaluations.empty_text', ['name' => $person->present()->givenName()])</p>
                @else
                    <ol class="evaluation-timeline">
                        @foreach ($evaluations as $evaluation)
                            @if ($evaluation->status == 'forced')
                                <li class="evaluation evaluation--forced">
                            @else
                                <li class="evaluation">
                                    @endif
                                    <span class="evaluation__time">{!!  HTML::time($evaluation->created_at) !!}</span>
                                    @if ($evaluation->value)
                                        <span class="evaluation__value--positive"
                                              title="@lang('profile.evaluations.positive_title')">
                            <i class="fa fa-thumbs-up"></i>
                        </span>
                                    @else
                                        <span class="evaluation__value--negative"
                                              title="@lang('profile.evaluations.negative_title')">
                            <i class="fa fa-thumbs-down"></i>
                        </span>
                                    @endif
                                    <div class="evaluation__content">
                                        <a class="evaluation__profile" href="{{ route('profiles.show', [$evaluation->user->getRouteKey()]) }}"
                                           title="@lang('profile.evaluations.profile_title', ['name' => $evaluation->user->present()->givenName()])">
                                            {!! HTML::profilePicture(
                                                $evaluation->user->facebook_id,
                                                $evaluation->user->present()->givenName(),
                                                17,['evaluation__profile-image']) !!}
                                            {{ $evaluation->user->present()->givenName() }}
                                        </a>
                                        @if ($evaluation->body)
                                            <p class="evaluation__body">{{ $evaluation->body }}</p>
                                        @elseif ($evaluation->status == 'forced')
                                            <p class="evaluation__body">@lang('profile.evaluations.forced_text')</p>
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                    </ol>
                @endif

            </div>
        </div>
    </div>
@stop
