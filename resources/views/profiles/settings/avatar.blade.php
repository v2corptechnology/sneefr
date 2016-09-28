<div class="panel panel-default" id="profile-avatar">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-camera"></i>
            @lang('profile.settings.avatar_title')
        </h3>
    </div>
    <div class="panel-body">
        <form class="js-avatar-form" action="{{ route('profiles.settings.update', auth()->user()) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
            <input type="hidden" name="settings_category" value="avatar">
            <div class="form-group text-center">
                <img class="img-responsive img-thumbnail"
                     src="{{ \Img::avatar(auth()->user()->avatar, '185x185') }}"
                     alt="{{ auth()->user()->present()->fullname() }}"
                     style="margin: 0 auto;">
            </div>
            <div class="form-group text-center {{ ($errors->has('avatar')) ? 'has-error' :'' }}">
                <input class="hidden js-avatar-file" type="file" name="avatar">
                <button type="button" class="btn btn-default js-avatar-button">
                    <i class="fa fa-image"></i> @lang('profile.settings.avatar_upload_button')
                </button>
                @if ($errors->has('avatar'))
                    <p class="help-block">{{ $errors->first('avatar') }}</p>
                @endif
            </div>
        </form>
    </div>
</div>