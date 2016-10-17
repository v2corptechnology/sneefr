@section('modals')
    @parent
    @include('partials.modals._report_profile', ['name' => $person->present()->givenName(), 'id' => $person->getRouteKey()])
@stop

@if ($isMine)
    <ul class="summary">
        <li class="summary__item{{ setActive('profiles.notifications.index', '--selected') }}">
            <h2 class="summary__head">
                <i class="fa fa-bell summary__icon"></i>
                <a href="{{ route('profiles.notifications.index', $person) }}"
                   title="@lang('profile.sidebar.me.notifications_title')">
                    @choice('profile.sidebar.me.notifications', $unreadNotificationsCount, ['nb' => $unreadNotificationsCount])
                </a>
            </h2>
            <p class="summary__content summary__content--extra">
                @choice('profile.sidebar.me.notifications_text', $unreadNotificationsCount, ['nb' => $unreadNotificationsCount])
            </p>
        </li>

        <li class="summary__item{{ setActive('profiles.settings.edit', '--selected') }}">
            <h2 class="summary__head">
                <i class="fa fa-cog summary__icon"></i>
                <a href="{{ route('profiles.settings.edit', $person) }}"
                   title="@lang('profile.sidebar.me.parameters_title')">@lang('profile.sidebar.me.parameters')</a>
            </h2>
            <p class="summary__content summary__content--extra">
                 @lang('profile.sidebar.me.parameters_details', [
                    'urlEmail' => route('profiles.settings.edit', $person) . '#info',
                    'urlNotifications' => route('profiles.settings.edit', $person) . '#notifs',
                ])
            </p>
        </li>
    </ul>
@endif

<ul class="summary">
    <li class="summary__item{{ setActive('profiles.ads.index', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-globe summary__icon"></i>
            <a href="{{ route('profiles.ads.index', $person) }}"
               title="@choice('profile.sidebar.ads_title', count($ads), [
                                'nb' => count($ads),
                                'name' => $person->present()->givenName()])">
                @choice('profile.sidebar.ads', count($ads), ['nb' => count($ads), 'name' => $person->present()->givenName()])
            </a>
        </h2>
        <p class="summary__content summary__content--extra">
            @choice('profile.sidebar.sold', count($soldAds), ['nb' => count($soldAds)])
        </p>
    </li>
    @if (!$person->shops->isEmpty())
        <?php $shop = $person->shops->first(); ?>
        <li class="summary__item">
            <h2 class="summary__head">
                <i class="fa fa-shopping-bag summary__icon"></i>
                <a href="{{ route('shops.show', $shop) }}"
                   title="{{ $shop->getName() }}">
                    {{ $shop->getName() }}
                </a>
            </h2>
            <p class="summary__content summary__content--extra">
                @choice('profile.sidebar.shop', $shop->ads->count(), ['nb' => $shop->ads->count()])
            </p>
        </li>
    @endif
    <li class="summary__item{{ setActive('profiles.evaluations.index', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-trophy summary__icon"></i>
            <a href="{{ route('profiles.evaluations.index', $person) }}"
               title="@choice('profile.sidebar.evaluations_title', $evaluationRatio, [
                                'ratio' => $evaluationRatio,
                                'name' => $person->present()->givenName()])">
                @choice('profile.sidebar.evaluations', $evaluationRatio, ['ratio' => $evaluationRatio])
            </a>
        </h2>
        <p class="summary__content summary__content--extra">
        </p>
    </li>
    <li class="summary__item{{ setActive('profiles.networks.index', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-users summary__icon"></i>
            <a href="{{ route('profiles.networks.index', $person) }}"
               title="@lang('profile.sidebar.networks_title', ['name' => $person->present()->givenName()])">
                @lang('profile.sidebar.networks')
            </a>
            @if (!$isMine && count($commonPersons))
                <small class="pull-right visible-xs">
                    @choice('profile.sidebar.network_common', count($commonPersons), ['nb' => count($commonPersons)])
                </small>
            @endif
        </h2>
        <p class="summary__content summary__content--extra">
            <a href="{{ route('profiles.networks.index', $person) }}#following"
               title="@lang('profile.sidebar.network_following_title', ['name' => $person->present()->givenName()])">
                @choice('profile.sidebar.network_following', count($followingPersons), ['nb' => count($followingPersons)])
            </a> &bull;
            <a href="{{ route('profiles.networks.index', $person) }}#followed"
               title="@lang('profile.sidebar.network_followed_title', ['name' => $person->present()->givenName()])">
                @choice('profile.sidebar.network_followed', count($followedPersons), ['nb' => count($followedPersons)])
            </a>
            @if (!$isMine && count($commonPersons))
                &bull; <a href="{{ route('profiles.networks.index', $person) }}#common"
                   title="@lang('profile.sidebar.network_common_title', ['name' => $person->present()->givenName()])">
                    @choice('profile.sidebar.network_common', count($commonPersons), ['nb' => count($commonPersons)])
                </a>
            @endif
        </p>
    </li>
    <li class="summary__item{{ setActive('profiles.places.index', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-map-marker summary__icon"></i>
            <a href="{{ route('profiles.places.index', $person) }}"
               title="@lang('profile.sidebar.locations_title', ['name' => $person->present()->givenName()])">
                @choice('profile.sidebar.locations', count($followedPlaces), ['nb' => count($followedPlaces)])
            </a>
        </h2>
        <p class="summary__content summary__content--extra">
            @if ($followedPlaces)
                {!!
                    $followedPlaces->take(3)->map(function ($item, $key) {
                        return link_to_route('places.show', $item->getName(), $item->getId(), ['title' => trans('person.sidebar.location_title', ['place' => $item->getLongName()])]);
                    })->implode(' &bull; ')
                !!}
            @else
                @lang('profile.sidebar.locations_empty', ['name' => $person->present()->givenName()])
            @endif
        </p>
    </li>
</ul>

@if (!$searches->isEmpty())
    <ul class="summary">
        @foreach ($searches as $search)
            <li class="summary__item">

                @if ($isMine)
                    {!! Form::open(['route' => ['search.destroy', $search], 'method' => 'delete']) !!}
                    <button type="submit" class="close" title="@lang('profile.search_delete_title')">
                        <span aria-hidden="true">&times;</span><span class="sr-only">@lang('modal.close')</span>
                    </button>
                    {!! Form::close() !!}
                @endif

                <h2 class="summary__head">
                    <i class="fa fa-search summary__icon"></i>
                    {{ $search->body }}
                </h2>
            </li>
        @endforeach
    </ul>
@endif

@if (!$isMine && auth()->id())
    <a href="#" class="report-profile text-danger" data-toggle="modal" data-target="#reportProfile"
       title="@lang('profile.sidebar.report_title')">
        <small>@lang('profile.sidebar.report', ['name' => $person->present()->givenName()])</small>
    </a>
@endif
