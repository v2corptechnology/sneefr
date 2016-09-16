@extends('layouts.master')

@section('title', trans('evaluations.create.page_title'))

@section('content')
<div class="container">

    <header class="hero hero--centered">
        @if ($ad->shop)
            <img class="hero__img img-rounded" alt="{{ $ad->shop->getName() }}"
                 src="{{ $ad->shop->getLogo('200x200') }}" width="100" height="100">
        @elseif ($ad->seller)
            {!! HTML::profilePicture($ad->seller->socialNetworkId(), $ad->seller->present()->fullName(), $dimensions = 100, ['hero__img', 'img-rounded'])  !!}
        @endif
        <h1 class="hero__title">@lang('evaluations.create.heading')</h1>
        <p class="hero__tagline">@lang('evaluations.create.tagline')</p>
    </header>

    <main class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            <div class="box">
                <form class="form2 clearfix" action="{{ route('evaluations.store') }}"
                      method="POST" enctype="multipart/form-data">

                    {!! csrf_field() !!}

                    <div class="form-group text-center">
                        <div class="col-xs-6">
                            <label class="control-label thumbs-container thumbs-down-container">
                                <i class="fa fa-thumbs-down"></i>
                                <input type="radio" name="evaluation" id="negative" value="0" autocomplete="off" required>
                            </label>
                        </div>
                        <div class="col-xs-6">
                            <label class="control-label thumbs-container thumbs-up-container">
                                <i class="fa fa-thumbs-up"></i>
                                <input type="radio" name="evaluation" id="positive" value="1" autocomplete="off" required>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="body">Commentaire <small>(facultatif)</small></label>
                        <textarea class="form-control" id="body" name="body" placeholder="Laissez votre commentaire..." cols="10" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-lg btn-primary btn-primary2 pull-right">@lang('evaluations.create.save_label')</button>
                    </div>

                    <input type="hidden" name="key" value="{{ $key }}">
                </form>
            </div>
        </div>
    </main>
</div>
@stop
