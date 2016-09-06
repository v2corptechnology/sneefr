{{-- Include ‘Like’ button --}}
@include('widgets.like', ['item' => $item->value])

@if (in_array($item->type, ['ad', 'shop_ad']))
    @include('partials._share_button', ['ad' => $item->value])
@endif
