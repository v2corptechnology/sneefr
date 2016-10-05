<nav class="navbar navbar__sneefr hidden" role="navigation">
	<div class="container">

        <div class="navbar-header">
            <a class="navbar-brand" href="/">
                <img class="img-responsive" src="{{ asset('img/logo-sneefr.svg') }}" alt="Buy from great local trusted shops in your city, all in one place">
            </a>

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#search-responsive" style="margin-top: 5px;">
                <i class="fa fa-search color-pink"></i>
            </button>
        </div>

        <form action="{{ route('search.index') }}" class="collapse navbar__sneefr__form__mobile" role="search" id="search-responsive">
            <div class="form-group col-xs-10 search__mobile">
                <input type="search"
                       class="form-control navbar__sneefr__input--mobile"
                       placeholder="@lang('navigation.search_label')"
                       name="q"  id="q" autocomplete="of"
                       value="{{ $query }}">
                <input type="text"
                       class="form-control navbar__sneefr__input--mobile"
                       placeholder="@lang('navigation.search_place_label')"
                       name="location" autocomplete="off">
                <input type="hidden" name="type" value="{{ $type }}">
            </div>

            <div class="form-group col-xs-2 search__mobile">
                <button type="submit" class="btn btn-sky-blue navbar__sneefr__search--mobile btn-block">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>

        <div class="collapse navbar-collapse navbar__sneefr__collapse">
            <form action="{{ route('search.index') }}" class=" navbar-left navbar__sneefr__form hidden-xs" role="search">
                <div class="form-group col-sm-6">
                    <input type="search"
                           class="form-control navbar__sneefr__input"
                           placeholder="@lang('navigation.search_label')"
                           name="q" id="q"
                           value="{{ $query }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                </div>
                <div class="form-group col-sm-4 col-md-5">
                    <input type="text"
                           class="form-control navbar__sneefr__input"
                           placeholder="@lang('navigation.search_place_label')"
                           name="geo">
                </div>
                <div class="form-group col-sm-1">
                    <button type="submit" class="btn btn-sky-blue navbar__sneefr__search"><i class="fa fa-search"></i></button>
                </div>
            </form>

            @unless(auth()->check())
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="navbar__sneefr__item" href="{{ url('login') }}">
                            @lang('navigation.connect')
                        </a>
                    </li>
                    <li>
                        <a class="navbar__sneefr__item--pink" href="{{ url('register') }}">
                            @lang('navigation.register')
                        </a>
                    </li>
                </ul>
            @endunless

        </div>
    </div>
</nav>

<nav class="navbar navbar-default navbar-sneefr">
    <div class="container">
        {{-- Brand and toggle get grouped for better mobile display --}}
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#js-nav-menu" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand visible-xs" href="{{ route('home') }}">
                <img class="img-responsive" src="{{ asset('img/logo-sneefr.svg') }}"
                     alt="Buy from great local trusted shops in your city, all in one place">
            </a>
        </div>

        {{-- Collect the nav links, forms, and other content for toggling --}}
        <div class="collapse navbar-collapse" id="js-nav-menu">
            <ul class="nav navbar-nav">

                <li class="{{ setActive('home') }}">
                    <a href="{{ route('home') }}" title="Home">Home</a>
                </li>

                {{-- Item creation --}}
                @if (auth()->check())
                    <li class="{{ setActive('items.create') }}">
                        <a href="{{ route('items.create') }}" title="Create an ad">
                            <i class="fa fa-plus-circle hidden-xs"></i> Create an ad
                        </a>
                    </li>
                @endif

                {{-- Settings --}}
                @if (auth()->check())
                    <li class="{{ setActive('me.index') }}">
                        <a href="{{ route('me.index') }}" title="My profile">My profile</a>
                    </li>
                @endif

                {{-- Shop creation or display --}}
                @if (auth()->check() && auth()->user()->shop)
                    <li class="{{ url()->current() == route('shops.show', auth()->user()->shop) ? 'active' : '' }}">
                        <a href="{{ route('shops.show', auth()->user()->shop) }}" title="My Shop">My shop</a>
                    </li>
                @else
                    <li>
                        <a class="navbar__sneefr__item" href="{{ route('pricing') }}" title="@lang('navigation.create_shop_title')">@lang('navigation.create_shop')</a>
                    </li>
                @endif

                {{-- Discussions --}}
                @if (auth()->check())
                    <li class="{{ setActive(['discussions.index', 'discussions.show', 'discussions.ads.index']) }} js-messages js-pushes-target-{{ auth()->user()->getRouteKey() }}">
                        <a href="{{ route('discussions.index') }}#latest" title="Messages">
                            <sup class="notification-badge js-notification-badge {{ $unread ? '' : 'hidden' }}">{{ $unread }}</sup> Messages
                        </a>
                    </li>
                @endif
            </ul>

            {{-- Display logout when connected --}}
            @if (auth()->check())
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="{{ route('logout') }}" title="Log me out">Log out</a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>
