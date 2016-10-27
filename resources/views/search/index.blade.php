@extends('layouts.master')

@if (empty($query))
    @section('title', trans('search.page_title_for_latest'))
@else
    @section('title', trans('search.page_title', ['search' => $query]))
@endif

@push('footer-js')
    <script src="{{ elixir('js/sneefr.slider.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav class="sort">
                <ul class="menu">
                    <li class="menu__item @if ($type == 'ad') active @endif">
                        <a class="menu__item-link" href="{{ route('search.index', ['type' => 'ad', 'q' => $query])
                        }}">@choice('search.type_ad_label', 0, ['nb' => $ads->count()])</a></li>
                    <li class="menu__item @if ($type == 'shop') active @endif">
                        <a class="menu__item-link" href="{{ route('search.index', ['type' => 'shop', 'q' => $query])
                        }}">@choice('search.type_shop_label', 0, ['nb' => $shops->count()])</a>
                    </li>

                </ul>

                {{--
                    If we are searching for ads,
                    display a menu providing ways to order the search results.
                --}}
                {{--
                @if ($type == 'ad' && $ads->count())
                    @include('partials._sort_menu', [
                        'urlParams' => $request->all(),
                        'sort' => $request->get('sort', 'relevance'),
                        'order' => $request->get('order')
                    ])
                @endif
                --}}
            </nav>
        </div>

        {{--
            If the person is searching for ads and think we found
            categories that are related to the specified search
            terms, show links to searches in these categories.
        --}}
        @if ($type == 'ad')

            @if ($linkedCategories)
                <div class="col-md-12">
                    <p class="bg-warning">
                        <b>@choice('search.linked_categories', count($linkedCategories))</b>
                        @foreach ($linkedCategories as $linkedCategoryId)
                            <a href="{{ route('search.index', ['category' => $linkedCategoryId]) }}">
                                @lang("category.{$linkedCategoryId}")
                            </a>
                        @endforeach
                    </p>
                </div>
            @endif

            {{-- Display the found ads using a partial --}}
            @foreach ($ads as $ad)
                <div class="col-sm-4 col-md-3">

                    @include('ads.card', ['ad' => $ad, 'gallerySize' => '260x200', 'detail' => request('sort', 'relevance')])

                </div>
            @endforeach

        @elseif ($type == 'shop')

            @foreach ($shops as $shop)
                <div class="col-sm-4 col-md-3">

                    @include('shops.card', ['shop' => $shop, 'coverSize' => '260x200', 'classes' => 'card--center'])

                </div>
            @endforeach

        @endif
    </div>
</div>
@stop
