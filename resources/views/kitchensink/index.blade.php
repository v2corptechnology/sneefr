@extends('layouts.master')

@section('title', 'Kitchen sink')

@section('content')
    <script src="//rawgit.com/gilbitron/Ideal-Image-Slider/master/ideal-image-slider.min.js"></script>
    <link rel="stylesheet" href="//rawgit.com/gilbitron/Ideal-Image-Slider/master/ideal-image-slider.css">
    <link rel="stylesheet" href="//rawgit.com/gilbitron/Ideal-Image-Slider/master/themes/default/default.css">
    <style>
        .row {
            margin-bottom: 4rem;
        }
    </style>
    <div class="container">
        <div>
            <h1>Cards</h1>


            <h2>Basic</h2>

            <div class="row">
                <div class="col-md-4">
                    <?php $ad = \Sneefr\Models\Ad::where('user_id', auth()->id())->first();?>
                    @include('partials._card', ['item' => $ad])
                </div>

                <div class="col-md-6">
                    <?php $ad = \Sneefr\Models\Ad::first();?>
                    @include('partials._card', [
                        'item' => $ad,
                        'avatarSize' => '60x60',
                        'gallerySize' => '400x200'
                    ])
                </div>

                <div class="col-md-2">
                    <?php $ad = \Sneefr\Models\Ad::latest()->first();?>
                    @include('partials._card', [
                        'item' => $ad,
                        'gallerySize' => '165x100',
                        'modifiers' => 'card--xs card--no-footer card--no-avatar'
                    ])
                </div>
            </div>


            <h2>Narrowed</h2>

            <div class="row">
                <div class="col-md-4">
                    <?php $ad = \Sneefr\Models\Ad::where('user_id', auth()->id())->first();?>
                    @include('partials._card', ['item' => $ad])
                </div>

                <div class="col-md-4">
                    <?php $ad = \Sneefr\Models\Ad::where('user_id', auth()->id())->first();?>
                    @include('partials._card', [
                        'item' => $ad,
                        'gallerySize' => '400x400',
                        'modifiers' => 'card--no-content card--no-avatar'
                    ])
                </div>

            </div>


            <h2>Columns</h2>

            <div class="row">
                <div class="col-md-6">
                    <?php $ad = \Sneefr\Models\Ad::latest()->first();?>
                    @include('partials._card', [
                        'item' => $ad,
                        'gallerySize' => '160x100',
                        'modifiers' => 'card--columns card--transparent card--no-footer card--no-avatar'
                    ])
                </div>

                <div class="col-md-3">
                    <?php $user = \Sneefr\Models\User::first();?>
                    @include('partials._card', [
                        'item' => $user,
                        'modifiers' => 'card--no-gallery card--columns card--no-delete'
                    ])
                </div>
            </div>
        </div>


        <h2>Centered</h2>

        <div class="row">
            <div class="col-md-4">
                <?php $shop = \Sneefr\Models\Shop::latest()->first();?>
                @include('partials._card', [
                    'item' => $shop,
                    'gallerySize' => '360x120',
                    'modifiers' => 'card--center'
                ])
            </div>

        </div>
    </div>
@stop
