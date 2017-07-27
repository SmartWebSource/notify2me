@extends('layouts.master')

@section('page-header') User List @endsection

@section('content')

<div class="row">
    <div class="my-page-header">
        <div class="col-md-8"><h4>User List</h4></div>
        <div class="col-md-4">
            <a href="#" class="btn btn-danger btnAddUser"><i class="fa fa-plus-circle"></i> Add User</a>
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
                        <th>Created at</th>
                        <th width='10%' class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr id='user_{{$user->id}}'>
                        <td>{!! $user->name !!}</td>
                        <td width='10%'>{!! Carbon::parse($user->created_at)->format('d M, Y') !!}</td>
                        <td width='10%' class="text-center">
                            <a href="#" class="btn btn-default btn-xs hide" title="View contact"><i class="fa fa-eye white"></i></a>
                            <a href="javascript:void(0);" data-id="{{$user->id}}" class="btn btn-success btn-xs btnEditUser" title="Edit contact"><i class="fa fa-edit white"></i></a>
                            <a href="#" data-id="{{$user->id}}" data-action="users/delete" data-message="Are you sure, You want to delete this contact?" class="btn btn-danger btn-xs alert-dialog hide" title="Delete User"><i class="fa fa-trash white"></i></a>
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
    <div class="col-sm-4">{{$users->paginationSummery}}</div>
    <div class="col-sm-8 text-right">{!! $users->links() !!}</div>
</div>

@include('users.add-edit-modal')

@endsection

@section('custom-script')
<script>
    $(document).ready(function(){
        
        $('.btnAddUser').click(function(){
            $('#user-add-edit-modal .modal-title').html('Add New User');
            
            $('input[name=email]').removeAttr('disabled');
            
            $('select[name=company]').val('');
            $('input[name=name]').val('');
            $('input[name=email]').val('');
            $('input[name=activated]').prop( "checked", true );
            $('input[name=id]').val(0);
            $('#user-add-edit-modal').modal('show');
        });
        
        $('.btnEditUser').click(function(){
            var id = $(this).attr('data-id');
            $(".validation-error").text('*');
            $("#ajaxloader").removeClass('hide');
            $.ajax({
                url: "{{ url('users/edit') }}",
                type: "POST",
                data: {id:id},
                success: function(response){
                    
                    $('input[name=email]').attr('disabled','disabled');
                    
                    $('#user-add-edit-modal .modal-title').html('Edit User: '+response.name);
                    $('select[name=company]').val(response.company_id);
                    $('input[name=name]').val(response.name);
                    $('input[name=email]').val(response.email);
                    $('input[name=active]').prop( "checked", response.active );
                    $('input[name=id]').val(response.id);
                    $("#ajaxloader").addClass('hide');
                    $('#user-add-edit-modal').modal('show');
                }
            });
        });
        
        /*$('.btnUserAddEdit').click(function(){
            var action = $(this).attr('data-action');
            if(action === 'add'){
                $('#user-add-edit-modal .modal-title').html('Add New User');
                $('input[name=title]').val('');
                $('select[name=parent]').val('');
                $('textarea[name=details]').val('');
                $('input[name=id]').val(0);
            }else{
                
            }
            $('#user-add-edit-modal').modal('show');
        });*/
        
        $(".btnUserView").click(function(){
            $("#ajaxloader").removeClass('hide');
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ url('users/view') }}",
                type: "POST",
                data: {id:id},
                success: function(response){
                    var obj = jQuery.parseJSON(response);
                    $('#user-single-view-modal .modal-title').html(obj.title);
                    $('#user-single-view-modal .modal-body').html(response);
                    $('#user-single-view-modal').modal('show');
                    $("#ajaxloader").addClass('hide');
                }
            });
        });
    });
    
    function save(){
        $(".validation-error").text('*');
        $("#ajaxloader").removeClass('hide');
        $.ajax({
            url: "{{ url('users/save') }}",
            type: "POST",
            data: $("#userAddEditForm").serialize(),
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
                        }, 1500); // delay 1.5s
                    }
                }
                
                $("#ajaxloader").addClass('hide');
            }
        });
    }
    
</script>
@endsection