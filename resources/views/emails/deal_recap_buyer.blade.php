@extends('layouts.email')

@section('title', trans('mail.deal_recap_buyer.title'))

@section('content')
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 24px; font-weight: bold; margin: 0;">
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 24px; font-weight: bold; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
            @lang('mail.deal_recap_buyer.title')
        </td>
    </tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 20px 20px 0;" valign="top" width="200px">
            <a href="{{ route('ad.show', $ad->getSlug()) }}" title="{{ $ad->getTitle() }}">
                <img src="{{ $ad->firstImageUrl('200x200') }}" alt="{{ $ad->getTitle() }}" width="200" height="200">
            </a>
        </td>
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; margin: 0; padding: 0;" valign="top">
            <div style="font-weight: bold; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; margin: 0; padding: 0;">
                {{ $ad->present()->negotiatedPrice() }} &bull; @lang('mail.deal_recap_buyer.lead', ['title' => link_to_route('ad.show', $ad->getTitle(), $ad->getSlug())])
            </div>
            <div style="padding: 10px 0 20px;">
                @lang('mail.deal_recap_buyer.text', ['seller' => link_to_route('profiles.ads.index', $seller->present()->fullName(), $seller)])
            </div>
            <div class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                <a href="{{ route('ad.show', $ad->slug()) }}" class="btn-p rimary" itemprop="url" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; background: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;" title="@lang('mail.deal_recap_buyer.button_title')">
                    @lang('mail.deal_recap_buyer.button')
                </a>
            </div>
            <div class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; margin: 0; color: #999999; padding: 0 0 20px;" valign="top">
                @lang('mail.deal_recap_buyer.tip')
            </div>
        </td>
    </tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
@stop
