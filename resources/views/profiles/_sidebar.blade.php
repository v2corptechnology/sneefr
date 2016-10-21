@section('modals')
    @parent
    @include('partials.modals._report_profile', ['name' => $person->present()->givenName(), 'id' => $person->getRouteKey()])
@stop

@if ($isMine)
    <ul class="summary">

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
    @if ($person->hasShop())
        <?php $shop = $person->shop; ?>
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
