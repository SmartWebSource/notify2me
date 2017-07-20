@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">Create Contact</div>
        {!! Form::open(['url'=>'contacts/create','name'=>'frmCreateContact','class'=>'form-horizontal']) !!}
        @include('contacts.form')
        {!! Form::close() !!}
    </div>
</div>

@endsection
