@extends('layouts.master')

@section('title', trans('pricing.page_title'))

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ elixir('css/sneefr.pricing.css') }}">
@endsection

@section('content')
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
