<aside class="box box--narrow">

    <h1 class="box__heading">Best Seller</h1>

    <div class="box__content">
        @foreach ($items as $item)
            <article class="row best-selling">
                <a class="best-selling__item" href="{{ route('ad.show', $item) }}">
                <span class="col-xs-4 is-narrow-pr">
                    <img class="img-responsive best-selling__image" src="{{ $item->images('65x65', true)[0] }}"
                         alt="{{ $item->present()->title() }}">
                </span>
                <span class="col-xs-8 is-narrow-pl">
                    <h1 class="best-selling__heading is-truncated">{{ $item->present()->title() }}</h1>
                    <p>{!! $item->price()->formatted() !!}</p>
                </span>
                </a>
            </article>
        @endforeach
    </div>

    <footer class="text-center">
        <a class="btn btn-default-o"
           href="{{ route('search.index', ['type' => 'ad']) }}">@lang('button.see_more')</a>
    </footer>
</aside>
