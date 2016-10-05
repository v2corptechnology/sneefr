<div class="box__panel box__panel--padding">
    <div class="home__section">
        <h4 class="home__section__title home__section__title--small">Best Seller</h4>
    </div>
    <div class="row">
        @foreach($items as $item)
            <div class="card__box__footer">
                <div class="card__box__avatar">
                    <a href="{{ route('ad.show', $item) }}">
                        <img class="card__box__avatar__img" src="{{ $item->images('60x60', true)[0] }}" alt="">
                    </a>
                </div>
                <div class="card__box__description">
                    <span class="text-indent">{{ $item->present()->title() }}</span>
                    <div>
                        <span class="text-primary">{!! $item->present()->price() !!}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center">
        <a class="btn btn-default-o" href="{{ route('search.index', ['type' => 'ad']) }}">@lang('button.see_more')</a>
    </div>
</div>
