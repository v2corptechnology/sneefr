<?php $recipient = $currentDiscussion->recipient();?>
<nav class="answer-container navbar navbar-fixed-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                @if (!$recipient->trashed())
                    <form class='ajax-message answer js-answer'
                          action="{{ route('messages.store', $currentDiscussion->id()) }}"
                          method="POST"
                          data-js-discussion-id="{{ $currentDiscussion->id() }}"
                          data-js-mark-read-url="{{ route('discussions.markRead', $currentDiscussion->id()) }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="recipient_identifier" value="{{ $recipient->getRouteKey() }}">
                        <div class="form-group">
                            <label class="control-label sr-only" for="message-body">
                                @lang('message.reply_label')
                            </label>

                            <?php
                                $isShop = Route::is('shop_discussions.*');
                                $hasReplies = $currentDiscussion->messagesOnlyTo(auth()->user()->id)->count();
                                $hasPosts = !$currentDiscussion->messagesOnlyTo($recipient->id)->count();
                                $isLocked = !$hasReplies && !$isShop;

                                $placeholder = trans('message.reply_unlocked_placeholder');
                                if ($isLocked) {
                                    $placeholder = trans('message.reply_locked_placeholder', ['name' => $recipient->present()->givenName()]);
                                } elseif ($hasPosts) {
                                    $placeholder = trans('message.reply_first_reply_placeholder', ['name' => $recipient->present()->givenName()]);
                                }
                            ?>
                            <textarea class="answer-zone form-control autosend js-answer-body"
                                id="body" name="body" cols="30" rows="5"
                                data-placeholder-unlocked="@lang('message.reply_unlocked_placeholder')"
                                placeholder="{{ $placeholder }}"
                                @if ($isLocked) disabled="disabled" @endif></textarea>

                            <input type="hidden" name="discussion_id" value="{{ $currentDiscussion->id() }}">

                            <div class="text-muted text-right autosubmit hidden-xs hidden-sm">
                                <small>@lang('message.autosend')</small>
                            </div>
                            <div class="text-muted text-right saving hidden-xs hidden">
                                <small>
                                    <span class="fa fa-spinner fa-spin" aria-hidden="true"></span>
                                    @lang('message.sending')
                                </small>
                            </div>

                            <div class="visible-xs visible-sm clearfix text-right" style="margin-top: 0.5rem;">
                                <span class="autosubmit">
                                    <button type="submit" class="btn btn-default btn-xs">
                                        @lang('message.answer_button')
                                    </button>
                                </span>
                                <span class="saving hidden">
                                    <button type="button" class="btn btn-default btn-xs" disabled>
                                        <span class="fa fa-spinner fa-spin" aria-hidden="true"></span>
                                        @lang('message.sending')
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</nav>
