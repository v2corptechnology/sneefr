<div class="user {{ $modifiers ?? null }}" itemprop="author">

    @unless (isset($showName) && $showName == false)
        <a class="user__name" href="{{ route('profiles.show', $user) }}"
           title="@lang('ad.show_profile_title', ['name' => $user->present()->givenName()])">
            {{ $user->present()->givenName() }}
        </a>
    @endunless

    @unless (isset($showImage) && $showImage == false)
        <a class="user__picture" href="{{ route('profiles.show', $user) }}"
           title="@lang('ad.show_profile_title', ['name' => $user->present()->givenName()])">
            {!! HTML::profilePicture($user->socialNetworkId(), $user->present()->givenName(), $size ?? 60, ['user__image']) !!}
        </a>
    @endunless

    @unless (isset($showRank) && $showRank == false)
        <div class="user__rank user__rank--{{ $user->getRank() }}"
              title="@lang('rank.'.$user->getRank())">
            <span class="user__rank-label">@lang('rank.'.$user->getRank())</span>
        </div>
    @endunless
</div>
