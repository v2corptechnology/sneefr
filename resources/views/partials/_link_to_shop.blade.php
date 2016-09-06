<div class="user {{ $modifiers ?? null }}" itemprop="shop">


        <a class="user__picture" href="{{route('shops.show', $shop) }}"
           title="@lang('ad.show_profile_title', ['name' => $shop->getName()])">
            <img class="user__image" src="{{ $shop->getLogo() }}"
                 alt="{{ $shop->getName() }}" height="{{ $size ?? 40 }}"
                 width="{{ $size ?? 40 }}">
        </a>


</div>
