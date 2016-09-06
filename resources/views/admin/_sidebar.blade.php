<ul class="summary">
    <li class="summary__item{{ setActive('admin.users', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-users summary__icon"></i>
            <a href="{{ route('admin.users') }}">{{ $totals['users'] }} Utilisateurs</a>
        </h2>
        <p class="summary__content summary__content--extra">
            {{ $lastDay['users.created'] }} créés <small>/24h</small>, {{ $lastDay['users.viewed'] }} vues <small>/24h</small>.
        </p>
    </li>
    <li class="summary__item{{ setActive('admin.ads', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-globe summary__icon"></i>
            <a href="{{ route('admin.ads') }}">{{ $totals['ads'] }} Annonces</a>
        </h2>
        <p class="summary__content summary__content--extra">
            {{ $lastDay['ads.created'] }} créées <small>/24h</small>, {{ $lastDay['ads.viewed'] }} vues <small>/24h</small>.
        </p>
    </li>
    <li class="summary__item{{ setActive('admin.deals', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-euro summary__icon"></i>
            <a href="{{ route('admin.deals') }}">{{ $totals['ads.sold'] }} Ventes</a>
        </h2>
        <p class="summary__content summary__content--extra">
            {{ $lastDay['ads.sold'] }} ventes <small>/24h</small>, totalisant {{ $totals['ads.amount'] }} @lang('common.currency_symbol')
        </p>
    </li>
    <li class="summary__item{{ setActive('admin.reported', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-warning summary__icon"></i>
            <a href="{{ route('admin.reported') }}">{{ $totals['reports'] }} Signalements</a>
        </h2>
        <p class="summary__content summary__content--extra">
            <a href="{{ route('admin.reported') }}#ads">{{ count($reports['ads']) }} Annonces</a>,
            <a href="{{ route('admin.reported') }}#users">{{ count($reports['users']) }} Personnes</a>
        </p>
    </li>
    <li class="summary__item{{ setActive('admin.misc', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-bar-chart summary__icon"></i>
            <a href="{{ route('admin.misc') }}">Divers</a>

        </h2>
        <p class="summary__content summary__content--extra">
            {{ $totals['discussions'] }} Discussions, {{ $totals['stripe_profiles'] }} comptes Stripe
        </p>
    </li>
    <li class="summary__item{{ setActive('admin.searches', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-search summary__icon"></i>
            <a href="{{ route('admin.searches') }}">{{ $totals['searches'] }} Recherches</a>
        </h2>
        <p class="summary__content summary__content--extra">
            {{ $totals['shared_searches'] }} recherches partagées
        </p>
    </li>
</ul>
