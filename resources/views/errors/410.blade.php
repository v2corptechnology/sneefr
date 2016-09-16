@extends('layouts.master')

@section('title', trans('error.410.page_title'))

@section('content')
    <div class="container">
        <div class="text-center">
            <h1>@lang('error.410.header')</h1>
            <h2>@lang('error.410.lead')</h2>
            <p>@lang('error.410.text', ['searchUrl' => route('search.index', ['type' => 'person'])])</p>
            <p class="text-muted">@lang('error.410.help', ['helpUrl' => url('help')])</p>
        </div>
    </div>
@stop
