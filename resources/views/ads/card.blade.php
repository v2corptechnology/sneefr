<?php

// Default size value
$gallerySize = $gallerySize ?? '360x250';

// Extract width and height from given size
list($width, $height) = explode('x', $gallerySize);

$images = $ad->images($gallerySize, true);
$images2x = $ad->images($gallerySize . '@2x', true);

switch ($detail ?? null) {
    case 'proximity':
        $footer = '<i class="fa fa-map-marker"></i> ' . $ad->present()->distance() . ' &mdash; ' . $ad->location();
        break;
    case 'evaluation':
        $footer = trans_choice('ad.show.evaluations_ratio', $ad->present()->evaluationRatio(), ['ratio' => $ad->present()->evaluationRatio()]);
        break;
    case 'date':
        $footer = HTML::time($ad->created_at);
        break;
}

?>
<article class="card {{ $classes ?? null }}" itemscope itemtype="http://schema.org/Product">

    <?php $galleryKey = 'slider-ad-' . $ad->getId() . mt_rand(); ?>

    <figure class="card__gallery js-slider" id="{{ $galleryKey }}" data-slider-h="{{ $height }}"
            title="{{ $ad->present()->title() }}" data-slider-target="#{{ $galleryKey }}">
        @foreach ($images as $i => $image)
            <a class="{{ !$loop->first ? 'hidden' : '' }}" href="{{ route('ad.show', $ad) }}" title="{{ $ad->present()->title() }}">
                <img class="card__image" {{ !$loop->first ? 'data-' : null }}src="{{ $image }}"
                     alt="{{ $ad->present()->title() }}" width="{{ $width }}"
                     {{-- data-src-2x="{{ $images2x[$loop->index] }}"
                     srcset="{{ $images2x[$loop->index] }} 2x" --}}
                     height="{{ $height }}" itemprop="image">
            </a>
        @endforeach
    </figure>

    @include('partials.avatar', ['of' => $ad->shop, 'size' => $avatar ?? null])

    <div class="card__content">
        <h1 class="card__title" itemprop="name">

            <span class="offer" itemscope itemtype="http://schema.org/Offer">
                <span class="price" itemprop="price">{!! $ad->present()->price() !!}</span>
            </span>

            <span class="card__title-separator">&bull;</span>

            <a href="{{ route('ad.show', $ad) }}" title="{{ $ad->present()->title() }}"
               itemprop="url">{{ $ad->present()->title() }}</a>
        </h1>

        @if (isset($description))
            <div class="card__text" itemprop="description">
                <p>{{ $description }}</p>
            </div>
        @endif

        @if (isset($footer))
            <footer class="card__footer">
                <p>{!! $footer !!}</p>
            </footer>
        @endif
    </div>
</article>
