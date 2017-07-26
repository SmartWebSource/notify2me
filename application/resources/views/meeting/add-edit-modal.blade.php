<div id="meeting-add-edit-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        {!! Form::open(['id'=>'meetingAddEditForm']) !!}
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
            <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title" class="control-label">Company Name {!! validation_error($errors->first('title'),'title') !!}</label>
                            {!! Form::text('title', null, ['class'=>'form-control','maxlength'=>250]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="next_meeting_date" class="control-label">Next Meeting Date {!! validation_error($errors->first('next_meeting_date'),'next_meeting_date') !!}</label>
                            {!! Form::text('next_meeting_date', null, ['class'=>'form-control datepicker']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
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
                    <label for="meeting_details" class="control-label">Meeting Details {!! validation_error($errors->first('meeting_details'),'meeting_details') !!}</label>
                    {!! Form::textarea('meeting_details', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="attendee" class="control-label">Attendee {!! validation_error($errors->first('attendee'),'attendee') !!}</label>
                    {!! Form::select('attendee[]', App\Contact::getDropDownList(), null, ['id'=>'attendee','class'=>'form-control chosen-select','multiple']) !!}
                </div>
            </div>
            <!-- footer modal -->
            <div class="modal-footer">
                {!! validationHints() !!}
                {!!Form::hidden('id')!!}
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="javascript:save();" class="btn btn-sm btn-success">Save <i class="fa fa-spinner spinner hide"></i></button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>