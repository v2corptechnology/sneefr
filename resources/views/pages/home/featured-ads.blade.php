<section class="container best-of">
    <header class="best-of__header">
        <img class="best-of__image" src="{{ base64Image('img/b64/best-of__ads.svg') }}" height="60" @lang('login.img_sellers')>
        <h1 class="best-of__heading">
            {!! link_to_route('search.index', trans('login.ads_heading'), ['type' => 'ad'], ['title' => trans('login.ads_heading_title')]) !!}
        </h1>
    </header>
    <ol class="best-of__list">
        @foreach ($ads as $ad)
            <li class="best-of__list-item best-of__list-item--small">

                @include('ads.card', ['ad' => $ad, 'classes'=> 'card--no-avatar'])

            </li>
        @endforeach
    </ol>
</section>
