@extends('layouts.shop', ['shop' => $shop])

@section('title', trans('shop.evaluations.page_title', ['name' => $shop->getName() ]))

@section('shop_content')
    <div class="row">

        <div class="col-sm-8">
            <h1 class="content-head" id="common">
                @choice('shop.evaluations.head', $shop->evaluations->count(), ['nb' => $shop->evaluations->count(), 'name' => $shop->getName()])
            </h1>

            @if ($shop->evaluations->isEmpty())
                <p class="text-muted">@lang('shop.evaluations.empty_text', ['name' => $shop->getName()])</p>
            @else
                <ol class="evaluation-timeline">
                    @foreach ($shop->evaluations as $evaluation)
                        <li class="evaluation {{ $evaluation->status == 'forced' ? 'evaluation--forced' : null }}">
                            <span class="evaluation__time">{!!  HTML::time($evaluation->created_at) !!}</span>
                            @if ($evaluation->value)
                                <span class="evaluation__value--positive"
                                      title="@lang('profile.evaluations.positive_title')">
                                    <i class="fa fa-thumbs-up"></i>
                                </span>
                            @else
                                <span class="evaluation__value--negative"
                                      title="@lang('profile.evaluations.negative_title')">
                                    <i class="fa fa-thumbs-down"></i>
                                </span>
                            @endif

                            <div class="evaluation__content">

                                <img class="evaluation__profile-image" src="{{ $evaluation->evaluator->getPicture('17x17') }}"
                                     srcset="{{ $evaluation->evaluator->getPicture('34x34') }} 2x"
                                     width="17" height="17" alt="{{ $evaluation->evaluator->present()->givenName() }}">

                            @if ($evaluation->body)
                                    <p class="evaluation__body">{{ $evaluation->body }}</p>
                                @elseif ($evaluation->status == 'forced')
                                    <p class="evaluation__body">@lang('profile.evaluations.forced_text')</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
@stop
