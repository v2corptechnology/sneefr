<div class="navbar navbar-default navbar-sneefr navbar-search hidden-xs">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img class="img-responsive" src="{{ asset('img/logo-sneefr.svg') }}"
                     alt="Buy from great local trusted shops in your city, all in one place">
            </a>
        </div>

        <form class="navbar-form navbar-left has-no-mb" action="{{ route('search.index') }}">
            <div class="row">
                <div class="col-sm-6 is-narrow-pr">
                    <input type="search" class="form-control" name="q"
                           placeholder="What do you want to buy? Phonecase, wallet, tshirt…"
                           value="{{ $query }}">
                </div>
                <div class="col-sm-4 is-narrow-pl is-narrow-pr">
                    <input type="text" class="form-control has-no-mb" name="location"
                           placeholder="Where? Los Angeles, CA">
                </div>
                <div class="col-sm-2 is-narrow-pl">
                    <button type="submit" class="btn btn-sky-blue">
                        <i class="fa fa-lg fa-search"></i>
                        <span class="sr-only">Search</span>
                    </button>
                </div>
            </div>
        </form>

        {{-- Login/register links --}}
        @unless (auth()->check())
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a class="btn-link" href="{{ url('login') }}" title="Login">Login</a>
                </li>
                <li>
                    <a class="btn-link btn-pink" href="{{ url('register') }}" title="Register">Register</a>
                </li>
            </ul>
        @endunless

    </div>
</div>

<nav class="navbar navbar-default navbar-sneefr navbar-menu">
    <div class="container">
        {{-- Brand and toggle get grouped for better mobile display --}}
        <div class="navbar-header">

            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#js-nav-menu" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#js-nav-search" aria-expanded="false">
                <span class="sr-only">Toggle search</span>
                <i class="fa fa-lg fa-search"></i>
            </button>

            <a class="navbar-brand visible-xs" href="{{ route('home') }}">
                <img class="img-responsive" src="{{ asset('img/logo-sneefr.svg') }}"
                     alt="Buy from great local trusted shops in your city, all in one place">
            </a>
        </div>

        {{-- Wrapped into another div for smooth animation --}}
        <div class="visible-xs">
            {{-- Collect the nav links, forms, and other content for toggling --}}
            <div class="collapse navbar-collapse" id="js-nav-search">
                <form class="navbar-form navbar-left" action="{{ route('search.index') }}">
                    <div class="row">
                        <div class="col-xs-10 is-narrow-pr">
                            <input type="search" class="form-control" name="q"
                                   placeholder="What do you want to buy? Phonecase, wallet, tshirt…"
                                   value="{{ $query }}">
                            <input type="text" class="form-control has-no-mb" name="location"
                                   placeholder="Where? Los Angeles, CA">
                        </div>
                        <div class="col-xs-2 is-narrow-pl">
                            <button type="submit" class="btn btn-sky-blue">
                                <i class="fa fa-2x fa-search"></i>
                                <span class="sr-only">Search</span>
                            </button>
                        </div>
                        <input type="hidden" name="type" value="{{ $type }}">
                    </div>
                </form>
            </div>
        </div>

        {{-- Collect the nav links, forms, and other content for toggling --}}
        <div class="collapse navbar-collapse" id="js-nav-menu">
            <ul class="nav navbar-nav">

                <li class="{{ setActive('home') }}">
                    <a href="{{ route('home') }}" title="Home">Home</a>
                </li>

                {{-- Item creation --}}
                @if (auth()->check() && auth()->user()->shop )
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
                        <a class="navbar__sneefr__item" href="{{ route('pricing') }}" title="Open my shop">Open my shop</a>
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
