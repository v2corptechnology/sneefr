<div class="panel panel-default" id="payment">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-cc-stripe"></i>
            @lang('profile.settings.payment.heading')
        </h3>
    </div>
    <div class="panel-body">
        @if (! auth()->user()->payment)

            <p>@lang('profile.settings.payment.explain')</p>

            <p>
                <a href="{{ $authorizeUrl }}" class="stripe-connect" title="@lang('profile.settings.payment.btn_link_title')">
                    <span class="stripe-connect__text">@lang('profile.settings.payment.btn_link')</span>
                </a>
            </p>

            <p class="bg-warning text-warning">@lang('profile.settings.payment.be_careful')</p>
        @else
            <p>@lang('profile.settings.payment.linked')</p>
        @endif
    </div>
</div>
