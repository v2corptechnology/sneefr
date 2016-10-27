<?php

$size = $size ?? '40x40';

$nolink = $nolink ?? false;

list($width, $height) = explode('x', $size);

$route = route('shops.show', $of);
$src = $of->getLogo($size);
$src2x = $of->getLogo($size . '@2x');
$name = $of->getName();
?>

@unless($nolink)
    <a class="avatar {{ $classes ?? '' }}" href="{{ $route }}" title="{{ $name }}">
@endunless

    <img class="avatar__image" src="{{ $src }}" alt="{{ $name }}"
         srcset="{{ $src2x }} 2x" height="{{ $height }}" width="{{ $width }}">

@unless($nolink)
    </a>
@endunless
