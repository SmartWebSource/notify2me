@extends('layouts.master')

@section('content')

<div class="row">
    <div class="my-page-header">
        <div class="col-md-8"><h4>Event List</h4></div>
        <div class="col-md-4">
            <a href="#" class="btn btn-danger btnAddEvent"><i class="fa fa-plus-circle"></i> Add Event</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Event Title</th>
                        <th width='15%'>Date of Next Meet</th>
                        <th width='10%'>Created at</th>
                        <th width='10%' class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr id='event_{{$event->id}}'>
                        <td>{!! $event->title !!}</td>
                        <td width='15%'>{!! Carbon::parse($event->start_date)->format('d M, Y @ h:i A') !!}</td>
                        <td width='10%'>{!! $event->created_at->format('d M, Y') !!}</td>
                        <td width='10%' class="text-center">
                            <a href="#" class="btn btn-default btn-xs hide" title="View Event"><i class="fa fa-eye white"></i></a>
                            <a href="javascript:void(0);" data-id="{{$event->id}}" class="btn btn-success btn-xs btnEditEvent" title="Edit Event"><i class="fa fa-edit white"></i></a>
                            <a href="#" data-id="{{$event->id}}" data-action="events/delete" data-message="Are you sure, You want to delete this event?" class="btn btn-danger btn-xs alert-dialog hide" title="Delete Event"><i class="fa fa-trash white"></i></a>
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
    <div class="col-sm-4">{{$events->paginationSummery}}</div>
    <div class="col-sm-8 text-right">{!! $events->links() !!}</div>
</div>

@include('events.add-edit-modal')

@endsection

@section('custom-style')
<link rel="stylesheet" href="{{$assets}}/plugins/summernote/summernote.css">

@endsection

@section('custom-script')
<script src="{{$assets}}/plugins/summernote/summernote.min.js"></script>

<script>
    $(document).ready(function(){

        $("select[name=type]").on('change', function(){
            var type = $(this).val();
            if(type === 'personal'){
                $("#official_event_element").addClass('hide');
            }else{
                $("#official_event_element").removeClass('hide');
            }
        });

        $('#event-add-edit-modal').on('hidden.bs.modal', function () {
            $('input[name=title]').val('');
            $('input[name=start_date]').val('');
            $('select[name=type]').val('personal');
            $("#official_event_element").addClass('hide');
            $('input[name=concern_person_name]').val('');
            $('input[name=concern_person_phone]').val('');
            $('input[name=concern_person_designation]').val('');
            $('textarea[name=description]').summernote('code', '');
            $('#attendee').val('').trigger('chosen:updated');
            $('select[name=priority]').val('normal');
            $('input[name=id]').val(0);
        });

        $('textarea[name=description]').summernote({
            height: 150
        });

        $('.btnAddEvent').click(function(){
            $('#event-add-edit-modal .modal-title').html('Add New Event');
            $("input[name=title]").val("Untitled event");
            $("input[name=title]").focus();
            $('#event-add-edit-modal').modal('show');
        });

        $('.btnEditEvent').click(function(){
            var id = $(this).attr('data-id');
            $(".validation-error").text('*');
            $("#ajaxloader").removeClass('hide');
            $.ajax({
                url: "{{ url('events/edit') }}",
                type: "POST",
                data: {id:id},
                success: function(response){

                    $('#event-add-edit-modal .modal-title').html('Edit event: '+response.title);
                    $('input[name=title]').val(response.title);
                    $('input[name=start_date]').val(response.start_date);
                    $('select[name=type]').val(response.type);

                    if(response.type === 'official'){
                        $('input[name=concern_person_name]').val(response.concern_person_name);
                        $('input[name=concern_person_phone]').val(response.concern_person_phone);
                        $('input[name=concern_person_designation]').val(response.concern_person_designation);
                        $("#official_event_element").removeClass('hide');
                    }else{
                        $('input[name=concern_person_name]').val();
                        $('input[name=concern_person_phone]').val();
                        $('input[name=concern_person_designation]').val();
                        $("#official_event_element").addClass('hide');
                    }
                    
                    $('textarea[name=description]').summernote('code', response.description);

                    var myAttendees = $.parseJSON(response.myAttendees);
                    $('#attendee').val(myAttendees).trigger('chosen:updated');

                    $('select[name=priority]').val(response.priority);

                    $('input[name=id]').val(response.id);
                    
                    $("#ajaxloader").addClass('hide');
                    $('#event-add-edit-modal').modal('show');
                }
            });
        });
        
        $(".btnEventView").click(function(){
            $("#ajaxloader").removeClass('hide');
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ url('events/view') }}",
                type: "POST",
                data: {id:id},
                success: function(response){
                    var obj = jQuery.parseJSON(response);
                    $('#event-single-view-modal .modal-title').html(obj.title);
                    $('#event-single-view-modal .modal-body').html(response);
                    $('#event-single-view-modal').modal('show');
                    $("#ajaxloader").addClass('hide');
                }
            });
        });
    });
    
    function save(){
        $(".validation-error").text('*');
        $("#ajaxloader").removeClass('hide');
        $.ajax({
            url: "{{ url('events/save') }}",
            type: "POST",
            data: $("#eventAddEditForm").serialize(),
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
