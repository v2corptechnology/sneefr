<?php
// Default size for cover
$coverSize = $coverSize ?? '360x250';
// Extract width and height from given size
list($width, $height) = explode('x', $coverSize);
?>

<a class="card {{ $classes ?? null }}" href="{{ route('shops.show', $shop) }}" title="{{ $shop->getName() }}">
    <figure class="card__gallery card__gallery--vignette">
        <img class="card__image" src="{{ $shop->getCover($coverSize) }}"
             srcset="{{ $shop->getCover($coverSize.'@2x') }} 2x"
             alt="{{ $shop->getName() }}" width="{{ $width }}"
             height="{{ $height }}" itemprop="image">
        <figcaption class="card__title">{{ $shop->getName() }}</figcaption>
    </figure>
</a>

    {{--
        @include('partials.avatar', ['of' => $shop, 'size' => '40x40', 'noLink' => true])

        <div class="card__content">

            <h1 itemprop="name"></h1>

            @if ($description ?? null)
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
    </article>--}}
