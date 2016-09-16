@section('modals')
    @parent
    @include('partials.modals._profile_picture', [
        'name' => $person->present()->fullName(),
        'socialNetworkId' => $person->socialNetworkId(),
        'alt' => $person->present()->fullName()
    ])
@stop

<div class="profile">
    <div class="profile__summary">
        <a href="#" data-toggle="modal" data-target="#profilePicture">
            {!! HTML::profilePicture($person->socialNetworkId(), $person->present()->fullName(), 70, ['profile__image']) !!}
        </a>
        <a class="profile__rank" href="#" title="@lang('rank.'.$person->getRank())">
            <span class="rank">
                @lang('rank.'.$person->getRank())
            </span>
        </a>
    </div>

    <div class="profile-details">
        <h1 class="profile-details__title">
            {{ $person->present()->fullName() }}
        </h1>

        <div class="profile-details__tagline">
            <i class="fa fa-map-marker"></i>
            @if ($person->getLocation())
                {{ $person->getLocation() }}
            @elseif ($isMine)
                <a href="{{ route('profiles.settings.edit', auth()->user()) }}"
                   title="@lang('profile.header.geolocation_fill_title')">
                    @lang('profile.header.geolocation_fill')
                </a>
            @else
                @lang('profile.header.geolocation_missing')
            @endif

            @if (!$person->isVerified())
                &mdash; <span class="text-danger">@lang('profile.not_verified')</span>
            @endif
        </div>
    </div>

    <ul class="profile-nav">
        @if ($isMine)
            <li>
                <span class="profile-nav__item--quote" title="{{ trans('funny.' . array_rand(trans('funny'), 1)) }}">
                    &ldquo;&nbsp;{{ trans('funny.' . array_rand(trans('funny'), 1)) }}&nbsp;&rdquo;
                </span>
            </li>
        @else
            <li>
                @if ($isFollowed)
                    @include('partials.buttons._unfollow', ['user' => $person, 'btnClasses' => 'btn-link profile-nav__item'])
                @elseif ($person->id() != auth()->id())
                    @include('partials.buttons._follow', ['user' => $person, 'btnClasses' => 'btn-link profile-nav__item'])
                @else
                    <span class="profile-nav__item--disabled" title="{{ trans('funny.' . array_rand(trans('funny'), 1)) }}">
                        {{ trans('funny.' . array_rand(trans('funny'), 1)) }}
                    </span>
                @endif
            </li>
            <li>
                <a class="profile-nav__item" href="{{ route('profiles.write.create', $person) }}#message-body"
                   title="@lang('profile.header.write_title', ['name' => $person->present()->givenName()])">
                    <i class="fa fa-envelope"></i>
                    @lang('profile.header.write')
                </a>
            </li>
        @endif
    </ul>
</div>
