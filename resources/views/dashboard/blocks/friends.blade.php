<h1 class="block-title">
    <a class="block-title__main" href="{{ route('profiles.networks.index', auth()->user()) }}#sneefers"
       title="@lang('dashboard.sneefers_head_title')">@lang('dashboard.sneefers_head')</a>
    <span class="block-title__secondary">
        <span role="presentation" aria-hidden="true"> · </span>
        <a href="{{ route('profiles.networks.index', auth()->user()) }}"
           title="@choice(
                'dashboard.sneefers_count_title',
                count($connections),
                ['num' => count($connections)]
            )">
           @choice(
                'dashboard.sneefers_count',
                count($connections),
                ['num' => count($connections)]
            )
        </a>
    </span>
</h1>

<ol class="list-unstyled row friends-list">
    @foreach ($connections->take(18) as $connection)
        <li class="col-md-2 friends-list__item">
            <a class="pop" data-placement="top" data-content="@choice(
                'dashboard.sneefr_tooltip',
                $connection->ads->count(), [
                    'num' => $connection->ads->count(),
                    'name' => $connection->present()->givenName()
                ]
            )" href="{{ route('profiles.show', [$connection]) }}">
                <img class="img-responsive" src="{{ Img::avatar($connection, 48) }}"
                     srcset="{{ Img::avatar($connection, 48) }} 1x, {{ Img::avatar($connection, '48x48@2x') }} 2x"
                     width="48" height="48" alt="{{ $connection->present()->fullName() }}">
            </a>
        </li>
    @endforeach
</ol>
