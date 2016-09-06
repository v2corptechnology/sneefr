<div class="vertical-list">
    @foreach ($discussions as $discussion)
        <?php $route = $shopSlug ? 'shop_discussions.show': 'discussions.show';?>
        <a class="vertical-list-item js-discussion-{{ $discussion->id() }} {{ $chosenDiscussion->id() == $discussion->id() ? 'active' : null }}"
           href="{{ route($route, [$discussion, $shopSlug]) }}#latest">

            <?php $unread = $discussion->messages->unread()->count();?>
            <span class="badge js-notification-badge {{ $unread ? null : 'hidden' }}">{{ $unread }}</span>

            @if ($discussion->shop && $discussion->shop->owner->getId() != auth()->id())
                <img class="pull-left"
                     src="{{ $discussion->shop->getLogo('70x70') }}"
                     alt="{{ $discussion->shop->getName() }}" width="35"
                     height="35">

                <h5>{{ $discussion->shop->getName() }}</h5>
            @else
                {!! HTML::profilePicture($discussion->recipient()->facebook_id, $discussion->recipient()->present()->fullName(), 35, ['pull-left']) !!}

                <h5>{{ $discussion->recipient()->present()->fullName() }}</h5>
            @endif
        </a>
    @endforeach
</div>
