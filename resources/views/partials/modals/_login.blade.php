<div class="modal fade" id="LoginBefore" tabindex="-1" role="dialog"
     aria-labelledby="writeToLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">@lang('modal.close')</span>
                </button>
                <h4 class="modal-title" id="writeToLabel">
                    @lang('modal.login.header')
                </h4>
            </div>
            <div class="modal-body">
                <p class="text-center">
                    @lang('modal.login.connect_first')
                    <br><br>
                    <a href="{{ url('/login') }}"
                       title="@lang('modal.write.btn_connect_title')"
                       class="btn btn-lg btn-primary btn-primary2">
                       @lang('modal.login.btn_connect')
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
