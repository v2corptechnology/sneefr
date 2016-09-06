<a class="activity__author" title="{{ $author->getName() }}"
   href="{{ route('shops.show', $author) }}">
    <img src="{{ $author->getLogo('40x40') }}" srcset="{{ $author->getLogo('80x80') }} 2x" alt="{{ $author->getName() }}">
</a>

<div class="activity__title">
    <h2 class="activity__heading">
        <a title="{{ $author->getName() }}" href="{{ route('shops.show', $author) }}">
            {{ $author->getName() }}
        </a>
        @lang("dashboard.activity.{$item->type}.head", $headData)
    </h2>
    {!! HTML::time($item->value->created_at) !!}
</div>
