@extends('layouts.master')

@section('title', trans('error.missing_scopes.page_title'))

@section('content')

    <style>
        .explanation .btn {
            font-size: 2.5rem;
            margin-top: 3rem;
        }
    </style>

    <div class="container">
        <div class="explanation">
            <h1>@lang('error.missing_scopes.heading')</h1>
            <p class="lead">@lang('error.missing_scopes.explanation')</p>
            <ul>
                <li>@lang('error.missing_scopes.email')</li>
                <li>@lang('error.missing_scopes.birthdate')</li>
                <li>@lang('error.missing_scopes.friendlist')</li>
            </ul>
            <p class="bg-info text-info">@lang('error.missing_scopes.privacy')</p>
            <p class="text-center">
                <a class="btn btn-primary btn-lg"
                   role="button"
                   href="/re-auth">
                    <i class="fa fa-facebook"></i>
                    @lang('login.relogging')
                </a>
            </p>
        </div>
    </div>

@stop
