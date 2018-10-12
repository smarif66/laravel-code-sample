<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use App\Models\Model;

class TaskAssignmentList extends Model
{
    protected $table = 'task_assignement_list';

    protected $casts = [
        'id' => 'int',
    ];

    protected $fillable = [
        'id',
        'TaskAssignmentID',
        'TaskID',
        'DomainID',
        'SubDomainID',
        'DomainAreaID',
        'ActivityID',
        'CreatedBy',
        'UpdatedBy',
        'created_at',
        'updated_at'
    ];

    public function taskActivity()
    {
        return $this->belongsTo(TaskActivity::class,'TaskActivitiesID');
    }

}
