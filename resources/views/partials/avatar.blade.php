<?php

list($width, $height) = explode('x', $size);

if ($of instanceof \Sneefr\Models\Shop) {

    $route = route('shops.show', $of);
    $src = $of->getLogo($size);
    $src2x = $of->getLogo($size . '@2x');
    $name = $of->getName();

} else {

    $route = route('profiles.ads.index', $of);
    $src = \Img::avatar($of->facebook_id, $size);
    $src2x = \Img::avatar($of->facebook_id, $size . '@2x');
    $name = $of->present()->fullName();

}
?>
<a class="card__avatar" href="{{ $route }}" title="{{ $name }}">
    <img class="card__image" src="{{ $src }}" alt="{{ $name }}" srcset="{{ $src2x }} 2x" height="{{ $height }}" width="{{ $width }}">
</a>
