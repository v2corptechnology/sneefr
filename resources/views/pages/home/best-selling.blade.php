<div class="box__panel">

    <div class="box__panel--padding">
        <div class="home__section">
            <h4 class="home__section__title home__section__title--small">Best Seller</h4>
        </div>
    </div>

    @foreach ($items as $item)
        <div class="row best-selling">
            <a class="best-selling__item" href="{{ route('ad.show', $item) }}">
                <span class="col-xs-4 is-narrow-pr">
                    <img class="img-responsive img-rounded" src="{{ $item->images('60x60', true)[0] }}"
                         alt="{{ $item->present()->title() }}">
                </span>
                <span class="col-xs-8 is-narrow-pl">
                    <h1 class="best-selling__heading is-truncated">{{ $item->present()->title() }}</h1>
                    <p>{!! $item->present()->price() !!}</p>
                </span>
            </a>
        </div>
    @endforeach

    <div class="text-center">
        <a class="btn btn-default-o"
           href="{{ route('search.index', ['type' => 'ad']) }}">@lang('button.see_more')</a>
    </div>

</div>
