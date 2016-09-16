@extends('layouts.email')

@section('title', trans('mail.deal_finished_seller.title'))

@section('content')
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 24px; font-weight: bold; margin: 0;">
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 24px; font-weight: bold; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
            @lang('mail.deal_finished_seller.title')
        </td>
    </tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 20px 20px 0;" valign="top"
        width="200px">
            <img src="{{ $ad->firstImageUrl('200x200') }}" alt="{{ $ad->getTitle() }}" width="200" height="200">
        </td>
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; margin: 0; padding: 0;" valign="top">
            <div style="font-weight: bold; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; margin: 0; padding: 0;">
                 @lang('mail.deal_finished_seller.lead', [
                    'finalPrice' => $ad->present()->negotiatedPrice(),
                    'title' => $ad->getTitle(),
                    'buyer' => link_to_route('profiles.ads.index', $buyer->present()->fullName(), $buyer)
                ])
            </div>
            
            <div style="padding: 10px 0 20px;">
                @lang('mail.deal_finished_seller.text', ['buyer' => $buyer->present()->fullName()])
            </div>

            <div style="padding: 10px 0 20px;">
                @lang('mail.deal_finished_seller.info', ['info' => $extraInfo])
            </div>

            <div style="padding: 10px 0 20px;">
                @lang('mail.deal_finished_seller.address', ['address' => $address])
            </div>

        </td>
    </tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
@stop
