<?php

namespace App\Http\Controllers;


use App\Models\Curriculum;
use App\Models\DomainWeightFactor;
use App\Models\TaskActivity;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class TaskActivityController extends Controller
{
    function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('auth');
        $this->url = 'task-activities.'.$this->url;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['rows'] = TaskActivity::orderBy('TaskID')->with('createdUser')->with('updatedUser')->with('task')->get();
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
        $data['weights'] = DomainWeightFactor::pluck('planName','id');
        $data['tasks'] = Curriculum::where('CurriType','T')->get();
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
            'TaskID' => 'required',
            'ActivityName' => 'required|max:191',
        ]);

        $activityName = $request->ActivityName;
        $types = $request->type;
        //take input from request
        foreach ($request->all() as $key => $value) {
            if($key != 'id' && $key != '_token' && $key != 'image' && $key != 'ActivityName' && $key != 'type'){

                $inputs[$key] = strip_tags($value);
            }
        }

        $taskID = $request->TaskID;

        $areaID = Curriculum::where('id',$taskID)->pluck('ParentID')->toArray();
        $inputs['areaID'] = $areaID[0];

        $subDomainID = Curriculum::where('id',$areaID)->pluck('ParentID')->toArray();
        $inputs['subDomainID'] = $subDomainID[0];

        $domainID = Curriculum::where('id',$subDomainID)->pluck('ParentID')->toArray();
        $inputs['domainID'] = $domainID[0];

        $inputs['CreatedBy'] = Auth::user()->id;

        foreach ($activityName as $key=>$activity) {
            if($activity == '' || $types[$key] == ''){
                continue;
            }else{
                $inputs['ActivityName'] = $activity;
                $inputs['type'] = $types[$key];
//                dd($inputs);
                if($record = TaskActivity::create($inputs)){
                    // session message for seccuss to save data
                    session()->flash('message_type', "success");
                    session()->flash('message', config('message.commom.create.y'));
                }else{
                    // session message for fail to save data
                    session()->flash('message_type', "info");
                    session()->flash('message', config('message.commom.create.n'));
                }
            }
        }

        // $url = str_replace('-store', '', $this->url);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['weights'] = DomainWeightFactor::pluck('planName','id');
        $data['tasks'] = Curriculum::where('CurriType','T')->get();
        $taskID = TaskActivity::where('id',$id)->pluck('TaskID')->toArray();
        $data['rows'] = TaskActivity::where('TaskID',$taskID[0])->get();

        $data['row'] = TaskActivity::with('createdUser')->with('updatedUser')->find($id);

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
        $data['weights'] = DomainWeightFactor::pluck('planName','id');
        $data['tasks'] = Curriculum::where('CurriType','T')->get();
        $taskID = TaskActivity::where('id',$id)->pluck('TaskID')->toArray();
        $data['rows'] = TaskActivity::where('TaskID',$taskID[0])->get();

        $data['row'] = TaskActivity::with('createdUser')->with('updatedUser')->find($id);
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
//        dd($request->all());
        //check validation
        $this->validate($request,[
            'TaskID' => 'required',
            'ActivityName' => 'required|max:191',
        ]);

        $activityName = $request->ActivityName;
        $types = $request->type;
        // if the record and parent id is same then it will return back with message

        foreach ($request->all() as $key => $value) {
            if($key != 'id' && $key != '_token' && $key != 'image' && $key != 'ActivityName' && $key != 'type'){

                $inputs[$key] = strip_tags($value);
            }
        }

        $taskID = $request->TaskID;

        $areaID = Curriculum::where('id',$taskID)->pluck('ParentID')->toArray();
        $inputs['areaID'] = $areaID[0];

        $subDomainID = Curriculum::where('id',$areaID)->pluck('ParentID')->toArray();
        $inputs['subDomainID'] = $subDomainID[0];

        $domainID = Curriculum::where('id',$subDomainID)->pluck('ParentID')->toArray();
        $inputs['domainID'] = $domainID[0];


        $inputs['CreatedBy'] = Auth::user()->id;

        $inputs['UpdatedBy'] = Auth::user()->id;
//        dd($inputs);
        $typeIndex = 0;
        foreach ($activityName as $key=>$activity) {
            if($activity == '' && $key == ''){
                continue;
            }elseif ($key != '' && $activity == ''){
                TaskActivity::destroy($key);
            } else{
                $inputs['ActivityName'] = $activity;
                $inputs['type'] = $types[$typeIndex];
                if(TaskActivity::where('id', $key)->count() > 0 ){

                    if(TaskActivity::find($key)->update($inputs)){

                        // session message for update success
                        session()->flash('message_type', "success");
                        session()->flash('message', config('message.commom.update.y'));
                    }else{

                        // session message for update fail
                        session()->flash('message_type', "info");
                        session()->flash('message', config('message.commom.update.n'));
                    }
                }else{
                    $inputs['CreatedBy'] = Auth::user()->id;
                    if(TaskActivity::create($inputs)){
                        // session message for update success
                        session()->flash('message_type', "success");
                        session()->flash('message', config('message.commom.update.y'));
                    }else{
                        // session message for update fail
                        session()->flash('message_type', "info");
                        session()->flash('message', config('message.commom.update.n'));
                    }
                }
            }
            $typeIndex++;
        }

        return redirect()->back();
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

        TaskActivity::destroy($id);
        $status = 1;
        // session()->flash('message', "The record has been deleted successfully.");

        // session message
        session()->flash('message_type', "success");
        return $status;
    }


    /**
     * Update the specified resource's status from storage.
     *
     * @param  int  $id
     * @return Response
     */
    function updateStatus(Request $request){

        $id = $request->id;

        $data = ($request->is_status == 1)? array('IsActive'=>0) : array('IsActive'=>1);
        $data['UpdatedBy'] = Auth::user()->id;
        $data['updated_at'] = date('Y-m-d H:i:s');

//         dd($data);

        if (TaskActivity::where('id', $id)->update($data)) {
            $status = 1;
            $msg = ($request->is_status == 1)? config('message.commom.active.u') : config('message.commom.active.p');
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


    //task weight setting index
    public function taskWeightSetting()
    {
//        $data['rows'] = TaskActivity::orderBy('TaskID')->with('createdUser')->with('updatedUser')->with('task')->get();
        $data['rows'] = TaskActivity::orderBy('TaskID')->with('createdUser')->with('updatedUser')->with('task')->get();
        return view($this->url, $data)
            ->with('page_title', $this->page_title);
    }

    public function taskWeightSettingEdit($id)
    {
        $data['row'] = TaskActivity::with('createdUser')->with('updatedUser')->find($id);
//        dd($data['row']);
        return view($this->url, $data)->with('page_title', $this->page_title);
    }

    public function taskWeightSettingUpdate(Request $request, $id)
    {
        foreach ($request->all() as $key => $value) {
            if($key != 'id' && $key != '_token'){

                $inputs[$key] = strip_tags($value);
            }
        }

        TaskActivity::find($id)->update($inputs);

        session()->flash('message_type', "success");
        session()->flash('message', config('message.commom.update.y'));

        return redirect()->back();

    }

    public function taskWeightSettingBulkEdit()
    {
        $data['domains'] = DB::table('task_activities')
            ->join('curriculum','curriculum.id','=','task_activities.domainID')
            ->orderBy('task_activities.domainID','ASC')
            ->pluck('curriculum.CurriName','task_activities.domainID')
            ->toArray();

        $data['subDomains'] = DB::table('task_activities')
            ->join('curriculum','curriculum.id','=','task_activities.subDomainID')
            ->orderBy('task_activities.subDomainID','ASC')
            ->pluck('curriculum.CurriName','task_activities.subDomainID')
            ->toArray();

        $data['areas'] = DB::table('task_activities')
            ->join('curriculum','curriculum.id','=','task_activities.areaID')
            ->orderBy('task_activities.areaID','ASC')
            ->pluck('curriculum.CurriName','task_activities.areaID')
            ->toArray();

        $data['activities'] = DB::table('task_activities')
            ->join('curriculum','curriculum.id','=','task_activities.TaskID')
            ->orderBy('task_activities.TaskID','ASC')
            ->pluck('curriculum.CurriName','task_activities.TaskID')
            ->toArray();

        return view($this->url, $data)
            ->with('page_title', $this->page_title);
    }

    public function setTaskWeightOption(Request $request)
    {
        $data = [];
        $domainId = $request->domainId;
        $subDomainId = $request->subDomainId;
        $areaId = $request->areaId;
        $taskId = $request->taskId;

//        dd($domainId);

//        $domainOption = '';
        if($domainId != null && $subDomainId == null  && $areaId == null  && $taskId== null){
            $subDomains = DB::table('task_activities')
                ->join('curriculum','curriculum.id','=','task_activities.subDomainID')
                ->where('task_activities.domainID',$domainId)
                ->orderBy('task_activities.subDomainID','ASC')
                ->pluck('curriculum.CurriName','task_activities.subDomainID')
                ->toArray();
            $domainOption = '';
            $domainOption .= "<select class=\"form-control strip-tags\" onchange=\"changeOptionById('subDomainID')\" id=\"subDomainID\" name=\"subDomainID\">";
            $domainOption .= "<option selected value=\"\">--Select--</option>";
            foreach ($subDomains as $key => $subDomain){
                $domainOption .= "<option value=\"$key\">".$subDomain."</option>";
            }
            $domainOption .= "</select>";

            $data['taskActivities'] = TaskActivity::where('domainID',$domainId)->get();

//            dd($domainOption);
            $domainWeight = view($this->url, $data)
                ->with('page_title', $this->page_title);
            $domainWeightt = $domainWeight->render();


            return response()->json([
                'selectBox' => $domainOption,
                'domainWeight' => $domainWeightt
            ]);


        }else if($domainId != null && $subDomainId != null  && $areaId == null  && $taskId== null){

            $areas = DB::table('task_activities')
                ->join('curriculum','curriculum.id','=','task_activities.areaID')
                ->where(['task_activities.domainID' => $domainId, 'task_activities.subDomainID' => $subDomainId])
                ->orderBy('task_activities.areaID','ASC')
                ->pluck('curriculum.CurriName','task_activities.areaID')
                ->toArray();

            $domainOption = '';
            $domainOption .= "<select class=\"form-control strip-tags\" onchange=\"changeOptionById('areaID')\" id=\"areaID\" name=\"areaID\">";
            $domainOption .= "<option selected value=\"\">--Select--</option>";
            foreach ($areas as $aID => $area){
                $domainOption .= "<option value=\"$aID\">".$area."</option>";
            }
            $domainOption .= "</select>";

            $data['taskActivities'] = TaskActivity::where(['domainID' => $domainId, 'subDomainID' => $subDomainId])->get();

//            dd($domainOption);
            $domainWeight = view($this->url, $data)
                ->with('page_title', $this->page_title);
            $domainWeightt = $domainWeight->render();


            return response()->json([
                'selectBox' => $domainOption,
                'domainWeight' => $domainWeightt
            ]);

        }else if($domainId != null && $subDomainId != null  && $areaId != null  && $taskId== null){

            $tasks = DB::table('task_activities')
                ->join('curriculum','curriculum.id','=','task_activities.TaskID')
                ->where(['task_activities.domainID' => $domainId, 'task_activities.subDomainID' => $subDomainId, 'task_activities.areaID' => $areaId])
                ->orderBy('task_activities.TaskID','ASC')
                ->pluck('curriculum.CurriName','task_activities.TaskID')
                ->toArray();

            $domainOption = '';
            $domainOption .= "<select class=\"form-control strip-tags\" onchange=\"changeOptionById('TaskID')\" id=\"TaskID\" name=\"TaskID\">";
            $domainOption .= "<option selected value=\"\">--Select--</option>";
            foreach ($tasks as $tID => $task){
                $domainOption .= "<option value=\"$tID\">".$task."</option>";
            }
            $domainOption .= "</select>";

            $data['taskActivities'] = TaskActivity::where(['domainID' => $domainId, 'subDomainID' => $subDomainId, 'areaID' => $areaId])->get();

//            dd($domainOption);
            $domainWeight = view($this->url, $data)
                ->with('page_title', $this->page_title);
            $domainWeightt = $domainWeight->render();


            return response()->json([
                'selectBox' => $domainOption,
                'domainWeight' => $domainWeightt
            ]);
        }else{

            $data['taskActivities'] = TaskActivity::where(['domainID' => $domainId, 'subDomainID' => $subDomainId,
                'areaID' => $areaId, 'TaskID' => $taskId])->get();
//            dd($domainOption);
            $domainWeight = view($this->url, $data)
                ->with('page_title', $this->page_title);
            $domainWeightt = $domainWeight->render();


            return response()->json([
                'domainWeight' => $domainWeightt
            ]);
        }


    }

    public function taskWeightSettingstore(Request $request)
    {
        $userID = Auth::user()->id;
        $ids = $request->id;
        $tskTimes = $request->tskTime;
        $tskSequences = $request->tskSequence;
        $tskQuantities = $request->tskQuantity;
        $tskQualities = $request->tskQuality;
        $tskDeliveries = $request->tskQuality;
        $tskTimetaken = $request->tskTimetaken;
        $tskTargets = $request->tskTarget;

        foreach ($ids as $key => $id){
            $data = [];
            $data['tskTime'] = $tskTimes[$id];
            $data['tskSequence'] = $tskSequences[$id];
            $data['tskQuantity'] = $tskQuantities[$id];
            $data['tskQuality'] = $tskQualities[$id];
            $data['tskDelivery'] = $tskDeliveries[$id];
            $data['tskTimetaken'] = $tskTimetaken[$id];
            $data['tskTarget'] = $tskTargets[$id];
            $data['UpdatedBy'] = $userID;

            TaskActivity::find($id)->update($data);
        }

        return redirect()->back();
    }

}
