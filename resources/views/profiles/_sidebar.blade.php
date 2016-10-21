@if (!$searches->isEmpty())
    <ul class="summary">
        @foreach ($searches as $search)
            <li class="summary__item">

                @if ($isMine)
                    {!! Form::open(['route' => ['search.destroy', $search], 'method' => 'delete']) !!}
                    <button type="submit" class="close" title="@lang('profile.search_delete_title')">
                        <span aria-hidden="true">&times;</span><span class="sr-only">@lang('modal.close')</span>
                    </button>
                    {!! Form::close() !!}
                @endif

                <h2 class="summary__head">
                    <i class="fa fa-search summary__icon"></i>
                    {{ $search->body }}
                </h2>
            </li>
        @endforeach
    </ul>
@endif
