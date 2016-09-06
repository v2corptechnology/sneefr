@extends('layouts.master')

@section('title', trans('ad.buyer.page_title'))

@section('body', 'choose-buyer')

@push('footer-js')
    <script src="{{ elixir('js/sneefr.auto-navigate.js') }}"></script>
@endpush

@section('content')

<div class="container">

    <header class="hero hero--centered">
        <img src="{{ asset('img/pig.svg') }}" width="100" alt="sneefR" class="hero__img">
        <h1 class="hero__title">@lang('ad.buyer.heading')</h1>
        @if (!$discussing->isEmpty())
            <p class="hero__tagline">@lang('ad.buyer.tagline')</p>
        @endif
    </header>

    <main class="row">
        <div class="col-md-8 col-md-offset-2">
            @if (!$discussing->isEmpty())
                <div class="row">
                    @foreach($discussing as $discussion)
                        <?php $recipient = $discussion->recipient();?>
                        <div class="col-xs-6 col-sm-4 col-md-3">
                            <div class="box box--center">
                                {!! HTML::profilePicture($recipient->facebook_id, $recipient->present()->fullName(), 60, ['buyer__figure']) !!}
                                <span class="buyer__name">{{ $recipient->present()->fullName() }}</span>
                                <a class="btn btn-default btn-block btn-default2"
                                   title="@lang('button.ad.sell_one_title')"
                                   href="{{ route('discussions.ads.show', [$discussion->id(), $ad->slug()]) }}">
                                    @lang('button.ad.sell_one')
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>
            @endif

            @if (!$notDiscussing->isEmpty())

                <div class="hero">
                    <p class="hero__tagline">
                        @lang('ad.buyer.heading_not_discussing')
                    </p>
                </div>

                <div class="not-discussing-zone">
                    <label for="not_discussing" class="not-discussing">
                        <select class="not-discussing-list js-auto-navigate" name="not_discussing" id="not_discussing">
                            <option class="not-discussing-item" value="" selected>@lang('button.choose.ad')</option>
                            @foreach($notDiscussing as $discussion)
                                <?php $recipient = $discussion->recipient();?>
                                <option class="not-discussing-item"
                                        value="{{ route('discussions.ads.show', [$discussion->id(), $ad->slug()]) }}">
                                    {{ $recipient->present()->fullName() }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <hr>
            @endif

            <div class="delete-zone">
                <form action="{{ route('ad.destroy', $ad->slug()) }}" method="POST">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button class="btn btn-lg btn-danger btn-danger2" type="submit"
                            title="@lang('button.ad.delete_alt_title')">
                        @lang('button.ad.delete_alt')
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>
@stop
