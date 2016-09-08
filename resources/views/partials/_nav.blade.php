<nav class="navbar navbar-default {{ setActive(
        ['discussions.index', 'discussions.show', 'discussions.ads.index', 'shop_discussions.index', 'shop_discussions.show'],
        'navbar-fixed-top') }}" role="navigation">
    <div class="container">

        <div class="navbar-header">
            @if (auth()->check())
                <button type="button"
                        class="navbar-evil-hamburger navbar-toggle navbar-toggle-menu collapsed"
                        data-toggle="collapse" data-target="#menu">
                @if ($notifications || $unread)
                        <sup class="notification-badge">{{ $notifications + $unread }}</sup>
                    @endif
                    <span class="sr-only">@lang('navigation.toggle')</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            @endif

            @if (!auth()->check())
                <div class="pull-right visible-xs">
                    <div class="navbar-btn navbar-right">
                        <a href="{{ route('login') }}" title="@lang('navigation.connect_title')" class="btn btn-primary btn-sm">
                            <i class="fa fa-facebook"></i> @lang('navigation.connect')
                        </a>
                    </div>
                </div>
            @endif

                <button type="button"
                        class="navbar-toggle navbar-toggle-search navbar-toggle-menu collapsed"
                        data-toggle="collapse" data-target="#search">
                <span class="fa fa-search"></span>
            </button>

            <a class="navbar-brand" href="{{ route('home') }}" style="position: relative">
                <img src="{{ base64Image('img/b64/pig_extended.png') }}" width="101" height="20"
                     srcset="{{ asset('img/pig_extended@2x.png') }} 2x" alt="sneefR's pig">
                <span class="label label-warning" style="position:absolute; bottom: 0.2rem;font-size:0.7rem; left: 11rem">Beta</span>
            </a>

            <form class="navbar-form pull-left" role="search"
                  action="{{ route('search.index') }}" method="GET">
                <div class="form-group">
                    <div class="input-group input-group-sm">
                        <!-- results="5" autosave="search" -->
                        <input type="search" name="q" class="form-control js-add-autocompletion nav-search"
                               placeholder="@lang('navigation.search_placeholder')"
                               value="{{ $query }}" id="q">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="fa fa-search"></i>
                                <span class="sr-only">@lang('navigation.search_placeholder')</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="collapse {{ setActive('search.index', 'in') }} navbar-left navbar-search-wide" id="search">
            <form class="navbar-form-special" role="search"
                  action="{{ route('search.index') }}" method="GET">
                <div class="form-group">
                    <div class="input-group input-group-sm">
                        <input type="search" name="q" class="form-control js-add-autocompletion nav-mobile-search"
                               placeholder="@lang('navigation.search_placeholder')"
                               value="{{ $query or '' }}">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="fa fa-search"></i>
                                <span class="sr-only">@lang('navigation.search_placeholder')</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if (auth()->check())
            <ul class="collapse navbar-collapse nav navbar-nav navbar-right" id="menu">
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

                @if(auth()->user()->shop)
                    <li>
                        <a href="{{ route('ad.create') }}" title="@lang('navigation.create_ad_title')"
                           class="menu-icon {{ setActive('ad.create', 'active') }}">
                            <i class="fa fa-plus-circle"></i>
                            @lang('navigation.create_ad')
                        </a>
                    </li>
                @else
                    <li role="presentation" class="visible-xs">
                        <a href="{{ route('pricing') }}" title="@lang('navigation.create_shop_title')"
                           class="menu-icon">
                            <i class="fa fa-usd text-warning"></i>
                            @lang('navigation.create_shop')
                        </a>
                    </li>
                @endif

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
                <li role="presentation">
                    @include('partials.avatar', ['of' => auth()->user()->shop ??  auth()->user(), 'size' => '25x25' ])
                </li>
                <li role="presentation" class="dropdown hidden-xs">
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
            </ul>
        @else
            <div class="navbar-right hidden-xs navbar-login-btn">
                <div class="navbar-btn hidden-xs">
                    <a href="{{ route('login') }}" title="@lang('navigation.connect_title')" class="btn btn-primary btn-sm">
                        <i class="fa fa-facebook"></i> @lang('navigation.connect')
                    </a>
                </div>
            </div>
        @endif

    </div>
</nav>
