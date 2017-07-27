@extends('layouts.master')

<!-- Main Content -->
@section('content')

    <div class="row">
        <div class="my-page-header">
            <div class="col-md-12"><h4>Reset Password</h4></div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            {!! Form::open(['url'=>'/password/email', 'class'=>'form-horizontal']) !!}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
                        </button>
                        <a class="btn btn-link" href="/login">Back to login</a>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection