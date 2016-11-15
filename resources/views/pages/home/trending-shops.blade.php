<div class="col-sm-12">
    <div class="home__section home__section--padding">
        <h4 class="home__section__title">

            @lang('home.highlighted_shops')

            <span class="home__section__description">
                @lang('home.highlighted_description')
            </span>
        </h4>
    </div>
</div>

@foreach($shops as $shop)
    <div class="col-sm-6">

        @include('shops.card', ['shop' => $shop, 'coverSize' => '410x200'])

    </div>
@endforeach
