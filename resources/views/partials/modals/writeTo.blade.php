<div class="modal fade" id="writeTo" tabindex="-1" role="dialog"
     aria-labelledby="writeToLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <form action="" method="POST">

                {!! csrf_field() !!}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">@lang('modal.close')</span>
                    </button>
                    <h4 class="modal-title" id="writeToLabel">
                        Write to {{ $shop->getName() }}
                    </h4>
                </div>

                @if (auth()->check())
                    <div class="modal-body">
                        <label class="control-label sr-only" for="message-body"> 
                            Write to {{ $shop->getName() }}
                        </label> 
                        <textarea class="form-control" rows="5" cols="10" id="message-body"
                                  name="message-body"
                                  placeholder="Saying Hello is the best way to start a conversation!"
                                  required></textarea> 
                    </div>

                    <div class="modal-footer">
                        <a href="#" class="btn" data-dismiss="modal">Close</a>
                        <a href="#" class="btn btn-primary">Send</a>
                    </div>
                @else
                    <div class="modal-body">
                        Please login before.
                    </div>

                    <div class="modal-footer">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
