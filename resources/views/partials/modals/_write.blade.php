<?php

if ($recipient instanceof \Sneefr\Models\Shop) {
    $recipientName = $recipient->getName();
    $recipientIdentifier = $recipient->getRouteKey();
    $recipientIsShop = true;
} else {
    $recipientName = $recipient->present()->givenName();
    $recipientIdentifier = $recipient->getRouteKey();
    $recipientIsShop = false;
}

?>

<div class="modal fade" id="writeTo" tabindex="-1" role="dialog"
     aria-labelledby="writeToLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('messages.store') }}" method="POST">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">@lang('modal.close')</span>
                    </button>
                    <h4 class="modal-title" id="writeToLabel">
                        @lang('modal.write.header', ['name' => $recipientName])
                    </h4>
                </div>
                <div class="modal-body">
                    @if (auth()->check())
                        @if(auth()->user()->inCompleteInfo())
                            <p class="text-center">
                                @lang('modal.write.complete_info_first')
                                <br><br>
                                <a href="{{ url('/me') }}"
                                   title="@lang('modal.write.btn_settings_title')"
                                   class="btn btn-lg btn-primary btn-primary2">@lang('modal.write.btn_settings')</a>
                            </p>
                        @else
                            <label class="control-label sr-only" for="message-body"> 
                                @lang('modal.write.body_label')
                            </label> 
                            <textarea class="form-control" rows="5" cols="10"
                                      id="body" name="body"
                                      placeholder="@lang('modal.write.body_placeholder')"
                                      required></textarea> 
                        @endif
                    @else
                        <p class="text-center">
                            @lang('modal.write.connect_first')
                            <br><br>
                            <a href="{{ route('login') }}"
                               title="@lang('modal.write.btn_connect_title')"
                               class="btn btn-lg btn-primary btn-primary2">@lang('modal.write.btn_connect')</a>
                        </p>
                    @endif
                </div>
                @if(auth()->check() && !auth()->user()->inCompleteInfo())
                    <div class="modal-footer">
                        <input type="hidden" name="ad_id" id="ad_id"
                               value="{{ $adId }}">  
                        <input type="hidden" name="recipient_identifier"
                               value="{{ $recipientIdentifier }}"> 
                        <input type="hidden" name="recipient_is_shop"
                               value="{{ $recipientIsShop }}"> 
                        <a href="#" class="btn btn-link" data-dismiss="modal"
                           title="@lang('modal.write.btn_cancel_title')">@lang('modal.write.btn_cancel')</a>
                        <button type="submit" class="btn btn-success"
                                title="@lang('modal.write.btn_send_title')"> 
                            <i class="fa fa-envelope"></i> @lang('modal.write.btn_send')
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
