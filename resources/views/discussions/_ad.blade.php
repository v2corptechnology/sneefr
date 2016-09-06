@if ($ad->pivot->deleted_at && !$ad->trashed())
    <li class="media media--separated js-ad-{{ $ad->getId() }}">
        @lang('message.ad.removed', ['title' => link_to_route('ad.show', $title = $ad->getTitle(), $parameters = $ad->slug())])
    </li>
@elseif ($ad->trashed() && $ad->sold_to)
    <li class="media media--separated js-ad-{{ $ad->getId() }}">
        @lang('message.ad.sold', ['title' => $ad->getTitle()])
    </li>
@elseif ($ad->trashed())
    <li class="media media--separated js-ad-{{ $ad->getId() }}">
        @lang('message.ad.deleted', ['title' => $ad->getTitle()])
    </li>
@else
    <li class="media2 js-ad-{{ $ad->getId() }}">
        <div class="media2__figure">
            <a href="{{ route('ad.show', [$ad->slug()]) }}" title="{{ $ad->getTitle() }}">
                <img src="{{ $ad->firstImageUrl(80) }}" alt="{{ $ad->getTitle() }}">
            </a>
        </div>
        <div class="media2__body">
            @unless($ad->isLockedFor(auth()->user()->id))
                {!! Form::open([
                    'method' => 'delete',
                    'route' => ['discussions.ads.destroy', $currentDiscussion->id(), $ad]
                ]) !!}
                    <button type="submit" class="close pull-right"
                            title="@lang('ad.remove_from_discussion_title')">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">@lang('modal.close')</span>
                    </button>
                {!! Form::close() !!}
            @endunless

            <h5 class="media2__title">
                <a href="{{ route('ad.show', [$ad->slug()]) }}" title="{{ $ad->getTitle() }}">
                    {{ $ad->getTitle() }}
                </a>
            </h5>

            <p class="media2__content media2__content--narrow text-muted">
                @if ($ad->isLockedFor(auth()->user()->id))
                    <strong>{!! $ad->present()->negotiatedPrice() !!}</strong>

                    &bull;

                    @lang('message.ad.waiting', ['name' => $currentDiscussion->recipient()->present()->givenName()])
                @else
                    <strong>{!! $ad->present()->price() !!}</strong>

                    &bull;

                    {{ $ad->oneLineDescription() }}
                @endif
            </p>

            @if ($ad->isMine())
                @if($ad->isLocked())
                    <span class="label label-info">@lang('ad.sold.waiting_for_confirmation')</span>
                @else
                    <a class="btn btn-default btn-sm" title="@lang('ad.sold_button_title')"
                        href="{{ route('discussions.ads.show', [$currentDiscussion->id(), $ad->slug()]) }}">
                        @choice('button.ad.sold_to', count($ad))
                    </a>
                @endif
            @elseif ($ad->isLockedFor(auth()->id()))
                <a href="{{ route('payments.create', ['ad' => $ad]) }}" class="btn btn-default btn-sm">@lang('button.ad.confirm')</a>
                <a href="{{ route('payments.refuse', $ad) }}" class="btn-btn-link btn-sm" title="@lang('button.ad.refuse_title')">
                    @lang('button.ad.refuse')
                </a>
            @endif
        </div>
    </li>
@endif
