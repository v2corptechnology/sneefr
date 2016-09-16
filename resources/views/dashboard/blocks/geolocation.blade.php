<h1 class="block-title">
    <span class="block-title__main">@lang('dashboard.ask_location_header')</span>
</h1>

<p class="text-muted">
    @lang('dashboard.ask_location_text', ['url' => route('profiles.show', auth()->user()) . '#settings'])
</p>
