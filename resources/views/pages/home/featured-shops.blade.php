<section class="container best-of">
    <header class="best-of__header">
        <img class="best-of__image"
             src="{{ base64Image('img/b64/best-of__shops.svg') }}"
             height="60" alt="@lang('login.img_shops')">
        <h1 class="best-of__heading">
            {!! link_to_route('search.index', trans('login.shops_heading'), ['type' => 'shop'], ['title' => trans('login.shops_heading_title')]) !!}
        </h1>
    </header>
    <ol class="best-of__list">
        @foreach ($shops as $shop)
            <li class="best-of__list-item">

                @include('shops.card', ['shop' => $shop, 'coverSize' => '360x120', 'classes' => 'card--center'])

            </li>
        @endforeach
    </ol>
    <p class="cta__container--center">
        @lang('login.shops_text')<br>
        <a class="cta__btn btn"
           href="{{ route('pricing') }}"
           title="@lang('login.btn_shops_title')">@lang('login.btn_shops')</a>
    </p>
</section>
