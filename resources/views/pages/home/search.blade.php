<div class="hidden-xs">
    <form action="{{ route('search.index') }}" method="GET" role="search">
        <div class="input-group input-group-lg">
            <input type="search" name="q"
                   class="form-control js-add-autocompletion home-search"
                   placeholder="@lang('login.btn_search_placeholder')"
                   autofocus>
                    <span class="input-group-btn">
                        <button class="btn btn-primary btn-primary2"
                                type="submit">@lang('login.btn_search')</button>
                    </span>
        </div>
    </form>
</div>
<div class="visible-xs">
    <form action="{{ route('search.index') }}" method="GET" role="search">
        <div class="input-group">
            <input type="search" name="q"
                   class="form-control js-add-autocompletion"
                   placeholder="@lang('login.btn_search_placeholder')">
                    <span class="input-group-btn">
                        <button class="btn btn-primary btn-primary2"
                                type="submit">@lang('login.btn_search')</button>
                    </span>
        </div>
    </form>
</div>
