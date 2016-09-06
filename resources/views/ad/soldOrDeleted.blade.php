@extends('layouts.master')

@section('title', trans('ad.gone_title'))

@section('content')
<div class="container">
    <p class="bg-warning text-warning">
        @if ($ad->isSold)
            @lang('ad.gone_deleted_text', [
                'title' => $ad->getTitle(),
                'url' => route('search.index', ['q' => $ad->getTitle()])
            ])
        @elseif ($ad->trashed())
            @lang('ad.gone_sold_text', [
                'title' => $ad->getTitle(),
                'url' => route('search.index', ['q' => $ad->getTitle()])
            ])
        @endif
    </p>
</div>
@stop
