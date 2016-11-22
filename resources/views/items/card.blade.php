<?php

// Default size value
$gallerySize = $gallerySize ?? '360x250';

$galleryKey = 'slider-ad-' . $ad->getId() . mt_rand();

// Extract width and height from given size
list($width, $height) = explode('x', $gallerySize);

$images = $ad->images($gallerySize, true);
$images2x = $ad->images($gallerySize . '@2x', true);

?>
<a class="card" href="{{ route('ad.show', $ad) }}" title="{{ $ad->present()->title() }}">
    <figure class="card__gallery">
        <img class="card__image"src="{{ $images[0] }}"
             alt="{{ $ad->present()->title() }}" width="{{ $width }}"
             srcset="{{ $images2x[0] }} 2x"
             height="{{ $height }}" itemprop="image">

        <figcaption class="card__title card__title--small">
            <span class="card__price">{!! $ad->price()->formatted() !!}</span>
            {{ $ad->present()->title() }}
        </figcaption>
    </figure>
</a>
