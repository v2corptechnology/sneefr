<?php
    $seller = $seller ?? null;
    $isDeletable = $isDeletable ?? false;
    $detail = $sort ?? 'date';
?>
<figure class="ad--small">
    @if ($isDeletable)
        <a class="remove-block close" href="{{ route('ads.chooseBuyer', $ad->getRouteKey()) }}"
           title="@lang('button.ad.delete_title')">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">@lang('button.ad.delete')</span>
        </a>
    @endif
    <a class="ad--small__image" href="{{ route('ad.show', $ad->getRouteKey()) }}"
       title="{{ $ad->getTitle() }}" style="background-image: url('{{ $ad->firstImageUrl('300x200') }}');">
    </a>

    @if ($seller)
        <a class="ad--small__seller" href="{{ route('profiles.show', $seller) }}"
           title="{{ $seller->present()->fullName() }}">
            {!! HTML::profilePicture($seller->socialNetworkId(), $seller->present()->fullName(), 40, ['ad--small__seller-image']) !!}
        </a>
    @endif

    <figcaption class="ad--small__caption">

        <h6 class="ad--small__title">
            <strong class="ad--small__price">{!! $ad->present()->price() !!}</strong>
            <small class="ad--small__price-separator">&bull;</small>
            <a href="{{ route('ad.show', $ad->getRouteKey()) }}" title="{{ $ad->getTitle() }}">{{ $ad->getTitle() }}</a>
        </h6>

        @include('ad._details', ['ad' => $ad, 'detail' => $detail])

    </figcaption>
</figure>
