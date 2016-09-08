<?php

// Class modifiers for the card
$modifiers = isset($modifiers) ? $modifiers : null;

// Default sizes for avatar and gallery
$avatarSize = isset($avatarSize) ? $avatarSize : '40x40';
$gallerySize = isset($gallerySize) ? $gallerySize : '360x250';

// Extract width and height from given size
$avatarSizes = array_combine(['width', 'height'], explode('x', $avatarSize));
$gallerySizes = array_combine(['width', 'height'], explode('x', $gallerySize));

if ($item instanceof \Sneefr\Models\Ad) {
    echo view('ads.card', ['ad' => $item, 'classes'=> $modifiers, 'detail' => $detail])->render();
    return;
} elseif ($item instanceof \Sneefr\Models\Place) {
    $title = $item->getName();
    $url = route('places.show', $item);
    $galleryKey = null;
    $gallery = [$item->getMapUrl($gallerySizes['width'], $gallerySizes['height'])];
    $footer = trans_choice('card.place.footer_ads_count', 0, ['nb' => 0]);
} elseif ($item instanceof \Sneefr\Models\Shop) {

    $title = $item->getName();
    $url = route('shops.show', $item);
    $footer = ($item->ads_count ?? $item->ads->count()) . ' ads';
    $deletable = $item->isOwner();
    $avatar = [
        'url' => route('shops.show', $item),
        'image'  => $item->getLogo($avatarSize),
        'title' => $item->getName(),
    ];
    $galleryKey = null;
    $gallery = [$item->getCover($gallerySize)];
} elseif ($item instanceof \Sneefr\Models\User) {
    $title = $item->present()->fullName();
    $url = route('profiles.ads.index', $item);
    $footer = ($item->ads_count ?? $item->ads->count()) . ' ads';
    $avatar = [
        'url' => $url,
        'image'  => \Img::avatar($item->socialNetworkId(), [$avatarSizes['width'], $avatarSizes['height'], 2]),
        'title' => $item->present()->fullName(),
    ];
}
?>

<article class="card {{ $modifiers }}" itemscope itemtype="http://schema.org/Product">
    @if (isset($gallery) && $gallery)
        <figure class="card__gallery @if($galleryKey) js-slider @endif"
                @if($galleryKey) id="{{ $galleryKey }}" @endif
                title="{{ $title }}" data-slider-target="#{{ $galleryKey }}"
                data-slider-h="{{ $gallerySizes['height'] }}">
            @foreach ($gallery as $i => $image)
                <a href="{{ $url }}" title="{{ $title }}">
                    <?php $srcPrefix = ($i !== 0) ? 'data-' : null;?>
                    <img class="card__image" {{ $srcPrefix }}src="{{ $image }}"
                         alt="{{ $title }}" width="{{ $gallerySizes['width'] }}"
                         height="{{ $gallerySizes['height'] }}" itemprop="image">
                </a>
            @endforeach
        </figure>
    @endif

    @if (isset($avatar) && $avatar)
        <a class="card__avatar" href="{{ $avatar['url'] }}" title="{{ $avatar['title'] }}">
            <img class="card__image" src="{{ $avatar['image'] }}"
                 alt="{{ $avatar['title'] }}" height="{{ $avatarSizes['height'] }}"
                 width="{{ $avatarSizes['width'] }}">
        </a>
    @endif

    <div class="card__content">
        <h1 class="card__title" itemprop="name">
            @if (isset($price) && $price)
                <span class="offer" itemscope itemtype="http://schema.org/Offer">
                    <span class="price" itemprop="price">{!! $price['formatted'] !!}</span>
                </span>
                <span class="card__title-separator">&bull;</span>
            @endif
            <a href="{{ $url }}" title="{{ $title }}" itemprop="url">{{ $title }}</a>
        </h1>

        @if (isset($description) && $description)
            <div class="card__text" itemprop="description">
                <p>{{ $description }}</p>
            </div>
        @endif

        @if (isset($footer) && $footer)
            <footer class="card__footer">
                <p>{!! $footer !!}</p>
            </footer>
        @endif
    </div>

    @if (isset($deletable) && $deletable)
        <a href="#" class="card__delete">&times;</a>
    @endif
</article>


{{--
<figure class="ad">

    <a class="ad__image" href="{{ $route }}" title="{{ $title }}" style="background-image: url('{{ $image }}');">
        <span class="sr-only">{{ $title }}</span>
    </a>

    <figcaption class="ad__caption">
        <h6 class="ad__title">
            <a href="{{ $route }}" title="{{  $title }}">{{ $title }}</a>
        </h6>
        {{ $details }}
    </figcaption>

</figure>
--}}
