<form action="{{ route('likes.store') }}" method="post" class="like-form js-like-form">

    {!! csrf_field() !!}

    <input type="hidden" name="payload" value="{{ $item->getPayload() }}">

    <button type="submit" class="action__like {{ $item->liked() ? 'action__like--active' : null }}">
        <i class="fa fa-heart"></i>
        <span class="js-like-text"
              data-unlike-text="@lang('dashboard.activity.unlike')"
              data-like-text="@lang('dashboard.activity.like')">
            @lang( $item->liked() ? 'dashboard.activity.unlike' : 'dashboard.activity.like')
        </span>
    </button>

</form>


