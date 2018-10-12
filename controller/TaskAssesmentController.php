<?php

namespace App\Http\Controllers;
//require_once('../../../vendor/autoload.php');

use App\Models\Curriculum;
use App\Models\DomainWeightFactor;
use App\Models\TaskActivity;
use App\Models\TaskAssesment;
use App\Models\TaskAssesmentList;
use App\Models\TaskAssignment;
use App\Models\TaskAssignmentList;
use App\Models\TaskDetail;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

use MathPHP\Probability\Distribution\Continuous\StandardNormal;
use MathPHP\Statistics\Average;
use MathPHP\Statistics\Descriptive;
use MathPHP\Statistics\Significance;

class TaskAssesmentController extends Controller
{
    function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('auth');
        $this->url = 'task-assesment.'.$this->url;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['rows'] = TaskAssesment::with('createdUser')->with('updatedUser')->with('student')->with('task_assignment')->paginate(20);
        return view($this->url, $data)
            ->with('page_title', $this->page_title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stdId = session()->get('StudentID');

        $data['students'] = DB::table('task_assignement')
            ->distinct()
            ->join('students','task_assignement.StudentID','=','students.id')
            ->select('task_assignement.StudentID','students.StudentName')
            ->where('task_assignement.StudentID',$stdId)
            ->get();
        return view($this->url, $data)->with('page_title', $this->page_title);
    }

    /**
     * Show the form for previous Itp Scoring.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_previous_scoring()
    {
        $stdId = session()->get('StudentID');

        $data['students'] = DB::table('task_assignement')
            ->distinct()
            ->join('students','task_assignement.StudentID','=','students.id')
            ->select('task_assignement.StudentID','students.StudentName')
            ->where('task_assignement.StudentID',$stdId)
            ->get();
        return view($this->url, $data)->with('page_title', $this->page_title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //check validation
        $this->validate($request,[
            'TaskAssignementID' => 'required',
            'StudentID' => 'required',
            'TeacherID' => 'required',
            'AssesmentDate' => 'required',
        ]);

//        dd($request->all());

        //take input from request
        foreach ($request->all() as $key => $value) {
            if($key == 'TaskAssignementID' || $key == 'StudentID' || $key == 'TeacherID' || $key == 'AssesmentDate' || $key == 'IsActive'){

                $inputs[$key] = trim(strip_tags($value));
            }
        }
        $inputs['CreatedBy'] = Auth::user()->id;
//        dd($inputs);
        if(TaskAssesment::where('StudentID',$inputs['StudentID'])->where('TeacherID',$inputs['TeacherID'])->where('AssesmentDate',$inputs['AssesmentDate'])
            ->where('TaskAssignementID',$inputs['TaskAssignementID'])->count() > 0){
            return response()->json(['duplicate' => 'This Assesments Already Exist !!']);
        }

        $taskActivitiesIDs = $request->TaskActivitiesID;
//        dd($taskActivitiesIDs);
//            dd($inputs);
        if($record = TaskAssesment::create($inputs)){
            //create array for task_assesment_list table
            $taskAssesmentLists['TaskAssesmentID'] = $record->id;
            foreach ($taskActivitiesIDs as $key => $taskActivitiesID):
                $taskAssesmentLists['TaskActivitiesID'] = $taskActivitiesID;
                $taskAssesmentLists['TaskActivitiesID'] = $taskActivitiesID;
                $taskAssesmentLists['StudentID'] = $inputs['StudentID'];
                $tskTime ='tskTime'.$key;
                $tskSequence ='tskSequence'.$key;
                $tskQuality ='tskQuality'.$key;
                $tskQuantity ='tskQuantity'.$key;
                $tskDelivery ='tskDelivery'.$key;
                $tskTimetaken ='tskTimetaken'.$key;
                $tskTarget ='tskTarget'.$key;
                $AnsScore ='AnsScore'.$key;
                $maxScore ='maxScore'.$key;
                $Remarks ='Remarks'.$key;
//                dd($request->all());
                $taskAssesmentLists['tskTime'] = $request->$tskTime;
                $taskAssesmentLists['tskSequence'] = $request->$tskSequence;
                $taskAssesmentLists['tskQuality'] = $request->$tskQuality;
                $taskAssesmentLists['tskQuantity'] = $request->$tskQuantity;
                $taskAssesmentLists['tskDelivery'] = $request->$tskDelivery;
                $taskAssesmentLists['tskTimetaken'] = $request->$tskTimetaken;
                $taskAssesmentLists['tskTarget'] = $request->$tskTarget;
                $taskAssesmentLists['AnsScore'] = $request->$AnsScore;
                $taskAssesmentLists['maxScore'] = $request->$maxScore;
                $taskAssesmentLists['Remarks'] = $request->$Remarks;
                $taskAssesmentLists['LocalZScore'] = 0;
                $taskAssesmentLists['GlobalZscore'] = 0;
                $taskAssesmentLists['AssesmentDate'] = $inputs['AssesmentDate'];
                $taskAssesmentLists['CreatedBy'] = Auth::user()->id;
//                dd($taskAssesmentLists);
                TaskAssesmentList::create($taskAssesmentLists);
            endforeach;
                $this->calculateScoreUpdate($request->StudentID);
                return 1;
        }else{
            return 0;
        }

        // $url = str_replace('-store', '', $this->url);
//        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['tasks'] = Curriculum::where('CurriType','T')->pluck('CurriName','id')->all();
        $data['students'] = DB::table('task_assignement')
            ->distinct()
            ->join('students','task_assignement.StudentID','=','students.id')
            ->select('task_assignement.StudentID','students.StudentName')
            ->get();
        $data['users'] = DB::table('users')->select('id','FirstName')->get();
        $data['row'] = TaskAssesment::find($id);
        $data['TaskAssesments'] = TaskAssesmentList::where('TaskAssesmentID',$id)->get();

        return view($this->url, $data)->with('page_title', $this->page_title);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['students'] = DB::table('task_assignement')
            ->distinct()
            ->join('students','task_assignement.StudentID','=','students.id')
            ->select('task_assignement.StudentID','students.StudentName')
            ->get();
        $data['row'] = TaskAssesment::find($id);
        $data['TaskAssesments'] = TaskAssesmentList::where('TaskAssesmentID',$id)->get();

        return view($this->url, $data)->with('page_title', $this->page_title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //check validation
        $this->validate($request,[

        ]);

        //take input from request
        foreach ($request->all() as $key => $value) {
            if($key != 'id' && $key != '_token' && $key != 'image' && $key != 'taskActivities'){

                $inputs[$key] = strip_tags($value);
            }
        }
        $inputs['UpdatedBy'] = Auth::user()->id;
//        $taskAssignments['CreatedBy'] = Auth::user()->id;

        $taskActivities = $request->taskActivities;
        if(TaskAssesment::find($id)->update($inputs)){

            //first delete all TaskAssignment List against TaskAssignmentID then create again
            TaskAssignmentList::where('TaskAssignmentID',$id)->delete();
            $taskAssignments['TaskAssignmentID'] = $id;
            foreach ($taskActivities as $key=>$taskActivity){
                $taskAssignments['TaskActivitiesID'] = strip_tags($taskActivity);
                TaskAssignmentList::create($taskAssignments);
            }
            return 2;
        }else{

            return 0;
        }

//        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        TaskAssesmentList::where('TaskAssesmentID',$id)->delete();
        TaskAssesment::destroy($id);

        // session message
        session()->flash('message_type', "success");
        return 1;
    }


    /**
     * Update the specified resource's status from storage.
     *
     * @param  int  $id
     * @return Response
     */
    function updateStatus(Request $request){

        $id = $request->id;

        $data = ($request->is_status == 'Y')? array('IsActive'=>'N') : array('IsActive'=>'Y');
        $data['UpdatedBy'] = Auth::user()->id;
        $data['updated_at'] = date('Y-m-d H:i:s');

//         dd($data);

        if (TaskAssesment::where('id', $id)->update($data)) {
            $status = 1;
            $msg = ($request->is_status == 'Y')? config('message.commom.active.u') : config('message.commom.active.p');
            session()->flash('message_type', "success");
            session()->flash('message', $msg);
        } else {
            session()->flash('message_type', "info");
            session()->flash('message', config('message.commom.active.n'));
            $status = 0;
        }
//        dd($status);

        return $status;
    }

    //return curriculum according to TaskAssignment Type
    public function getParentAccToType(Request $request)
    {
        $type = $request->type;

        $options = '<option value="0">Top</option>';
        if($type == 'C'){
            return $options;
        }else if($type == 'D') {
            $curriculums = TaskAssesment::where('CurriType', 'C')->get();
            foreach ($curriculums as $curriculum) {
                $options .= "<option value=\"$curriculum->id\">$curriculum->CurriName</option>";
            }
        }else if($type == 'S') {
            $curriculums = TaskAssesment::where('CurriType', 'D')->get();
//            $options = ddlHierarchyOptions( 0, 0, $curriculums, 'curriculum', 'CurriName', 'id', 'Indent');
            foreach ($curriculums as $curriculum) {
                $options .= "<option value=\"$curriculum->id\">$curriculum->CurriName</option>";
            }
        }else if($type == 'A') {
            $curriculums = TaskAssesment::where('CurriType','S')->get();
            foreach ($curriculums as $curriculum) {
                $options .= "<option value=\"$curriculum->id\">$curriculum->CurriName</option>";
            }
        }else if($type == 'T') {
            $curriculums = TaskAssesment::where('CurriType', 'A')->get();
            foreach ($curriculums as $curriculum) {
                $options .= "<option value=\"$curriculum->id\">$curriculum->CurriName</option>";
            }
        }
        return $options;
    }

    //student wise teacher
    public function studentWiseTeacher(Request $request)
    {
        $studentId = $request->student;
        $teacherID = Auth::user()->id;
        $teachers = DB::table('task_assignement')
            ->distinct()
            ->join('users','task_assignement.AssignedTo','=','users.id')
            ->select('task_assignement.AssignedTo','users.FirstName','users.LastName')
            ->where('task_assignement.StudentID',$studentId)
            ->where('users.id',$teacherID)
            ->get();
//        dd($teachers);
        $teacherOptons = "";
        foreach ($teachers as $key => $teacher){
            $teacherOptons .= "<option value='$teacher->AssignedTo'>$teacher->FirstName</option>";
        }

        return $teacherOptons;

    }

    //teacher wise task
    public function teacherWiseTask(Request $request)
    {
        $teacherId = $request->teacher;
        $studentId = $request->student;

        $tasks = TaskAssignment::where('AssignedTo',$teacherId)->where('StudentID',$studentId)->pluck('AssignTaskName','id');

        $taskOptons = '';
        if(count($tasks) > 0){
            foreach ($tasks as $key => $task){
                $taskOptons .= "<option value='$key'>$task</option>";
            }
        }else{
            $taskOptons = " <option value=\"\">No ITP</option>";
        }

        return $taskOptons;

    }

    //select student, teacher and task wise assignment list by ajax call
    public function taskWiseAssignmentList(Request $request)
    {
        $task = $request->task;
        $student = $request->student;
        $teacher = $request->teacher;
        $dateNow = date('Y-m-d');

        //get data from task_assignment_list and task_tassignment table
        $taskAssignmentLists = DB::table('task_assignement_list')
            ->join('task_assignement', 'task_assignement.id', '=', 'task_assignement_list.TaskAssignmentID')
            ->join('task_activities', 'task_activities.id', '=', 'task_assignement_list.TaskID')
            ->select('task_assignement_list.id','task_activities.ActivityName', 'task_assignement_list.TaskAssignmentID',
                'task_assignement_list.TaskID','task_assignement.TaskInstruction','task_activities.tskTime','task_activities.tskSequence'
                ,'task_activities.tskQuantity','task_activities.tskQuality','task_activities.tskDelivery',
                'task_activities.tskTimetaken','task_activities.tskTarget','task_activities.domainID')
            ->where('task_assignement.id',$task)
            ->where('task_assignement.ITPCalculationStartDate','<=',$dateNow)
            ->where('task_assignement.ITPCalculationEndDate','>=',$dateNow)
            ->where('task_assignement.StudentID',$student)
            ->where('task_assignement.AssignedTo', $teacher)
            ->where('task_assignement_list.IsActive', 'Y')
            ->orderBy('task_assignement_list.DomainID','ASC')
            ->get();

        //get all domain form curriculum table
        $domains = Curriculum::where('CurriType','D')->pluck('CurriName','id')->toArray();

        //        dd($taskAssignmentLists);

        if(!isset($taskAssignmentLists[0])){
            // dd($taskAssignmentLists);
            $data = '';
            $data .= "<span class='text-bold text-danger pt-20 pb-20'>There is no ITP set for this student or ITP has been Expired</span>";
            return $data;
        }else{
            $taskAssignmentListData = '';
            $lastDomainID = '';
            foreach ($taskAssignmentLists as $key => $taskAssignmentList):
//                dd($taskAssignmentList->TaskID);
                $recentITP = DB::table('task_assesment_list')
                    ->join('task_assesments', 'task_assesments.id', '=', 'task_assesment_list.TaskAssesmentID')
                    ->select('task_assesment_list.*')
                    ->where(['task_assesment_list.TaskActivitiesID' => $taskAssignmentList->TaskID,
                        'task_assesment_list.StudentID'=>$student, 'task_assesment_list.AssesmentDate' => $dateNow,
                        'task_assesments.TaskAssignementID' => $task])
//                    ->orderBy('task_assesment_list.id','DESC')
                    ->first();
//                if($taskAssignmentList->TaskID == 2319){
//                    dd($recentITP);
//                }
//                dd($recentITP->tskTime);


                //create all tr of the view table
                $serial = $key + 1;
                $domainId = $taskAssignmentList->domainID;
                if($key==0){
                    $taskAssignmentListData .= "<tr class='post_trans_details_tr'>";
                    $taskAssignmentListData .= "<td class='bl_n'>$domains[$domainId]</td>";
                    $taskAssignmentListData .= "</tr>";
                    $lastDomainID = $domainId;
                }else{
                    if($lastDomainID != $domainId){
                        $taskAssignmentListData .= "<tr class='post_trans_details_tr'>";
                        $taskAssignmentListData .= "<td class='bl_n'>$domains[$domainId]</td>";
                        $taskAssignmentListData .= "</tr>";
                        $lastDomainID = $domainId;
                    }
                }

               $taskActivityName =$taskAssignmentList->ActivityName;

                $taskAssignmentListData .= "<tr id=\"tr$key\" class=\"post_trans_details_tr\">";
                $taskAssignmentListData .= "<td>$serial</td>";
                $taskAssignmentListData .= "<td><input readonly class='p_t_input' title=\"$taskActivityName\"  type=\"text\" value=\"$taskActivityName\" required name=\"TaskActivitiesName[]\"></td>";
                $taskAssignmentListData .= "<input type=\"hidden\" required name=\"TaskActivitiesID[]\"  value=".$taskAssignmentList->TaskID.">";
                $taskAssignmentListData .= "<input type=\"hidden\" required name=\"TaskAssignementID\"  value=".$taskAssignmentList->TaskAssignmentID.">";

                if($taskAssignmentList->tskTime > 0){
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTime\" name=\"tskTime$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskTime)? $recentITP->tskTime : '')."></td>";
                }else{
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTime\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskTimeRow\" value=".$taskAssignmentList->tskTime.">";

                if($taskAssignmentList->tskSequence > 0){
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskSequence\" name=\"tskSequence$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskSequence)? $recentITP->tskSequence : '')."></td>";
                }else{
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskSequence\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskSequenceRow\" value=".$taskAssignmentList->tskSequence.">";

                if($taskAssignmentList->tskQuality > 0){
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskQuality\" name=\"tskQuality$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskQuality)? $recentITP->tskQuality : '')."></td>";
                }else{
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskQuality\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskQualityRow\" value=".$taskAssignmentList->tskQuality.">";

                if($taskAssignmentList->tskQuantity > 0){
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskQuantity\" name=\"tskQuantity$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskQuantity)? $recentITP->tskQuantity : '')."></td>";
                }else{
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskQuantity\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskQuantityRow\" value=".$taskAssignmentList->tskQuantity.">";

                if($taskAssignmentList->tskDelivery > 0){
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskDelivery\" name=\"tskDelivery$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskDelivery)? $recentITP->tskDelivery : '')."></td>";
                }else{
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskDelivery\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskDeliveryRow\" value=".$taskAssignmentList->tskDelivery.">";

                if($taskAssignmentList->tskTimetaken > 0){
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTimetaken\" name=\"tskTimetaken$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskTimetaken)? $recentITP->tskTimetaken : '')."></td>";
                }else{
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTimetaken\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskTimetakenRow\" value=".$taskAssignmentList->tskTimetaken.">";

                if($taskAssignmentList->tskTarget > 0){
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTarget\" name=\"tskTarget$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskTarget)? $recentITP->tskTarget : '')."></td>";
                }else{
                   $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTarget\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskTargetRow\" value=".$taskAssignmentList->tskTarget.">";

                $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input AnsScore\" name=\"AnsScore$key\" required readonly value=".(isset($recentITP->AnsScore)? $recentITP->AnsScore : '')." ></td>";
                $taskAssignmentListData .= "<input type=\"hidden\" onchange='calculateAnsScore(this)' class=\"small_input maxScore\" name=\"maxScore$key\" required >";
                $taskAssignmentListData .= "<td><input type=\"text\" onchange='calculateAnsScore(this)' class=\"Remarks remarks\" name=\"Remarks$key\" value=".(isset($recentITP->Remarks)? $recentITP->Remarks : '')."></td>";
                $taskAssignmentListData .= "<td><a class='btn btn-dark btn-circle' onclick='viewDetails(this)'  title='View'
                                               href='javascript:void(0)'
                                               data-toggle='modal' data-target='#dynamicViewModal'
                                               data-href='/task-details-view-itp/$taskAssignmentList->TaskID'>
                                                <i class='fa fa-eye fa-lg'></i>
                                                </a></td>";
                $taskAssignmentListData .= "</tr>";
            endforeach;
               $taskAssignmentListData .= "<tr class='hidden'><td id='taskInstructionID'>".$taskAssignmentLists[0]->TaskInstruction."<td></tr>";

            //        dd($taskAssignmentListData);
            return $taskAssignmentListData;
        }
    }

    //select student, teacher and task wise assignment list by ajax call
    public function taskWiseAssignmentListPrev(Request $request)
    {
        $task = $request->task;
        $student = (int) session()->get('StudentID');
        $teacher = Auth::user()->id;
        $assesment_date = $request->date;

        //get data from task_assignment_list and task_tassignment table
        $taskAssignmentLists = DB::table('task_assignement_list')
            ->join('task_assignement', 'task_assignement.id', '=', 'task_assignement_list.TaskAssignmentID')
            ->join('task_activities', 'task_activities.id', '=', 'task_assignement_list.TaskID')
            ->select('task_assignement_list.id','task_activities.ActivityName', 'task_assignement_list.TaskAssignmentID',
                'task_assignement_list.TaskID','task_assignement.TaskInstruction','task_activities.tskTime','task_activities.tskSequence'
                ,'task_activities.tskQuantity','task_activities.tskQuality','task_activities.tskDelivery',
                'task_activities.tskTimetaken','task_activities.tskTarget','task_activities.domainID')
            ->where('task_assignement.id',$task)
            ->where('task_assignement.ITPCalculationStartDate','<=',$assesment_date)
            ->where('task_assignement.ITPCalculationEndDate','>=',$assesment_date)
            ->where('task_assignement.StudentID',$student)
            ->where('task_assignement.AssignedTo', $teacher)
            ->where('task_assignement_list.IsActive', 'Y')
            ->orderBy('task_assignement_list.DomainID','ASC')
            ->get();

        //get all domain form curriculum table
        $domains = Curriculum::where('CurriType','D')->pluck('CurriName','id')->toArray();

        //        dd($taskAssignmentLists);

        if(!isset($taskAssignmentLists[0])){
            // dd($taskAssignmentLists);
            $data = '';
            $data .= "<span class='text-bold text-danger pt-20 pb-20'>There is no ITP set for this student or ITP has been Expired</span>";
            return $data;
        }else{
            $taskAssignmentListData = '';
            $lastDomainID = '';
            foreach ($taskAssignmentLists as $key => $taskAssignmentList):

                $recentITP = DB::table('task_assesment_list')
                    ->join('task_assesments', 'task_assesments.id', '=', 'task_assesment_list.TaskAssesmentID')
                    ->select('task_assesment_list.*')
                    ->where(['task_assesment_list.TaskActivitiesID' => $taskAssignmentList->TaskID,
                        'task_assesment_list.StudentID'=>$student, 'task_assesment_list.AssesmentDate' => $assesment_date,
                        'task_assesments.TaskAssignementID' => $task])
//                    ->orderBy('task_assesment_list.id','DESC')
                    ->get();

                //create all tr of the view table
                $serial = $key + 1;
                $domainId = $taskAssignmentList->domainID;
                if($key==0){
                    $taskAssignmentListData .= "<tr class='post_trans_details_tr'>";
                    $taskAssignmentListData .= "<td class='bl_n'>$domains[$domainId]</td>";
                    $taskAssignmentListData .= "</tr>";
                    $lastDomainID = $domainId;
                }else{
                    if($lastDomainID != $domainId){
                        $taskAssignmentListData .= "<tr class='post_trans_details_tr'>";
                        $taskAssignmentListData .= "<td class='bl_n'>$domains[$domainId]</td>";
                        $taskAssignmentListData .= "</tr>";
                        $lastDomainID = $domainId;
                    }
                }

                $taskActivityName =$taskAssignmentList->ActivityName;

                $taskAssignmentListData .= "<tr id=\"tr$key\" class=\"post_trans_details_tr\">";
                $taskAssignmentListData .= "<td>$serial</td>";
                $taskAssignmentListData .= "<td><input readonly class='p_t_input' title=\"$taskActivityName\"  type=\"text\" value=\"$taskActivityName\" required name=\"TaskActivitiesName[]\"></td>";
                $taskAssignmentListData .= "<input type=\"hidden\" required name=\"TaskActivitiesID[]\"  value=".$taskAssignmentList->TaskID.">";
                $taskAssignmentListData .= "<input type=\"hidden\" required name=\"TaskAssignementID\"  value=".$taskAssignmentList->TaskAssignmentID.">";

                if($taskAssignmentList->tskTime > 0){
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTime\" name=\"tskTime$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskTime)? $recentITP->tskTime : '')."></td>";
                }else{
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTime\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskTimeRow\" value=".$taskAssignmentList->tskTime.">";

                if($taskAssignmentList->tskSequence > 0){
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskSequence\" name=\"tskSequence$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskSequence)? $recentITP->tskSequence : '')."></td>";
                }else{
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskSequence\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskSequenceRow\" value=".$taskAssignmentList->tskSequence.">";

                if($taskAssignmentList->tskQuality > 0){
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskQuality\" name=\"tskQuality$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskQuality)? $recentITP->tskQuality : '')."></td>";
                }else{
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskQuality\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskQualityRow\" value=".$taskAssignmentList->tskQuality.">";

                if($taskAssignmentList->tskQuantity > 0){
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskQuantity\" name=\"tskQuantity$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskQuantity)? $recentITP->tskQuantity : '')."></td>";
                }else{
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskQuantity\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskQuantityRow\" value=".$taskAssignmentList->tskQuantity.">";

                if($taskAssignmentList->tskDelivery > 0){
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskDelivery\" name=\"tskDelivery$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskDelivery)? $recentITP->tskDelivery : '')."></td>";
                }else{
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskDelivery\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskDeliveryRow\" value=".$taskAssignmentList->tskDelivery.">";

                if($taskAssignmentList->tskTimetaken > 0){
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTimetaken\" name=\"tskTimetaken$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskTimetaken)? $recentITP->tskTimetaken : '')."></td>";
                }else{
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTimetaken\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskTimetakenRow\" value=".$taskAssignmentList->tskTimetaken.">";

                if($taskAssignmentList->tskTarget > 0){
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTarget\" name=\"tskTarget$key\"  min=\"1\" max=\"5\" required value=".(isset($recentITP->tskTarget)? $recentITP->tskTarget : '')."></td>";
                }else{
                    $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input tskTarget\" disabled ></td>";
                }
                $taskAssignmentListData .= "<input type=\"hidden\" class=\"tskTargetRow\" value=".$taskAssignmentList->tskTarget.">";

                $taskAssignmentListData .= "<td><input type=\"number\" onchange='calculateAnsScore(this)' class=\"small_input AnsScore\" name=\"AnsScore$key\" required readonly value=".(isset($recentITP->AnsScore)? $recentITP->AnsScore : '')." ></td>";
                $taskAssignmentListData .= "<input type=\"hidden\" onchange='calculateAnsScore(this)' class=\"small_input maxScore\" name=\"maxScore$key\" required >";
                $taskAssignmentListData .= "<td><input type=\"text\" onchange='calculateAnsScore(this)' class=\"Remarks remarks\" name=\"Remarks$key\" value=".(isset($recentITP->Remarks)? $recentITP->Remarks : '')."></td>";
                $taskAssignmentListData .= "<td><a class='btn btn-dark btn-circle' onclick='viewDetails(this)'  title='View'
                                               href='javascript:void(0)'
                                               data-toggle='modal' data-target='#dynamicViewModal'
                                               data-href='/task-details-view-itp/$taskAssignmentList->TaskID'>
                                                <i class='fa fa-eye fa-lg'></i>
                                                </a></td>";
                $taskAssignmentListData .= "</tr>";
            endforeach;
            $taskAssignmentListData .= "<tr class='hidden'><td id='taskInstructionID'>".$taskAssignmentLists[0]->TaskInstruction."<td></tr>";

            //        dd($taskAssignmentListData);
            return $taskAssignmentListData;
        }
    }

    public function calculateScore()
    {
        $data['add_button'] = 1;
        return view($this->url,$data)
            ->with('page_title', $this->page_title);
    }
    public function calculateScoreUpdate($stdID='')
    {
        if($stdID == ''){
            $taskAssignmentLists = TaskAssesmentList::all();
        }else{
            $taskAssignmentLists = TaskAssesmentList::where('StudentID',$stdID)->get();
        }
//        dd($taskAssignmentLists);
        $ansScrStd = [];
        foreach ($taskAssignmentLists as $key => $taskAssignmentList):
            $currentStudent = $taskAssignmentList->StudentID;
            $currentActivity = $taskAssignmentList->TaskActivitiesID;
            $currentAnsScore = $taskAssignmentList->AnsScore;
            $date = $taskAssignmentList->AssesmentDate;
            $datePlsOne = date("Y-m-d", strtotime("$date +1 day"));

            //list of logical rows
            $taskAsgnmtLstStd = TaskAssesmentList::where(['StudentID'=>$currentStudent,'TaskActivitiesID'=>$currentActivity,])
                ->where('AssesmentDate','<',$datePlsOne)->get();
            ;

            $taskAsgnmtLstActvi = TaskAssesmentList::where('TaskActivitiesID',$currentActivity)
                ->where('AssesmentDate','<',$datePlsOne)->get();
            ;

            //list of scores
            foreach ($taskAsgnmtLstStd as $ansScroStd):
                $ansScrStd[] = $ansScroStd->AnsScore;
            endforeach;

            foreach ($taskAsgnmtLstActvi as $ansScroActvi):
                $ansScrAcvti[] = $ansScroActvi->AnsScore;
            endforeach;

            //get mean/average
            $meanOfStd   = Average::mean($ansScrStd);
            $meanOfAcvti   = Average::mean($ansScrAcvti);

            //get Descriptive
            $descriptiveOfStd = Descriptive::sd($ansScrStd);
            $descriptiveOfActvti = Descriptive::sd($ansScrAcvti);

            if($descriptiveOfStd==0 || $descriptiveOfActvti == 0):
                continue;
            else:
                //get local zScore
                $zscoreSTD=($currentAnsScore-$meanOfStd)/$descriptiveOfStd;
                $standardNormal = new StandardNormal();

                if ($currentAnsScore < $meanOfStd) {
                    $p1Std = $standardNormal->cdf($zscoreSTD);
                } else {
                    $p1Std = $standardNormal->above($zscoreSTD);
                }

                $ppercentStd= $p1Std*100;

                $zscore_std = (int)$ppercentStd;

                //get global zScore
                $zscoreActiviti =($currentAnsScore-$meanOfAcvti)/$descriptiveOfActvti;
                $standardNormal = new StandardNormal();

                if ($currentAnsScore < $meanOfAcvti) {
                    $p1Actviti = $standardNormal->cdf($zscoreActiviti);
                } else {
                    $p1Actviti = $standardNormal->above($zscoreActiviti);
                }

                $ppercentActiviti= $p1Actviti*100;

                $zscore_activiti = (int)$ppercentActiviti;
            endif;
            $data = array();
            $data['LocalZScore'] = $zscore_std;
            $data['GlobalZscore'] = $zscore_activiti;
            $data['UpdatedBy'] = Auth::user()->id;
            TaskAssesmentList::find($taskAssignmentList->id)->update($data);
        endforeach;
        session()->flash('message_type', "success");
        session()->flash('message', 'Score Calculation has benn completed successful');

        return redirect()->back();
    }
}
