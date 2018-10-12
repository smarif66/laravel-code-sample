<?php

namespace App\Models;


class TaskAssesmentList extends Model
{
    protected $table = 'task_assesment_list';

    protected $casts = [
      'id' => 'int',
      'TaskAssignementListID' => 'int',
      'TaskActivitiesID' => 'int',
      'StudentID' => 'int',
      'AnsScore' => 'int',
    ];


    protected $fillable = [
        'TaskAssesmentID',
        'TaskActivitiesID',
        'StudentID',
        'tskTime',
        'tskSequence',
        'tskQuantity',
        'tskQuality',
        'tskDelivery',
        'tskTimetaken',
        'tskTarget',
        'AnsScore',
        'maxScore',
        'LocalZScore',
        'GlobalZscore',
        'Remarks',
        'CreatedBy',
        'UpdatedBy',
        'AssesmentDate',
    ];

    public function task_activities()
    {
        return $this->belongsTo(TaskActivity::class,'TaskActivitiesID');
    }



}
