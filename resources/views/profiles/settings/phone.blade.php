<div class="panel panel-default">
    
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-phone"></i>
            @lang('profile.parameters.phone_title')
        </h3>
    </div>

    <div class="panel-body">
        <form action="{{ route('profiles.settings.update', auth()->user()) }}" method="POST">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}

            <input type="hidden" name="settings_category" value="{{ (Session::has('phoneConfirm')) ? 'phoneConfirm' : 'handlingPhone' }}">

            <div class="form-group {{ ($errors->has('phone')) ? 'has-error' :'' }}">
                @if (auth()->user()->phone->isVerified())
                    <label for="inputPhone">@lang('profile.parameters.phone_verified_label')</label>
                @else
                    <label for="inputPhone">@lang('profile.parameters.phone_label')</label>
                @endif
                <input type="tel" name="phone" id="inputPhone"
                       class="form-control" required
                       value="{{ old('phone', auth()->user()->present()->protectedPhone()) }}"
                       placeholder="e.g. +1 702 123 4567" {{ (Session::has('phoneConfirm')) ? 'disabled' : '' }}>
                @if ($errors->has('phone'))
                    <p class="help-block">{{ $errors->first('phone') }}</p>
                @endif
            </div>
            
            @if(Session::has('phoneConfirm') || $errors->has('code_confirm'))

                <div class="form-group {{ ($errors->has('phoneConfirm')) ? 'has-error' :'' }} " >
                    <label for="phoneConfirm">@lang('profile.parameters.sms_code')</label>
                    <input type="number" name="code_confirm" id="phoneConfirm"
                           class="form-control" required="required"
                           placeholder="####">

                    @if ($errors->has('code_confirm'))
                        <p class="help-block">{{ $errors->first('code_confirm') }}</p>
                    @endif

                    {{--
                    @lang('profile.parameters.retry_msg')
                    <a href="">@lang('profile.parameters.retry')</a>
                     --}}
                </div>


            @endif

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    @lang('profile.parameters.button_save_parameters')
                </button>
            </div>
        </form>
    </div>
</div>
