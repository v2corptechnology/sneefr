<div class="container">
    <div class="row">
        <ul class="categories">
            @foreach ($categories as $highlight)
                <li class="categories__item">
                    <a class="category category--{{ $highlight['class'] }}"
                       href="{{ route('search.index') }}?categories={{ json_encode($highlight['ids']) }}">
                        <strong class="category__heading">@lang('category.'.$highlight['parentId'])</strong>
                            <span class="category__details">
                                @choice('login.articles_in_category',
                                    $highlight['ads']->count(),
                                    ['nb' => $highlight['ads']->count()])
                            </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
