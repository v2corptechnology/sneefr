<section class="container best-of">
    <header class="best-of__header">
        <img class="best-of__image"
             src="{{ base64Image('img/b64/best-of__places.svg') }}"
             height="60" @lang('login.img_places')>
        <h1 class="best-of__heading">
            {!! link_to_route('search.index', trans('login.places_heading'), ['type' => 'place'], ['title' => trans('login.places_heading_title')]) !!}
        </h1>
    </header>
    <ol class="best-of__list">
        @foreach ($places as $place)
            <li class="best-of__list-item">
                @include('partials._card', [
                    'item' => $place,
                    'gallerySize' => '360x120',
                    'modifiers' => 'card--center'
                ])
            </li>
        @endforeach
    </ol>
</section>
