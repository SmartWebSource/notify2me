@extends('layouts.master')

@section('content')

<div class="row">
    <div class="my-page-header">
        <div class="col-md-8"><h4>Company List</h4></div>
        <div class="col-md-4">
            <a href="#" class="btn btn-danger btnAddCompany"><i class="fa fa-plus-circle"></i> Add Company</a>
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
                        <th width='10%' class="text-center">Total Users</th>
                        <th width='10%' class="text-center">Total Contacts</th>
                        <th width='10%' class="text-center">Total Events</th>
                        <th width='10%'>Created at</th>
                        <th width='10%' class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                    <tr id='user_{{$company->id}}'>
                        <td>{!! $company->name !!}</td>
                        <td width='10%' class="text-center">{{$company->users->count()}}</td>
                        <td width='10%' class="text-center">{{$company->contacts->count()}}</td>
                        <td width='10%' class="text-center">{{$company->events->count()}}</td>
                        <td width='10%'>{!! Carbon::parse($company->created_at)->format('d M, Y') !!}</td>
                        <td width='10%' class="text-center">
                            <a href="#" class="btn btn-default btn-xs hide" title="View contact"><i class="fa fa-eye white"></i></a>
                            <a href="javascript:void(0);" data-id="{{$company->id}}" class="btn btn-success btn-xs btnEditCompany" title="Edit contact"><i class="fa fa-edit white"></i></a>
                            <a href="#" data-id="{{$company->id}}" data-action="users/delete" data-message="Are you sure, You want to delete this contact?" class="btn btn-danger btn-xs alert-dialog hide" title="Delete User"><i class="fa fa-trash white"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">{{$companies->paginationSummery}}</div>
    <div class="col-sm-8 text-right">{!! $companies->links() !!}</div>
</div>

@include('companies.add-edit-modal')

@endsection

@section('custom-script')
<script>
    $(document).ready(function(){
        
        $('.btnAddCompany').click(function(){
            $('#company-add-edit-modal .modal-title').html('Add New Company');
            $('input[name=name]').val('');
            $('textarea[name=address]').val('');
            $('textarea[name=description]').val('');
            $('input[name=id]').val(0);
            $('#company-add-edit-modal').modal('show');
        });
        
        $('.btnEditCompany').click(function(){
            var id = $(this).attr('data-id');
            $(".validation-error").text('*');
            $("#ajaxloader").removeClass('hide');
            $.ajax({
                url: "{{ url('company/edit') }}",
                type: "POST",
                data: {id:id},
                success: function(response){                    
                    $('#company-add-edit-modal .modal-title').html('Edit Company: '+response.name);
                    $('input[name=name]').val(response.name);
                    $('textarea[name=address]').val(response.address);
                    $('textarea[name=description]').val(response.description);
                    $('input[name=id]').val(response.id);
                    $("#ajaxloader").addClass('hide');
                    $('#company-add-edit-modal').modal('show');
                }
            });
        });
        
        /*$('.btnUserAddEdit').click(function(){
            var action = $(this).attr('data-action');
            if(action === 'add'){
                $('#company-add-edit-modal .modal-title').html('Add New User');
                $('input[name=title]').val('');
                $('select[name=parent]').val('');
                $('textarea[name=details]').val('');
                $('input[name=id]').val(0);
            }else{
                
            }
            $('#company-add-edit-modal').modal('show');
        });*/
        
        $(".btnUserView").click(function(){
            $("#ajaxloader").removeClass('hide');
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ url('company/view') }}",
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
            url: "{{ url('company/save') }}",
            type: "POST",
            data: $("#companyAddEditForm").serialize(),
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