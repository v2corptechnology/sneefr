@extends('layouts.shop', ['shop' => $shop])

@section('title', trans('shop.ads.page_title', ['name' => $shop->getName() ]))

@section('shop_content')
    <div class="row">

        <div class="col-sm-8">
            <h1 class="content-head" id="common">
                @choice('shops.show.heading', $displayedAds->count(), ['name' => $shop->getName(), 'nb' => $displayedAds->count()])
            </h1>
        </div>

        {{-- Filter the ads --}}
        <div class="col-sm-4">
            @include('partials._filter', ['q' => $q ?? null, 'route' => route('shops.search', $shop)])
        </div>

    </div>

    {{-- Display warning when filtering the ads --}}
    @if (isset($q))
        <p class="bg-warning">
            @lang('shops.show.filtering', ['filter' => $q])
        </p>
    @endif

    {{-- Show the filtered or all the ads --}}
    <div class="row">
        @forelse($displayedAds as $ad)
            <div class="col-md-6">
                @include('ad._ad', ['ad' => $ad])
            </div>
        @empty
            <div class="col-md-12">
                @if ($shop->isOwner())
                    <p class="bg-info text-info">
                        @lang('shop.ad.empty_text_for_owner', ['name' => $shop->getName() ])
                    </p>
                    <a class="btn btn-primary" href="{{ route('items.create') }}"
                       title="@lang('shop.ads.btn_create_first_ad_title')">@lang('shop.ads.btn_create_first_ad')</a>
                @else
                    <p class="text-muted">
                        @lang('shop.ad.empty_text', ['name' => $shop->getName() ])
                    </p>
                @endif
            </div>
        @endforelse
    </div>

    @if(session('stripe_modal'))
        @include('partials.modals._link_stripe')
        <script>

            window.addEventListener('load', function () {
                $('#link-stripe-modal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            }, false );

        </script>
    @endif
@stop
