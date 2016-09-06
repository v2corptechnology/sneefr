<?php

$relation = ($item->owner->id == $item->value->initiator->id) ? $item->value->user : $item->value->initiator;

$headData = ['name' => link_to_route('profiles.show', $relation->present()->fullName(), $relation->getRouteKey(), ['title' => $relation->present()->fullName()])];

?>
<div class="activity__header">
    @include('dashboard.item._header', ['item' => $item, 'author' => $item->owner, 'headData' => $headData])
</div>

<div class="activity__body">
    @lang('dashboard.activity.followed_person.body', [
        'link' => trans('dashboard.activity.followed_person.body_link', [
            'name' => link_to_route('profiles.show', $relation->present()->givenName(), $relation->getRouteKey(), ['title' => $relation->present()->givenName()])
        ])
    ])
</div>

<div class="activity__actions">
    @include('dashboard.item._actions', ['item' => $item])
</div>

@include('dashboard.item._footer', ['item' => $item])


