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

                @if(isset($row))
                <div class="form-group col-md-12">
                    <label class="control-label col-xs-12 col-md-12" for="taskTime">{{ $row->ActivityName }}</label>
                    {{--<div class="col-xs-12 col-md-8 input-group">--}}
                        {{--{!! Form::text("tskTimefddf",--}}
                            {{--isset($row->ActivityName)? $row->ActivityName : null,--}}
                            {{--array('class'=>'form-control strip-tags', 'readonly', 'id'=>'tskTime', 'min'=>'0','max'=>'5',))--}}
                        {{--!!}--}}
                    {{--</div>--}}
                </div>
                <div class="form-group col-md-4">
                    <label class="control-label col-xs-12 col-md-7" for="taskTime">Time<span class="required" aria-required="true"></span></label>
                    <div class="col-xs-12 col-md-5 input-group">
                        {!! Form::number("tskTime",
                            isset($row->tskTime)? $row->tskTime : null,
                            array('class'=>'form-control strip-tags', 'id'=>'tskTime', 'min'=>'0','max'=>'5',))
                        !!}
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label class="control-label col-xs-12 col-md-7" for="tskSequence">Sequence<span class="required" aria-required="true"></span></label>
                    <div class="col-xs-12 col-md-5 input-group">
                        {!! Form::number("tskSequence",
                            isset($row->tskSequence)? $row->tskSequence : null,
                            array('class'=>'form-control strip-tags', 'id'=>'tskSequence', 'min'=>'0','max'=>'5',))
                        !!}
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label class="control-label col-xs-12 col-md-7" for="tskQuantity">Quantity<span class="required" aria-required="true"></span></label>
                    <div class="col-xs-12 col-md-5 input-group">
                        {!! Form::number("tskQuantity",
                            isset($row->tskQuantity)? $row->tskQuantity : null,
                            array('class'=>'form-control strip-tags', 'id'=>'tskQuantity', 'min'=>'0','max'=>'5',))
                        !!}
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label class="control-label col-xs-12 col-md-7" for="tskQuality">Quality<span class="required" aria-required="true"></span></label>
                    <div class="col-xs-12 col-md-5 input-group">
                        {!! Form::number("tskQuality",
                            isset($row->tskQuality)? $row->tskQuality : null,
                            array('class'=>'form-control strip-tags', 'id'=>'tskQuality', 'min'=>'0','max'=>'5',))
                        !!}
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label class="control-label col-xs-12 col-md-7" for="tskDelivery">Delivery<span class="required" aria-required="true"></span></label>
                    <div class="col-xs-12 col-md-5 input-group">
                        {!! Form::number("tskDelivery",
                            isset($row->tskDelivery)? $row->tskDelivery : null,
                            array('class'=>'form-control strip-tags', 'id'=>'tskDelivery', 'min'=>'0','max'=>'5',))
                        !!}
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label class="control-label col-xs-12 col-md-7" for="tskTimetaken">Time Taken<span class="required" aria-required="true"></span></label>
                    <div class="col-xs-12 col-md-5 input-group">
                        {!! Form::number("tskTimetaken",
                            isset($row->tskTimetaken)? $row->tskTimetaken : null,
                            array('class'=>'form-control strip-tags', 'id'=>'tskTimetaken', 'min'=>'0','max'=>'5',))
                        !!}
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label class="control-label col-xs-12 col-md-7" for="taskTime">Target<span class="required" aria-required="true"></span></label>
                    <div class="col-xs-12 col-md-5 input-group">
                        {!! Form::number("tskTarget",
                            isset($row->tskTarget)? $row->tskTarget : null,
                            array('class'=>'form-control strip-tags', 'id'=>'tskTarget', 'min'=>'0','max'=>'5',))
                        !!}
                    </div>
                </div>
                @else

                    <div class="form-group col-md-6">
                        <label class="control-label col-xs-12 col-md-3" for="Task Id" title="Task Id">Domain </label>
                        <div class="col-xs-12 col-md-9 input-group" id="prevDomainID">
                            {!! Form::select("domainID",$domains,null,
                                array('class'=>'form-control strip-tags', 'required', 'onchange' => "changeOptionById('domainID')", 'id'=>'domainID','placeholder' => '--Select--',))
                            !!}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label col-xs-12 col-md-3" for="Task Id" title="Task Id">Sub-Domain </label>
                        <div class="col-xs-12 col-md-9 input-group" id="prevSubDomainID">
                            {!! Form::select("subDomainID",$subDomains,null,
                                array('class'=>'form-control strip-tags', 'onchange' => "changeOptionById('subDomainID')", 'id'=>'subDomainID','placeholder' => '--Select--',))
                            !!}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label col-xs-12 col-md-3" for="Task Id" title="Task Id">Area </label>
                        <div class="col-xs-12 col-md-9 input-group" id="prevAreaID">
                            {!! Form::select("areaID",$areas,null,
                                array('class'=>'form-control strip-tags', 'onchange' => "changeOptionById('areaID')", 'id'=>'areaID','placeholder' => '--Select--',))
                            !!}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label col-xs-12 col-md-3" for="Task Id" title="Task Id">Activity </label>
                        <div class="col-xs-12 col-md-9 input-group" id="prevTaskID">
                            {!! Form::select("TaskID",$activities,null,
                                array('class'=>'form-control strip-tags', 'onchange' => "changeOptionById('TaskID')", 'id'=>'TaskID','placeholder' => '--Select--',))
                            !!}
                        </div>
                    </div>
                    <div id="setWeightOption">
                    </div>
                @endif

            </span>
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

    function changeOptionById(selectID) {

        if(selectID == 'domainID'){

            var domainID = $("#"+selectID).val();
            var subDomainID = '';
            var areaID = '';
            var taskID = '';
            $("#subDomainID, #areaID, #TaskID").val('');

            var url_path = base_url + '/set-task-weight-option';
            $.ajax({
                type    : "POST",
                url     : url_path,
                // dataType: "json",
                data    :{'domainId':domainID, 'subDomainId':subDomainID, 'areaId':areaID, 'taskId':taskID},
                success :function(response){
                    $('#prevSubDomainID').html(response.selectBox);
                    $('#setWeightOption').html(response.domainWeight);
                }
            });

        }else if(selectID == 'subDomainID'){

            var subDomainID = $("#"+selectID).val();
            var domainID = $("#domainID").val();
            var areaID = '';
            var taskID = '';
            $("#areaID, #TaskID").val('');

            var url_path = base_url + '/set-task-weight-option';
            $.ajax({
                type    : "POST",
                url     : url_path,
                // dataType: "json",
                data    :{'domainId':domainID, 'subDomainId':subDomainID, 'areaId':areaID, 'taskId':taskID},
                success :function(response){
                    $('#prevAreaID').html(response.selectBox);
                    $('#setWeightOption').html(response.domainWeight);
                }
            });

        }else if(selectID == 'areaID'){

            var areaID = $("#"+selectID).val();
            var domainID = $("#domainID").val();
            var subDomainID = $("#subDomainID").val();
            var taskID = '';
            $("#TaskID").val('');

            var url_path = base_url + '/set-task-weight-option';
            $.ajax({
                type    : "POST",
                url     : url_path,
                // dataType: "json",
                data    :{'domainId':domainID, 'subDomainId':subDomainID, 'areaId':areaID, 'taskId':taskID},
                success :function(response){
                    $('#prevTaskID').html(response.selectBox);
                    $('#setWeightOption').html(response.domainWeight);
                }
            });

        }else{

            var taskID = $("#"+selectID).val();
            var domainID = $("#domainID").val();
            var subDomainID = $("#subDomainID").val();
            var areaID = $("#areaID").val();

            var url_path = base_url + '/set-task-weight-option';
            $.ajax({
                type    : "POST",
                url     : url_path,
                // dataType: "json",
                data    :{'domainId':domainID, 'subDomainId':subDomainID, 'areaId':areaID, 'taskId':taskID},
                success :function(response){
                    $('#setWeightOption').html(response.domainWeight);
                }
            });
        }



    }

</script>
