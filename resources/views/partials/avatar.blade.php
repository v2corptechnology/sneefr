<?php

list($width, $height) = explode('x', $size);

if ($of instanceof \Sneefr\Models\Shop) {

    $route = route('shops.show', $of);
    $src = $of->getLogo($size);
    $name = $of->getName();

} else {

    $route = route('profiles.ads.index', $of);
    $src = \Img::avatar($of->facebook_id, $size);
    $name = $of->present()->fullName();

}
?>
<a class="card__avatar" href="{{ $route }}" title="{{ $name }}">
    <img class="card__image" src="{{ $src }}" alt="{{ $name }}" height="{{ $height }}" width="{{ $width }}">
</a>
