<div class="box">

    <form class="subscription-plan" action="{{ route('subscriptions.store') }}" method="POST">

        {!! csrf_field() !!}

        <noscript>
            <p class="bg-danger text-danger">@lang('payments.subscribe.no_script_warning')</p>
        </noscript>

        <div class="form-group">
            <label>@lang('payments.subscribe.heading_plan') </label>
            <small>
                <a href="{{ route('pricing', ['redirect' => request()->path()]) }}#pricing"
                   title="@lang('payments.subscribe.heanding_plan_tip_title')">
                    @lang('payments.subscribe.heanding_plan_tip')
                </a>
            </small>
            <br>

            <label class="radio-inline">
                <input type="radio" name="plan" id="plan-monhly" value="monthly" required
                       {{ request()->get('plan', session('plan')) == 'monthly' ? 'checked' : '' }}>
                @lang('payments.subscribe.monthly_cost', ['price' => trans('pricing.shop_monthly_price')])
            </label>
            <label class="radio-inline">
                <input type="radio" name="plan" id="plan-yearly" value="yearly_launch_offer" required
                        {{ request()->get('plan', session('plan', 'yearly')) == 'yearly' ? 'checked' : '' }}>
                @lang('payments.subscribe.yearly_cost', ['price' => trans('pricing.shop_yearly_price')])
            </label>
        </div>

        <div class="form-group hidden">
            <label for="coupon">@lang('payments.subscribe.coupon_label')</label>
            <input class="form-control js-coupon" type="text" name="coupon" value="WELOVEYOU"
                   placeholder="@lang('payments.subscribe.coupon_placeholder')">
        </div>

        <button class="btn btn-primary js-add-stripe" type="submit"
                data-image="{{ asset('img/particular_pig.png') }}"
                data-locale="{{ auth()->user()->getLanguage() }}"
                data-email="{{ auth()->user()->getEmail() }}"
                data-currency="USD"
                data-name="@lang('payments.subscribe.subscription_name')"
                data-description="@lang('payments.subscribe.subscription_description')">
            @lang('payments.subscribe.btn_subscribe')
        </button>

    </form>

</div>
