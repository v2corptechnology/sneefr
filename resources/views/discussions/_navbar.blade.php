<div class="messages-head js-message-head">

    <?php $discussionsRoute = auth()->user()->shops ? 'discussions.index' : 'discussions.index'; ?>

    <a class="visible-xs-inline" href="{{ route($discussionsRoute) }}"
       title="@lang('message.back_to_discussions_title')">
        <i class="fa fa-angle-left"></i> @lang('message.back_to_discussions')
    </a>

    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="...">

        @if (! $currentDiscussion->isLockedForMe())
            @if (count($userAds))
                <a class="btn btn-default" href="{{ route('discussions.ads.index', $currentDiscussion->id()) }}" title="...">
                    @choice('button.ad.sell', count($userAds))
                </a>
            @else
                <a class="btn btn-default disabled" href="#" title="...">
                    @choice('button.ad.sell', count($userAds))
                </a>
            @endif
        @endif
        <!--<button type="button" class="btn btn-default">
            <i class="fa fa-cog"></i>
        </button>-->
    </div>
</div>
