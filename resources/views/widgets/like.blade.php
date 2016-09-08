<form action="{{ route('likes.store') }}" method="post" class="like-form js-like-form">

    {!! csrf_field() !!}

    <input type="hidden" name="payload" value="{{ $item->getPayload() }}">

    <button type="submit" class="action__like {{ $item->liked() ? 'action__like--active' : null }}">
        <img src="{{ asset('img/pig.svg') }}" width="16" alt="sneefR" class="hero__img">
        <span class="js-like-text"
              data-unlike-text="@lang('common.like.unlike')"
              data-like-text="@lang('common.like.like')">
            @lang( $item->liked() ? 'common.like.unlike' : 'common.like.like')
        </span>
    </button>

</form>


