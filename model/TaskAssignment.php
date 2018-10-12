<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use App\Models\Model;
use App\Models\Student;

class TaskAssignment extends Model
{
    protected $table = 'task_assignement';

    protected $casts = [
        'id' => 'int',
    ];

    protected $fillable = [
        'id',
        'AssignedTo',
        'StudentID',
        'AssignTaskName',
        'TaskInstruction',
        'ITPCalculationStartDate',
        'ITPCalculationEndDate',
        'CreatedBy',
        'UpdatedBy',
        'created_at',
        'updated_at',
        'IsActive'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class,'StudentID');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'AssignedTo');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class,'TaskID');
    }

    public function taskAssignmentList()
    {
        return $this->hasMany(TaskAssignment::class,'TaskAssignmentID');
    }
}
