@extends('layouts.master')

@section('title', trans('profile.parameters.page_title'))

@section('scripts')
    <script src="//maps.googleapis.com/maps/api/js?libraries=places&key={{ config('sneefr.keys.GOOGLE_API_KEY') }}"></script>
    @parent
@stop

@section('modals')
    @parent
    @include('partials.modals._delete_account')
@stop

@section('content')
    <div class="timeline">
        <div class="row">
            <div class="col-md-8">

                {{-- General info such as email and geoloc panel --}}
                @include('profiles.settings.general')

                {{-- Payment panel --}}
                @if(auth()->user()->shop)
                    @include('profiles.settings.payment', ['authUrl' => $authorizeUrl])
                @endif

                {{-- Phone validation panel --}}
                @include('profiles.settings.phone')
            </div>

            <div class="col-md-4">

                {{-- Profile avatar panel --}}
                @include('profiles.settings.avatar')

                {{--
                    Link to delete account.
                --}}
                <p class="text-right">
                    <a href="#" class="text-danger" data-toggle="modal" data-target="#confirm-delete">@lang('profile.parameters.button_danger_zone')</a>
                </p>
            </div>
        </div>
    </div>
@stop
