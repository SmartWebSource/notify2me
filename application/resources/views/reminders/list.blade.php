@extends('layouts.master')

@section('content')

<div class="row">
    <div class="my-page-header">
        <div class="col-md-8"><h4>Reminder List</h4></div>
        <div class="col-md-4">
            <a href="#" class="btn btn-danger btnAddReminder"><i class="fa fa-plus-circle"></i> Add Reminder</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Remind at (timezone)</th>
                        <th>Status</th>
                        <th width="10%">Created at</th>
                        <th width="10%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reminders as $reminder)
                    <tr id='reminder_{{$reminder->id}}'>
                        <td>{!! $reminder->event->title !!}</td>
                        <td>{{Carbon::parse($reminder->trigger_at)->format('d M, Y @ h:i A')}} ({{$reminder->timezone}})</td>
                        <td>{!! $reminder->email_status !!}</td>
                        <td width='10%'>{!! $reminder->created_at->format('d M, Y') !!}</td>
                        <td width='10%' class="text-center">
                            <a href="#" class="btn btn-default btn-xs hide" title="View Reminder"><i class="fa fa-eye white"></i></a>
                            <a href="javascript:void(0);" data-id="{{$reminder->id}}" class="btn btn-success btn-xs btnEditReminder" title="Edit Reminder"><i class="fa fa-edit white"></i></a>
                            <a href="#" data-id="{{$reminder->id}}" data-action="Reminders/delete" data-message="Are you sure, You want to delete this Reminder?" class="btn btn-danger btn-xs alert-dialog  hide" title="Delete Reminder"><i class="fa fa-trash white"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">{{$reminders->paginationSummery}}</div>
    <div class="col-sm-8 text-right">{!! $reminders->links() !!}</div>
</div>

@include('reminders.add-edit-modal')

@endsection

@section('custom-style')

@endsection

@section('custom-script')


<script>
    $(document).ready(function(){

        $('.datetimepicker').datetimepicker({
            format: "yyyy-mm-dd HH:ii P",
            showMeridian: true,
            autoclose: true,
            todayHighlight: true
        });

        $('.btnAddReminder').click(function(){
            $('#reminder-add-edit-modal .modal-title').html('Add New Reminder');
            $('input[name=name]').val('');
            $('textarea[name=address]').val('');
            $('input[name=id]').val(0);
            
            $('#reminder-add-edit-modal').modal('show');
        });
        
        $('.btnEditReminder').click(function(){
            var id = $(this).attr('data-id');
            $(".validation-error").text('*');
            $("#ajaxloader").removeClass('hide');
            $.ajax({
                url: "{{ url('reminders/edit') }}",
                type: "POST",
                data: {id:id},
                success: function(response){

                    var remind_via_email = response.email_payload === '' ? false : true;
                    var remind_via_sms = response.sms_payload === '' ? false : true;

                    $('#reminder-add-edit-modal .modal-title').html('Edit Reminder');
                    $('select[name=event]').val(response.event_id);
                    $('select[name=timezone]').val(response.timezone);
                    $('input[name=remind_at]').val(response.trigger_at);
                    $( 'input[name=remind_via_email]' ).prop( 'checked', remind_via_email );
                    $( 'input[name=remind_via_sms]' ).prop( 'checked', remind_via_sms );
                    $('input[name=id]').val(response.id);
                    
                    $("#ajaxloader").addClass('hide');
                    $('#reminder-add-edit-modal').modal('show');
                }
            });
        });
        
        $('.btnReminderAddEdit').click(function(){
            var action = $(this).attr('data-action');
            if(action === 'add'){
                $('#reminder-add-edit-modal .modal-title').html('Add New Reminder');
                $('input[name=title]').val('');
                $('select[name=parent]').val('');
                $('textarea[name=details]').val('');
                $('input[name=id]').val(0);
            }else{
                
            }
            $('#reminder-add-edit-modal').modal('show');
        });
        
        $(".btnReminderView").click(function(){
            $("#ajaxloader").removeClass('hide');
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ url('reminders/view') }}",
                type: "POST",
                data: {id:id},
                success: function(response){
                    var obj = jQuery.parseJSON(response);
                    $('#Reminder-single-view-modal .modal-title').html(obj.title);
                    $('#Reminder-single-view-modal .modal-body').html(response);
                    $('#Reminder-single-view-modal').modal('show');
                    $("#ajaxloader").addClass('hide');
                }
            });
        });
    });
    
    function save(){
        $(".validation-error").text('*');
        $("#ajaxloader").removeClass('hide');
        $.ajax({
            url: "{{ url('reminders/save') }}",
            type: "POST",
            data: $("#reminderAddEditForm").serialize(),
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
