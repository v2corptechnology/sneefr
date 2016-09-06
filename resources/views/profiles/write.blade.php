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

            <div class="col-md-8">

                <h1 class="content-head">
                    <label for="message-body">@lang('profile.write.head', ['name' => $person->present()->givenName()])</label>
                </h1>

                {{-- The person has to be logged in to write a message. --}}
                @if (auth()->id())
                    {!! Form::open(['route' => ['messages.store']]) !!}
                        <input type="hidden" name="recipient_identifier" value="{{ $person->getRouteKey() }}">
                        <div class="form-group">
                        <textarea rows="5" cols="10" class="write__body" id="body" name="body"
                                  placeholder="@lang('modal.sneef_placeholder')" required></textarea>
                        </div>
                        <button type="submit" class="write__send">
                            <i class="fa fa-envelope"></i> @lang('modal.sneef_confirm')
                        </button>
                    {!! Form::close() !!}
                @else
                    {{-- If the person is not logged in, we ask her to do so. --}}
                    <p class="write__connect-text">
                        @lang('profile.write.connect_for_writing')
                    </p>

                    <a class="write__connect" href="{{ route('login') }}"
                       title="@lang('profile.write.button_connect_for_writing_title', ['name' => $person->present()->givenName()])">
                        @lang('profile.write.button_connect_for_writing')
                    </a>
                @endif

            </div>
        </div>
    </div>
@stop
