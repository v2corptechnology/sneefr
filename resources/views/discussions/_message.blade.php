<?php
    $isShop = $shop && $shop->owner->getId() == $sender->getId();

    $senderName = $isShop ?  $shop->getName() . ' <small>(' . $sender->present()->givenName() . ')</small>' : $sender->present()->fullName();
    $imageAlt = $isShop ? $shop->getName() : $sender->present()->fullName();
    $senderRoute = $isShop ? route('shops.show', $shop) : route('profiles.show', $sender);
    $senderProfilePictureUrl = $isShop ? $shop->getLogo('40x40') : \Img::avatar($sender->facebook_id, 40);

?>
<li class="media">
    <div class="media-left">
        <a class="js-sender__link" href="{{ $senderRoute }}">
            <img class="media-object js-sender__avatar" src="{{ $senderProfilePictureUrl }}"
                 width="20" height="20" alt="{{ $imageAlt }}">
        </a>
    </div>
    <div class="media-body">
        <h5 class="media-heading">
            <a class="js-sender__link js-sender__name" href="{{ $senderRoute }}">{!! $senderName !!}</a>
            <span class="pull-right js-timestamp">{!! HTML::time($message->created_at) !!}</span>
        </h5>

        <p class="message__body js-body">{!! $message->body() !!}</p>
    </div>
</li>
