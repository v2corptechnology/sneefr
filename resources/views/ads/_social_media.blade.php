<meta property="fb:app_id"
      content="{{ config('sneefr.keys.FACEBOOK_CLIENT_ID') }}">
<meta property="og:site_name" content="sneefR"/>
<meta property="og:type" content="product.item"/>
<meta property="og:url" content="{{ Request::url() }} ">
<meta property="og:updated_time"
      content="{{ $ad->created_at->format(\Carbon\Carbon::W3C) }} ">
<meta property="og:title" content="{{ $ad->present()->title() }}">
<meta property="og:description" content="{{ $ad->present()->simpleDescription() }}">
@foreach ($ad->images('1200x630') as $k => $image)
    <meta property="og:image" content="{{ $image }}"/>
@endforeach
<meta property="product:retailer_item_id" content="{{ $ad->getId() }}"/>
<meta property="product:price:amount" content="{{ $ad->price()->readable() }}"/>
<meta property="product:price:currency" content="EUR"/>
<meta property="product:availability" content="in stock"/>
<meta property="product:condition"
      content="{{ $ad->getConditionId() == 5 ? 'new' : 'used'}}"/>
<meta property="og:locale" content="fr_FR">
<meta property="article:author"
      content="https://www.facebook.com/{{ $seller->socialNetworkId() }}">
