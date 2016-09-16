<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">@lang('modal.close')</span>
            </button>
            <h4 class="modal-title"
                id="shareAdLabel">@lang('modal.share.title', ['title' => $ad->getTitle()])</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <div class="input-group">
                        <input type="text" class="share-url form-control" value="{{ route('ad.show', $ad) }}">
                        <span class="input-group-btn">
                            <button class="copy__btn btn btn-default"
                                    type="button"
                                    title="Copy to clipboard"
                                    data-clipboard-target=".share-url"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    data-success-message="Copied!"
                                    data-error-message="Press Ctrl+C to copy">
                            <img width="14" src="data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9IjEwMjQiIHdpZHRoPSI4OTYiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+ICA8cGF0aCBkPSJNMTI4IDc2OGgyNTZ2NjRIMTI4di02NHogbTMyMC0zODRIMTI4djY0aDMyMHYtNjR6IG0xMjggMTkyVjQ0OEwzODQgNjQwbDE5MiAxOTJWNzA0aDMyMFY1NzZINTc2eiBtLTI4OC02NEgxMjh2NjRoMTYwdi02NHpNMTI4IDcwNGgxNjB2LTY0SDEyOHY2NHogbTU3NiA2NGg2NHYxMjhjLTEgMTgtNyAzMy0xOSA0NXMtMjcgMTgtNDUgMTlINjRjLTM1IDAtNjQtMjktNjQtNjRWMTkyYzAtMzUgMjktNjQgNjQtNjRoMTkyQzI1NiA1NyAzMTMgMCAzODQgMHMxMjggNTcgMTI4IDEyOGgxOTJjMzUgMCA2NCAyOSA2NCA2NHYzMjBoLTY0VjMyMEg2NHY1NzZoNjQwVjc2OHpNMTI4IDI1Nmg1MTJjMC0zNS0yOS02NC02NC02NGgtNjRjLTM1IDAtNjQtMjktNjQtNjRzLTI5LTY0LTY0LTY0LTY0IDI5LTY0IDY0LTI5IDY0LTY0IDY0aC02NGMtMzUgMC02NCAyOS02NCA2NHoiIC8+PC9zdmc+" alt="Copy">
                            </button>
                        </span>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <a class="btn btn-facebook form-control btn-md"
                       href="{{ $shareUrl }}"
                       title="@lang('modal.share.btn_facebook_title')">
                        <i class="fa fa-facebook-square"></i>
                        @lang('modal.share.btn_facebook')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
