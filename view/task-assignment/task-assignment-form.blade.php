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
    'class' => "form-horizontal task_assignment curriForm posting_transaction ds-form-".Request::segment(2)
    )) !!}
<div class="modal-body posting_transaction">
    <div class="row form_design task_assignment">
        <div class="col-xs-12 col-lg-12 col-md-12">
                <div class="row">
                    <span id="routeSpan"> {{ $route }}</span>

                    <div class="col-md-6">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="" title="Student">Student Name</label>
                        <div class="col-xs-12 col-md-12 input-group">
                            <select name="StudentID" onchange="studentWiseTask(this)" class="form-control strip-tags" id="StudentID" required>
                                <option value="">--Select Student--</option>
                                @foreach($students as $student)
                                    <option @if(isset($row->StudentID) && $row->StudentID == $student->id) selected @endif value="{{ $student->id }}">{{ $student->StudentName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="Assigned To" title="Assigned To">Assign Teacher </label>
                        <div class="col-xs-12 col-md-12 input-group">
                            <select name="AssignedTo" class="form-control strip-tags" onchange="setItpName()" id="AssignedTo" required>
                                <option value="">--Select Teacher--</option>
                                @foreach($users as $user)
                                    <option @if(isset($row->AssignedTo) && $row->AssignedTo == $user->id) selected @endif value="{{ $user->id }}">{{ $user->FirstName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="AssignTaskName">ITP Name <span class="required" aria-required="true"></span></label>
                        <div class="col-xs-12 col-md-12 input-group">
                            {!! Form::text('AssignTaskName',
                                isset($row->AssignTaskName)? $row->AssignTaskName : null,
                                array('class'=>'form-control strip-tags resetElement', 'id'=>'ITPName', 'placeholder'=>'Name of Assign Task', 'minlength'=>'1','maxlength'=>'255'))
                            !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="ITPCalculationStartDate ">Start Date<span class="required" aria-required="true"></span></label>
                        <div class="col-xs-12 col-md-12 input-group">
                            {!! Form::date('ITPCalculationStartDate',
                                isset($row->ITPCalculationStartDate)? $row->ITPCalculationStartDate : date('Y-m-d'),
                                array('class'=>'form-control strip-tags', 'id'=>'ITPCalculationStartDate','data-date-format'=>"dd/mm/yyyy"))
                            !!}
                        </div>
                    </div>

                    <div class="col-md-4">
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

                    <div class="col-md-12" id="studentWiseTask">
                        <label class="control-label col-xs-6 col-md-12 text-left p-left-0">Task List: </label>
                        @if(isset($stdMtpGroups))
                            {{--@foreach($stdMtpGroups as $stdMtpGrousNew)--}}
                                @php
                                    $oldDomain = 0;
                                    $oldSub_domain = 0;
                                    $oldArea = 0;
                                    $oldActivity = 0;
                                    $i = 0;
                                @endphp
                                @foreach($stdMtpGroups as $key => $stdMtpGroup)
                                    @php
                                        $domain = $stdMtpGroup->DomainID;
                                        $sub_domain = $stdMtpGroup->SubDomainID;
                                        $area = $stdMtpGroup->DomainAreaID;
                                        $activity = $stdMtpGroup->ActivityID;
                                    @endphp
                                    @if($oldDomain == $domain && $oldSub_domain == $sub_domain && $oldArea == $area && $oldActivity == $activity)
                                    @else
                                        @if($i != 0)
                                                                </ul>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        @endif
                                        @php
                                            $DSArAc = DB::table('curriculum')
                                            ->select('CurriName')
                                            ->whereIn('id', [$domain, $sub_domain, $area, $activity])
                                            ->get();
                                            $oldDomain = $domain;
                                            $oldSub_domain = $sub_domain;
                                            $oldArea = $area;
                                            $oldActivity = $activity;
                                        @endphp

                                    <div class="input-group col-xs-12 col-md-12">
                                        <div class="panel panel-info">
                                            <div class="panel-heading task_assignment" data-perform="panel-collapse">
                                                <div class="pull-left">
                                                    <span class='task_span'>{{ $DSArAc[0]->CurriName }} > {{ $DSArAc[1]->CurriName }} > {{ $DSArAc[2]->CurriName }} > {{ $DSArAc[3]->CurriName }}</span>
                                                    <a href="#" ><i class="ti-minus task_assignment"></i></a> <a href="#" data-perform="panel-dismiss"></a>
                                                </div>
                                            </div>
                                            <div class="panel-wrapper collapse" aria-expanded="true">
                                                <div class="panel-body task_assignment">
                                                    <ul class="list-icons">
                                    @php $i += 1; $isCheck = 0; @endphp
                                    @endif
                                                        <li class="task_assignment">
                                                            <div class="checkbox checkbox-danger">
                                                                {{--@foreach($checkTasks as $assignListid => $checkTask)--}}
                                                                {{--@php $checkTask =  @endphp--}}
                                                                    @if(array_key_exists($stdMtpGroup->TaskID,$checkTasks))

                                                                        <input name='tasks[]' onclick='checkSelectedDiv(this)' class='isSelected' id='{{ $key.$stdMtpGroup->id }}' type='checkbox' value='{{ $stdMtpGroup->TaskID }}' checked>
                                                                        <label class='task_assignment' for='{{ $key.$stdMtpGroup->id }}'> <span class='task_assignment'>{{ $stdMtpGroup->ActivityName }}<span class='m-l-20 p-l-10'> Type: {{ config("constant.ActivityType.".$stdMtpGroup->type) }}</span></span> </label>

                                                                    {{--@php $isCheck = 1 @endphp--}}
                                                                        {{--@break;--}}
                                                                    @else
                                                                        {{--@continue--}}
                                                                    {{--@endif--}}
                                                                {{--@endforeach--}}
                                                                {{--@if($isCheck==0)--}}
                                                                    <input name='tasks[]' onclick='checkSelectedDiv(this)' class='isSelected' id='{{ $key.$stdMtpGroup->id }}' type='checkbox' value='{{ $stdMtpGroup->TaskID }}'>
                                                                    <label class='task_assignment' for='{{ $key.$stdMtpGroup->id }}'> <span class='task_assignment'>{{ $stdMtpGroup->ActivityName }}<span class='m-l-20 p-l-10'> Type: {{ config("constant.ActivityType.".$stdMtpGroup->type) }}</span></span> </label>
                                                                @endif
                                                            </div>
                                                        </li>
                                @endforeach
                            {{--@endforeach--}}
                                @if($i > 1)
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                        @endif
                    </div>

                    <div class="col-md-12">
                        <label class="control-label col-xs-6 col-md-12 text-left p-left-0">Published: </label>
                        <div class="input-group col-xs-12 col-md-12">
                            <div class="input-group">
                                <div id="radio-Btn" class="btn-group">
                                    @if(isset($row->IsActive))
                                        <a class="btn btn-sm {{ ($row->IsActive == 'Y')? 'active btn-success' : 'notActive btn-default text-danger' }}"
                                           data-toggle="is_active" data-title="Y"
                                           onclick="optionBtn(this)">Yes</a>

                                        <a class="btn btn-sm {{ ($row->IsActive == 'N')? 'active btn-success' : 'notActive btn-default text-danger' }}"
                                           data-toggle="is_active" data-title="N"
                                           onclick="optionBtn(this)">No</a>
                                    @else

                                        <a class="btn btn-sm active btn-success"
                                           data-toggle="is_active" data-title="Y"
                                           onclick="optionBtn(this)">Yes</a>

                                        <a class="btn btn-sm notActive btn-default text-danger"
                                           data-toggle="is_active" data-title="N"
                                           onclick="optionBtn(this)">No</a>
                                    @endif
                                </div>
                                <input type="hidden" name="IsActive" id="is_active" value="{{ (isset($row->IsActive))? $row->IsActive :  'Y' }}" required>
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
    function selectDeselectChecbox(thisId){
        if(thisId === 'selectMe'){
            $('.activityCheckbox').prop('checked',true);
        }else{
            $('.activityCheckbox').prop('checked',false);
        }
    }
    function setItpName() {
        var StudentID = $("#StudentID").val();
        var assignToID = $("#AssignedTo").val();
        var assignToName = $("#AssignedTo option:selected").text();
        if(StudentID != '' && assignToID != ''){
            var stdName = $("#StudentID option:selected").text();
            var date = new Date();
            var dateTick = date.getTime();
            var itpName = stdName+'::'+assignToName+'::'+dateTick;
            $("#ITPName").val(itpName);

        }

    }
    function studentWiseTask(ele){
        var sttID = $(ele).val();

        var assignToID = $("#AssignedTo").val();

        if(assignToID != ''){
            setItpName();
        }

        var url_path = base_url + '/student-wise-task';

        $.ajax({
            type    : "POST",
            url     : url_path,
            data    :{'stdID':sttID},
            success :function(response){
                // console.log(response);
                $("#studentWiseTask").html(response);
            }
        });
    }


    $("#TaskID").change(function () {
        var task = $(this).val();
        var url_path = base_url + '/task-wise-activities';
        $.ajax({
            type    : "POST",
            url     : url_path,
            // dataType: "json",
            data    :{'task':task},
            success :function(response){
                // console.log(response);
                $("#TaskActivity").html(response);
            }
        });
    });

    $('.task_assignment').submit(function () {
        var form_data = $(this).serialize();
        var url_path = $("#routeSpan").html();
        $.ajax({
            type    : "POST",
            url     : url_path,
            // dataType: "json",
            data    :form_data,
            success :function(response){
                if(response == 1){
                    //show successful message
                    // $("#dynamicAddModal").modal('hide');
                    $('.ds-js-sys-alert').html('').fadeIn(100);
                    var alert_content = '<i class="fa fa-info"></i> '
                        +'<a href="#" class="label text-center pull-right" data-dismiss="alert" aria-label="close">&times;</a>'
                        +'<span class="alert-content">Record is successfully Added</span>'

                    $('.ds-js-sys-alert').removeClass('hidden').addClass('alert-info');
                    $('.ds-js-sys-alert').html(alert_content).fadeOut(3000);

                    //reload the page after save data
                    // location.reload();


                    //reset all form and table data after save data
                    $('.task_assignment').trigger("reset");
                    $('#studentWiseTask').html('');
                }else{
                    $("#dynamicAddModal").modal('hide');
                    $('.ds-js-sys-alert').html('').fadeIn(100);
                    var alert_content = '<i class="fa fa-info"></i> '
                        +'<a href="#" class="label text-center pull-right" data-dismiss="alert" aria-label="close">&times;</a>'
                        +'<span class="alert-content">Record is successfully Added</span>'

                    $('.ds-js-sys-alert').removeClass('hidden').addClass('alert-info');
                    $('.ds-js-sys-alert').html(alert_content).fadeOut(3000);

                    //reload the page after save data
                    location.reload();
                }
            },
            error: function(data) {
                var errors = data.responseJSON;
                //catch errors in p tad
                mainError = '';
                $.each(errors.errors, function( key, value ) {
                    mainError += '<p class="alert-content">';
                    mainError += value[0];
                    mainError += '</p>';

                });

                //show alert message
                $('.ds-js-sys-alert').html('').fadeIn(100);
                var alert_content = '<i class="fa fa-info"></i> '
                    +'<a href="#" class="label text-center pull-right close" data-dismiss="alert" aria-label="close">&times;</a>'+
                    mainError;
                $('.ds-js-sys-alert').removeClass('hidden').addClass('alert-danger');
                $('.ds-js-sys-alert').html(alert_content);
                // $('.ds-js-sys-alert').html(alert_content).fadeOut(6000);
                $('.close').click(function () {
                    $('.ds-js-sys-alert').fadeOut(300);
                });
            }
        });
        return false;
    })

    $(document).ready(function(){
        var task = $('li.task_assignment input').html();
    })


    function checkSelectedDiv(elm) {
        // alert($(elm).val())
        $('.isSelected:not(:checked)').each(function () {

            $(this).parents().eq(5).children().first().removeClass('bg-red');
        });
        $('.isSelected:checked').each(function () {

           $(this).parents().eq(5).children().first().addClass('bg-red');

        });

    }

    $(document).ready(function () {
        $('.isSelected:not(:checked)').each(function () {

            $(this).parents().eq(5).children().first().removeClass('bg-red');
        });
        $('.isSelected:checked').each(function () {

            $(this).parents().eq(5).children().first().addClass('bg-red');

        });
    })


</script>
