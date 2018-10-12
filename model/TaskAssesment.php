<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 24 Jun 2018 13:48:51 +0000.
 */

namespace App\Models;
use App\Models\Student;

/**
 * Class TaskAssesment
 * 
 * @property int $id
 * @property int $TaskID
 * @property int $StudentID
 * @property int $ActivityID
 * @property \Carbon\Carbon $AssesmentDate
 * @property string $Answer
 * @property int $CreatedBy
 * @property int $UpdatedBy
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class TaskAssesment extends Model
{
    protected $casts = [
        'TaskAssignementID' => 'int',
        'StudentID' => 'int',
        'TeacherID' => 'int',
        'CreatedBy' => 'int',
        'UpdatedBy' => 'int'
    ];


    protected $dates = [
        'AssesmentDate'
    ];

    protected $fillable = [
        'TaskAssignementID',
        'StudentID',
        'TeacherID',
        'AssesmentDate',
        'IsActive',
        'CreatedBy',
        'UpdatedBy'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class,'StudentID');
    }
    public function task_assignment()
    {
        return $this->belongsTo(TaskAssignment::class,'TaskAssignementID');
    }
    public function teacher()
    {
        return $this->belongsTo(User::class,'TeacherID');
    }
}
