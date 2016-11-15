<?php
// Default size for cover
$coverSize = $coverSize ?? '360x250';
// Extract width and height from given size
list($width, $height) = explode('x', $coverSize);
?>

<article class="card {{ $classes ?? null }}" itemscope itemtype="http://schema.org/Product">
    <figure class="card__gallery">
        <a href="{{ route('shops.show', $shop) }}" title="{{ $shop->getName() }}">
            <img class="card__image" src="{{ $shop->getCover($coverSize) }}"
                 srcset="{{ $shop->getCover($coverSize.'@2x') }} 2x"
                 alt="{{ $shop->getName() }}" width="{{ $width }}"
                 height="{{ $height }}" itemprop="image">
        </a>
    </figure>

    @include('partials.avatar', ['of' => $shop, 'size' => '40x40'])

    <div class="card__content">
        <h1 class="card__title" itemprop="name">
            <a href="{{ route('shops.show', $shop) }}"
               title="{{ $shop->getName() }}"
               itemprop="url">{{ $shop->getName() }}</a>
        </h1>

        @if (isset($description) && $description)
            <div class="card__text" itemprop="description">
                <p>{{ $description }}</p>
            </div>
        @endif

        @if ($footer ?? null)
            <footer class="card__footer">
                <p>{{ ($shop->ads_count ?? $shop->ads->count()) . ' ads' }}</p>
            </footer>
        @endif
    </div>
</article>
