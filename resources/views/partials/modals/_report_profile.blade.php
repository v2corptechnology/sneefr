<div class="modal fade" id="reportProfile" tabindex="-1" role="dialog" aria-labelledby="reportProfileLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">@lang('modal.close')</span></button>
                <h4 class="modal-title" id="reportProfileLabel">@lang('modal.report_profile_header', ['name' => $name])</h4>
            </div>
            <div class="modal-body">
                <p>@lang('modal.report_profile_body')</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(['route' => ['report.store']]) !!}
                <input type="hidden" name="id" value="{{ $id }}">
                <input type="hidden" name="type" value="profile">
                <button class="btn btn-danger" type="submit">@lang('modal.report_profile_confirm')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('modal.report_profile_cancel')</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>