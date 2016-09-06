<h1 class="block-title">
    <a class="block-title__main" href="{{ route('profiles.places.index', auth()->user()) }}"
       title="@lang('dashboard.places_of_interest_head_title')">
        @lang('dashboard.places_of_interest_head')
    </a>
</h1>

{{--
    For each location, a link will point to the search page by specifying
    the coordinates and the type of the location.
 --}}
<ul class="poi-list list-unstyled">
    @foreach ($places as $place)
        <li>
            <a title="{{ $place->getLongName() }}"
               href="{{ route('places.show', $place) }}">
                <span class="fa fa-map-marker"></span> {{ $place->getName() }}
            </a>
        </li>
    @endforeach
</ul>
