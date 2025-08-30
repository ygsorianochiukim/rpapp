<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLogs extends Model
{
    protected $table = "task_logs";

    protected $primaryKey = "id";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        's_bpartner_employee_id',
        'task_i_information_id',
        'TaskDateComplete',
        'is_active',
        'created_by',
        'created_date',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_i_information_id', 'task_i_information_id');
    }
    public function user()
    {
        return $this->belongsTo(UserAccount::class, 's_bpartner_employee_id', 's_bpartner_employee_id');
    }
}
