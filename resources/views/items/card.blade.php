<?php

// Default size value
$gallerySize = $gallerySize ?? '360x250';

$galleryKey = 'slider-ad-' . $ad->getId() . mt_rand();

// Extract width and height from given size
list($width, $height) = explode('x', $gallerySize);

$images = $ad->images($gallerySize, true);
$images2x = $ad->images($gallerySize . '@2x', true);
$hasMultipleImages = count($images) > 1;

?>
<a class="card" href="{{ route('ad.show', $ad) }}" title="{{ $ad->present()->title() }}">
    <figure class="card__gallery {{ $hasMultipleImages ? 'js-slider' : null }}" id="{{ $galleryKey }}" data-slider-h="{{ $height }}" data-slider-target="#{{ $galleryKey }}">
        @foreach ($images as $i => $image)
            <span class="{{ !$loop->first ? 'hidden' : '' }}">
                <img class="card__image" {{ !$loop->first ? 'data-' : null }}src="{{ $image }}"
                     alt="{{ $ad->present()->title() }}" width="{{ $width }}"
                     srcset="{{ $images2x[$loop->index] }} 2x"
                     height="{{ $height }}" itemprop="image">
            </span>
        @endforeach

        <figcaption class="card__title card__title--small">
            <span class="card__price">{!! $ad->price()->formatted() !!}</span>
            {{ $ad->present()->title() }}
        </figcaption>
    </figure>
</a>
