@extends('layouts.email_alert')

<?php
$text = "font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; color: #777777; font-size: 15px; font-weight: normal; line-height: 19px; margin: 0; margin-bottom: 10px; padding: 0; text-align: left;";
?>

@section('pre-title')
    <p style="{{ $text }}">@lang('mails.message.title', [
    'name' => $sender->present()->fullName(),
    'title' => $ad->present()->title()
])</p>
@endsection

@section('title', trans('mails.message.content', ['message' => $body]))

@section('footer')
    @lang('mails.message.reply', [
        'name' => $sender->present()->givenName(),
        'email' => $sender->getEmail()
    ])
@endsection
