@if ($detail == 'notes')
    <span title="@choice('ad.profile_positive_evaluations_title',
                    $ad->seller->evaluations->positives()->count(),
                    ['num' => $ad->seller->evaluations->positives()->count()])
        &nbsp;
        @choice('ad.profile_negative_evaluations_title',
            $ad->seller->evaluations->negatives()->count(),
            ['num' => $ad->seller->evaluations->negatives()->count()])">
        @choice('ad.profile_evaluations_percentage',
            $ad->seller->evaluations->ratio(),
            ['percentage' => $ad->seller->evaluations->ratio() * 100])
    </span>
@elseif ($detail == 'proximity')
    <span title="{{ $ad->location() }}">{{ $ad->present()->distance() }}</span>
    <small>&bull;</small>
    <i class="fa fa-map-marker"></i> {{ $ad->location() }}
@elseif ($detail == 'condition')
    @lang('condition.'.$ad->getConditionId().'_alt')
@else
    {!!  HTML::time($ad->created_at) !!}
@endif
