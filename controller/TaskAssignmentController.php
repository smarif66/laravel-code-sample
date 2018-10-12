<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\StudentMTP;
use App\Models\TaskActivity;
use App\Models\TaskAssignment;
use App\Models\TaskAssignmentList;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Description;

class TaskAssignmentController extends Controller
{
    function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('auth');
        $this->url = 'task-assignment.'.$this->url;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['rows'] = TaskAssignment::with('createdUser')->with('updatedUser')->with('student')
            ->with('user')->with('curriculum')->paginate(20);
        return view($this->url, $data)
            ->with('page_title', $this->page_title);
//        return view('task-assignment.task-assignment',$data)->with('page_title', $this->page_title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['students'] = DB::table('students')->select('id','StudentName')->get();
        $data['users'] = DB::table('users')->select('id','FirstName')->get();
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
            'AssignedTo' => 'required',
            'StudentID' => 'required',
            'AssignTaskName' => 'required',
            'ITPCalculationStartDate' => 'required',
            'ITPCalculationEndDate' => 'required',
            'tasks' => 'required'
        ]);

        //take input from request
        foreach ($request->all() as $key => $value) {
            if($key != 'id' && $key != '_token' && $key != 'image' && $key != 'tasks'){

                $inputs[$key] = strip_tags($value);
            }
        }

        $inputs['CreatedBy'] = Auth::user()->id;
//        dd($inputs);
        $tasks = $request->tasks;
//        dd($tasks);
        $taskAssignments['CreatedBy'] = Auth::user()->id;

//         dd($inputs);
        if($record = TaskAssignment::create($inputs)){

            //save task assignment data
            $taskAssignments['TaskAssignmentID'] = $record->id;

            foreach ($tasks as $task){

                $taskAssignments['TaskID'] = strip_tags($task);

                $task= TaskActivity::find($task)->toArray();
                $taskAssignments['DomainID'] = strip_tags($task['domainID']);
                $taskAssignments['SubDomainID'] = strip_tags($task['subDomainID']);
                $taskAssignments['DomainAreaID'] = strip_tags($task['areaID']);
                $taskAssignments['ActivityID'] = strip_tags($task['TaskID']);

//                dd($taskAssignments);

                TaskAssignmentList::create($taskAssignments);
            }

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
        $data['row'] = TaskAssignment::find($id);
        $studentID = $data['row']->StudentID;

        $studentMtps = StudentMTP::all()->where('StudentID',$studentID)->groupBy(['DomainID','SubDomainID','DomainAreaID','ActivityID'])->toArray();

        foreach ($studentMtps as $studentMtp) {

            foreach ($studentMtp as $value) {
                foreach ($value as $val) {
                    $stdMtpGroups[] = $val;

                }
            }
        }

        $data['stdMtpGroups'] = $stdMtpGroups;
        $data['checkTasks'] = TaskAssignmentList::where('TaskAssignmentID',$id)->where('IsActive','Y')->pluck('TaskID','id')->toArray();
//        dd($data['checkTasks']);
        $data['row'] = TaskAssignment::find($id);
        $data['students'] = DB::table('students')->select('id','StudentName')->get();
        $data['users'] = DB::table('users')->select('id','FirstName','LastName')->get();

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
        $data['row'] = TaskAssignment::find($id);
        $studentID = $data['row']->StudentID;
//        dd($studentID);

        $studentMtps = DB::table('studentmitps')
            ->join('task_activities','task_activities.id', '=', 'studentmitps.TaskID')
            ->select('studentmitps.id', 'studentmitps.StudentID', 'studentmitps.TaskID', 'studentmitps.ActivityID', 'studentmitps.DomainAreaID',
                'studentmitps.SubDomainID', 'studentmitps.DomainID', 'studentmitps.Isused', 'task_activities.ActivityName', 'task_activities.type')
            ->where('studentmitps.StudentID', $studentID)
//            ->groupBy(['DomainID', 'SubDomainID', 'DomainAreaID', 'ActivityID'])
            ->orderBy('studentmitps.DomainID','studentmitps.SubDomainID','studentmitps.DomainAreaID','studentmitps.ActivityID')
//            ->toSql();
            ->get();

//        dd($studentMtps);

//        $studentMtps = StudentMTP::all()->where('StudentID',$studentID)->groupBy(['DomainID','SubDomainID','DomainAreaID','ActivityID'])->toArray();
//        foreach ($studentMtps as $studentMtp) {
//            foreach ($studentMtp as $value) {
//                foreach ($value as $val) {
//                    $stdMtpGroups[] = $val;
//                }
//            }
//        }

        $data['stdMtpGroups'] = $studentMtps;

        $data['checkTasks'] = TaskAssignmentList::where('TaskAssignmentID',$id)->where('IsActive','Y')->pluck('id','TaskID')->toArray();
//        dd($data['checkTasks']);
        $data['row'] = TaskAssignment::find($id);
        $data['students'] = DB::table('students')->select('id','StudentName')->get();
        $data['users'] = DB::table('users')->select('id','FirstName')->get();

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
            'AssignedTo' => 'required',
            'StudentID' => 'required',
            'AssignTaskName' => 'required',
            'ITPCalculationStartDate' => 'required',
            'ITPCalculationEndDate' => 'required',
            'tasks' => 'required',
        ]);

        //take input from request
        foreach ($request->all() as $key => $value) {
            if($key != 'id' && $key != '_token' && $key != 'image' && $key != 'tasks' && $key != 'ids'){

                $inputs[$key] = strip_tags($value);
            }
        }


        $inputs['UpdatedBy'] = Auth::user()->id;

        $data['checkTasks'] = TaskAssignmentList::where('TaskAssignmentID',$id)->where('IsActive','Y')->pluck('TaskID')->toArray();

        $tasks = $request->tasks;
        $oldTask = $data['checkTasks'];

//        dd($data['checkTasks']);
//        dd($tasks);
        if(TaskAssignment::find($id)->update($inputs)){


            foreach ($tasks as $task):

//            print_r($element);
                if(in_array($task, $oldTask)){
//                    dd($element-1);
                    $element = array_search($task,$oldTask);
                    unset($oldTask[$element]);
                }else{
//                    dd($task);
                    if(TaskAssignmentList::where('TaskAssignmentID',$id)->where('TaskID',$task)->count()>0){
                        $statusUpdateData['UpdatedBy'] = Auth::user()->id;
                        $statusUpdateData['IsActive'] = 'Y';
                        TaskAssignmentList::where('TaskAssignmentID',$id)->where('TaskID',$task)->update($statusUpdateData);
                        $statusUpdateData = array();
                    }else{
                        $taskRow= TaskActivity::find($task)->toArray();
//                    dd($taskRow);
                        $taskAssignments['CreatedBy'] = Auth::user()->id;
                        $taskAssignments['TaskAssignmentID'] = $id;
                        $taskAssignments['DomainID'] = strip_tags($taskRow['domainID']);
                        $taskAssignments['SubDomainID'] = strip_tags($taskRow['subDomainID']);
                        $taskAssignments['DomainAreaID'] = strip_tags($taskRow['areaID']);
                        $taskAssignments['ActivityID'] = strip_tags($taskRow['TaskID']);
                        $taskAssignments['TaskID'] = strip_tags($taskRow['id']);
                        $taskAssignments['IsActive'] = 'Y';
                        TaskAssignmentList::create($taskAssignments);
                    }
                }
            endforeach;

//                dd($oldTask);
            if(count($oldTask)>0){
                foreach ($oldTask as $oldTas){
                    $statusUpdateData['UpdatedBy'] = Auth::user()->id;
                    $statusUpdateData['IsActive'] = 'N';
                    TaskAssignmentList::where('TaskAssignmentID',$id)->where('TaskID',$oldTas)->update($statusUpdateData);
                }
            }
            return 2;

        }else{

            return 0;
        }


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

        TaskAssignmentList::where('TaskAssignmentID',$id)->delete();
        TaskAssignment::destroy($id);

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

        if (TaskAssignment::where('id', $id)->update($data)) {
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
            $curriculums = TaskAssignment::where('CurriType', 'C')->get();
            foreach ($curriculums as $curriculum) {
                $options .= "<option value=\"$curriculum->id\">$curriculum->CurriName</option>";
            }
        }else if($type == 'S') {
            $curriculums = TaskAssignment::where('CurriType', 'D')->get();
//            $options = ddlHierarchyOptions( 0, 0, $curriculums, 'curriculum', 'CurriName', 'id', 'Indent');
            foreach ($curriculums as $curriculum) {
                $options .= "<option value=\"$curriculum->id\">$curriculum->CurriName</option>";
            }
        }else if($type == 'A') {
            $curriculums = TaskAssignment::where('CurriType','S')->get();
            foreach ($curriculums as $curriculum) {
                $options .= "<option value=\"$curriculum->id\">$curriculum->CurriName</option>";
            }
        }else if($type == 'T') {
            $curriculums = TaskAssignment::where('CurriType', 'A')->get();
            foreach ($curriculums as $curriculum) {
                $options .= "<option value=\"$curriculum->id\">$curriculum->CurriName</option>";
            }
        }
        return $options;
    }

    //select task wise activities by ajax call
    public function taskWiseActivities(Request $request)
    {
        $task = $request->task;
        $tasks = TaskActivity::where('TaskID',$task)->pluck('ActivityName','id');

        $taskActivity = '';

        foreach ($tasks as $key=>$task){
            $taskActivity .= '<div class="checkbox checkbox-success col-md-3">';
            $taskActivity .= "<input type='checkbox' class='activityCheckbox' name='taskActivities[]' checked value=$key>";
            $taskActivity .= "<label for='checkbox3'>$task</label>";
            $taskActivity .= '</div>';
        }
        return $taskActivity;
    }

    //get student wise tasks
    public function studentWiseTask(Request $request)
    {

        $stdID = $request->stdID;
//        $stdMtpGroups = array();
//        $studentMtps = StudentMTP::all()->where('StudentID', $stdID)
//            ->groupBy(['DomainID', 'SubDomainID', 'DomainAreaID', 'ActivityID'])
////            ->orderBy('DomainID')
////            ->toSql();
//            ->toArray();
        $studentMtps = DB::table('studentmitps')
            ->join('task_activities','task_activities.id', '=', 'studentmitps.TaskID')
            ->select('studentmitps.id', 'studentmitps.StudentID', 'studentmitps.TaskID', 'studentmitps.ActivityID', 'studentmitps.DomainAreaID',
                'studentmitps.SubDomainID', 'studentmitps.DomainID', 'studentmitps.Isused', 'task_activities.ActivityName', 'task_activities.type')
            ->where('studentmitps.StudentID', $stdID)
//            ->groupBy(['DomainID', 'SubDomainID', 'DomainAreaID', 'ActivityID'])
            ->orderBy('studentmitps.DomainID','studentmitps.SubDomainID','studentmitps.DomainAreaID','studentmitps.ActivityID')
//            ->toSql();
            ->get();
//        dd($studentMtps);
//        DB::table('task_assignement')
//            ->distinct()
//            ->join('students','task_assignement.StudentID','=','students.id')
//            ->select('task_assignement.StudentID','students.StudentName')
//            ->where('task_assignement.StudentID',$stdId)
//            ->get();

//        dd($studentMtps);

        $task = '<label class="control-label col-xs-6 col-md-12 text-left p-left-0">Task List: </label>';

        $oldDomain = 0;
        $oldSub_domain = 0;
        $oldArea = 0;
        $oldActivity = 0;
        $i = 0;
        foreach ($studentMtps as $key => $stdMtpGroup){

            $domain = $stdMtpGroup->DomainID;
            $sub_domain = $stdMtpGroup->SubDomainID;
            $area = $stdMtpGroup->DomainAreaID;
            $activity = $stdMtpGroup->ActivityID;

            if($oldDomain == $domain && $oldSub_domain == $sub_domain && $oldArea == $area && $oldActivity == $activity)
            {




            }else{
                if($i != 0){
                    $task .= '</ul></div></div></div></div>';

                }

                $DSArAc = DB::table('curriculum')
                    ->select('CurriName')
                    ->whereIn('id', [$domain, $sub_domain, $area, $activity])
                    ->get();
                $oldDomain = $domain;
                $oldSub_domain = $sub_domain;
                $oldArea = $area;
                $oldActivity = $activity;

                $task .= '<div class="col-xs-12 col-md-12 input-group">';
                $task .= '<div class="panel panel-info">';
                $task .= '<div class="panel-heading task_assignment" data-perform="panel-collapse">';
                $task .= '<div class="pull-left">';
                $task .= "<span class='task_span'>".$DSArAc[0]->CurriName.' > '.$DSArAc[1]->CurriName.' > '.$DSArAc[2]->CurriName.' > '.$DSArAc[3]->CurriName.'</span>';
                $task .= '<a href="#" ><i class="ti-plus task_assignment"></i></a> <a href="#" data-perform="panel-dismiss"></a> </div>';
                $task .= '</div>';
                $task .= '<div class="panel-wrapper collapse" aria-expanded="true">';
                $task .= '<div class="panel-body task_assignment">';
                $task .= '<ul class="list-icons">';
                $i += 1;
            }

//                dd($stdMtpGroup[0]['DomainID']);
//                $domain = Curriculum::where('id',$stdMtpGroup[0]['DomainID'])->pluck('CurriName')->toArray();
//                dd($domain);



//                $tasks = TaskActivity::find();
                $task .= '<li class="task_assignment">';
                $task .= '<div class="checkbox checkbox-danger">';
                $task .= "<input name='tasks[]' onclick='checkSelectedDiv(this)' class='isSelected' id='".$key.$stdMtpGroup->id."' type='checkbox' value='".$stdMtpGroup->TaskID."'>";
                $task .= "<label class='task_assignment' for='".$key.$stdMtpGroup->id."'> <span class='task_assignment'>".$stdMtpGroup->ActivityName."  <span class='m-l-20 p-l-10'> Type: " .config('constant.ActivityType.'.$stdMtpGroup->type)."</span></span> </span> </label>";
                $task .= '</div></li>';
        }
        //end foreach

        if($i > 1){
            $task .= '</ul></div></div></div></div>';

        }

        return $task;
    }

    //get student wise tasks
    public function studentWiseTaskEdit(Request $request)
    {

        $stdID = $request->stdID;
        $studentMtps = StudentMTP::all()->where('StudentID',$stdID)->groupBy(['DomainID','SubDomainID','DomainAreaID','ActivityID'])->toArray();

        $task = '<label class="control-label col-xs-6 col-md-12 text-left p-left-0">Task Activities: </label>';
        foreach ($studentMtps as $studentMtp) {

            foreach ($studentMtp as $value){
                foreach ($value as $val){
                    $stdMtpGroups = $val;
                }
            }
            foreach ($stdMtpGroups as $key => $stdMtpGroup){

                $domain = $stdMtpGroup[0]['DomainID'];
                $sub_domain = $stdMtpGroup[0]['SubDomainID'];
                $area = $stdMtpGroup[0]['DomainAreaID'];
                $Activity = $stdMtpGroup[0]['ActivityID'];

                $DSArAc = DB::table('curriculum')
                    ->select('CurriName')
                    ->whereIn('id', [$domain, $sub_domain, $area, $Activity])
                    ->get();

//                dd($stdMtpGroup[0]['DomainID']);
//                $domain = Curriculum::where('id',$stdMtpGroup[0]['DomainID'])->pluck('CurriName')->toArray();
//                dd($domain);
                $task .= '<div class="col-xs-12 col-md-12 input-group">';
                $task .= '<div class="panel panel-info">';
                $task .= '<div class="panel-heading task_assignment" data-perform="panel-collapse">';
                $task .= '<div class="pull-left">';
                $task .= "<span class='task_span'>".$DSArAc[0]->CurriName.' > '.$DSArAc[1]->CurriName.' > '.$DSArAc[2]->CurriName.' > '.$DSArAc[3]->CurriName.'</span>';
                $task .= '<a href="#" ><i class="ti-minus task_assignment"></i></a> <a href="#" data-perform="panel-dismiss"></a> </div>';
                $task .= '</div>';
                $task .= '<div class="panel-wrapper collapse" aria-expanded="true">';
                $task .= '<div class="panel-body task_assignment">';
                $task .= '<ul class="list-icons">';

                foreach($stdMtpGroup as $key2 => $val2):
                    $tasks = TaskActivity::where('id',$val2['TaskID'])->pluck('ActivityName','id')->toArray();
//                        dd($tasks);
                    $task .= '<li class="task_assignment">';
                    $task .= '<div class="checkbox checkbox-danger">';
                    $task .= "<input name='tasks[]' onclick='checkSelectedDiv(this)' class='isSelected' id='".$key.$key2."' type='checkbox' value='".key($tasks)."'>";
                    $task .= "<label class='task_assignment' for='".$key.$key2."'> <span class='task_assignment'>".$tasks[key($tasks)]."</span> </label>";
                    $task .= '</div></li>';

                endforeach;

                $task .= '</ul></div></div></div></div>';
            }
        }

        return $task;
    }


    //create ITP form ITP
    public function createItpFromMitp(Request $request)
    {
        $tasks = $request->tasks;


        foreach ($tasks as $key => $task){
            if($task != null){
                $tasksList[$key] = $task;
            }
        }
        $data['tasks'] = $tasksList;
        $data['users'] = User::pluck('FirstName','id')->toArray();

        return view($this->url, $data)->with('page_title', $this->page_title);
    }

    //store ITP form ITP
    public function itpStoreFromMitp(Request $request)
    {
        $userID = Auth::user()->id;
        $tasks = $request->TaskID;
        $comments = $request->comment;

        $stdID = session()->get('StudentID');

        //take data from user input
        foreach ($request->all() as $key => $value) {
            if($key != 'id' && $key != '_token' && $key != 'comment' && $key != 'TaskID'){

                $inputs[$key] = strip_tags($value);
            }
        }
        $inputs['StudentID'] = $stdID;
        $inputs['CreatedBy'] = $userID;

        //save data to task_assignment table
        if($record = TaskAssignment::create($inputs)){
            foreach ($tasks as $taskID => $task){

                $details = [];
                //get activity info(domainId, subDomainId....)
                $activityRow = TaskActivity::where('id',$taskID)->first();
                $details['TaskAssignmentID'] = $record->id;
                $details['TaskID'] = $taskID;
                $details['DomainID'] = $activityRow['domainID'];
                $details['SubDomainID'] = $activityRow['subDomainID'];
                $details['DomainAreaID'] = $activityRow['areaID'];
                $details['ActivityID'] = $activityRow['TaskID'];
                $details['comment'] = $comments[$taskID];
                $details['CreatedBy'] = $userID;
                TaskAssignmentList::create($details);
            }
            return response()->json([
                'flag'=>1,
                'message_type' => 'success',
                'message' => config('message.commom.create.y')
            ]);
        }

    }


}
