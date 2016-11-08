@push('modals_2')
    @include('partials.modals.writeTo', ['shop' => $shop])
@endpush

<div class="box">

    <h1 class="item__heading">{{ $ad->present()->title() }}</h1>

    <div class="item__meta">
        <span class="item__stock">@choice('ad.show.stock', $ad->remaining_quantity, ['nb' => $ad->remaining_quantity])</span>
    </div>

    <hr class="item__separator">

    <div class="item__buttons">
        <span class="item__price">{{ $ad->price()->formatted() }}</span>

        @include('items.show._buy_dropdown', ['ad' => $ad])

    </div>

    <hr class="item__separator">

    <div class="item__social">
        <a href="{{ route('ads.share', $ad) }}" class="btn btn-link" title="" data-toggle="modal" data-remote="false" data-target="#shareModal">
            <i class="icon fa fa-lg fa-share-alt"></i> Share
        </a>
        
        @unless ($ad->isMine())
            <a href="#writeTo" class="btn btn-link" title="" data-toggle="modal" data-remote="false">
                <i class="icon fa fa-lg fa-comment-o"></i> Contact
            </a>
        @endunless
    </div>
</div>
