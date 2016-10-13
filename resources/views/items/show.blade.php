@extends('layouts.master')

@section('title', trans('ad.show.page_title', ['title' => $ad->getTitle()]))

@section('social_media')
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
          content="https://www.facebook.com/{{ $ad->seller->socialNetworkId() }}">
@endsection

@push('script')
    <style>
        .carousel-inner.onebyone-carosel { margin: auto; width: 90%; }
        .onebyone-carosel .active.left { left: -33.33%; }
        .onebyone-carosel .active.right { left: 33.33%; }
        .onebyone-carosel .next { left: 33.33%; }
        .onebyone-carosel .prev { left: -33.33%; }
    </style>
@endpush

@push('footer-js')
    <script >
        $(document).ready(function () {

            $('.js-slide-image-item').on('click', function () {
                var url = $(this).attr('src');
                $('.js-slide-image').attr('src', url);
            });

        });
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row adss">
            {{-- ad detail --}}
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="ad__images box">
                            <img class="ad__images__preview img-responsive js-slide-image" src="{{ $ad->firstImageUrl('500x500') }}" alt="">

                            <div class="ad__images__slides">
                                <div id="ad__images__slides" class="carousel fdi-Carousel slide ad__images__slides__content">
                                    <!-- Carousel items -->
                                    <div class="carousel fdi-Carousel slide" id="eventCarousel" data-interval="0">
                                        <div class="carousel-inner onebyone-carosel">
                                            <?php
                                                $k = 0;
                                                $images = $ad->images('500x500', true);
                                                $count = count($images);
                                                $slides = (int)($count/4);
                                                if(($count/4) > $slides) $slides++;
                                            ?>
                                            @for ($i= 0; $i < $slides ; $i++)
                                                <div class="item {{ ($i == 0) ? 'active' : '' }}">
                                                    <div class="col-xs-10 col-xs-offset-1">
                                                        <?php
                                                            if($i == $slides - 1){
                                                                if($i == 0){
                                                                    $n =  $count;
                                                                }else{
                                                                    $n =  $count - (4*$i);
                                                                }
                                                            }else{
                                                                $n = 4;
                                                            }
                                                        ?>
                                                        @for ($j=0; $j < $n; $j++)
                                                            <div class="col-xs-3 ad__images__slide__item">
                                                                <img src="{{ $images[$k] }}" class="img-responsive center-block ad__images__slide__img js-slide-image-item">
                                                            </div>
                                                            <?php $k++; ?>
                                                        @endfor()
                                                    </div>
                                                </div>
                                            @endfor()

                                        </div>
                                        <a class="left carousel-control" href="#eventCarousel" data-slide="prev"> <i class="icon fa fa-angle-left"></i></a>
                                        <a class="right carousel-control" href="#eventCarousel" data-slide="next"><i class="icon fa fa-angle-right"></i></a>
                                    </div>
                                    <!--/carousel-inner-->
                                </div><!--/myCarousel-->
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">

                        {{-- Title, price and buy buttons --}}
                        @include ('items.show._heading', ['ad' => $ad])

                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="box ad__description">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs ad__description__tabs" role="tablist">
                                <li role="presentation" class="ad__description__tabs__item active"><a href="#detail" aria-controls="detail" role="tab" data-toggle="tab">@lang('ad.show.ad_detail_title')</a></li>
                                <li role="presentation" class="ad__description__tabs__item"><a href="#delivery" aria-controls="delivery" role="tab" data-toggle="tab">@lang('ad.show.ad_shipping_title')</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content ad__description__content">
                                <div role="tabpanel" class="tab-pane active" id="detail">
                                    <p>{!! $ad->present()->description() !!}</p>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="delivery">
                                    <table class="table ad__description__table">
                                        <tbody>
                                            @foreach ($ad->present()->getFees() as $name => $fee)
                                                <tr>
                                                    <td>@lang('ad.show.delivery_'.$name)</td>
                                                    <td><span class="text-{{ $fee ? 'gold' : 'black' }}">@choice('ad.show.delevery_fee',$fee, ['fee' => $fee] )</span></td>
                                                    <td><span class="text-blue pull-right">{{ $ad->present()->priceWithFee($name) }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <p>Developed by the Intel Corporation, HDCP stands for high-bandwidth digital content protection. As the descriptive name implies.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            {{-- end detail --}}
            {{-- Shop detail --}}
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card__box ad__shop">
                            <div class="card__box__footer">
                                <div class="card__box__footer__content">
                                    <div class="card__box__avatar card__box__avatar--circle">
                                        <a href="{{ route('shops.show', $ad->shop) }}">
                                            <img class="card__box__avatar__img" src="{{ $ad->shop->getLogo('60x60') }}" alt="">
                                        </a>
                                    </div>
                                    <div class="card__box__description">
                                        <a href="{{ route('shops.show', $ad->shop) }}">
                                            <span class="card__box__description__title text-indent">{{ $ad->shop->getName() }}</span>
                                        </a>
                                        <div>
                                            <i class="fa fa-thumbs-up no-lr-padding color-green"></i> {{ $ad->shop->evaluations->positives()->count() }}
                                            <i class="fa fa-thumbs-down no-lr-padding color-red"></i> {{ $ad->shop->evaluations->negatives()->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p>{{ $ad->shop->getDescription() }}</p>
                                <div class="row ad__shop__map">
                                    <img class="img-responsive ad__shop__map__img" src="https://maps.googleapis.com/maps/api/staticmap?&zoom=13&size=300x160&markers=color:red%7C{{ $ad->shop->getLatitude() }},{{ $ad->shop->getLongitude() }}&key=AIzaSyBdinlP2NwN4G3P5f3Yte6CFZAS4E7P5Kc" alt="">
                                </div>
                                <i class="fa fa-map-marker"></i><span> {{ $ad->shop->getLocation() }}</span>
                                <hr class="ad__shop__divider">
                                <h5 class="timetable__title">Horaire</h5>
                                <table class="table timetable">
                                    <tbody>
                                        <tr>
                                            <td>Lun.</td>
                                            <td>10:00 - 18:00</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Mar.</td>
                                            <td>10:00 - 18:00</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Mar.</td>
                                            <td>10:00 - 18:00</td>
                                            <td><span class="color-green">Open now</span></td>
                                        </tr>
                                        <tr>
                                            <td>Jeu</td>
                                            <td>10:00 - 18:00</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Ven.</td>
                                            <td>10:00 - 18:00</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>sam.</td>
                                            <td>10:00 - 18:00</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>dim.</td>
                                            <td><span class="color-red">Close</span></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <div class="text-center">
                            <a href="{{ route('shops.show', $ad->shop) }}" class="btn btn-default-o">Learn more</a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end Shop detail --}}
        </div>
    </div>
@endsection
