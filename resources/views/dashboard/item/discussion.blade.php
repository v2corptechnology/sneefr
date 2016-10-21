<?php

if($item->value->isShopDiscussion() && !$item->value->shop->owners->pluck('id')->contains($item->owner->getId()))
{
    $link = link_to_route('shops.show', $item->value->shop->getName(), $item->value->shop, ['title' => $item->value->shop->getName()]);

}else{
    $recipient = $item->value->participants->reject(function($participant) use ($followedIds) {
        return in_array($participant->id(), $followedIds);
    })->first();

    // Todo: when the participants are both in my followedIds, we have a problem
    if (! $recipient) {
        $recipient = $item->value->participants->first();
    }

    $link = $recipient->present()->fullName();
}

$headData = ['name' => $link];

?>

<div class="activity__header">
    @if($item->value->isShopDiscussion() && $item->value->shop->owners->pluck('id')->contains($item->owner->getId()))
        @include('dashboard.item._header_shop', ['item' => $item, 'author' => $item->value->shop, 'headData' => $headData])
    @else
        @include('dashboard.item._header', ['item' => $item, 'author' => $item->owner, 'headData' => $headData])
    @endif
</div>

<div class="activity__body">
    <p>
        @lang('dashboard.activity.discussion.body', [
            'name' => $link
        ])
    </p>
</div>

<div class="activity__actions">
</div>

@include('dashboard.item._footer', ['item' => $item])
