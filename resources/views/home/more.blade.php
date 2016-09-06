@extends('layouts.master')

@section('title', trans('more.page_title'))

@section('content')

    <style>.img-responsive {margin: 0 auto;}.list-unstyled{margin-top: 3rem;}</style>

<div class="container">
    <div class="col-md-4 col-md-offset-2">
        <img src="{{ url('img/b64/gratuit.png') }}" alt="" width="100" class="img-responsive"/>
        <h1 class="text-center">@lang('more.free_head')</h1>
        <p>@lang('more.free_text', ['url' => route('ad.create')])</p>
    </div>
    <div class="col-md-4">
        <img src="{{ url('img/b64/securise.png') }}" alt="" width="100" class="img-responsive"/>
        <h1 class="text-center">@lang('more.secure_head')</h1>
        <p>@lang('more.secure_text')</p>
    </div>
    <div class="col-md-12 text-center" style="margin-top: 2rem;">
        <a class="btn btn-facebook btn-lg" href="{{ route('login') }}" title="@lang('button.connect.simple_title')"><i class="fa fa-facebook"></i> @lang('button.connect.simple')</a>
    </div>
    <div class="col-md-12">
        <ul class="list-inline list-unstyled text-center">
            <li>@lang('common.help', ['url' => url('help')])</li>
            <li>@lang('common.faq', ['url' => url('faq')])</li>
            <li>@lang('common.about', ['url' => url('about')])</li>
            <li>@lang('common.cgu', ['url' => url('cgu')])</li>
        </ul>
    </div>
</div>
@stop
