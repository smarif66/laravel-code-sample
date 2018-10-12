<?php

namespace App\Models;

use App\Models\Model;
//use Illuminate\Database\Eloquent\Model;

class TaskActivity extends Model
{
    protected $table = 'task_activities';

    protected $casts = [
        'id' => 'int',
    ];

    protected $fillable = [
        'id',
        'TaskID',
        'ActivityName',
        'domainWeightFactorID',
        'type',
        'domainID',
        'subDomainID',
        'areaID',
        'tskTime',
        'tskSequence',
        'tskQuantity',
        'tskQuality',
        'tskDelivery',
        'tskTimetaken',
        'tskTarget',
        'created_at',
        'updated_at',
        'CreatedBy',
        'UpdatedBy'
    ];

    public function task(){
        return $this->belongsTo(Curriculum::class,'TaskID');
    }
    public function weight(){
        return $this->belongsTo(DomainWeightFactor::class,'domainWeightFactorID');
    }
}
