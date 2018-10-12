<div class="modal-header bg-info">
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-capitalize text-white"><i class="fa fa-file fa-lg"></i> Add {{ $page_title }}</h4>
</div>
@php
    $filter_route = str_replace('/{id}', '', Route::current()->uri);
    $route = url( str_replace('-form', '', $filter_route). (isset($row->id)? "-update/".$row->id : '-store') );
@endphp

<!-- // Start From -->
<!-- // Start From -->
{!! Form::open(array(
    'url'   => $route,
    'files' => true,
    'name'  => 'Request::segment(2)',
    'id'    => 'data_form',
    'class' => "form-horizontal task_activities_form curriForm ds-form-".Request::segment(2)
    )) !!}

<div class="modal-body">
    <div class="row form_design">
        <div class=" col-xs-12 col-lg-12 col-md-12 ">

            <span id="curriSpan">

                <div class="form-group col-md-6">
                    <label class="control-label col-xs-12 col-md-3" for="Task Id" title="Task Id">Activity </label>
                    <div class="col-xs-12 col-md-9 input-group">
                        <select name="TaskID" class="form-control strip-tags" required>
                            <option value="">--Select--</option>
                            @if(isset($tasks) && $tasks != null)
                                @foreach($tasks as $task)
                                    @if(isset($row->TaskID))
                                        <option value="{{ $task->id }}" @if($task->id == $row->TaskID) selected @endif>{{ $task->CurriName }}</option>
                                    @else
                                        <option value="{{ $task->id }}">{{ $task->CurriName }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="control-label col-xs-12 col-md-3" for="domainWeightFactorID" title="domainWeightFactorID">Domain Weight </label>
                    <div class="col-xs-12 col-md-9 input-group">
                        <select name="domainWeightFactorID" class="form-control strip-tags" required>
                            <option value="">--Select--</option>
                            @foreach($weights as $key=>$weight)
                                <option @if(isset($row->domainWeightFactorID) && $row->domainWeightFactorID == $key) selected @endif value="{{ $key }}">{{ $weight }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if(isset($rows) && $rows != null)
                    
                    @foreach($rows as $value)
                        <div class="form-group col-md-8">
                            <label class="control-label col-xs-12 col-md-4" for="id">Task Name<span class="required" aria-required="true"></span></label>
                            <div class="col-xs-12 col-md-8 input-group">
                                {!! Form::text("ActivityName[$value->id]",
                                    isset($value->ActivityName)? $value->ActivityName : null,
                                    array('class'=>'form-control strip-tags', 'id'=>'ActivityName', 'placeholder'=>'Task Name Here', 'minlength'=>'1','maxlength'=>'191',))
                                !!}
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                        <label class="control-label col-xs-12 col-md-3" for="Task Id" title="Task Id">Type </label>
                        <div class="col-xs-12 col-md-8 input-group">
                            <select name="type[]" class="form-control strip-tags">
                                <option value="">--Select--</option>
                                <option value="P" @if(isset($value->type) && $value->type=='P') selected @endif>Pre-Vocational</option>
                                <option value="V" @if(isset($value->type) && $value->type=='V') selected @endif>Vocational</option>
                                <option value="I" @if(isset($value->type) && $value->type=='I') selected @endif>Independent</option>
                            </select>
                        </div>
                    </div>
                        {{--<input type="hidden" name="ActivityName['id']" value="{{ $value->id }}" required>--}}
                    @endforeach

                @else

                    @for($an=0; $an<=4; $an++)

                    <div class="form-group col-md-8">
                        <label class="control-label col-xs-12 col-md-4" for="id">Task Name <span class="required" aria-required="true"></span></label>
                        <div class="col-xs-12 col-md-8 input-group">
                            @if($an==0)
                                {!! Form::text('ActivityName[]',
                                isset($row->ActivityName)? $row->ActivityName : null,
                                array('class'=>'form-control strip-tags','required','id'=>'ActivityName-'.$an, 'placeholder'=>'Activity Name Here', 'minlength'=>'1','maxlength'=>'191',))
                                !!}
                            @else
                                {!! Form::text('ActivityName[]',
                                 isset($row->ActivityName)? $row->ActivityName : null,
                                 array('class'=>'form-control strip-tags','id'=>'ActivityName-'.$an, 'placeholder'=>'Activity Name Here', 'minlength'=>'1','maxlength'=>'191',))
                                !!}
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label col-xs-12 col-md-3" for="Task Id" title="Task Id">Type </label>
                        <div class="col-xs-12 col-md-8 input-group">
                            <select name="type[]" class="form-control strip-tags" @if($an==0) required @endif>
                                <option value="">--Select--</option>
                                <option value="P">Pre-Vocational</option>
                                <option value="V">Vocational</option>
                                <option value="I">Independent</option>
                            </select>
                        </div>
                    </div>
                    @endfor
                    
                @endif
            </span>
            <div class="form-group">
                <div class="col-xs-12 col-md-12 input-group">
                    <a class="btn btn-md btn-success btn-add-row pull-right"> + Add </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <div class="from-group">
        <div class="col-sm-offset-8 col-sm-2 pl-0">
            <button type="button" class="btn btn-block btn-danger  p-5 text-bold text-uppercase pull-right" onclick="return isSure(this)">Close</button>
        </div>
        <div class="col-sm-2 text-right">
            <button type="submit" class="btn btn-block btn-success p-5 text-bold text-uppercase pull-right">Save</button>
        </div>
    </div>
</div>

{{ Form::close() }}
<!-- // ..End From -->
{{--<div class="modal-footer text-left">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>--}}



<script type="text/javascript">
    // add new row on click on save
    $('.btn-add-row').on('click', function(event) {
        var content = '<div class="form-group col-md-8">' +
                        '<label class="control-label col-xs-12 col-md-4" for="id">Task Name <span class="required" aria-required="true"></span></label>'+
                        '<div class="col-xs-12 col-md-8 input-group">'+
                        '<input type="text" name="ActivityName[]" class="form-control strip-tags" placeholder="Activity Name Here" minlength="1" maxlength="191">'+
                        '</div></div>'+
                        '<div class="form-group col-md-4">'+
                        '<label class="control-label col-xs-12 col-md-3" for="Task Id" title="Task Id">Type </label>'+
                        '<div class="col-xs-12 col-md-8 input-group">'+
                        '<select name="type[]" class="form-control strip-tags">'+
                        '<option value="">--Select--</option>'+
                        '<option value="P">Pre-Vocational</option>'+
                        '<option value="V">Vocational</option>'+
                        '<option value="I">Independent</option>'+
                        '</select>'+
                        '</div></div>';
        $('#curriSpan').append(content);
    });
</script>
