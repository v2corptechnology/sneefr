@if(isset($follows) && $follows->count() )
	<?php
		$maxItems = $maxItems ?? 9;
		$maxMobileItems = $maxMobileItems ?? 3;
	?>
	<div class="col-sm-12">
		<div class="box followers">
			<div class="followers__title">
				<span class="followers__title__item">{{ $follows->count() }}</span>
				<span class="followers__title__item followers__title__item--botom">@lang('profile.networks.'.$type)</span>
				<div class="clearfix"></div>
			</div>

			<div class="followers__list">
				<?php $i = 0; ?>
				@foreach ($follows->take($maxItems) as $follow)
					<?php $i++; ?>
					<div class="followers__item {{ ($i > $maxMobileItems) ? 'hidden-xs' : '' }}">
						<a href="{{ route('profiles.show', $follow) }}"
							title="@lang('profile.networks.profile_title', ['name' => $follow->present()->givenName()])" >

							{!! HTML::profilePicture($follow->getSocialNetworkId(), $follow->present()->fullName(), 70, ['followers__img img-responsive']) !!}

						</a>
					</div>
				@endforeach

				@if($follows->count() > $maxItems)
					<div class="followers__item hidden-xs">
						<a href="{{ route('profiles.networks.'.$type, $person) }}" title="@lang('profile.sidebar.me.notifications_title')">
							<div class="followers__img followers__img--plus bg-primary">
								{{ $follows->count() - $maxItems }}+
							</div>

						</a>
					</div>
				@endif

				@if($follows->count() > $maxMobileItems)
					<div class="followers__item visible-xs-inline-block">
						<a href="{{ route('profiles.networks.'.$type, $person) }}" title="@lang('profile.sidebar.me.notifications_title')">
							<div class="followers__img followers__img--plus bg-primary">
								{{ $follows->count() - $maxMobileItems }}+
							</div>

						</a>
					</div>
				@endif
			</div>

		</div>
	</div>

@endif
