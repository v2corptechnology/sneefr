<?php
    if(!isset($type)) $type = "" ;
    switch ($type) {
        case 'referrals':
            $empty_text =  '';
            $network_head = trans('profile.networks.referrals');
            break;

        case 'followers':
            $empty_text =  'profile.networks.following_empty_text';
            $network_head = 'profile.networks.followed_head';
            break;

        case 'followed':
            $empty_text =  'followed_empty_text';
            $network_head = 'profile.networks.following_head';
            break;

        default:
            $empty_text = "";
            $network_head = "";
            break;
    }
?>
<div class="col-md-12">
    <h1 class="h5" id="followed">
        @choice(
            $network_head,
            count($follows),
            ['name' => $person->present()->givenName(), 'nb' => $follows->count()]
        )
    </h1>
    @if ($follows->isEmpty())
        <p class="text-muted">@lang($empty_text, ['name' => $person->present()->givenName()])</p>
    @else
        <div class="row">
            <ol class="profile-list">
                @foreach ($follows as $follow)
                    <li class="profile-list__item col-sm-6">
                        <div class="content">
                            <a href="{{ route('profiles.show', $follow) }}" class="person--small"
                               title="@lang('profile.networks.profile_title', ['name' => $follow->present()->givenName()])">
                                {!! HTML::profilePicture($follow->socialNetworkId(), $follow->present()->fullName(), 30, ['person__image']) !!}
                                {{ $follow->present()->fullName() }}
                            </a>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    @endif
</div>
