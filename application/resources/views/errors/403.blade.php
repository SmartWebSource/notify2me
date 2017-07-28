@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="my-page-header">
            <div class="col-md-12"><h4>403</h4></div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-dismissible alert-warning">
                <h4>Unauthorized Access</h4>
                <p><i class="fa fa-info-circle"></i> You do not have sufficient permission to perform this action.</p>
            </div>
        </div>
    </div>
@endsection
