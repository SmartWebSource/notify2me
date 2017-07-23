@extends('layouts.master')

@section('page-header') Meeting List @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        
        <div>
            <a href="#" class="btn btn-danger btnAddMeeting"><i class="fa fa-plus-circle"></i> Add Meeting</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Date of Next Meet</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $meeting)
                    <tr id='meeting_{{$meeting->id}}'>
                        <td>
                            {!! $meeting->title !!}
                        </td>
                        <td width='10%'>{!! Carbon::parse($meeting->next_meeting_date)->format('d M, Y') !!}</td>
                        <td width='15%' class="text-center">
                            <a href="#" class="btn btn-default btn-xs" title="View Meeting"><i class="fa fa-eye white"></i></a>
                            <a href="javascript:void(0);" data-id="{{$meeting->id}}" class="btn btn-success btn-xs btnEditMeeting" title="Edit Meeting"><i class="fa fa-edit white"></i></a>
                            <a href="#" data-id="{{$meeting->id}}" data-action="Meetings/delete" data-message="Are you sure, You want to delete this Meeting?" class="btn btn-danger btn-xs alert-dialog" title="Delete Meeting"><i class="fa fa-trash white"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-4">{{$meetings->paginationSummery}}</div>
            <div class="col-sm-8 text-right">
                {!! $meetings->links() !!}
            </div>
        </div>
    </div>
</div>

@include('meeting.add-edit-modal')

@endsection

@section('custom-style')

@endsection

@section('custom-script')


<script>
    $(document).ready(function(){
        $('.btnAddMeeting').click(function(){
            $('#meeting-add-edit-modal .modal-title').html('Add New Meeting');
            $('input[name=name]').val('');
            $('textarea[name=address]').val('');
            $('input[name=id]').val(0);
            
            $('#meeting-add-edit-modal').modal('show');
        });
        
        $('.btnEditMeeting').click(function(){
            var id = $(this).attr('data-id');
            $(".validation-error").text('*');
            $("#ajaxloader").removeClass('hide');
            $.ajax({
                url: "{{ url('Meetings/edit') }}",
                type: "POST",
                data: {id:id},
                success: function(response){

                    $('.my-tagsinput').tagsinput('removeAll');

                    $('#meeting-add-edit-modal .modal-title').html('Edit Meeting: '+response.name);
                    $('input[name=name]').val(response.name);
                    $('select[name=gender]').val(response.gender);
                    $('textarea[name=address]').val(response.address);
                    $('input[name=id]').val(response.id);
                    
                    $("#ajaxloader").addClass('hide');
                    $('#meeting-add-edit-modal').modal('show');
                }
            });
        });
        
        $('.btnMeetingAddEdit').click(function(){
            var action = $(this).attr('data-action');
            if(action === 'add'){
                $('#meeting-add-edit-modal .modal-title').html('Add New Meeting');
                $('input[name=title]').val('');
                $('select[name=parent]').val('');
                $('textarea[name=details]').val('');
                $('input[name=id]').val(0);
            }else{
                
            }
            $('#meeting-add-edit-modal').modal('show');
        });
        
        $(".btnMeetingView").click(function(){
            $("#ajaxloader").removeClass('hide');
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ url('meetings/view') }}",
                type: "POST",
                data: {id:id},
                success: function(response){
                    var obj = jQuery.parseJSON(response);
                    $('#Meeting-single-view-modal .modal-title').html(obj.title);
                    $('#Meeting-single-view-modal .modal-body').html(response);
                    $('#Meeting-single-view-modal').modal('show');
                    $("#ajaxloader").addClass('hide');
                }
            });
        });
    });
    
    function save(){
        $(".validation-error").text('*');
        $("#ajaxloader").removeClass('hide');
        $.ajax({
            url: "{{ url('meeting/save') }}",
            type: "POST",
            data: $("#meetingAddEditForm").serialize(),
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
