@extends('layouts.master')

@section('page-header') Contact List @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        
        <div>
            <a href="#" class="btn btn-danger btnAddContact"><i class="fa fa-plus-o"></i> Add Contact</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Created at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr id='contact_{{$contact->id}}'>
                        <td>
                            {!! $contact->name !!}
                        </td>
                        <td width='10%'>{!! $contact->created_at->format('d M, Y') !!}</td>
                        <td width='15%' class="text-center">
                            <a href="#" class="btn btn-default btn-xs" title="View contact"><i class="fa fa-eye white"></i></a>
                            <a href="javascript:void(0);" data-id="{{$contact->id}}" class="btn btn-success btn-xs btnEditContact" title="Edit contact"><i class="fa fa-edit white"></i></a>
                            <a href="#" data-id="{{$contact->id}}" data-action="contacts/delete" data-message="Are you sure, You want to delete this contact?" class="btn btn-danger btn-xs alert-dialog" title="Delete Contact"><i class="fa fa-trash white"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row">
                <div class="col-sm-4">{{$contacts->paginationSummery}}</div>
                <div class="col-sm-8 text-right">
                    {!! $contacts->links() !!}
                </div>
            </div>
    </div>
</div>

@include('contacts.add-edit-modal')

@endsection

@section('custom-script')

<script>
    $(document).ready(function(){        
        $('.btnAddContact').click(function(){
            $('#contact-add-edit-modal .modal-title').html('Add New Contact');
            $('input[name=name]').val('');
            $('textarea[name=address]').val('');
            $('textarea[name=purpose]').val('');
            $('textarea[name=phone_numbers]').val('');
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
                    $('#contact-add-edit-modal .modal-title').html('Edit Contact: '+response.name);
                    $('input[name=name]').val(response.name);
                    $('textarea[name=address]').val(response.address);
                    $('textarea[name=purpose]').val(response.purpose);
                    $('input[name=phone_numbers]').val(response.myNumbers);
                    //$('input[name=phone_numbers]').tagsinput('refresh');
                    $('input[name=id]').val(response.id);
                    /*var myGroup = [{ id: 1, text: 'Test' }];
                    console.log(myGroup);
                    console.log(response.myGroups);
                    $("#group").select2({data: myGroup});*/
                    
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
                   // _toastr(response.message,"top-center",response.type,false);
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
