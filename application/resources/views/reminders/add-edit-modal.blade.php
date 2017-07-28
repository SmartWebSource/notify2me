<div id="reminder-add-edit-modal" class="modal fade">
    <div class="modal-dialog modal-md">
        {!! Form::open(['id'=>'reminderAddEditForm']) !!}
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="form-group">
                    <label for="event" class="control-label">Event {!! validation_error($errors->first('event'),'event') !!}</label>
                    {!! Form::select('event', App\Event::getDropDownList(), null, ['class'=>'form-control']) !!}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="timezone" class="control-label">Timezone {!! validation_error($errors->first('timezone'),'timezone') !!}</label>
                            {!! Form::select('timezone', get_timezones(), env('APP_TIMEZONE'), ['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remind_at" class="control-label">Remind At {!! validation_error($errors->first('remind_at'),'remind_at') !!}</label>
                            {!! Form::text('remind_at', Carbon::now()->format('Y-m-d h:i A'), ['class'=>'form-control datetimepicker']) !!}
                        </div>
                    </div>
                </div>
                <div class="checkbox">
                    <label>{!! Form::checkbox('remind_via_email', 'email', true) !!} Remind via email</label>
                </div>
                <div class="checkbox">
                    <label>{!! Form::checkbox('remind_via_sms', 'email', false, ['disabled'=>'disabled']) !!} Remind via sms (coming soon...)</label>
                </div>
            </div>
            <!-- footer modal -->
            <div class="modal-footer">
                {!! validationHints() !!}
                {!!Form::hidden('id')!!}
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="javascript:save();" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>