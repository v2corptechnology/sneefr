<div class="box">

    <h1 class="item__heading">{{ $ad->present()->title() }}</h1>

    <div class="item__meta">
        <span class="item__condition">Condition : <strong>{{ $ad->present()->condition() }}</strong></span>
        <span class="item__stock">@choice('ad.show.stock', $ad->remaining_quantity, ['nb' => $ad->remaining_quantity])</span>
    </div>

    <hr class="item__separator">

    <div class="item__buttons">
        <span class="item__price">{{ $ad->present()->price() }}</span>

        @include('items.show._buy_dropdown', ['ad' => $ad])

    </div>

    <hr class="item__separator">

    <div class="item__social">
        <a href="{{ route('ads.share', $ad) }}" class="btn btn-link" title="" data-toggle="modal" data-remote="false" data-target="#shareModal">
            <i class="icon fa fa-lg fa-share-alt"></i> Share
        </a>
    </div>
</div>
