@extends('layouts.master')

@section('title', trans('dashboard.page_title'))

@section('body', 'dashboard')

@push('footer-js')
    <script src="{{ elixir('js/sneefr.dashboard.js') }}"></script>
@endpush

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 dashboard-flow">
            <ol class="media-list">

                {{-- Show a form allowing to quickly start the creation of an ad --}}
                <li class="media hidden-xs">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-4">
                            <div class="content">
                                @include('dashboard.blocks._prepare_ad')
                            </div>
                        </div>
                    </div>
                </li>


                @if ($actions->count() <= 5)
                    <li class="media activity">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-4">
                                <p class="bg-info">
                                    @lang('dashboard.empty_text', [
                                        'user_link' => route('search.index', ['type' => 'person']),
                                        'place_link' => route('search.index', ['type' => 'place']),
                                        'shop_link' => route('search.index', ['type' => 'shop'])
                                    ])
                                </p>
                            </div>
                        </div>
                    </li>
                @endif

                {{-- Show the main content of the feed --}}
                @foreach ($actions as $item)
                    <li class="media activity js-activity activity-{{ $item->type }}">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-4">
                                <div class="activity__content">
                                    @include('dashboard.item.'.$item->type, ['item' => $item, 'followedIds' => $followedUsers->identifiers()])
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach

                {{-- Display a note if there are more than a given amount of available items --}}
                @if (count($actions) >= 30)
                    <li class="activity">
                        <p class="bg-info col-md-8 col-md-offset-4">@lang('dashboard.too_much_activity')</p>
                    </li>
                @endif
            </ol>
        </div>

        <div class="col-md-4 hidden-xs">


            @if (auth()->user()->showLocationDemand || $followedPlaces->isEmpty() || auth()->user()->payment()->isAsked())
                <div class="dashboard-aside-block">
                    @if (auth()->user()->showLocationDemand)
                        {{-- Show the person an incentive to specify her location --}}
                        @include('dashboard.blocks.geolocation')
                    @elseif ($followedPlaces->isEmpty())
                        <h1 class="block-title">
                            <a class="block-title__main" href="{{ route('profiles.places.index', auth()->user()) }}#settings"
                               title="@lang('dashboard.places_of_interest_head_title')">
                                @lang('dashboard.places_of_interest_head')
                            </a>
                        </h1>
                    @endif
                </div>
            @endif

            {{-- Show the places of interest of the person --}}
            @if (!$followedPlaces->isEmpty())
                <div class="dashboard-aside-block">
                    @include('dashboard.blocks.points_of_interest', ['places' => $followedPlaces])
                </div>
            @endif

            {{-- Show the list of people who are connected to the authenticated person --}}
            <div class="dashboard-aside-block">
                @include('dashboard.blocks.friends', ['connections' => auth()->user()->followedUsers->shuffle()])
            </div>

        </div>

    </div>
</div>

@stop
