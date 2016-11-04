@extends('layouts.email_alert')

@section('title', trans_choice('mails.sold.title', $quantity, [
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
    @lang('mails.sold.content', ['name' => $buyer->present()->fullName()])
@endsection

<?php
    $heading = "font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 20px; font-weight: normal; line-height: 1.3; margin: 0; margin-bottom: 10px; padding: 0; text-align: left; word-wrap: normal;";
    $text = "font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; color: #777777; font-size: 15px; font-weight: normal; line-height: 19px; margin: 0; margin-bottom: 10px; padding: 0; text-align: left;";
?>

@section('secondary.1')
    <h1 style="{{ $heading }}">Buyers's Address</h1>
    <p style="{{ $text }}">{!! $address !!}</p>
@endsection

@section('secondary.2')
    <h1 style="{{ $heading }}">Buyers's Info</h1>
    <p style="{{ $text }}">{{ $extraInfo }}</p>
@endsection

@section('footer')
    This email was sent automatically, if you have any question, please contact the vendor.
@endsection
