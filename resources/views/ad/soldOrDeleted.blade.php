@extends('layouts.master')

@section('title', trans('ad.gone_title'))

@section('content')
<div class="container">
    <p class="bg-warning text-warning">
        @lang('ad.gone_deleted_text', [
            'title' => $ad->getTitle(),
            'url' => route('search.index', ['q' => $ad->getTitle()])
        ])
    </p>
</div>
@stop
