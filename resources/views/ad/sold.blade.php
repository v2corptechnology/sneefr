@extends('layouts.master')

@section('title', trans('ad.sold.page_title', ['title' => $ad->getTitle()]))

@section('content')

    <div class="sold-ad__container">
        <div class="container">
            <div class="text-center">
                @if (count($personsWithAd) || count($personsDiscussingWith))
                    <h3 class="negative-background-text sold-ad__header">@lang('ad.sold.head')</h3>
                @else
                    <h3 class="negative-background-text sold-ad__header">@lang('ad.sold.head_nobody')</h3>
                    <p class="negative-background-text">@lang('ad.sold.head_nobody_text')</p>

                    {!! Form::open(['route' => ['ad.destroy', $ad->getSlug()], 'method' => 'delete']) !!}
                        <button class="btn btn-danger remove-ad__button negative-background-text"
                                type="submit">@lang('button.ad.remove_delete')
                        </button>
                    {!! Form::close() !!}
                @endif
            </div>
            <div class="row row-centered">
                @if (count($personsWithAd))
                    {!! Form::open(['route' => ['ad.confirmSold', $ad->getSlug()], 'method' => 'post']) !!}
                        <div class="col-md-12 text-center">
                            <label class="negative-background-text">
                               @lang('ad.sold.discussed_label')
                            </label>
                        </div>
                        @foreach ($personsWithAd as $person)
                            <div class="col-md-3 col-centered interlocutor">
                                <button class="btn btn-sm btn-default btn-block" type="submit" name="sold_to" value="{{ $person->getRouteKey() }}">
                                    <div class="media">
                                        <div class="media-left media-middle">
                                            {!! HTML::profilePicture($person->socialNetworkId(), $person->present()->fullName(), 25, ['img-circle']) !!}
                                        </div>
                                        <div class="media-body media-middle">
                                            {{ $person->present()->fullName() }}
                                        </div>
                                    </div>
                                </button>
                            </div>
                        @endforeach
                    {!! Form::close() !!}
                @endif

                @if (count($personsDiscussingWith))
                    <div class="col-md-12"></div>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            {!! Form::open(['route' => ['ad.confirmSold', $ad->getSlug()], 'method' => 'post']) !!}
                                <label for="" class="negative-background-text">
                                    @lang('ad.sold.other_discussions_label')
                                </label>

                                <select class="input-sm" name="sold_to">
                                    @foreach ($personsDiscussingWith as $discussion)
                                        <option value="{{ $discussion->correspondent()->getRouteKey() }}">{{ $discussion->correspondent()->present()->fullName() }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-default" type="submit">@lang('button.ad.sold_to')</button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop
