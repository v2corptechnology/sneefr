<aside class="box seller">
    @include ('partials.avatar', ['of' => $shop, 'size' => '40x40', 'classes' => 'pull-left avatar--circle'])

    <h1 class="seller__heading">{{ $shop->getName() }}</h1>

    <div class="seller__evaluations">
        <i class="fa fa-thumbs-up"></i> {{ $shop->evaluations->positives()->count() }}
        <i class="fa fa-thumbs-down"></i> {{ $shop->evaluations->negatives()->count() }}
    </div>

    <p class="seller__description">{{ $shop->getDescription() }}</p>

    <div class="seller__map">
        <a href="{{ route('shops.show', $shop) }}" title="{{ $shop->getName() }}">
            <img  class="img-responsive" src="{{ $shop->getMapUrl(270, 160, false, 11) }}"
                  srcset="{{ $shop->getMapUrl(270, 160, true, 11) }} 2x" alt="{{ $shop->getName() }}">
        </a>
    </div>

    <p class="seller__address">
        <i class="fa fa-fw fa-map-marker"></i> {{ $shop->getLocation() }}
    </p>
    {{--
    <hr class="seller__separator">
    <a href="{{ route('shops.show', $shop) }}" class="btn btn-default-o">Learn more</a>
    --}}
</aside>
