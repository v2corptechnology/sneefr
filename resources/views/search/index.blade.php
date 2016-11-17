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
                        }}">@choice('search.type_ad_label', $ads->total(), ['nb' => $ads->total()])</a></li>
                    <li class="menu__item @if ($type == 'shop') active @endif">
                        <a class="menu__item-link" href="{{ route('search.index', ['type' => 'shop', 'q' => $query])
                        }}">@choice('search.type_shop_label', $shops->total(), ['nb' => $shops->total()])</a>
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

        @if ($type == 'ad')

            {{-- Display the found ads using a partial --}}
            @foreach ($ads as $ad)
                <div class="col-sm-4 col-md-3">

                    @include('ads.card', ['ad' => $ad, 'gallerySize' => '260x200'])

                </div>
            @endforeach

            <div class="col-sm-12 text-center">

                {{ $ads->links() }}

            </div>

        @elseif ($type == 'shop')

            @foreach ($shops as $shop)

                <div class="col-sm-4 col-md-3">

                    @include('shops.card', ['shop' => $shop, 'coverSize' => '265x200'])

                </div>
            @endforeach

            <div class="col-sm-12 text-center">

                {{ $shops->links() }}

            </div>

        @endif
    </div>
</div>
@stop
