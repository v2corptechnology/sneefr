<div class="col-sm-12">
    <div class="home__section home__section--padding">
        <h4 class="home__section__title">

            @lang('common.trending_shops')

            <span class="home__section__description">
                @lang('common.trending_description')
            </span>
        </h4>

        <a href="{{ route('search.index', ['type' => 'shop']) }}"
           class="btn btn-default-o pull-right">
            @lang('button.see_all')
        </a>
    </div>
</div>

@foreach($shops as $shop)
    <div class="col-sm-6">

        @include('partials.card', ['item' => $shop, 'multiple' => true])

    </div>
@endforeach
