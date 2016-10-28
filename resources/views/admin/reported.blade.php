@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="col-md-4">
            @include('admin._sidebar')
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="content-head" id="users">{{ count($reports['users']) }} Utilisateurs signalés</h1>
                    <ul class="media-list content">
                        @foreach ($reports['users'] as $person)
                            <li class="media">
                                <div class="media-body">
                                    <h4 class="media-heading h5">
                                        <a href="{{ route('profiles.show', $person) }}">{{ $person->present()->fullName() }}</a>
                                    </h4>
                                    A été signalé le... par ...
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-sm-6">
                    <h1 class="content-head" id="ads">{{ count($reports['ads']) }} Annonces signalées</h1>
                    <ul class="media-list content">
                        @foreach ($reports['ads'] as $ad)
                            <li class="media">
                                <div class="media-left">
                                    <a href="{{ route('ad.show', [$ad->getSlug()]) }}">
                                        <img src="{{ Img::cropped($ad, 0, 128) }}"  height="50" alt=""/>
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading h5">
                                        {{ $ad->getTitle() }}
                                    </h4>
                                    A été signalé le... par ...
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop
