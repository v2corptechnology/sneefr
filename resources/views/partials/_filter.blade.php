<form class="{{ $classes ?? null }}" action="{{ $route ?? null }}" method="get">
    <div class="form-group">
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" name="q"
                   placeholder="@lang('button.filter.placeholder')"
                   value="{{ $q ?? $filter ?? null }}">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </div>
    </div>
</form>
