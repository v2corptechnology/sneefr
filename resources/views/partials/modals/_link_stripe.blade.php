<div class="modal fade" id="link-stripe-modal" tabindex="-1" role="dialog"
     aria-labelledby="writeToLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="writeToLabel">
                    @lang('modal.stripe.header')
                </h4>
            </div>
            <div class="modal-body">
                <p class="text-center">
                    @lang('modal.stripe.message')
                    <br><br>
                    <a href="{{ route('me.show') }}"
                       title="@lang('modal.stripe.btn_action')"
                       class="btn btn-lg btn-primary btn-primary2">
                        @lang('modal.stripe.btn_action')
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
