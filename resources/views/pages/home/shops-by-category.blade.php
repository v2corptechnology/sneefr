<div class="col-sm-12">
    <div class="home__section home__section--padding">
        <h4 class="home__section__title">@lang('common.browse_by_category')</h4>
    </div>
</div>

{{-- categpries --}}
<div class="col-sm-2">
    <div class="row">
        <div class="col-xs-12">
            <ul class="home__categories">
                <li>
                    <a href="{{ route('home') }}#shop-categories">All</a>
                </li>
                @foreach($tags as $tag)
                    <li class="home__categories__item">
                        <a href="{{ route('home', "tag={$tag->alias}#shop-categories") }}">{{ $tag->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
{{-- ads by categorie  --}}
<div class="col-sm-10">
    <div class="row">
        <div class="col-sm-12">
            @foreach($shops as $shop)
                <div class="col-sm-4">
                    @include('partials.card', ['item' => $shop, 'multiple' => false])
                </div>
            @endforeach
        </div>
        <div class="col-sm-12" style="padding-right: 30px;">
            <a href="{{ route('search.index', ['type' => 'shop']) }}"
               class="btn btn-default-o pull-right">@lang('button.see_more')</a>
        </div>
    </div>
</div>
