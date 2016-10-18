@if ($item->supplyReason === \Sneefr\Services\ActivityFeed\ActivityFeedItem::REASON_LIKED_BY_RELATIONSHIP)
    <div class="activity__pre-header">
        <?php
            $friendLikes = $item->likes->where('isFollowed', true)->take(1);
            $hashIds = app('hashids');
        ?>
        @foreach ($friendLikes as $like)
            @lang('dashboard.activity.liked_by_friend', ['name' => link_to_route('profiles.ads.index', $like['givenName'] . ' ' . $like['surname'], $hashIds->encode($like['personId']))])
        @endforeach
    </div>
@endif

<a class="activity__author" title="{{ $author->present()->fullName() }}"
   href="{{ route('profiles.show', $author) }}">
    {!! HTML::profilePicture($author->socialNetworkId(), $author->present()->fullName(), 40) !!}
</a>

<div class="activity__title">
    <h2 class="activity__heading">
        <a title="{{ $author->present()->fullName() }}" href="{{ route('profiles.show', $author) }}">
            {{ $author->present()->fullName() }}
        </a>
        @lang("dashboard.activity.{$item->type}.head", $headData)
    </h2>
    {!! HTML::time($item->value->created_at) !!}
</div>
