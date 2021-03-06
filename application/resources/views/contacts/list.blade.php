@extends('layouts.master')

@section('content')

<div class="row">
    <div class="my-page-header">
        <div class="col-md-8"><h4>Contact List</h4></div>
        <div class="col-md-4">
            <a href="#" class="btn btn-danger btnAddContact"><i class="fa fa-plus-circle"></i> Add Contact</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th width="10%">Created at</th>
                        <th width="10%"" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr id='contact_{{$contact->id}}'>
                        <td>
                            {!! $contact->name !!}
                        </td>
                        <td width='10%'>{!! $contact->created_at->format('d M, Y') !!}</td>
                        <td width='10%' class="text-center">
                            <a href="#" class="btn btn-default btn-xs hide" title="View contact"><i class="fa fa-eye white"></i></a>
                            <a href="javascript:void(0);" data-id="{{$contact->id}}" class="btn btn-success btn-xs btnEditContact" title="Edit contact"><i class="fa fa-edit white"></i></a>
                            <a href="#" data-id="{{$contact->id}}" data-action="contacts/delete" data-message="Are you sure, You want to delete this contact?" class="btn btn-danger btn-xs alert-dialog hide" title="Delete Contact"><i class="fa fa-trash white"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">{{$contacts->paginationSummery}}</div>
    <div class="col-sm-8 text-right">{!! $contacts->links() !!}</div>
</div>

@include('contacts.add-edit-modal')

@endsection

@section('custom-style')
<link rel="stylesheet" href="{{$assets}}/plugins/bootstrap-tagsinput/src/bootstrap-tagsinput.css">
<style type="text/css">
    .bootstrap-tagsinput{width: 100% !important;}
</style>
@endsection

@section('custom-script')

<script src="{{$assets}}/plugins/bootstrap-tagsinput/src/bootstrap-tagsinput.js"></script>

<script>
    $(document).ready(function(){
        $('.btnAddContact').click(function(){
            $('#contact-add-edit-modal .modal-title').html('Add New Contact');
            $('input[name=name]').val('');
            $('textarea[name=address]').val('');
            $('.my-tagsinput').tagsinput('removeAll');
            $('input[name=id]').val(0);
            
            $('#contact-add-edit-modal').modal('show');
        });
        
        $('.btnEditContact').click(function(){
            var id = $(this).attr('data-id');
            $(".validation-error").text('*');
            $("#ajaxloader").removeClass('hide');
            $.ajax({
                url: "{{ url('contacts/edit') }}",
                type: "POST",
                data: {id:id},
                success: function(response){

                    $('.my-tagsinput').tagsinput('removeAll');

                    $('#contact-add-edit-modal .modal-title').html('Edit Contact: '+response.name);
                    $('input[name=name]').val(response.name);
                    $('select[name=gender]').val(response.gender);
                    $('input[name=phone_numbers]').tagsinput('add', response.contactPhones);
                    $('input[name=email_addresses]').tagsinput('add', response.contactEmails);
                    $('textarea[name=address]').val(response.address);
                    $('input[name=id]').val(response.id);
                    
                    $("#ajaxloader").addClass('hide');
                    $('#contact-add-edit-modal').modal('show');
                }
            });
        });
        
        $('.btnContactAddEdit').click(function(){
            var action = $(this).attr('data-action');
            if(action === 'add'){
                $('#contact-add-edit-modal .modal-title').html('Add New Contact');
                $('input[name=title]').val('');
                $('select[name=parent]').val('');
                $('textarea[name=details]').val('');
                $('input[name=id]').val(0);
            }else{
                
            }
            $('#contact-add-edit-modal').modal('show');
        });
        
        $(".btnContactView").click(function(){
            $("#ajaxloader").removeClass('hide');
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ url('contacts/view') }}",
                type: "POST",
                data: {id:id},
                success: function(response){
                    var obj = jQuery.parseJSON(response);
                    $('#contact-single-view-modal .modal-title').html(obj.title);
                    $('#contact-single-view-modal .modal-body').html(response);
                    $('#contact-single-view-modal').modal('show');
                    $("#ajaxloader").addClass('hide');
                }
            });
        });
    });
    
    function save(){
        $(".validation-error").text('*');
        $("#ajaxloader").removeClass('hide');
        $.ajax({
            url: "{{ url('contacts/save') }}",
            type: "POST",
            data: $("#contactAddEditForm").serialize(),
            success: function(response){
                
                if(response.status === 400){
                    //validation error
                    $.each(response.error, function(index, value) {
                        $("#ve-"+index).html('['+value+']');
                    });
                }else{
                    toastMsg(response.message, response.type);
                    if(response.status === 200){
                        setTimeout(function(){
                            location.reload();
                        }, 2000); // delay 1.5s
                    }
                }
                
                $("#ajaxloader").addClass('hide');
            }
        });
    }
    
</script>
@endsection
