@extends('layouts.master')

@section('title', trans('error.404.page_title'))

@section('content')
    <div class="container">
        <div class="text-center">
            <h1>@lang('error.404.header')</h1>
            <h2>@lang('error.404.lead')</h2>
            <p>@lang('error.404.text', ['searchUrl' => route('search.index')])</p>
            <p class="text-muted">@lang('error.404.help', ['helpUrl' => url('help')])</p>
        </div>
    </div>
@stop
