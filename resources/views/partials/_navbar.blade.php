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

                    <li class="navbar__avatar hidden-xs">
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
    <div class="navbar__menu">
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
                            <li class="js-messages js-pushes-target-{{ auth()->user()->shop->getRouteKey() }} ">
                                <a class="navbar__sneefr__item{{ setActive(['shop_discussions.index', 'shop_discussions.show'], '--active') }}"
                                   href="{{ route('shop_discussions.index', auth()->user()->shop) }}#latest"
                                   title="@lang('navigation.messages_title')">
                                    <sup class="notification-badge js-notification-badge {{ $unreadShop ? '' : 'hidden' }}">{{ $unreadShop }}</sup>
                                    @lang('navigation.messages')
                                </a>
                            </li>
                        @else
                            <li class="js-messages js-pushes-target-{{ auth()->user()->getRouteKey() }} ">
                                <a class="navbar__sneefr__item{{ setActive(['discussions.index', 'discussions.show', 'discussions.ads.index'], '--active') }}"
                                   href="{{ route('discussions.index') }}#latest"
                                   title="@lang('navigation.messages_title')">
                                    <sup class="notification-badge js-notification-badge {{ $unread ? '' : 'hidden' }}">{{ $unread }}</sup>
                                    @lang('navigation.messages')
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
