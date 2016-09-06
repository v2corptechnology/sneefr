<div class="gamification gamification--alert bg-warning text-warning clearfix visible-xs visible-sm">
    <div class="gamification__progressbar">
        <div class="gamification__progress" style="width:{{ $gamificator->getPercentageDone() }}%"></div>
    </div>
    <p>
        @lang('rank.'.$gamificator->getRank().'_to_'.$gamificator->getNextRank(), [
            'current' => trans('rank.'.$gamificator->getRank()),
            'next' =>trans('rank.'.$gamificator->getNextRank())
        ])
        <span class="gamification__objective--lower">
            <?php
                $objectives = $gamificator->getMissingObjectives();
                $firstMissing = array_shift($objectives);
                $url = $gamificator->getObjectiveUrl($firstMissing);
            ?>
            @if ($url)
                <a href="{{ $url }}">
                    @lang('rank.objectives.'.$firstMissing)</a>.
            @else
                @lang('rank.objectives.'.$firstMissing).
            @endif
        </span>
    </p>
</div>
