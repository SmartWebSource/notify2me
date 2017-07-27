@extends('layouts.master')

@section('content')

<div class="row">
    <div class="my-page-header">
        <div class="col-md-8"><h4>Meeting List</h4></div>
        <div class="col-md-4">
            <a href="#" class="btn btn-danger btnAddMeeting"><i class="fa fa-plus-circle"></i> Add Meeting</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Company Name / Meeting Title</th>
                        <th width='15%'>Date of Next Meet</th>
                        <th width='10%'>Created at</th>
                        <th width='10%' class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $meeting)
                    <tr id='meeting_{{$meeting->id}}'>
                        <td>{!! $meeting->title !!}</td>
                        <td width='15%'>{!! Carbon::parse($meeting->next_meeting_date)->format('d M, Y @ h:i A') !!}</td>
                        <td width='10%'>{!! $meeting->created_at->format('d M, Y') !!}</td>
                        <td width='10%' class="text-center">
                            <a href="#" class="btn btn-default btn-xs hide" title="View Meeting"><i class="fa fa-eye white"></i></a>
                            <a href="javascript:void(0);" data-id="{{$meeting->id}}" class="btn btn-success btn-xs btnEditMeeting" title="Edit Meeting"><i class="fa fa-edit white"></i></a>
                            <a href="#" data-id="{{$meeting->id}}" data-action="Meetings/delete" data-message="Are you sure, You want to delete this Meeting?" class="btn btn-danger btn-xs alert-dialog hide" title="Delete Meeting"><i class="fa fa-trash white"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">{{$meetings->paginationSummery}}</div>
    <div class="col-sm-8 text-right">{!! $meetings->links() !!}</div>
</div>

@include('meeting.add-edit-modal')

@endsection

@section('custom-style')
<link rel="stylesheet" href="{{$assets}}/plugins/summernote/summernote.css">

@endsection

@section('custom-script')
<script src="{{$assets}}/plugins/summernote/summernote.min.js"></script>

<script>
    $(document).ready(function(){

        $('#meeting-add-edit-modal').on('hidden.bs.modal', function () {
            $('input[name=title]').val('');
            $('input[name=next_meeting_date]').val('');
            $('input[name=concern_person_name]').val('');
            $('input[name=concern_person_phone]').val('');
            $('input[name=concern_person_designation]').val('');
            $('textarea[name=meeting_details]').summernote('code', '');
            $('#attendee').val('').trigger('chosen:updated');
            $('input[name=id]').val(0);
        });

        $('textarea[name=meeting_details]').summernote({
            height: 150
        });

        $('.btnAddMeeting').click(function(){
            $('#meeting-add-edit-modal .modal-title').html('Add New Meeting');
            $('#meeting-add-edit-modal').modal('show');
        });

        $('.btnEditMeeting').click(function(){
            var id = $(this).attr('data-id');
            $(".validation-error").text('*');
            $("#ajaxloader").removeClass('hide');
            $.ajax({
                url: "{{ url('meeting/edit') }}",
                type: "POST",
                data: {id:id},
                success: function(response){

                    $('#meeting-add-edit-modal .modal-title').html('Edit Meeting: '+response.title);
                    $('input[name=title]').val(response.title);
                    $('input[name=next_meeting_date]').val(response.next_meeting_date);
                    $('input[name=concern_person_name]').val(response.concern_person_name);
                    $('input[name=concern_person_phone]').val(response.concern_person_phone);
                    $('input[name=concern_person_designation]').val(response.concern_person_designation);
                    
                    $('textarea[name=meeting_details]').summernote('code', response.details);

                    var myAttendees = $.parseJSON(response.myAttendees);
                    $('#attendee').val(myAttendees).trigger('chosen:updated');

                    $('input[name=id]').val(response.id);
                    
                    $("#ajaxloader").addClass('hide');
                    $('#meeting-add-edit-modal').modal('show');
                }
            });
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
                        $("#ve-"+index).html('[This field is required.]');
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
