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
                        <th width='15%'>Date of Next Meet</th>
                        <th width='10%' class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $meeting)
                    <tr id='meeting_{{$meeting->id}}'>
                        <td>
                            {!! $meeting->title !!}
                        </td>
                        <td width='15%'>{!! Carbon::parse($meeting->next_meeting_date)->format('d M, Y') !!}</td>
                        <td width='10%' class="text-center">
                            <a href="#" class="btn btn-default btn-xs hide" title="View Meeting"><i class="fa fa-eye white"></i></a>
                            <a href="javascript:void(0);" data-id="{{$meeting->id}}" class="btn btn-success btn-xs btnEditMeeting" title="Edit Meeting"><i class="fa fa-edit white"></i></a>
                            <a href="#" data-id="{{$meeting->id}}" data-action="Meetings/delete" data-message="Are you sure, You want to delete this Meeting?" class="btn btn-danger btn-xs alert-dialog hide" title="Delete Meeting"><i class="fa fa-trash white"></i></a>
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
<link rel="stylesheet" href="{{$assets}}/plugins/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="{{$assets}}/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="{{$assets}}/plugins/summernote/summernote.css">

<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css">

@endsection

@section('custom-script')

<script src="{{$assets}}/plugins/select2/dist/js/select2.min.js"></script>
<script src="{{$assets}}/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="{{$assets}}/plugins/summernote/summernote.min.js"></script>

<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>

<script>
    $(document).ready(function(){

        $('.select2').select2();
        $(".chosen-select").chosen({width: "95%"});
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $('textarea[name=meeting_details]').summernote({
            height: 200
        });

        $('.btnAddMeeting').click(function(){
            $('#meeting-add-edit-modal .modal-title').html('Add New Meeting');
            $('input[name=title]').val('');
            $('input[name=next_meeting_date]').val('');
            $('input[name=concern_person_name]').val('');
            $('input[name=concern_person_phone]').val('');
            $('input[name=concern_person_designation]').val('');
            $('textarea[name=meeting_details]').summernote('code', '');
            $('input[name=id]').val(0);
            
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
                    $.each(myAttendees, function( index, value ){
                       $('#attendee').find('option[value="'+ value +'"]').attr('Selected', 'Selected');
                       $("#attendee").trigger('chosen:updated');   
                    });

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
