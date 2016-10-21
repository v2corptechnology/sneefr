@push('modals_2')
    @include('partials.modals._login')
    @include('partials.modals._report_ad', ['title' => $ad->getTitle(), 'id' => $ad->getId()])
@endpush

<div class="btn-group btn-group-lg pull-right">

    @if ($ad->isMine() )
        <a class="btn btn-primary" href="{{ route('items.edit', $ad) }}" title=""><i class="fa fa-fw fa-pencil"></i> Edit</a>
    @elseif (auth()->check())
        <a href="{{ route('payments.create', ['ad' => $ad]) }}" title=""
           class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy</a>
    @else
        <a href="#LoginBefore" data-toggle="modal" title=""
           class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy</a>
    @endif

    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu">
        @if (auth()->check() && (auth()->user()->isAdmin() || $ad->isMine()))
            <li><a href="{{ route('items.edit', $ad) }}" title=""><i class="fa fa-fw fa-pencil"></i> Edit</a></li>
        @endif

        @if (auth()->check() && $ad->isReported())
            <li class="disabled"><a href="#" title=""><i class="fa fa-fw fa-warning"></i> Already reported</a></li>
        @else
            <li><a href="#reportAd" title="" data-toggle="modal"><i class="fa fa-fw fa-warning"></i> Report item</a></li>
        @endif
    </ul>
</div>
