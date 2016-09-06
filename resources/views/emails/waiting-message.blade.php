@extends('layouts.email')

@section('title', trans_choice('mail.waiting-message.title', $nb, ['name' => $name, 'nb' => $nb]))

@section('content')
    <?php
    $route = ($discussion->isShopDiscussion() && $discussion->shop->isOwner($user->getId()) ) ? route('shop_discussions.show', [$discussion, $discussion->shop]) : route('discussions.show', $discussion) ;
    ?>
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
            {!! trans_choice(
                'mail.waiting-message.lead',
                $nb,
                ['name' => $name, 'nb' => $nb]
            ) !!}
        </td>
    </tr>
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
            <blockquote style="color: #333; border-left: 3px solid #CCC; background-color: #F9F9F9; padding: 10px 15px; font-style:italic; margin: 0;">
                <p>{{ $firstUnread }}</p>
                <cite>— <a href="{{ $route }}#latest"
                           style="color: #333;">{{ $firstUnreadAuthor }}</a></cite>
            </blockquote>
        </td>
    </tr>
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
            <a href="{{ $route }}#latest" class="btn-p rimary" itemprop="url" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; background: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;" title="@lang('mail.waiting-message.button_title')">
                @lang('mail.waiting-message.button')
            </a>
        </td>
    </tr>
@stop
