@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="col-md-4">
            @include('admin._sidebar')
        </div>
        <div class="col-md-8">
            <h1 class="content-head">{{ $totals['ads'] }} Annonces</h1>
            <h2 class="h6 text-muted">Stats... bientôt</h2>
            <div class="content">
                <ul class="media-list">
                    @foreach ($ads as $ad)
                        <li class="media">
                            <div class="media-left">
                                <a href="{{ route('ad.show', [$ad->id]) }}">
                                    <img src="{!! Img::cropped($ad, 0, '47x59') !!}" alt="" width="47" style="min-width:47px; display: inline-block;"/>
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading h5">
                                    <a href="{{ route('ad.show', [$ad->id]) }}">{{ $ad->title }}</a>
                                </h4>
                                <h6 class="text-muted">
                                    {!! $ad->present()->price() !!} par
                                    <a href="{{ route('profiles.show', $ad->user->getRouteKey()) }}">
                                        {{ $ad->user->present()->fullName() }}
                                    </a>
                                </h6>
                                {!! $ad->description !!}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@stop
