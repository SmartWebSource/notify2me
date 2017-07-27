@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="my-page-header">
            <div class="col-md-12"><h4>Dashboard</h4></div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@section('custom-style')
<link rel="stylesheet" href="{{$assets}}/plugins/fullcalendar/fullcalendar.min.css">
@endsection

@section('custom-script')
<script src="{{$assets}}/plugins/fullcalendar/lib/moment.min.js"></script>
<script src="{{$assets}}/plugins/fullcalendar/fullcalendar.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

        $("#ajaxloader").removeClass('hide');

        $.get("{{url('meeting/json')}}", function(response){
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: false,
                eventLimit: true,
                events: response,
                eventColor: 'red',
                eventClick: function(event, jsEvent, view) {
                    console.log(event);
                    console.log(jsEvent);
                    console.log(view);
                    /*var date=new Date(event.end._i);
                    var end_date=new Date(date.setDate(date.getDate()-1));
                    $('#holiday_reason').val(event.title);
                    $('#holiday_start_date').val(event.start._i);
                    $('#holiday_end_date').val(end_date.getFullYear()+'-'+("0" + (end_date.getMonth()+1)).slice(-2)+'-'+("0"+end_date.getDate()).slice(-2));
                    $('#holiday_id').val(event.id);
                    $('#modal_holiday').modal("show");*/                                           
                }
            });
            $("#ajaxloader").addClass('hide');
        });
    });
</script>
@endsection
