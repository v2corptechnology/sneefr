
@include('partials.avatar', ['of' => $ad->shop ?? $ad->seller, 'classes' => 'user'])

<div class="featured__content">
    <a class="featured__heading"
       href="{{ route('ad.show', $ad->getSlug()) }}"
       title="{{ $ad->present()->title() }}">{{ $ad->present()->title() }}</a>
    <div class="featured__description">

        @lang('login.sold_by')

        @if($ad->isInShop())
            <a class="featured__link"
               href="{{ route('shops.show', $ad->shop)  }}"
               title="{{ $ad->shop->getName() }}">{{ $ad->shop->getName() }}
            </a>
        @else
            <a class="featured__link"
               href="{{ route('profiles.ads.index', $ad->seller) }}"
               title="{{ $ad->seller->present()->fullName() }}">{{ $ad->seller->present()->fullName() }}
            </a>
        @endif
    </div>
    <div class="featured__price">{!! $ad->present()->price() !!}</div>
</div>
