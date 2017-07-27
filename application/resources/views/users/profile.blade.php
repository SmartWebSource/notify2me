@extends('layouts.master')

@section('content')

{!! Form::model(Auth::user(), ['url'=>'profile']) !!}
<br>
<div class="row">
    <div class="col-md-6">
        <fieldset>
            <legend>Basic Information</legend>
            <div id="basic-information">
                <div class="form-group">
                    <label for="name" class="control-label">Name {!! validation_error($errors->first('name'),'name') !!}</label>
                    {!! Form::text('name', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    {!! Form::email('email', null, ['class'=>'form-control','disabled'=>'disabled']) !!}
                </div>
            </div>
            <legend>Change Password</legend>
            
            <p class="validation-error-hints"><i class="fa fa-info-circle"></i> If you do not want to change your password, just leave password fields blank.</p>
            
            <div id="change-password">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="control-label">Password {!! validation_error($errors->first('password'),'password', true) !!}</label>
                            {!! Form::password('password', ['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation" class="control-label">Confirm Password {!! validation_error($errors->first('password_confirmation'),'password_confirmation', true) !!}</label>
                            {!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
            
        </fieldset>
    </div>
    <div class="col-md-6"></div>
</div>
{!! Form::close() !!}

@endsection

@section('custom-script')

@endsection