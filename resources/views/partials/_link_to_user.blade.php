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
            {!! HTML::profilePicture($user->getSocialNetworkId(), $user->present()->givenName(), $size ?? 60, ['user__image']) !!}
        </a>
    @endunless
</div>
