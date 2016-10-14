@extends('layouts.email_alert')

@section('title', trans_choice('mails.purchased.title', $quantity, [
    'nb' => $quantity,
    'item' => $ad->present()->title(),
    'price' => $price
]))

@section('image')
    <a href="{{ route('ad.show', $ad) }}" title="{{ $ad->present()->title() }}">
        <img src="{{ $ad->images('516x200', true)[0] }}"
             srcset="{{ $ad->images('1032x400', true)[0] }} 2x"
             alt="{{ $ad->present()->title() }}">
    </a>
@endsection

@section('content')
    @lang('mails.purchased.content', ['name' => $shop->getName()])
@endsection

@section('content.button')
    <a href="{{ $evaluateLink }}" title="@lang('mails.purchased.btn_evaluate_title', ['vendorName' => $shop->getName()])" itemprop="url" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; background: #ff316e; margin: 0; border-color: #ff316e; border-style: solid; border-width: 10px 20px;">
        @lang('mails.purchased.btn_evaluate', ['vendorName' => $shop->getName()])
    </a>
@endsection

<?php
    $heading = "font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 20px; font-weight: normal; line-height: 1.3; margin: 0; margin-bottom: 10px; padding: 0; text-align: left; word-wrap: normal;";
    $text = "font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; color: #777777; font-size: 15px; font-weight: normal; line-height: 19px; margin: 0; margin-bottom: 10px; padding: 0; text-align: left;";
?>

@section('secondary.1')
    <h1 style="{{ $heading }}">Seller's Address</h1>
    <p style="{{ $text }}">{{ $shop->getLocation() }}</p>
@endsection

@section('secondary.2')
    <h1 style="{{ $heading }}">Seller's Info</h1>
    <p style="{{ $text }}">{{ $shop->getDescription() }}</p>
@endsection

@section('footer')
    This email was sent automatically, if you have any question, please contact the vendor Sneefr.
@endsection
