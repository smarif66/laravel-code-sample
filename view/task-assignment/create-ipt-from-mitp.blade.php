<div class="modal-header bg-info">
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-capitalize text-white"><i class="fa fa-file fa-lg"></i> Add {{ $page_title }}</h4>
</div>
@php
    $filter_route = str_replace('/{id}', '', Route::current()->uri);
    $route = url( str_replace('-form', '', $filter_route). (isset($row->id)? "-update/$row->id" : '-store') );
@endphp
<!-- // Start From -->
<!-- // Start From -->
{!! Form::open(array(
    'url'   => $route,
    'files' => true,
    'name'  => 'Request::segment(2)',
    'id'    => 'data_form',
    'onsubmit' => "formSubmit(this, event)",
    'class' => "form-horizontal task_assignment curriForm posting_transaction ds-form-".Request::segment(2)
    )) !!}
<div class="modal-body posting_transaction">
    <div class="row form_design task_assignment">
        <div class="col-xs-12 col-lg-12 col-md-12">
                <div class="row">
                    <span id="routeSpan"> {{ $route }}</span>

                    <div class="col-md-3">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="Assigned To" title="Assigned To">Assign Teacher </label>
                        <div class="col-xs-12 col-md-12 input-group">
                            <select name="AssignedTo" class="form-control strip-tags" onchange="setItpName()" id="AssignedTo" required>
                                <option value="">--Select Teacher--</option>
                                @foreach($users as $key=>$user)
                                    <option @if(isset($row->AssignedTo) && $row->AssignedTo == $key) selected @endif value="{{ $key }}">{{ $user }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="AssignTaskName">ITP Name <span class="required" aria-required="true"></span></label>
                        <div class="col-xs-12 col-md-12 input-group">
                            {!! Form::text('AssignTaskName',
                                isset($row->AssignTaskName)? $row->AssignTaskName : null,
                                array('class'=>'form-control strip-tags resetElement', 'id'=>'ITPName', 'placeholder'=>'Name of Assign Task', 'minlength'=>'1','maxlength'=>'255'))
                            !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="ITPCalculationStartDate ">Start Date<span class="required" aria-required="true"></span></label>
                        <div class="col-xs-12 col-md-12 input-group">
                            {!! Form::date('ITPCalculationStartDate',
                                isset($row->ITPCalculationStartDate)? $row->ITPCalculationStartDate : date('Y-m-d'),
                                array('class'=>'form-control strip-tags', 'id'=>'ITPCalculationStartDate','data-date-format'=>"dd/mm/yyyy"))
                            !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="ITPCalculationEndDate ">End Date<span class="required" aria-required="true"></span></label>
                        <div class="col-xs-12 col-md-12 input-group">
                            {!! Form::date('ITPCalculationEndDate',
                                isset($row->ITPCalculationEndDate)? $row->ITPCalculationEndDate : date('Y-m-d'),
                                array('class'=>'form-control strip-tags', 'id'=>'ITPCalculationEndDate','data-date-format'=>"dd/mm/yyyy"))
                            !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="AssignTaskName">Instruction <span class="required" aria-required="true"></span></label>
                        <div class="col-xs-12 col-md-12 input-group">
                            {!! Form::textarea('TaskInstruction',
                                isset($row->TaskInstruction)? $row->TaskInstruction : null,
                                array('class'=>'form-control strip-tags resetElement','rows'=>'2','cols'=>'5', 'id'=>'TaskInstruction', 'placeholder'=>'Task Assignment Instruction Here', 'minlength'=>'1'))
                            !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="list-group m-t-20">
                            <div class="white-box posting_transaction">
                                <div class="table-responsive">
                                    <table class="table editable-table table-bordered table-striped m-b-0 table-responsive">
                                        <tr class="post_trans_details_tr">
                                            <th>Selected Task</th>
                                            <th>Comment</th>
                                        </tr>
                                        <tbody id="taskAssignmentListData">
                                            @if(isset($tasks) && $tasks != null)
                                                @foreach($tasks as $key => $task)
                                                    <tr class="post_trans_details_tr">
                                                        <td>
                                                            <input type="hidden" name="TaskID[{{ $key }}]" value="{{ $key }}">{{ $task }}
                                                        </td>
                                                        <td>
                                                            <input type="text" name="comment[{{ $key }}]">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="from-group">
        <div class="col-sm-offset-6 col-sm-3 pl-0">
            <button type="button" class="btn btn-block btn-danger  p-5 text-bold text-uppercase pull-right" onclick="return isSure(this)">Close</button>
        </div>
        <div class="col-sm-3 text-right pr-0">
            <button type="submit" class="btn btn-block btn-success p-5 text-bold text-uppercase pull-right">Save</button>
        </div>
    </div>
    {{ Form::close() }}
</div>
<script>

</script>
