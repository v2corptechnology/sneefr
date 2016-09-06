@extends('layouts.master')

@section('title', trans('message.page_title'))

@section('body', 'with-fixed')

@push('footer-js')
    <script src="{{ elixir('js/sneefr.messages.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <div class="row">

        {{-- Display info when inbox is empty --}}
        @if ($discussions->isEmpty())
            <div class="col-md-6 col-md-offset-3">
                <p class="bg-warning text-warning">
                    @if ($type == 'shop')
                        <strong>@lang('shopdiscussions.empty_heading')</strong><br/>
                        @lang('shopdiscussions.empty')
                    @else
                        <strong>@lang('message.discussion_empty')</strong><br/>
                        @lang('message.no_discussions')
                    @endif
                </p>
            </div>
        @else

            {{-- Hidden on mobile if discussion is chosen --}}
            <div class="col-md-3 @if(isset($chosenDiscussion)) hidden-xs hidden-sm @endif">

                @include('discussions._correspondents', [
                    'discussions' => $discussions,
                    'chosenDiscussion' => $chosenDiscussion ?? $discussions->first(),
                ])
            </div>

            {{-- Hidden on mobile if discussion is chosen --}}
            <div class="col-md-6 @if(!isset($chosenDiscussion)) hidden-xs hidden-sm @endif">

                {{-- Mesages and ads for the discussion --}}
                @include('discussions._content', [
                    'discussions' => $discussions,
                    'chosenDiscussion' => $chosenDiscussion ?? $discussions->first(),
                ])

                {{-- Answer zone --}}
                @include('discussions._answer', [
                    'currentDiscussion' => $chosenDiscussion ?? $discussions->first()
                ])

            </div>
        @endif
    </div>
</div>
@stop
