@if ($gamificator->hasNextRank())
    <div class="gamification clearfix hidden-xs hidden-sm">
        <div class="gamification__counter">
            <span class="gamification__count"
                  style="color:{{ $gamificator->getPercentageColor() }}">{{ $gamificator->getPercentageDone() }}%</span>
            <svg height="100" width="100">
                <defs>
                    <clipPath id="cut-off-bottom">
                        <rect x="0" y="0" width="100" height="{{ 100 - $gamificator->getPercentageDone() }}"/>
                    </clipPath>
                </defs>
                <circle r="45" cx="50" cy="50" stroke-width="10" stroke="#DCDCDC" fill="transparent"></circle>
                <circle r="45" cx="50" cy="50" stroke-width="9" stroke="{{ $gamificator->getPercentageColor() }}" fill="transparent"></circle>
                <circle r="45" cx="50" cy="50" stroke-width="9" stroke="#EEE" fill="transparent"
                        clip-path="url(#cut-off-bottom)"></circle>
            </svg>
        </div>
        <p>
            @lang('rank.'.$gamificator->getRank().'_to_'.$gamificator->getNextRank(), [
                'current' => trans('rank.'.$gamificator->getRank()),
                'next' =>trans('rank.'.$gamificator->getNextRank())
            ])
        </p>
        <ul>
            @foreach ($gamificator->getAchievedObjectives() as $achievedObjective)
                <li class="text-muted"><s>@lang('rank.objectives.'.$achievedObjective)</s></li>
            @endforeach
            @foreach ($gamificator->getMissingObjectives() as $missingObjective)
                <li>
                    @if ($gamificator->getObjectiveUrl($missingObjective, auth()->user()))
                        <a href="{{ $gamificator->getObjectiveUrl($missingObjective, auth()->user()) }}">
                            @lang('rank.objectives.'.$missingObjective)</a>
                    @else
                        @lang('rank.objectives.'.$missingObjective)
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif
