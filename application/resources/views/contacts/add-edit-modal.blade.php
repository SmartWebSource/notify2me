<div id="contact-add-edit-modal" class="modal fade">
    <div class="modal-dialog modal-md">
        {!! Form::open(['id'=>'contactAddEditForm']) !!}
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
                            <label for="name" class="control-label">Name {!! validation_error($errors->first('name'),'name') !!}</label>
                            {!! Form::text('name', null, ['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gender" class="control-label">Gender</label>
                            {!! Form::select('gender', ['male'=>'Male','female'=>'Female'], null, ['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone_numbers" class="control-label">Phone Numbers {!! validation_error($errors->first('phone_numbers'),'phone_numbers') !!}</label>
                    {!! Form::text('phone_numbers', '', ['class'=>'form-control my-tagsinput']) !!}
                    <small>i.e 8801xxxxxxxxx, 8801xxxxxxxxx</small>
                </div>
                <div class="form-group">
                    <label for="email_addresses" class="control-label">Email Addresses {!! validation_error($errors->first('email_addresses'),'email_addresses') !!}</label>
                    {!! Form::text('email_addresses', '', ['class'=>'form-control my-tagsinput']) !!}
                    <small>i.e abc@example.com, def@example.com</small>
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