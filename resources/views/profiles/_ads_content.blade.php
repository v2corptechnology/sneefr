<div class="row">
    <div class="col-sm-8">
        <h1 class="content-head" id="common">
            @if ($filter)
                @choice('profile.ads.head_filtered', count($displayedAds), ['name' => $person->present()->givenName(), 'nb' => count($displayedAds)])
            @else
                @choice('profile.ads.head', count($displayedAds), ['name' => $person->present()->givenName(), 'nb' => count($displayedAds)])
            @endif
        </h1>
    </div>
    <div class="col-sm-4">
        @include('partials._filter', ['$filter' => $filter])
    </div>
</div>

@if ($filter && $displayedAds->isEmpty())
    <p class="bg-warning">
        @lang('profile.ads.no_results', ['filter' => $filter, 'url' => $resetUrl])
    </p>
@elseif ($filter)
    <p class="bg-warning">
        @lang('profile.ads.filtering', ['filter' => $filter, 'url' => $resetUrl])
    </p>
@elseif ($displayedAds->isEmpty())
    <p class="text-muted">
        @lang('profile.ads.empty_text', ['name' => $person->present()->givenName() ])
    </p>
@endif

@if (!$displayedAds->isEmpty())
    <div class="row">
        @foreach ($displayedAds as $ad)
            <div class="col-sm-6">

                @include('ad._ad', ['ad' => $ad, 'isDeletable' => $ad->sellerId() === auth()->id() ])

            </div>
        @endforeach
    </div>
@endif
