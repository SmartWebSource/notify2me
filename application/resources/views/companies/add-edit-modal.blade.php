<div id="company-add-edit-modal" class="modal fade">
    <div class="modal-dialog modal-md">
        {!! Form::open(['id'=>'companyAddEditForm']) !!}
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="control-label">Company Name {!! validation_error($errors->first('name'),'name') !!}</label>
                    {!! Form::text('name', null, ['class'=>'form-control']) !!}
                </div>
                
                <div class="form-group">
                    <label for="address" class="control-label">Company Address</label>
                    {!! Form::textarea('address', null, ['class'=>'form-control','rows'=>3]) !!}
                </div>
                
                <div class="form-group">
                    <label for="description" class="control-label">Company Description</label>
                    {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>3]) !!}
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