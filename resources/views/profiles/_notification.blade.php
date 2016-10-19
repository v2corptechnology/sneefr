<?php
    $from = $notification->notifiable->user;

    $heading = '<a href="' . route('profiles.show', $notification->notifiable->user) . '">' . $notification->notifiable->user->present()->fullName() . '</a>';
    $body = null;

    switch (get_class($notification->notifiable)) {

        case Sneefr\Models\Like::class :
            $item = $notification->notifiable->likeable;
            switch(get_class($item)) {

                case Sneefr\Models\Ad::class :
                    $body = trans('profile.notifications.someone_liked_your_ad', [
                            'url' => route('ad.show', $item),
                            'title' => $item->title,
                            'link' => $item->title
                    ]);
                    break;

                case Sneefr\Models\Search::class :
                    $body = trans('profile.notifications.someone_liked_your_search', [
                            'search' => $item->body
                    ]);
                    break;
            }
            break;

        case Sneefr\Models\Evaluation::class :
            if ($notification->notifiable->isWaiting) {
                $body = trans('profile.notifications.someone_bought_your_ad', [
                        'url' => route('ad.show', $notification->notifiable->ad),
                        'title' => $notification->notifiable->ad->title,
                        'link' => $notification->notifiable->ad->title
                ]);
            } elseif ($notification->notifiable->isForced) {
                $from = null;
                $heading = trans('profile.notifications.evaluation_auto_validated_title');
                $body = trans('profile.notifications.evaluation_auto_validated');
            }
            break;

        case Sneefr\Models\Tag::class :
            $from = $notification->notifiable->by;
            $heading = '<a href="' . route('profiles.show', $notification->notifiable->by) . '">' . $notification->notifiable->by->present()->fullName() . '</a>';

            if ($notification->notifiable->isAd) {
                $body = trans('profile.notifications.someone_tagged_you', [
                        'url' => route('ad.show', $notification->notifiable->taggable),
                        'title' => $notification->notifiable->taggable->title,
                        'link' => $notification->notifiable->taggable->title
                ]);
            } elseif ($notification->notifiable->isSearch) {
                $body = trans('profile.notifications.you_might_be_interested_in', [
                        'url' => route('profiles.show', $notification->notifiable->user),
                        'title' => $notification->notifiable->user->present()->fullName(),
                        'link' => $notification->notifiable->user->present()->fullName()
                ]);
            }
            break;

        case Sneefr\Models\Ad::class :
            $item = $notification->notifiable;
            if ($item->user->id == auth()->user()->id) {
                $fromName = $from->present()->fullName();
                $fromRoute = route('profiles.show', $item->buyer);
                $heading = '<a href="'. $fromRoute .'">'. $fromName .'</a>';
                $body = trans('profile.notifications.sold_ad', [
                        'url'   => route('ad.show', $item),
                        'title' => $item->title,
                        'link'  => $item->title,
                ]);
            } else {
                // TODO: check why this weird thing is happening => $from should be $item->seller
                $fromName = $item->isInShop() ?$item->shop->getName() : $from->present()->fullName();
                $fromRoute = $item->isInShop() ? route('shops.show', $item->shop) : route('profiles.show', $item->buyer);
                $heading = '<a href="'. $fromRoute .'">'. $fromName .'</a>';
                $pictureUrl = $fromRoute;
                $pictureName = $fromName;
                $picture = '<img class="media-object img-circle" src="'. $item->shop->getLogo('30x30') .'" srcset="'. $item->shop->getLogo('60x60') .' 2x" alt="'. $pictureName.'" height="30" width="30">';

                        $body = trans('profile.notifications.bought_ad', [
                        'url'   => route('ad.show', $item),
                        'title' => $item->title,
                        'link'  => $item->title,
                ]);
            }
            break;
    }

    $class = $notification->isUnread()
            ? ($notification->isSpecial() ? 'media-unread--important' : 'media-unread')
            : null;

    $pictureUrl = $pictureUrl ?? route('profiles.show', $from->getRouteKey());
    $pictureName = $pictureName ?? $from->present()->fullName();
    $picture = $picture ?? HTML::profilePicture($from->facebook_id, $from->present()->fullName(), 30, ['media-object', 'img-circle']);
?>

<li class="dashboard-aside-block__item media {{ $class }}">
    @if ($from)
        <div class="media-left">
            <a href="{{ $pictureUrl }}" title="{{ $pictureName }}">
                {!! $picture !!}
            </a>
        </div>
    @endif
    <div class="media-body">
        <h5 class="media-heading">{!! $heading !!}</h5>
        <p>
            {!! $body !!}
            <br>
            {!! HTML::time($notification->created_at) !!}
        </p>
    </div>
</li>
