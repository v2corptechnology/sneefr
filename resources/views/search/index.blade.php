@extends('layouts.master')

@if (empty($query))
    @section('title', trans('search.page_title_for_latest'))
@else
    @section('title', trans('search.page_title', ['search' => $query]))
@endif

@push('footer-js')
    <script src="//rawgit.com/gilbitron/Ideal-Image-Slider/master/ideal-image-slider.min.js"></script>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav class="sort">
                <ul class="menu">
                    <li class="menu__item @if ($type == 'ad') active @endif">
                        <a class="menu__item-link" href="{{ route('search.index', ['type' => 'ad', 'q' => $query])
                        }}">@choice('search.type_ad_label', $ads->count(), ['nb' => $ads->count()])</a></li>
                    <li class="menu__item @if ($type == 'shop') active @endif">
                        <a class="menu__item-link" href="{{ route('search.index', ['type' => 'shop', 'q' => $query])
                        }}">@choice('search.type_shop_label', $shops->count(), ['nb' => $shops->count()])</a>
                    </li>

                </ul>

                {{--
                    If we are searching for ads,
                    display a menu providing ways to order the search results.
                --}}
                @if ($type == 'ad' && $ads->count())
                    @include('partials._sort_menu', [
                        'urlParams' => $request->all(),
                        'sort' => $request->get('sort', 'relevance'),
                        'order' => $request->get('order')
                    ])
                @endif
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
            @foreach ($ads->get() as $ad)
                <div class="col-sm-4 col-md-3">
                    @include('partials._card', [
                        'item' => $ad,
                        'gallerySize' => '260x200',
                        'detail' => $request->get('sort', 'relevance')
                    ])
                </div>
            @endforeach

        @elseif ($type == 'shop')

            <!-- Display the found shops  -->
            @foreach ($shops as $shop)
                <div class="col-sm-4 col-md-3">
                    @include('partials._card', [
                        'item' => $shop,
                        'gallerySize' => '260x200',
                        'modifiers' => 'card--center'
                    ])
                </div>
            @endforeach

        @endif
    </div>

    @if (false && $type == 'ad' )
        {{-- If the person is authenticated, show a button to publish the search --}}
        @if ($query && auth()->id())
            <form action="{{ route('search.store') }}" method="POST" class="publish-search">
                {!! csrf_field() !!}
                <p><small>@lang('search.share_text', ['term' => $query])</small></p>
                <div class="form-group">
                        <input type="hidden" name="query" value="{{ $query }}">
                        <button class="btn btn-success btn-sm" type="submit">
                            @lang('search.ask_my_sneefers_button')
                        </button>
                </div>
            </form>
            {{-- Otherwise, show an incentive message and a button to log in --}}
        @elseif ($query)
            <div class="ask-for-connect">
                <p><small>@lang('search.not_connected_warning', ['term' => $query])</small></p>
                <a class="btn btn-success btn-sm" href="{{ route('home') }}">
                    @lang('search.not_connected_button')
                </a>
            </div>
        @endif
    @endif
</div>
@stop
