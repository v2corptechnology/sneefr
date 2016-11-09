<ul class="summary">
    <li class="summary__item{{ setActive('admin.tools', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-tag summary__icon"></i>
            <a href="{{ route('admin.tools') }}">Tags</a>
        </h2>
        <p class="summary__content summary__content--extra">
            Manage tags for shops and items.
        </p>
    </li>
    <li class="summary__item{{ setActive('highlightedShops.index', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-diamond summary__icon"></i>
            <a href="{{ route('highlightedShops.index') }}">Highlighted shops</a>
        </h2>
        <p class="summary__content summary__content--extra">
            Pick homepage highlighted shops.
        </p>
    </li>
    <li class="summary__item{{ setActive('shopClaimer.index', '--selected') }}">
        <h2 class="summary__head">
            <i class="fa fa-handshake-o summary__icon"></i>
            <a href="{{ route('shopClaimer.index') }}">Shop claims</a>
        </h2>
        <p class="summary__content summary__content--extra">
            {{ \Sneefr\Models\Claim::all()->count() }} Pending.
        </p>
    </li>
</ul>
