@extends('layouts.email')

@section('title', trans('mail.expired_lock_buyer.title', ['title' => $ad->getTitle()]))

@section('content')
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
            @lang('mail.expired_lock_buyer.lead')
        </td>
    </tr>
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
            @lang('mail.expired_lock_buyer.text')
        </td>
    </tr>
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
            <a href="{{ route('ad.show', $ad->slug()) }}" class="btn-p rimary" itemprop="url" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; background: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;" title="@lang('mail.expired_lock_buyer.button_title')">
                @lang('mail.expired_lock_buyer.button')
            </a>
        </td>
    </tr>
@stop
