<div class="activity__footer @if($item->value->is_hidden_from_friends || !count($item->likes)) hidden @endif">
    @if ($item->value->is_hidden_from_friends)
        <span class="text-muted">{!! trans('action.private_ad') !!}</span>
    @else
        <?php $displayed = $item->likes ? $item->likes->take(3)->all() : []; ?>
    
        {!! implode(', ', array_map(function($like) {
            return '<a href="'.route('profiles.show', $like['user']->getRouteKey()).'">'.
                    $like['user']->present()->fullName().'</a>';
        }, $displayed)) !!}

        <?php $others = $item->likes ? $item->likes->slice(3)->all() : []; ?>
        @if (count($others))
            et <span class="pop" data-toggle="popover"
                     data-placement="top"
                     data-content="@foreach($others as $like){!! $like['user']->present()->fullName() !!} <br> @endforeach">
            {{ count($others) }} autre{{ count($others) > 1 ? 's' : '' }}
            </span>.
        @endif
    @endif
</div>
