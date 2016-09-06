@extends('layouts.master')

@section('title', trans('pricing.page_title'))

@section('content')
    <style>
        body {background-color: #FFF;}

        .jumbotron {
            background: url('./img/shops-welcome__background.jpg') no-repeat center center / cover;
            color: #FFF;
            min-height: 400px;
        }
        .jumbotron__heading {
            font-size: 2.5rem;
            padding-top: 2rem;
            text-align: center;
        }
        .jumbotron__subheading {
            font-size: 1.8rem;
            text-align: center;
        }

        .feature-list {
            list-style: none;
            padding: 5rem 0 2rem;
        }
        .feature-list__item {
            color: #FFF;
            display: block;
            font-size: 1.6rem;
            margin: 1rem 0;
        }
        .feature-list__item:hover,
        .feature-list__item:focus {
            color: #FFF;
            text-decoration: underline;
        }

        .price {text-align: center;}
        .price__amount {
            display: block;
            font-size: 2.8rem;
            font-weight: 400;
        }
        .price__recursion {
            font-weight: 200;
        }
        
        .bullet:before {
            background: transparent no-repeat center center;
            content: "";
            display: block;
            float: left;
            height: 50px;
            margin: 0 1rem 0 3rem;
            transform: translateY(-25%);
            width: 50px;
        }
        .bullet--circle:before {
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 50%;
        }
        .bullet--unlimited_ads:before {background-image: url('/img/shops-welcome__unlimited-ads.svg');}
        .bullet--no_fees:before {background-image: url('/img/shops-welcome__no-fees.svg');}
        .bullet--support:before {background-image: url('/img/shops-welcome__support.svg');}

        .separator {margin: 3rem auto;}
        .separator:after {display: none!important;}/* Remove that asap*/
        .separator--md {width: 30%;}
        .separator--discreet {opacity: 0.25;}

        .video {
            padding: 3rem 0 6rem;
        }
        .video h1 {
            margin-bottom: 2rem;}
        
        .pricing {
            background: #F1F4F9 url('/img/shops-welcome__pictos.png') repeat-x center left;
            padding: 6rem 0;
        }

        .pricing-table {
            color: #445868;
            width: 100%;
        }
        .pricing-table td {
            border-left: 1px solid #E7ECF4;
            text-align: center;
        }
        .pricing-table td:first-child {
            border-left: none;
            text-align: left;
        }
        .pricing-table th {
            font-weight: normal;
            text-align: center;
        }
        .pricing-table th:first-child {text-align: left;}
        .pricing-table strong {
            font-size: 1.5rem;
            font-weight: 500;
        }
        .pricing-table p {
            color: #95A0A9;
            font-size: 1.3rem;
            margin-top: 1rem;
        }

        .plan__name {display: block;}
        .plan__price {
            color: #2499D6;
            font-size: 2.4rem;
        }
        .plan__price-detail {color: #2499D6}



        @media screen and (min-width: 48rem) {
            .jumbotron__heading {
                font-size: 3.6rem;
                padding-top: 6rem;
            }

            .jumbotron__subheading {
                font-size: 3rem;
            }

            .pricing-table th,
            .pricing-table td {
                padding: 1rem 2rem;
            }

            .feature-list {
                text-align: center;
            }
        }
    </style>

    <header class="jumbotron">
        <div class="container">
            <h1 class="jumbotron__heading">@lang('pricing.header.heading')</h1>
            <h2 class="jumbotron__subheading">@lang('pricing.header.sub_heading')</h2>

            <ul class="feature-list list-inline">
                @foreach (trans('pricing.header.bullets') as $key => $bullet)
                    <li>
                        <a class="feature-list__item bullet bullet--circle bullet--{{ $key }}"
                           href="#{{ $key }}" title="{{ trans('pricing.table.items.'.$key.'.description') }}">{{ $bullet }}</a>
                    </li>
                @endforeach
            </ul>

            <hr class="separator separator--md separator--discreet">

            <div class="price">
                <p>@lang('pricing.header.plan_prefix')
                    <b class="price__amount">
                        @lang('pricing.shop_monthly_price')
                        <span class="price__recursion">@lang('pricing.header.plan_recursion')</span>
                    </b>
                </p>

                <a href="#pricing" class="btn btn-success btn-lg"
                   title="@lang('pricing.header.btn_buy_title')"
                   style="font-variant: small-caps">@lang('pricing.header.btn_buy')</a>
            </div>
        </div>
    </header>
    <!--
    <div class="video container text-center">
        <div class="col-md-8 col-md-offset-2">
            <h1>@lang('pricing.video.heading')</h1>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" width="420" height="315" src="https://www.youtube.com/embed/qGKrc3A6HHM" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>-->
    <main class="pricing">
        <div class="container" id="pricing">
            <div class="col-md-10 col-md-offset-1 box">
                <p class="text-center text-muted">@lang('pricing.table.offer')</p>
                <table class="pricing-table table-hover">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th class="plan" scope="col">
                                <span class="plan__name">@lang('pricing.table.monthly.heading')</span>
                                <span class="plan__price">@lang('pricing.shop_monthly_price')</span>
                                <span class="plan__price-detail">@lang('pricing.table.monthly.recursion')</span>
                            </th>
                            <th class="plan-header" scope="col">
                                <span class="plan__name">@lang('pricing.table.yearly.heading')</span>
                                <span class="plan__price">@lang('pricing.shop_yearly_price')</span>
                                <span class="plan__price-detail">@lang('pricing.table.yearly.recursion')</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (trans('pricing.table.items') as $key => $item)
                            <tr id="{{ $key }}">
                                <td>
                                    <strong>{{ $item['heading'] }}</strong>
                                    <p>{{ $item['description'] }}</p>
                                </td>
                                <td>@if ($item['enabled_monthly']) <img src="img/shops-welcome__tick.svg" alt="Enabled">@endif </td>
                                <td>@if ($item['enabled_yearly']) <img src="img/shops-welcome__tick.svg" alt="Enabled">@endif </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td scope="col"></td>
                            <td class="plan" scope="col">
                                <span class="plan__price">@lang('pricing.shop_monthly_price')</span>
                                <span class="plan__price-detail">@lang('pricing.table.monthly.recursion')</span>
                            </td>
                            <td class="plan-header" scope="col">
                                <span class="plan__price">@lang('pricing.shop_yearly_price')</span>
                                <span class="plan__price-detail">@lang('pricing.table.yearly.recursion')</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <?php
                                // Todo: remove this crap
                                $hasRedirect = request()->has('redirect');

                                $monthlyUrl = $hasRedirect
                                        ? url(request()->get('redirect')).'?plan=monthly'
                                        : route('shops.login', 'monthly');

                                $yearlyUrl = $hasRedirect
                                        ? url(request()->get('redirect')).'?plan=yearly'
                                        : route('shops.login', 'yearly');
                            ?>
                            <td><a href="{{ $monthlyUrl }}" class="btn btn-primary"
                                   title="@lang('pricing.table.btn_monthly_title')">@lang('pricing.table.btn_monthly')</a>
                            </td>
                            <td><a href="{{ $yearlyUrl }}" class="btn btn-primary"
                                   title="@lang('pricing.table.btn_yearly_title')">@lang('pricing.table.btn_yearly')</a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </main>
@stop
