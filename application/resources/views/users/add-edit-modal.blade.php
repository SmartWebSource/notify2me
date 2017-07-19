<div id="user-add-edit-modal" class="modal fade">
    <div class="modal-dialog modal-md">
        {!! Form::open(['id'=>'userAddEditForm']) !!}
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="control-label">Name {!! validation_error($errors->first('name'),'name') !!}</label>
                    {!! Form::text('name', null, ['class'=>'form-control']) !!}
                </div>
                
                <div class="form-group">
                    <label for="email" class="control-label">Email {!! validation_error($errors->first('email'),'email') !!}</label>
                    {!! Form::email('email', null, ['class'=>'form-control']) !!}
                </div>
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('activated', 1, true) !!} Active
                    </label>
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