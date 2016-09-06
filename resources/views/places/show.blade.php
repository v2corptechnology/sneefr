@extends('layouts.place', ['place' => $place])

@section('title', trans('place.ads.page_title', ['name' => $place->getName() ]))

@section('place_content')
    <div class="row">

        <div class="col-sm-8">
            <h1 class="content-head" id="common">
                @choice('places.show.heading', $displayedAds->count(), ['name' => $place->getName(), 'nb' => $displayedAds->count()])
            </h1>
        </div>

        {{-- Filter the ads --}}
        <div class="col-sm-4">
            @include('partials._filter', ['q' => $q ?? null, 'route' => route('places.search', $place)])
        </div>
    </div>

    {{-- Display warning when filtering the ads --}}
    @if (isset($q))
        <p class="bg-warning">
            @lang('places.show.filtering', ['filter' => $q])
        </p>
    @endif

    {{-- Show the filtered or all the ads --}}
    <div class="row">
        @forelse($displayedAds as $ad)
            <div class="col-md-6">
                @include('ad._ad', ['ad' => $ad])
            </div>
        @empty
            <div class="col-md-12">
                @if (! isset($q) && $nearbyAds->isEmpty())
                    <p class="text-muted">
                        @lang('place.nearby.alternative_text', ['name' => $place->getName(), 'url' => route('places.show', $place)])
                    </p>
                @elseif( isset($q) && $displayedAds->isEmpty())
                    <p class="bg-warning">
                        @lang('place.no_results', ['filter' => $q, 'url' => $resetUrl])
                    </p>
                @else
                    <p class="text-muted">
                        @lang('place.ads.empty_text', ['name' => $place->getName() ])
                    </p>
                @endif
            </div>
        @endforelse
    </div>
@stop
