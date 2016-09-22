<?php

    $multiple = $multiple ?? false;
    $item = $item ?? null;
    $title = "";
    $logo = "";
    $location = "";
    $images = [];
    $like = 0;
    $notLike = 0;
    $url = "";
    $shop_url = "";

    if($multiple) {
        $gallerySize = $gallerySize ?? '320x204';
    }else {
        $gallerySize = $gallerySize ?? '300x170';
    }

    if($item instanceof \Sneefr\Models\Shop){
        $title = $item->getName();
        $logo = $item->getLogo();
        $location = $item->getLocation();
        if($multiple)
            foreach ($item->ads->take(3) as $ad){
                array_push($images, $ad->images($gallerySize, true)[0]);
            }
        else{
            $images[0] = $item->getCover($gallerySize);
        }
        $url = route('shops.show', $item);
        $shop_url = $url;
        $like = $item->evaluations->positives()->count();
        $notLike = $item->evaluations->negatives()->count();
    }else if($item instanceof \Sneefr\Models\Ad){
        $title = $item->getTitle();
        $images = $item->images($gallerySize, true);
        $logo = $item->shop->getLogo();
        $location = $item->location();
        $like = $item->shop->evaluations->positives()->count();
        $notLike = $item->shop->evaluations->negatives()->count();
        $url = route('ad.show', $item);
        $shop_url = route('shops.show', $item->shop);;
    }
?>
<article class="card__box">
    <div class="card__box__preview">

        <div class="row">
           <div class="col-xs-12">
               <a href="{{ $url }}">
                   <div class="card__box__preview__item {{ ($multiple ) ? 'col-xs-8' : 'col-xs-12' }}">
                       <img class="card__box__preview__img" src="{{ $images[0] }}" alt="">
                   </div>
                   @if($multiple)
                       <div class="card__box__preview__item col-xs-4">
                           <img class="card__box__preview__img" src="{{ $images[1] }}" alt="">
                           <img class="card__box__preview__img" src="{{ $images[2] }}" alt="">
                       </div>
                   @endif
               </a>
           </div>
        </div>

    </div>
    <div class="card__box__footer">
        <div class="card__box__footer__content">
            <div class="card__box__avatar card__box__avatar--circle">
                <a href="{{ $shop_url }}">
                    <img class="card__box__avatar__img" src="{{ $logo }}" alt="">
                </a>
            </div>
            <div class="card__box__description">
                <a href="{{ $url }}">
                    <span class="card__box__description__title text-indent">{{ $title }}</span>
                </a>
                <div>
                    <i class="fa fa-thumbs-up no-lr-padding color-green"></i> {{ $like }} <i class="fa fa-thumbs-down no-lr-padding color-red"></i> {{ $notLike }}
                </div>
                <span><i class="fa fa-map-marker"></i> {{ $location }}</span>
            </div>
        </div>
    </div>
</article>