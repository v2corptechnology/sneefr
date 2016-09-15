<?php

$headData = ['search' => $item->value->body()];

?>
<div class="activity__header">
    @include('dashboard.item._header', ['item' => $item, 'author' => $item->owner, 'headData' => $headData])
</div>

<div class="activity__body">
    <p>
        @if ($item->value->user->id() == auth()->id())
            @lang('dashboard.activity.search.body_alt')
        @else
            @lang('dashboard.activity.search.body', [
                'link' => link_to_route('items.create', trans('dashboard.activity.search.body_link'), ['title' => $item->value->body()])
            ])
        @endif
    </p>
</div>

<div class="activity__actions">
    @include('dashboard.item._actions', ['item' => $item])
</div>

@include('dashboard.item._footer', ['item' => $item])
