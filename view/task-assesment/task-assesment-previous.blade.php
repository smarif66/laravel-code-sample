<div class="modal-header bg-info">
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-capitalize text-white"><i class="fa fa-file fa-lg"></i> ITP Scoring</h4>
    {{--<h4 class="modal-title text-capitalize text-white"><i class="fa fa-file fa-lg"></i> Add {{ $page_title }}</h4>--}}
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
    <div class="row form_design">
        <div class=" col-xs-12">
        {{--<div class=" col-xs-12 col-lg-12 col-md-12 ">--}}

                <div class="row">
                    <span id="routeSpan"> {{ $route }}</span>

                    <div class="col-md-6">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="StudentID" title="Student">Student </label>
                        <div class="col-xs-12 col-md-12 input-group">
                            <select disabled name="StudentID" class="form-control strip-tags" id="StudentID" required >
                                {{--<option value="">--Select--</option>--}}
                                @foreach($students as $student)
                                    <option @if(isset($row->StudentID) && $row->StudentID == $student->StudentID) selected @endif value="{{ $student->StudentID }}">{{ $student->StudentName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="TeacherID" title="Teacher">Teacher </label>
                        <div class="col-xs-12 col-md-12 input-group">
                            <select disabled onchange="teacherWiseTask()" name="TeacherID" class="form-control strip-tags" id="TeacherID" required>
                                {{--<option value="">--Select--</option>--}}
                                {{--@foreach($users as $user)--}}
                                    {{--<option @if(isset($row->AssignedTo) && $row->AssignedTo == $user->id) selected @endif value="{{ $user->id }}">{{ $user->FirstName }}</option>--}}
                                {{--@endforeach--}}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="Task Assignment Type" title="Task Assignment Type">Task </label>
                        <div class="col-xs-12 col-md-12 input-group">
                            <select disabled onchange="taskWiseAssignmentList()" name="TaskID" class="form-control strip-tags resetElement" id="TaskID" required>
                                <option value="">--Select--</option>
                                {{--@foreach($tasks as $id=>$task)--}}
                                    {{--<option @if(isset($row->TaskID) && $row->TaskID == $id) selected @endif value="{{ $id }}">{{ $task }}</option>--}}
                                {{--@endforeach--}}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="AssesmentDate">Assesment Date</label>
                        <div class="col-xs-12 col-md-12 input-group">
                            {!! Form::date('AssesmentDate',
                                isset($row->AssesmentDate)? $row->AssesmentDate : date('Y-m-d'),
                                array('class'=>'form-control strip-tags', 'id'=>'AssesmentDate', 'onchange'=>'taskWiseAssignmentListPrev()'))
                            !!}
                        </div>
                    </div>

                    <div class="col-md-6 taskInstruction displayNone">
                        <label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="">Instruction</label>
                        <div class="col-xs-12 col-md-12 input-group bg-white p-10">
                            <span class="instructionClass"></span>
                        </div>
                    </div>

                </div>

                <div class="row p-20 posting_transaction">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="white-box posting_transaction">
                                <p class="text-bold"><span class="text-danger">(1) Full physical prompt </span>
                                    <span class="text-info"> (2) Partial Support</span>
                                    <span class="text-blue"> (3) Pectoral guidance / follow others</span>
                                    <span class="text-success"> (4) Verbal Instruction</span>
                                    <span class="text-dark"> (5) Independent</span>
                                    <span id="error_span"></span>
                                    <span class="btn btn-info btn-sm pull-right" onclick="showTaskInstruction('taskInstructionID')">Instruction</span>
                                </p>
                                <div class="table-responsive">
                                    <table id="mainTable" class="table editable-table table-bordered table-striped m-b-0 table-responsive">
                                        <thead>
                                        <tr>
                                            @if(isset($presMedicines) && $presMedicines != null)
                                                <th hidden>Id</th>
                                            @endif
                                            <th>SL.</th>
                                            <th>Task</th>
                                            <th>Time</th>
                                            <th>Sequence</th>
                                            <th>Quality</th>
                                            <th>Quantity</th>
                                            <th>Delivery</th>
                                            <th>Time Take</th>
                                            <th>Target</th>
                                            <th>Ans Score</th>
                                            <th>Remark</th>
                                            <th>Details</th>
                                        </tr>
                                        </thead>
                                        <tbody class="tbodyy" id="taskAssignmentListData">
                                        @if(isset($TaskAssesments) && $TaskAssesments != null)
                                            @foreach($TaskAssesments as $key=>$taskAssesment)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $taskAssesment->TaskAssesmentID }}</td>
                                                    <td><input class="p_t_input" type="radio" name="AnsScore{{ $key }}" id="radio4" @if($taskAssesment->AnsScore == 1) checked @endif value="1"></td>
                                                    <td><input type="radio" name="AnsScore{{ $key }}" id="radio4" @if($taskAssesment->AnsScore == 2) checked @endif value="2"></td>
                                                    <td><input type="radio" name="AnsScore{{ $key }}" id="radio4" @if($taskAssesment->AnsScore == 3) checked @endif value="3"></td>
                                                    <td><input type="radio" name="AnsScore{{ $key }}" id="radio4" @if($taskAssesment->AnsScore == 4) checked @endif value="4"></td>
                                                    <td><input type="radio" name="AnsScore{{ $key }}" id="radio4" @if($taskAssesment->AnsScore == 5) checked @endif value="5"></td>
                                                    <td><button>...</button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                {{--<td></td><td></td><td></td><td></td><td></td><td></td><td></td>--}}
                                                {{--<td>1</td>--}}
                                                {{--<td>Name</td>--}}
                                                {{--<td><input type="number" class="small_input" name="tskTime" id="" min="1" max="5" required ></td>--}}
                                                {{--<td><input type="number" class="small_input" name="tskSequence" id="" min="1" max="5" required ></td>--}}
                                                {{--<td><input type="number" class="small_input" name="tskQuality" id="" min="1" max="5" required ></td>--}}
                                                {{--<td><input type="number" class="small_input" name="tskQuantity" id="" min="1" max="5" required ></td>--}}
                                                {{--<td><input type="number" class="small_input" name="tskDelivery" id="" min="1" max="5" required ></td>--}}
                                                {{--<td><input type="number" class="small_input" name="tskTimetaken" id="" min="1" max="5" required ></td>--}}
                                                {{--<td><input type="number" class="small_input" name="tskTarget" id="" min="1" max="5" required ></td>--}}
                                                {{--<td><input type="number" class="small_input" name="AnsScore" id="" min="1" max="5" required ></td>--}}
                                                {{--<td><input type="text" class="small_input" name="AnsScore" id="" ></td>--}}
                                            </tr>
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
        <div class="col-sm-offset-8 col-sm-2 pl-0">
            <button type="button" class="btn btn-block btn-danger  p-5 text-bold text-uppercase pull-right" onclick="return isSure(this)">Close</button>
        </div>
    </div>
</div>

{{ Form::close() }}
<!-- // ..End From -->
<script>

    var taskID;

    function selectDeselectChecbox(thisId){
        if(thisId === 'selectMe'){
            $('.activityCheckbox').prop('checked',true);
        }else{
            $('.activityCheckbox').prop('checked',false);
        }
    }

    //select teacher according to student
    function studentWiseTeacher(tID=1){
        var student = $('#StudentID').val();
        if(student != ''){
            var url_path = base_url + '/student-wise-teacher';
            $.ajax({
                type    : "POST",
                url     : url_path,
                // dataType: "json",
                data    :{'student':student},
                success :function(response){
                    $("#TeacherID").html(response);
                    $("#TeacherID").val(tID).prop('selected');
                }
            });
        }else{
            $("#TeacherID").html('<option value="">--Select--</option>');
            $("#taskAssignmentListData").html('');
        }
    }

    //select task according to teacher
    function teacherWiseTask(student=1,teacher=1){
        if(teacher != '' && student != ''){
            var url_path = base_url + '/teacher-wise-task';
            $.ajax({
                type    : "POST",
                url     : url_path,
                // dataType: "json",
                data    :{'teacher':teacher, 'student':student},
                success :function(response){
                    // console.log(response);
                    // alert(response);
                    $("#TaskID").html(response);
                    taskID = $("#TaskID option:first").val();

                    taskWiseAssignmentList(student,teacher,taskID);
                    // $("#TaskID option:nth-child(1)").prop('selected');
                    // $("#TaskID").val($("#TaskID option:se").val());

                }
            });
        }else{
            $("#TaskID").html('<option value="">--Select--</option>');
            $("#taskAssignmentListData").html('');
        }
    }

    //select assignment list by student, teacher and task
    function taskWiseAssignmentList(student=0,teacher=0,task=0){

        if(student == 0){
            var task = $('#TaskID').val();
            var student = $('#StudentID').val();
            var teacher = $('#TeacherID').val();
        }
        if(task != '' && student != '' && teacher != ''){
            var url_path = base_url + '/task-wise-assignment-list';
            $.ajax({
                type    : "POST",
                url     : url_path,
                // dataType: "json",
                data    :{'task':task,'student':student,'teacher':teacher},
                success :function(response){
                    // console.log(response);
                    $("#taskAssignmentListData").html(response);
                }
            });
        }else{
            $("#taskAssignmentListData").html('');
        }
    }

    //show previous itp scoring list
    function taskWiseAssignmentListPrev(student=0,teacher=0,task=0,AssesmentDate=0){

        var task = $('#TaskID').val();
        var AssesmentDate = $('#AssesmentDate').val();

        if(task != '' && AssesmentDate != ''){
            var url_path = base_url + '/task-wise-assignment-list-prev';
            $.ajax({
                type    : "POST",
                url     : url_path,
                // dataType: "json",
                data    :{'task':task,'date':AssesmentDate},
                success :function(response){
                    // console.log(response);
                    $("#taskAssignmentListData").html(response);
                }
            });
        }else{
            $("#taskAssignmentListData").html('');
        }
    }


    // $("#TaskID").change(function () {
    //     var task = $(this).val();
    //     var student = $('#StudentID').val();
    //     var teacher = $('#TeacherID').val();
    //     if(task != '' && student != '' && teacher != ''){
    //         var url_path = base_url + '/task-wise-assignment-list';
    //         $.ajax({
    //             type    : "POST",
    //             url     : url_path,
    //             // dataType: "json",
    //             data    :{'task':task,'student':student,'teacher':teacher},
    //             success :function(response){
    //                 // console.log(response);
    //                 $("#taskAssignmentListData").html(response);
    //             }
    //         });
    //     }
    // });

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
                    $("#taskAssignmentListData").html('');
                }else{
                    if(response.duplicate != ''){
                        //catch errors in p tad
                        mainError = '';
                        mainError += '<p class="alert-content">'+response.duplicate+'</p>';

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

    function calculateAnsScore(ele)
    {
        var trID = $(ele).parents().eq(1).attr('id');
        var trID = "#"+trID;
        var tskTime = $(trID +" .tskTime").val();
        var tskSequence = $(trID +" .tskSequence").val();
        var tskQuality = $(trID +" .tskQuality").val();
        var tskQuantity = $(trID +" .tskQuantity").val();
        var tskDelivery = $(trID +" .tskDelivery").val();
        var tskTimetaken = $(trID +" .tskTimetaken").val();
        var tskTarget = $(trID +" .tskTarget").val();

        var tskTimeRow = $(trID +" .tskTimeRow").val();
        var tskSequenceRow = $(trID +" .tskSequenceRow").val();
        var tskQualityRow = $(trID +" .tskQualityRow").val();
        var tskQuantityRow = $(trID +" .tskQuantityRow").val();
        var tskDeliveryRow = $(trID +" .tskDeliveryRow").val();
        var tskTimetakenRow = $(trID +" .tskTimetakenRow").val();
        var tskTargetRow = $(trID +" .tskTargetRow").val();

        if(tskTime == ''){
            tskTime = 0;
        }
        if(tskSequence == ''){
            tskSequence = 0;
        }
        if(tskQuality == ''){
            tskQuality = 0;
        }
        if(tskQuantity == ''){
            tskQuantity = 0;
        }
        if(tskDelivery == ''){
            tskDelivery = 0;
        }
        if(tskTimetaken == ''){
            tskTimetaken = 0;
        }
        if(tskTarget == ''){
            tskTarget = 0;
        }
        var total = Number(tskTime)*Number(tskTimeRow) + Number(tskSequence)*Number(tskSequenceRow)
            + Number(tskQuality)*Number(tskQualityRow) + Number(tskQuantity)*Number(tskQuantityRow)
            + Number(tskDelivery)*Number(tskDeliveryRow) + Number(tskTimetaken)*Number(tskTimetakenRow)
            + Number(tskTarget)*Number(tskTargetRow);

        $(trID +" .AnsScore").val(total);
    }



    var url_path = base_url + '/select-student-teacher';

    $.ajax({
        type    : "POST",
        url     : url_path,
        // dataType: "json",
        // data    :{'student':student},
        success :function(response){
            $('#StudentID').val(response.stdID).prop('selected');
            // $('#TeacherID').html("<option value="+response.teacherID+">--Select--</option>");
            studentWiseTeacher(response.teacherID);
            // alert(response.teacherID);
            // $('#TeacherID').val(response.teacherID).prop('selected');
            teacherWiseTask(response.stdID,response.teacherID);
            // taskWiseAssignmentList(response.stdID,response.teacherID,taskID)
            // var task = $('#TaskID').val();
            // alert(task);

            // response.stdID;
            // response.teacherID;

        }
    });

    //for view details
    function viewDetails(ths) {

        $("#dynamicViewModal  .modal-content").html(null);
        $('.preloader').removeClass('hidden').css('display', 'block');
        // e.preventDefault();

        // url = $(this).attr('href');
        var url = $(ths).data("href");
        // var url = base_url
        $.get(url, function (data) {
            // var data = $.parseJSON(data);
            // console.log(data);
            // console.log($("#dynamicAddModal form").attr('class'));

            // if($("#dynamicAddModal form").attr('name') == 'customer-form')
            // $("#dynamicAddModal").find('.modal-body').css(width: '80%');

            $("#dynamicViewModal .modal-content").html(data);
            $("#dynamicViewModal").modal("show");
            $('.preloader').addClass('hidden')
        });

    }// ..end show form in modal by ajax call

    //show taskInstruction
    function showTaskInstruction(instructionID) {
        var instruction = $("#"+instructionID).html();
        if(instruction != ''){
            $('.instructionClass').html(instruction);
            $('.taskInstruction').slideToggle(300)
        }else{
            $('.instructionClass').html('<span class="text-danger">No Instruction</span>');
            $('.taskInstruction').slideToggle(300)
        }
    }

</script>
