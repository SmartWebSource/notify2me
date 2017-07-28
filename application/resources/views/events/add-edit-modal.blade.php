<div id="event-add-edit-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        {!! Form::open(['id'=>'eventAddEditForm']) !!}
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
            <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title" class="control-label">Event Title {!! validation_error($errors->first('title'),'title') !!}</label>
                            {!! Form::text('title', null, ['class'=>'form-control','maxlength'=>250]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type" class="control-label">Event Type</label>
                            {!! Form::select('type', ['personal'=>'Personal','official'=>'Official'], null, ['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date" class="control-label">Event Date {!! validation_error($errors->first('start_date'),'start_date') !!}</label>
                            {!! Form::text('start_date', Carbon::now()->format('Y-m-d h:i A'), ['class'=>'form-control datetimepicker']) !!}
                        </div>
                    </div>
                </div>
                <div id="official_event_element" class="row hide">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="concern_person_name" class="control-label">Concern Person Name {!! validation_error($errors->first('concern_person_name'),'concern_person_name') !!}</label>
                            {!! Form::text('concern_person_name', null, ['class'=>'form-control','maxlength'=>50]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="concern_person_phone" class="control-label">Concern Person Phone</label>
                            {!! Form::text('concern_person_phone', null, ['class'=>'form-control','maxlength'=>20]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="title" class="control-label">Concern Person Designation</label>
                            {!! Form::text('concern_person_designation', null, ['class'=>'form-control','maxlength'=>30]) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="control-label">Event Description</label>
                    {!! Form::textarea('description', null, ['class'=>'form-control']) !!}
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="attendee" class="control-label">Event Attendee {!! validation_error($errors->first('attendee'),'attendee') !!}</label>
                            {!! Form::select('attendee[]', App\Contact::getDropDownList(), null, ['id'=>'attendee','class'=>'form-control chosen-select','multiple','data-placeholder'=>'Choose Attendees']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="priority" class="control-label">Event Priority</label>
                            {!! Form::select('priority', config('constants.priority'), '', ['class'=>'form-control']) !!}
                        </div>
                    </div>
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