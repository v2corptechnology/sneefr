<section class="container best-of">
    <header class="best-of__header">
        <img class="best-of__image"
             src="{{ base64Image('img/b64/best-of__sellers.svg') }}"
             height="60" @lang('login.img_sellers')>
        <h1 class="best-of__heading">
            {!! link_to_route('search.index', trans('login.sellers_heading'), ['type' => 'person'], ['title' => trans('login.sellers_heading_title')]) !!}
        </h1>
    </header>
    <ol class="best-of__list">
        @foreach ($users as $user)
            <li class="best-of__list-item best-of__list-item--small">
                @include('partials._card', [
                    'item' => $user,
                    'modifiers' => 'card--no-gallery card--columns card--no-delete'
                ])
            </li>
        @endforeach
    </ol>
</section>
