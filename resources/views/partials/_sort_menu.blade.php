<?php

    // Is this key sortable, if it is, which is the default direction
    $sortable = [
        'relevance'  => false,
        'evaluation' => 'desc',
        'price'      => 'asc',
        'proximity'  => false,
        'date'       => 'desc',
        'condition'  => 'desc',
    ];

?>
<ul class="menu menu--tools">
    <li class="menu__item dropdown">
        <a class="menu__item-link dropdown-toggle" id="sortMenu"
           data-toggle="dropdown" aria-expanded="true">
            @lang('search.sort')
            <span class="caret"></span>
        </a>

        <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="sortMenu">
            @foreach($sortable as $sortableName => $sortableOrder)
                <?php
                    $class = ($sort == $sortableName) ? 'menu__item-link--active' : null;

                    $sortParams = ['sort' => $sortableName, 'order' => null];

                    if ($sortableOrder) {
                        if ($order) {
                            $sortParams['order'] = ($order == 'desc' ? 'asc' : 'desc');
                        } else {
                            $sortParams['order'] = $sortableOrder;
                        }
                    }
                ?>
                <li role="presentation">
                    <a class="menu__item-link {{ $class }}" role="menuitem" tabindex="-1"
                       href="{{ route('search.index', change_url_param($urlParams, $sortParams)) }}">
                        @lang('search.sort_by_'.$sortableName)
                    </a>
                </li>
            @endforeach
        </ul>
    </li>
</ul>
