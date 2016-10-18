<?php

$headData = ['title' => link_to_route('ad.show', $item->value->title, $item->value->slug(), ['title' => $item->value->title])];

?>

<div class="activity__header">
    @include('dashboard.item._header', ['item' => $item, 'author' => $item->owner, 'headData' => $headData])
</div>

<div class="activity__body">
    <span class="activity__price">{!! $item->value->present()->price() !!}</span>
    <div class="activity__slider slider slider-{{ $item->value->id }}" data-target="slider-{{ $item->value->id }}">

        @foreach ($item->value->imageNames() as $k => $image)
            <?php $srcAttr = $k ? 'data-' : null; ?>
            <a href="{{ route('ad.show', $item->value->slug()) }}" title="{{ $item->value->present()->title() }}">
                <img class="img-responsive" width="488" height="400"
                     {{ $srcAttr }}src="{{ Img::thumbnail($item->value, $image, '488x400') }}"
                     alt="{{ $item->value->present()->title() }}">
            </a>
        @endforeach

    </div>
</div>

<div class="activity__actions">
</div>

@include('dashboard.item._footer', ['item' => $item])
