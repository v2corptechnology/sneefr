@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="col-sm-12">

                        <h3 style="text-align: center">Login</h3>
                        <br>
                        <div class="row">
                            <a href="{{ route('login') }}"
                               title="@lang('login.btn_facebook_login')"
                               class="btn btn-md btn-primary btn-block">
                                <i class="fa fa-facebook"></i>
                                @lang('login.btn_facebook_login')
                            </a>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-5">
                                <hr>
                            </div>
                            <div class="col-xs-2">Or</div>
                            <div class="col-xs-5">
                                <hr>
                            </div>
                        </div>
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-envelope-o"></i></div>
                                    <input type="email" class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-key"></i></div>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-default btn-block">
                                    Login
                                </button>
                            </div>

                            <div class="form-group">
                               <div class="row">
                                   <div class="checkbox col-sm-6">
                                       <label>
                                           <input type="checkbox" name="remember"> Remember Me
                                       </label>
                                   </div>
                                   <div class="col-sm-6">
                                       <a class="btn btn-link pull-right" href="{{ url('/password/reset') }}">
                                           Forgot Your Password?
                                       </a>
                                   </div>
                               </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
