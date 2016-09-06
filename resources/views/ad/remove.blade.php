@extends('layouts.master')

@section('title', trans('ad.remove.page_title', ['title' => $ad->getTitle()]))

@section('content')
    <div class="vertical-center remove-ad__container">
        <div class="container vertical-center-cell">
            <h1 class="negative-background-text text-center">@lang('ad.remove.head')</h1>

            <div class="row">
                <div class="col-xs-6 col-sm-4 col-md-3 col-sm-offset-2 col-md-offset-3">
                    <a class="btn btn-success btn-block remove-ad__button" href="{{ route('ad.sold', $ad->getSlug()) }}">@lang('button.ad.remove_sold')</a>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3">
                    {!! Form::open(['route' => ['ad.destroy', $ad->getSlug()], 'method' => 'delete']) !!}
                        <button class="btn btn-danger btn-block remove-ad__button negative-background-text" type="submit">@lang('button.ad.remove_delete')</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop


