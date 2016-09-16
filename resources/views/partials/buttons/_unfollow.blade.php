<form class="unfollow" action="{{ route('follows.destroy', [0, 'type' => 'user', 'item' => $user]) }}" method="POST">
    {!! csrf_field() !!}
    {!! method_field('DELETE') !!}
    <button type="submit" class="{{ $btnClasses ?? 'btn btn-default btn-xs pull-right' }}"
            title="@lang('button.unfollow_title', ['name' => $user->present()->givenName()])">
        <i class="fa fa-times-circle text-danger"></i>
        @lang('button.unfollow')
    </button>
</form>
