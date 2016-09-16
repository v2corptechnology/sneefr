<form class="follow" action="{{ route('follows.store', [0, 'type' => 'user', 'item' => $user]) }}" method="POST">
    {!! csrf_field() !!}
    <button type="submit" class="{{ $btnClasses ?? 'btn btn-default btn-xs pull-right' }}"
            title="@lang('button.follow_title', ['name' => $user->present()->givenName()])">
        <i class="fa fa-plus-circle text-success"></i>
        @lang('button.follow')
    </button>
</form>
