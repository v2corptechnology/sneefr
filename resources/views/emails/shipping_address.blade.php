@extends('layouts.email')

@section('title', trans('mail.shipping_address.title', ['title' => $ad->present()->title()]))

@section('content')
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
            @lang('mail.shipping_address.lead')
        </td>
    </tr>
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
            @lang('mail.shipping_address.text', ['address' => $address])
        </td>
    </tr>
@stop
