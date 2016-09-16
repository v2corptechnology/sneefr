<div class="modal fade" id="profilePicture" tabindex="-1" role="dialog"
     aria-labelledby="profilePictureLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span
                            class="sr-only">@lang('modal.close')</span></button>
                <h4 class="modal-title"
                    id="profilePictureLabel">{{ $shop->getName() }}</h4>
            </div>
            <div class="modal-body text-center">
                <img src="{{ $shop->getLogo('400x400') }}"
                     srcset="{{ $shop->getLogo('800x800') }} 2x" width="400"
                     height="400" alt="{{ $shop->getName() }}">
            </div>
        </div>
    </div>
</div>
