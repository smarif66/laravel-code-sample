<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 24 Jun 2018 13:48:51 +0000.
 */

namespace App\Models;
use App\Models\Model;

//use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TaskAssignement
 * 
 * @property int $id
 * @property int $TaskID
 * @property int $AssignedTo
 * @property int $StudentID
 * @property string $TaskInstruction
 * @property int $CreatedBy
 * @property int $UpdatedBy
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class TaskAssignement extends Model
{
	protected $casts = [
		'AssignedTo' => 'int',
		'StudentID' => 'int',
		'CreatedBy' => 'int',
		'UpdatedBy' => 'int'
	];

	protected $fillable = [
		'TaskID',
		'AssignedTo',
		'StudentID',
		'TaskInstruction',
		'CreatedBy',
		'UpdatedBy'
	];
}
