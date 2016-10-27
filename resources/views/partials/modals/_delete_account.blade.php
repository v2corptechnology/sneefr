<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                @lang('modal.delete_account_header')
            </div>
            <div class="modal-body">
                <p>@lang('modal.delete_account_body')</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(['route' => ['profiles.destroy', auth()->user()], 'method' => 'delete']) !!}
                <button class="btn btn-danger" type="submit">@lang('modal.delete_account_confirm')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('modal.delete_account_cancel')</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
