<?php

$headData = ['place' => link_to_route('places.show', $item->value->getName(), $item->value, ['title' => $item->value->getLongName()])];

?>
<div class="activity__header">
    @include('dashboard.item._header', ['item' => $item, 'author' => $item->owner, 'headData' => $headData])
</div>

<div class="activity__body">
    <a href="{{ route('places.show', $item->value) }}" title="{{ $item->value->getName() }}">
        <img class="img-responsive" src="{{ $item->value->getMapUrl(488, 400) }}"
             alt="{{ $item->value->getName() }}" width="488" height="400"/>
    </a>
</div>

<div class="activity__actions">
    @include('dashboard.item._actions', ['item' => $item])
</div>

@include('dashboard.item._footer', ['item' => $item])


