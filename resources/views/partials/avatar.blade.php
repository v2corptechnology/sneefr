<?php

$size = $size ?? '40x40';

$nolink = $nolink ?? false;

list($width, $height) = explode('x', $size);

if ($of instanceof \Sneefr\Models\Shop) {

    $route = route('shops.show', $of);
    $src = $of->getLogo($size);
    $src2x = $of->getLogo($size . '@2x');
    $name = $of->getName();

} else {

    $route = route('profiles.ads.index', $of);
    $src = \Img::avatar($of->avatar, $size);
    $src2x = \Img::avatar($of->avatar, $size . '@2x');
    $name = $of->present()->fullName();

}
?>

@unless($nolink)
    <a class="avatar {{ $classes ?? '' }}" href="{{ $route }}" title="{{ $name }}">
@endunless

    <img class="avatar__image" src="{{ $src }}" alt="{{ $name }}"
         srcset="{{ $src2x }} 2x" height="{{ $height }}" width="{{ $width }}">

@unless($nolink)
    </a>
@endunless
