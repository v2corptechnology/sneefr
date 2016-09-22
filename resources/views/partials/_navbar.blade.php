<nav class="navbar navbar__sneefr" role="navigation">
	<div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar__sneefr__collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <img class="img-responsive" src="{{ url('img/logo-sneefr.png') }}" alt="">
            </a>
        </div>

        <div class="collapse navbar-collapse navbar__sneefr__collapse">
            <form class=" navbar-left navbar__sneefr__form" role="search">
                <div class="form-group col-sm-6">
                    <input type="text" class="form-control navbar__sneefr__input" placeholder="Que voulez vous acheter? Phonecase, wallet, tshirt…">
                </div>
                <div class="form-group col-sm-4 col-md-5">
                    <input type="text" class="form-control navbar__sneefr__input" placeholder="À proximité de Las Vegas, NV">
                </div>
                <div class="form-group col-sm-1">
                    <button type="submit" class="btn btn-primary navbar__sneefr__search"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <ul class="nav navbar-nav navbar-right">
                @if(!auth()->check())
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
                @else

                    <li class="row visible-xs text-center menu__item--wide menu__item--split" role="presentation">
                        @if (auth()->user()->shop)
                            <a role="menuitem" tabindex="-1"
                               href="{{ route('shops.show', auth()->user()->shop) }}"
                               title="{{  auth()->user()->shop->getName() }}">
                                <img class="menu-icon nav-profile-image img-circle"
                                     src="{{  auth()->user()->shop->getLogo('30x30') }}"
                                     srcset="{{  auth()->user()->shop->getLogo('60x60') }} 2x"
                                     width="30" height="30"
                                     alt="{{  auth()->user()->shop->getName() }}"><br/>
                                {{ auth()->user()->shop->getName() }}
                            </a>
                        @endif
                    </li>

                    <li class="notifications js-notifications {{ setActive('profile.notifications.index', 'active') }}">
                        <a href="{{ route('profiles.notifications.index', auth()->user()) }}" title="@lang('navigation.notifications_title')" class="menu-icon">
                            @if ($notifications)
                                <sup class="notification-badge js-notification-badge">{{ $notifications }}</sup>
                            @endif
                            <i class="fa fa-bell"></i>
                            <span class="visible-xs-inline">@lang('navigation.notifications')</span>
                        </a>
                    </li>
                    <li class="js-messages js-pushes-target-{{ auth()->user()->getRouteKey() }} {{ setActive(['discussions.index', 'discussions.show', 'discussions.ads.index'], 'active') }}">
                        <a class="menu-icon" href="{{ route('discussions.index') }}#latest"
                           title="@lang('navigation.messages_title')">
                            <sup class="notification-badge js-notification-badge {{ $unread ? null : 'hidden' }}">{{ $unread }}</sup>
                            <i class="fa fa-envelope"></i>
                            <span class="visible-xs-inline">@lang('navigation.messages')</span>
                        </a>
                    </li>

                    @if (auth()->user()->shop)
                        <li class="js-messages js-pushes-target-{{ auth()->user()->shop->getRouteKey() }} {{ setActive(['shop_discussions.index', 'shop_discussions.show'], 'active') }}">
                            <a class="menu-icon" href="{{ route('shop_discussions.index', auth()->user()->shop) }}#latest"
                               title="@lang('navigation.messages_title')">
                                <sup class="notification-badge js-notification-badge @if (!$unreadShop) hidden @endif">{{ $unreadShop }}</sup>
                                <span class="label label-primary bottom-label">shop</span>
                                <i class="fa fa-envelope icon--shop"></i>
                                <span class="visible-xs-inline">@lang('navigation.messages')</span>
                            </a>
                        </li>
                    @endif

                    <li role="presentation" class="visible-xs">
                        <a role="menuitem" tabindex="-1" href="{{ route('me.index') }}" title="@lang('navigation.profile_title')" class="menu-icon">
                            <i class="fa fa-cog"></i>
                            @lang('navigation.parameters')
                        </a>
                    </li>

                    @if (auth()->user()->canSeeStats)
                        <li class="visible-xs">
                            <a href="{{ route('admin.users') }}" class="menu-icon">
                                <i class="fa fa-bar-chart"></i>
                                Stats
                            </a>
                        </li>
                    @endif

                    <li class="navbar__avatar">
                        @include('partials.avatar', ['of' => auth()->user()->shop ?? auth()->user(), 'size' => '25x25' ])
                    </li>
                    <li class="dropdown hidden-xs">
                        <a class="navbar__profile dropdown-toggle"
                           data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @unless(auth()->user()->shop)
                                <li><a href="{{ route('pricing') }}" title="@lang('navigation.create_shop_title')">@lang('navigation.create_shop')</a></li>
                            @endunless
                            <li>
                                <a role="menuitem" tabindex="-1" href="{{ route('me.index') }}" title="@lang('navigation.parameters_title')">
                                    @lang('navigation.parameters')
                                </a>
                            </li>
                            <li role="separator" class="divider"></li>
                            @if (auth()->user()->canSeeLogs)
                                <li><a href="{{ url('logs') }}">Logs</a></li>
                            @endif
                            @if (auth()->user()->canSeeStats)
                                <li><a href="{{ route('admin.users') }}">Stats</a></li>
                            @endif
                            @if (auth()->user()->canSeeStats || auth()->user()->canSeeLogs)
                                <li role="separator" class="divider"></li>
                            @endif
                            <li><a href="{{ url('help') }}" title="@lang('navigation.help_title')">@lang('navigation.help')</a></li>
                            <li><a href="{{ url('terms') }}" title="@lang('navigation.terms_title')">@lang('navigation.terms')</a></li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a role="menuitem" tabindex="-1" href="{{ route('logout') }}" title="@lang('navigation.logout_title')">
                                    @lang('navigation.logout')
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li role="presentation" class="visible-xs">
                        <a role="menuitem" tabindex="-1" href="{{ route('logout') }}" title="@lang('navigation.logout_title')" class="menu-icon">
                            <i class="fa fa-sign-out"></i>
                            @lang('navigation.logout')
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<nav class="navbar" role="navigation">
    <div class="container">
        <div class="collapse navbar-collapse navbar__sneefr__collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a class="navbar__sneefr__item{{ (request()->path() == "/") ? '--active' : '' }}" href="{{ route('home') }}">
                        Home
                    </a>
                </li>
                <li>
                    <a class="navbar__sneefr__item{{ (request()->path() == "me") ? '--active' : '' }}" href="{{ route('me.index') }}">
                        My profile
                    </a>
                </li>
                @if(auth()->check())
                    @if( auth()->user()->shop)
                        <li>
                            <a class="navbar__sneefr__item{{ ( url(request()->path() ) == route('shops.show', auth()->user()->shop)) ? '--active' : '' }}"
                               href="{{ route('shops.show', auth()->user()->shop) }}">
                                My Shop
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('items.create') }}" title="@lang('navigation.create_ad_title')"
                               class="navbar__sneefr__item{{ ( url(request()->path() ) == route('items.create')) ? '--active' : '' }}">
                                <i class="fa fa-plus-circle"></i>
                                @lang('navigation.create_ad')
                            </a>
                        </li>
                    @else
                        <li role="presentation" class="visible-xs">
                            <a href="{{ route('pricing') }}" title="@lang('navigation.create_shop_title')"
                               class="navbar__sneefr__item">
                                <i class="fa fa-usd text-warning"></i>
                                @lang('navigation.create_shop')
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
    </div>
</nav>